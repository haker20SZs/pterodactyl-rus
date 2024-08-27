import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import reinstallServer from '@/api/server/reinstallServer';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import { httpErrorToHuman } from '@/api/http';
import tw from 'twin.macro';
import { Button } from '@/components/elements/button/index';
import { Dialog } from '@/components/elements/dialog';

export default () => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const [modalVisible, setModalVisible] = useState(false);
    const { addFlash, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const reinstall = () => {
        clearFlashes('settings');
        reinstallServer(uuid)
            .then(() => {
                addFlash({
                    key: 'settings',
                    type: 'success',
                    message: 'На вашем сервере начался процесс переустановки.',
                });
            })
            .catch((error) => {
                console.error(error);

                addFlash({ key: 'settings', type: 'error', message: httpErrorToHuman(error) });
            })
            .then(() => setModalVisible(false));
    };

    useEffect(() => {
        clearFlashes();
    }, []);

    return (
        <TitledGreyBox title={'Переустановите сервер'} css={tw`relative`}>
            <Dialog.Confirm
                open={modalVisible}
                title={'Подтвердите переустановку сервера'}
                confirm={'Да, переустановите сервер'}
                onClose={() => setModalVisible(false)}
                onConfirmed={reinstall}
            >
                Ваш сервер будет остановлен, и некоторые файлы могут быть удалены или изменены во время этого процесса, вы уверены, что хотите продолжить?
            </Dialog.Confirm>
            <p css={tw`text-sm`}>
                Переустановка вашего сервера остановит его, а затем заново запустит сценарий установки, который первоначально установил его.&nbsp;
                <strong css={tw`font-medium`}>
                    В ходе этого процесса некоторые файлы могут быть удалены или изменены, поэтому перед продолжением работы создайте резервную копию данных продолжения.
                </strong>
            </p>
            <div css={tw`mt-6 text-right`}>
                <Button.Danger variant={Button.Variants.Secondary} onClick={() => setModalVisible(true)}>
                    Переустановите сервер
                </Button.Danger>
            </div>
        </TitledGreyBox>
    );
};
