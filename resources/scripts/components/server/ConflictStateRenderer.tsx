import React from 'react';
import { ServerContext } from '@/state/server';
import ScreenBlock from '@/components/elements/ScreenBlock';
import ServerInstallSvg from '@/assets/images/server_installing.svg';
import ServerErrorSvg from '@/assets/images/server_error.svg';
import ServerRestoreSvg from '@/assets/images/server_restore.svg';

export default () => {
    const status = ServerContext.useStoreState((state) => state.server.data?.status || null);
    const isTransferring = ServerContext.useStoreState((state) => state.server.data?.isTransferring || false);
    const isNodeUnderMaintenance = ServerContext.useStoreState(
        (state) => state.server.data?.isNodeUnderMaintenance || false
    );

    return status === 'installing' || status === 'install_failed' || status === 'reinstall_failed' ? (
        <ScreenBlock
            title={'Запуск программы установки'}
            image={ServerInstallSvg}
            message={'Ваш сервер скоро будет готов, пожалуйста, повторите попытку через несколько минут.'}
        />
    ) : status === 'suspended' ? (
        <ScreenBlock
            title={'Сервер приостановлен'}
            image={ServerErrorSvg}
            message={'Работа этого сервера приостановлена, и доступ к нему невозможен.'}
        />
    ) : isNodeUnderMaintenance ? (
        <ScreenBlock
            title={'Узел, находящийся на техническом обслуживании'}
            image={ServerErrorSvg}
            message={'Узел этого сервера в настоящее время находится на техническом обслуживании.'}
        />
    ) : (
        <ScreenBlock
            title={isTransferring ? 'Передача' : 'Восстановление из резервной копии'}
            image={ServerRestoreSvg}
            message={
                isTransferring
                    ? 'Ваш сервер переносится на новый узел, пожалуйста, зайдите позже.'
                    : 'В настоящее время ваш сервер восстанавливается из резервной копии, пожалуйста, зайдите через несколько минут.'
            }
        />
    );
};
