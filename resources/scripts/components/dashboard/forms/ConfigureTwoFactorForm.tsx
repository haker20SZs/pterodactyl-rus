import React, { useEffect, useState } from 'react';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import tw from 'twin.macro';
import { Button } from '@/components/elements/button/index';
import SetupTOTPDialog from '@/components/dashboard/forms/SetupTOTPDialog';
import RecoveryTokensDialog from '@/components/dashboard/forms/RecoveryTokensDialog';
import DisableTOTPDialog from '@/components/dashboard/forms/DisableTOTPDialog';
import { useFlashKey } from '@/plugins/useFlash';

export default () => {
    const [tokens, setTokens] = useState<string[]>([]);
    const [visible, setVisible] = useState<'enable' | 'disable' | null>(null);
    const isEnabled = useStoreState((state: ApplicationStore) => state.user.data!.useTotp);
    const { clearAndAddHttpError } = useFlashKey('account:two-step');

    useEffect(() => {
        return () => {
            clearAndAddHttpError();
        };
    }, [visible]);

    const onTokens = (tokens: string[]) => {
        setTokens(tokens);
        setVisible(null);
    };

    return (
        <div>
            <SetupTOTPDialog open={visible === 'enable'} onClose={() => setVisible(null)} onTokens={onTokens} />
            <RecoveryTokensDialog tokens={tokens} open={tokens.length > 0} onClose={() => setTokens([])} />
            <DisableTOTPDialog open={visible === 'disable'} onClose={() => setVisible(null)} />
            <p css={tw`text-sm`}>
                {isEnabled
                    ? 'В настоящее время в вашей учетной записи включена двухэтапная проверка.'
                    : 'В настоящее время в вашей учетной записи не включена двухэтапная верификация. Нажмите кнопку ниже, чтобы начать ее настройку.'}
            </p>
            <div css={tw`mt-6`}>
                {isEnabled ? (
                    <Button.Danger onClick={() => setVisible('disable')}>Отключить двухэтапную аутентификацию</Button.Danger>
                ) : (
                    <Button onClick={() => setVisible('enable')}>Включить двухэтапную аутентификацию</Button>
                )}
            </div>
        </div>
    );
};
