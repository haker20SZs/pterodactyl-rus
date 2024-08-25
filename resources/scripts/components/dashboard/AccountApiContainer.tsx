import React, { useEffect, useState } from 'react';
import ContentBox from '@/components/elements/ContentBox';
import CreateApiKeyForm from '@/components/dashboard/forms/CreateApiKeyForm';
import getApiKeys, { ApiKey } from '@/api/account/getApiKeys';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import deleteApiKey from '@/api/account/deleteApiKey';
import FlashMessageRender from '@/components/FlashMessageRender';
import { format } from 'date-fns';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import GreyRowBox from '@/components/elements/GreyRowBox';
import { KeyIcon, TrashIcon } from '@heroicons/react/solid';
import { Dialog } from '@/components/elements/dialog';
import { useFlashKey } from '@/plugins/useFlash';
import Code from '@/components/elements/Code';

export default () => {
    const [deleteIdentifier, setDeleteIdentifier] = useState('');
    const [keys, setKeys] = useState<ApiKey[]>([]);
    const [loading, setLoading] = useState(true);
    const { clearAndAddHttpError } = useFlashKey('account');

    useEffect(() => {
        getApiKeys()
            .then((keys) => setKeys(keys))
            .then(() => setLoading(false))
            .catch((error) => clearAndAddHttpError(error));
    }, []);

    const doDeletion = (identifier: string) => {
        setLoading(true);

        clearAndAddHttpError();
        deleteApiKey(identifier)
            .then(() => setKeys((s) => [...(s || []).filter((key) => key.identifier !== identifier)]))
            .catch((error) => clearAndAddHttpError(error))
            .then(() => {
                setLoading(false);
                setDeleteIdentifier('');
            });
    };

    return (
        <PageContentBlock title={'API учетной записи'}>
            <FlashMessageRender byKey={'account'} />
            <div css={tw`md:flex flex-nowrap my-10`}>
                <ContentBox title={'Создайте ключ API'} css={tw`flex-none w-full md:w-1/2`}>
                    <CreateApiKeyForm onKeyCreated={(key) => setKeys((s) => [...s!, key])} />
                </ContentBox>
                <ContentBox title={'Ключи API'} css={tw`flex-1 overflow-hidden mt-8 md:mt-0 md:ml-8`}>
                    <SpinnerOverlay visible={loading} />
                    <Dialog.Confirm
                        title={'Удалить ключ API'}
                        confirm={'Удалить ключ'}
                        open={!!deleteIdentifier}
                        onClose={() => setDeleteIdentifier('')}
                        onConfirmed={() => doDeletion(deleteIdentifier)}
                    >
                        Все запросы, использующие ключ <Code>{deleteIdentifier}</Code>, будут считаться недействительными.
                    </Dialog.Confirm>
                    {keys.length === 0 ? (
                        <p css={tw`text-center text-sm`}>
                            {loading ? 'Загрузка...' : 'Для этой учетной записи не существует ключей API.'}
                        </p>
                    ) : (
                        keys.map((key, index) => (
                            <GreyRowBox
                                key={key.identifier}
                                css={[
                                    tw`bg-zinc-300 dark:bg-zinc-700 border-zinc-500 flex items-center`,
                                    index > 0 && tw`mt-2`,
                                ]}
                            >
                                <KeyIcon css={tw`w-5 h-5 text-zinc-700 dark:text-zinc-300`} />
                                <div css={tw`ml-4 flex-1 overflow-hidden`}>
                                    <p css={tw`text-sm break-words`}>{key.description}</p>
                                    <p css={tw`text-2xs text-zinc-700 dark:text-zinc-300 uppercase`}>
                                        Последнее использование:&nbsp;
                                        {key.lastUsedAt ? format(key.lastUsedAt, 'MMM do, yyyy HH:mm') : 'Никогда'}
                                    </p>
                                </div>
                                <p css={tw`text-sm ml-4 hidden md:block`}>
                                    <code
                                        css={tw`font-mono py-1 px-2 border bg-zinc-100 border-zinc-500 dark:bg-zinc-900 rounded`}
                                    >
                                        {key.identifier}
                                    </code>
                                </p>
                                <button css={tw`ml-4 p-2 text-sm`} onClick={() => setDeleteIdentifier(key.identifier)}>
                                    <TrashIcon
                                        css={tw`w-5 h-5 text-zinc-600 dark:text-zinc-400 hover:(text-red-700 dark:text-red-400) transition-colors duration-200 ease-in-out`}
                                    />
                                </button>
                            </GreyRowBox>
                        ))
                    )}
                </ContentBox>
            </div>
        </PageContentBlock>
    );
};
