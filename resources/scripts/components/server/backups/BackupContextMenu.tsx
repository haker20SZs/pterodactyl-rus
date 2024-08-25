import React, { useState } from 'react';
import DropdownMenu, { DropdownButtonRow } from '@/components/elements/DropdownMenu';
import getBackupDownloadUrl from '@/api/server/backups/getBackupDownloadUrl';
import useFlash from '@/plugins/useFlash';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import deleteBackup from '@/api/server/backups/deleteBackup';
import Can from '@/components/elements/Can';
import tw from 'twin.macro';
import getServerBackups from '@/api/swr/getServerBackups';
import { ServerBackup } from '@/api/server/types';
import { ServerContext } from '@/state/server';
import Input from '@/components/elements/Input';
import { restoreServerBackup } from '@/api/server/backups';
import http, { httpErrorToHuman } from '@/api/http';
import {
    ArchiveIcon,
    CloudDownloadIcon,
    DotsVerticalIcon,
    LockClosedIcon,
    LockOpenIcon,
    TrashIcon,
} from '@heroicons/react/solid';
import { Dialog } from '@/components/elements/dialog';

interface Props {
    backup: ServerBackup;
}

export default ({ backup }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const setServerFromState = ServerContext.useStoreActions((actions) => actions.server.setServerFromState);
    const [modal, setModal] = useState('');
    const [loading, setLoading] = useState(false);
    const [truncate, setTruncate] = useState(false);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { mutate } = getServerBackups();

    const doDownload = () => {
        setLoading(true);
        clearFlashes('backups');
        getBackupDownloadUrl(uuid, backup.uuid)
            .then((url) => {
                // @ts-expect-error this is valid
                window.location = url;
            })
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ key: 'backups', error });
            })
            .then(() => setLoading(false));
    };

    const doDeletion = () => {
        setLoading(true);
        clearFlashes('backups');
        deleteBackup(uuid, backup.uuid)
            .then(() =>
                mutate(
                    (data) => ({
                        ...data,
                        items: data.items.filter((b) => b.uuid !== backup.uuid),
                        backupCount: data.backupCount - 1,
                    }),
                    false
                )
            )
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ key: 'backups', error });
                setLoading(false);
                setModal('');
            });
    };

    const doRestorationAction = () => {
        setLoading(true);
        clearFlashes('backups');
        restoreServerBackup(uuid, backup.uuid, truncate)
            .then(() =>
                setServerFromState((s) => ({
                    ...s,
                    status: 'restoring_backup',
                }))
            )
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ key: 'backups', error });
            })
            .then(() => setLoading(false))
            .then(() => setModal(''));
    };

    const onLockToggle = () => {
        if (backup.isLocked && modal !== 'unlock') {
            return setModal('unlock');
        }

        http.post(`/api/client/servers/${uuid}/backups/${backup.uuid}/lock`)
            .then(() =>
                mutate(
                    (data) => ({
                        ...data,
                        items: data.items.map((b) =>
                            b.uuid !== backup.uuid
                                ? b
                                : {
                                      ...b,
                                      isLocked: !b.isLocked,
                                  }
                        ),
                    }),
                    false
                )
            )
            .catch((error) => alert(httpErrorToHuman(error)))
            .then(() => setModal(''));
    };

    return (
        <>
            <Dialog.Confirm
                open={modal === 'Разблокировать'}
                onClose={() => setModal('')}
                title={`Разблокировать "${backup.name}"`}
                onConfirmed={onLockToggle}
            >
                Эта резервная копия больше не будет защищена от автоматического или случайного удаления.
            </Dialog.Confirm>
            <Dialog.Confirm
                open={modal === 'restore'}
                onClose={() => setModal('')}
                confirm={'Восстановить'}
                title={`Восстановить "${backup.name}"`}
                onConfirmed={() => doRestorationAction()}
            >
                <p>
                    Ваш сервер будет остановлен. Вы не сможете контролировать состояние питания, получить доступ к файловому
                    менеджера или создавать дополнительные резервные копии до завершения работы.
                </p>
                <p css={tw`mt-4 -mb-2 bg-zinc-700 p-3 rounded`}>
                    <label htmlFor={'restore_truncate'} css={tw`text-base flex items-center cursor-pointer`}>
                        <Input
                            type={'checkbox'}
                            css={tw`text-red-500! w-5! h-5! mr-2`}
                            id={'restore_truncate'}
                            value={'true'}
                            checked={truncate}
                            onChange={() => setTruncate((s) => !s)}
                        />
                        Удалите все файлы перед восстановлением резервной копии.
                    </label>
                </p>
            </Dialog.Confirm>
            <Dialog.Confirm
                title={`Удалить "${backup.name}"`}
                confirm={'Продолжить'}
                open={modal === 'delete'}
                onClose={() => setModal('')}
                onConfirmed={doDeletion}
            >
                Это постоянная операция. После удаления резервная копия не может быть восстановлена.
            </Dialog.Confirm>
            <SpinnerOverlay visible={loading} fixed />
            {backup.isSuccessful ? (
                <DropdownMenu
                    renderToggle={(onClick) => (
                        <button
                            onClick={onClick}
                            css={tw`text-zinc-200 transition-colors duration-150 hover:text-zinc-100 p-2`}
                        >
                            <DotsVerticalIcon css={tw`w-5 h-5`} />
                        </button>
                    )}
                >
                    <div css={tw`text-sm`}>
                        <Can action={'backup.download'}>
                            <DropdownButtonRow onClick={doDownload}>
                                <CloudDownloadIcon />
                                <span css={tw`ml-2`}>Скачать</span>
                            </DropdownButtonRow>
                        </Can>
                        <Can action={'backup.restore'}>
                            <DropdownButtonRow onClick={() => setModal('restore')}>
                                <ArchiveIcon />
                                <span css={tw`ml-2`}>Восстановить</span>
                            </DropdownButtonRow>
                        </Can>
                        <Can action={'backup.delete'}>
                            <>
                                <DropdownButtonRow onClick={onLockToggle}>
                                    {backup.isLocked ? (
                                        <LockOpenIcon css={tw`mr-2`} />
                                    ) : (
                                        <LockClosedIcon css={tw`mr-2`} />
                                    )}
                                    {backup.isLocked ? 'Разблокировать' : 'Заблокировать'}
                                </DropdownButtonRow>
                                {!backup.isLocked && (
                                    <DropdownButtonRow danger onClick={() => setModal('delete')}>
                                        <TrashIcon />
                                        <span css={tw`ml-2`}>Удалить</span>
                                    </DropdownButtonRow>
                                )}
                            </>
                        </Can>
                    </div>
                </DropdownMenu>
            ) : (
                <button
                    onClick={() => setModal('delete')}
                    css={tw`text-zinc-800 dark:text-zinc-200 transition-colors duration-150 hover:(text-zinc-900 dark:text-zinc-100) p-2`}
                >
                    <TrashIcon css={tw`w-5 h-5`} />
                </button>
            )}
        </>
    );
};
