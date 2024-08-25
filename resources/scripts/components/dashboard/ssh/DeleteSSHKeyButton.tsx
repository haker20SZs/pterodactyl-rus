import tw from 'twin.macro';
import React, { useState } from 'react';
import { useFlashKey } from '@/plugins/useFlash';
import { deleteSSHKey, useSSHKeys } from '@/api/account/ssh-keys';
import { TrashIcon } from '@heroicons/react/solid';
import { Dialog } from '@/components/elements/dialog';
import Code from '@/components/elements/Code';

export default ({ name, fingerprint }: { name: string; fingerprint: string }) => {
    const { clearAndAddHttpError } = useFlashKey('account');
    const [visible, setVisible] = useState(false);
    const { mutate } = useSSHKeys();

    const onClick = () => {
        clearAndAddHttpError();

        Promise.all([
            mutate((data) => data?.filter((value) => value.fingerprint !== fingerprint), false),
            deleteSSHKey(fingerprint),
        ]).catch((error) => {
            mutate(undefined, true).catch(console.error);
            clearAndAddHttpError(error);
        });
    };

    return (
        <>
            <Dialog.Confirm
                open={visible}
                title={'Удалить ключ SSH'}
                confirm={'Удалить ключ'}
                onConfirmed={onClick}
                onClose={() => setVisible(false)}
            >
                Удаление SSH-ключа <Code>{name}</Code> сделает недействительным его использование на панели.
            </Dialog.Confirm>
            <button css={tw`ml-4 p-2 text-sm`} onClick={() => setVisible(true)}>
                <TrashIcon css={tw`w-5 h-5 text-neutral-400 hover:text-red-400 transition-colors duration-150`} />
            </button>
        </>
    );
};
