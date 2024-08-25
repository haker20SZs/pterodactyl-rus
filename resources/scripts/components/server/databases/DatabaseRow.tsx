import React, { useState } from 'react';
import Modal from '@/components/elements/Modal';
import { Form, Formik, FormikHelpers } from 'formik';
import Field from '@/components/elements/Field';
import { object, string } from 'yup';
import FlashMessageRender from '@/components/FlashMessageRender';
import { ServerContext } from '@/state/server';
import deleteServerDatabase from '@/api/server/databases/deleteServerDatabase';
import { httpErrorToHuman } from '@/api/http';
import RotatePasswordButton from '@/components/server/databases/RotatePasswordButton';
import Can from '@/components/elements/Can';
import { ServerDatabase } from '@/api/server/databases/getServerDatabases';
import useFlash from '@/plugins/useFlash';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import Label from '@/components/elements/Label';
import Input from '@/components/elements/Input';
import GreyRowBox from '@/components/elements/GreyRowBox';
import CopyOnClick from '@/components/elements/CopyOnClick';
import { DatabaseIcon, EyeIcon, TrashIcon } from '@heroicons/react/solid';

interface Props {
    database: ServerDatabase;
    className?: string;
}

export default ({ database, className }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { addError, clearFlashes } = useFlash();
    const [visible, setVisible] = useState(false);
    const [connectionVisible, setConnectionVisible] = useState(false);

    const appendDatabase = ServerContext.useStoreActions((actions) => actions.databases.appendDatabase);
    const removeDatabase = ServerContext.useStoreActions((actions) => actions.databases.removeDatabase);

    const schema = object().shape({
        confirm: string()
            .required('Необходимо указать имя базы данных.')
            .oneOf([database.name.split('_', 2)[1], database.name], 'Необходимо указать имя базы данных.'),
    });

    const submit = (values: { confirm: string }, { setSubmitting }: FormikHelpers<{ confirm: string }>) => {
        clearFlashes();
        deleteServerDatabase(uuid, database.id)
            .then(() => {
                setVisible(false);
                setTimeout(() => removeDatabase(database.id), 150);
            })
            .catch((error) => {
                console.error(error);
                setSubmitting(false);
                addError({ key: 'database:delete', message: httpErrorToHuman(error) });
            });
    };

    return (
        <>
            <Formik onSubmit={submit} initialValues={{ confirm: '' }} validationSchema={schema} isInitialValid={false}>
                {({ isSubmitting, isValid, resetForm }) => (
                    <Modal
                        visible={visible}
                        dismissable={!isSubmitting}
                        showSpinnerOverlay={isSubmitting}
                        onDismissed={() => {
                            setVisible(false);
                            resetForm();
                        }}
                    >
                        <FlashMessageRender byKey={'database:delete'} css={tw`mb-6`} />
                        <h2 css={tw`text-2xl mb-6`}>Подтвердите удаление базы данных</h2>
                        <p css={tw`text-sm`}>
                            Удаление базы данных - это постоянное действие, которое нельзя отменить. Это приведет к безвозвратному удалению
                            базу данных <strong>{database.name}</strong> и удалите все связанные с ней данные.
                        </p>
                        <Form css={tw`m-0 mt-6`}>
                            <Field
                                type={'text'}
                                id={'confirm_name'}
                                name={'confirm'}
                                label={'Подтвердите имя базы данных'}
                                description={'Введите имя базы данных для подтверждения удаления.'}
                            />
                            <div css={tw`mt-6 text-right`}>
                                <Button type={'button'} isSecondary css={tw`mr-2`} onClick={() => setVisible(false)}>
                                    Закрыть
                                </Button>
                                <Button type={'submit'} color={'red'} disabled={!isValid}>
                                    Удалить базу данных
                                </Button>
                            </div>
                        </Form>
                    </Modal>
                )}
            </Formik>
            <Modal visible={connectionVisible} onDismissed={() => setConnectionVisible(false)}>
                <FlashMessageRender byKey={'database-connection-modal'} css={tw`mb-6`} />
                <h3 css={tw`mb-6 text-2xl`}>Детали подключения к базе данных</h3>
                <div>
                    <Label>Конечная точка</Label>
                    <CopyOnClick text={database.connectionString}>
                        <Input type={'text'} readOnly value={database.connectionString} />
                    </CopyOnClick>
                </div>
                <div css={tw`mt-6`}>
                    <Label>Соединения из</Label>
                    <Input type={'text'} readOnly value={database.allowConnectionsFrom} />
                </div>
                <div css={tw`mt-6`}>
                    <Label>Имя пользователя</Label>
                    <CopyOnClick text={database.username}>
                        <Input type={'text'} readOnly value={database.username} />
                    </CopyOnClick>
                </div>
                <Can action={'database.view_password'}>
                    <div css={tw`mt-6`}>
                        <Label>Пароль</Label>
                        <CopyOnClick text={database.password}>
                            <Input type={'text'} readOnly value={database.password} />
                        </CopyOnClick>
                    </div>
                </Can>
                <div css={tw`mt-6`}>
                    <Label>Строка подключения JDBC</Label>
                    <CopyOnClick
                        text={`jdbc:mysql://${database.username}:${database.password}@${database.connectionString}/${database.name}`}
                    >
                        <Input
                            type={'text'}
                            readOnly
                            value={`jdbc:mysql://${database.username}:${database.password}@${database.connectionString}/${database.name}`}
                        />
                    </CopyOnClick>
                </div>
                <div css={tw`mt-6 text-right`}>
                    <Can action={'database.update'}>
                        <RotatePasswordButton databaseId={database.id} onUpdate={appendDatabase} />
                    </Can>
                    <Button isSecondary onClick={() => setConnectionVisible(false)}>
                        Закрыть
                    </Button>
                </div>
            </Modal>
            <GreyRowBox $hoverable={false} className={className} css={tw`mb-2`}>
                <div css={tw`hidden md:block`}>
                    <DatabaseIcon css={tw`w-5 h-5`} />
                </div>
                <div css={tw`flex-1 ml-4`}>
                    <CopyOnClick text={database.name}>
                        <p css={tw`text-lg`}>{database.name}</p>
                    </CopyOnClick>
                </div>
                <div css={tw`ml-8 text-center hidden md:block`}>
                    <CopyOnClick text={database.connectionString}>
                        <p css={tw`text-sm`}>{database.connectionString}</p>
                    </CopyOnClick>
                    <p css={tw`mt-1 text-2xs text-zinc-600 dark:text-zinc-500 uppercase select-none`}>Конечная точка</p>
                </div>
                <div css={tw`ml-8 text-center hidden md:block`}>
                    <p css={tw`text-sm`}>{database.allowConnectionsFrom}</p>
                    <p css={tw`mt-1 text-2xs text-zinc-600 dark:text-zinc-500 uppercase select-none`}>
                        Connections from
                    </p>
                </div>
                <div css={tw`ml-8 text-center hidden md:block`}>
                    <CopyOnClick text={database.username}>
                        <p css={tw`text-sm`}>{database.username}</p>
                    </CopyOnClick>
                    <p css={tw`mt-1 text-2xs text-zinc-600 dark:text-zinc-500 uppercase select-none`}>Имя пользователя</p>
                </div>
                <div css={tw`ml-8`}>
                    <Button isSecondary css={tw`mr-2`} onClick={() => setConnectionVisible(true)}>
                        <EyeIcon css={tw`w-5 h-5`} />
                    </Button>
                    <Can action={'database.delete'}>
                        <Button color={'red'} isSecondary onClick={() => setVisible(true)}>
                            <TrashIcon css={tw`w-5 h-5`} />
                        </Button>
                    </Can>
                </div>
            </GreyRowBox>
        </>
    );
};
