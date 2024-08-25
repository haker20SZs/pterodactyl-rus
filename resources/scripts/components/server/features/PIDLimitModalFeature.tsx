import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import FlashMessageRender from '@/components/FlashMessageRender';
import useFlash from '@/plugins/useFlash';
import { SocketEvent } from '@/components/server/events';
import { useStoreState } from 'easy-peasy';
import { ExclamationIcon } from '@heroicons/react/solid';

const PIDLimitModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = [
            'pthread_create не удалось',
            'не удалось создать поток',
            'Невозможно создать тему',
            'Невозможно создать собственный поток',
            'Невозможно создать новый собственный поток',
            'исключение в потоке "поток управления асинхронным планировщиком"',
        ];

        const listener = (line: string) => {
            if (errors.some((p) => line.toLowerCase().includes(p))) {
                setVisible(true);
            }
        };

        instance.addListener(SocketEvent.CONSOLE_OUTPUT, listener);

        return () => {
            instance.removeListener(SocketEvent.CONSOLE_OUTPUT, listener);
        };
    }, [connected, instance, status]);

    useEffect(() => {
        clearFlashes('feature:pidLimit');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:pidLimit'} css={tw`mb-4`} />
            {isAdmin ? (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <ExclamationIcon css={tw`w-20 h-20 text-yellow-600 dark:text-yellow-500`} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100 `}>Достигнут предел памяти или процесса...</h2>
                    </div>
                    <p css={tw`mt-4`}>Этот сервер достиг максимального лимита процессов или памяти.</p>
                    <p css={tw`mt-4`}>
                        Увеличение <code css={tw`font-mono bg-neutral-900`}>container_pid_limit</code> в крыльях
                        конфигурации, <code css={tw`font-mono bg-neutral-900`}>config.yml</code>, может помочь решить эту проблему.
                    </p>
                    <p css={tw`mt-4`}>
                        <b>Примечание: Чтобы изменения в файле конфигурации вступили в силу, необходимо перезапустить Wings.</b>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Закрыть
                        </Button>
                    </div>
                </>
            ) : (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <ExclamationIcon css={tw`w-20 h-20 text-yellow-600 dark:text-yellow-500`} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Достигнут возможный лимит ресурсов...</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Этот сервер пытается использовать больше ресурсов, чем выделено. Пожалуйста, свяжитесь с администратором и сообщите ему о приведенной ниже ошибке.
                    </p>
                    <p css={tw`mt-4`}>
                        <code css={tw`font-mono bg-neutral-900`}>
                            pthread_create не удалось, возможно, закончилась память или достигнуты пределы процесса/ресурса
                        </code>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Закрыть
                        </Button>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default PIDLimitModalFeature;
