import React from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import styled, { keyframes } from 'styled-components';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import NotFoundSvg from '@/assets/images/not_found.svg';
import ServerErrorSvg from '@/assets/images/server_error.svg';
import { ArrowLeftIcon, RefreshIcon } from '@heroicons/react/solid';

interface BaseProps {
    title: string;
    image: string;
    message: string;
    onRetry?: () => void;
    onBack?: () => void;
}

interface PropsWithRetry extends BaseProps {
    onRetry?: () => void;
    onBack?: never;
}

interface PropsWithBack extends BaseProps {
    onBack?: () => void;
    onRetry?: never;
}

export type ScreenBlockProps = PropsWithBack | PropsWithRetry;

const spin = keyframes`
    to { transform: rotate(360deg) }
`;

const ActionButton = styled(Button)`
    ${tw`rounded-full w-8 h-8 flex items-center justify-center p-0`};

    &.hover\\:spin:hover {
        animation: ${spin} 2s linear infinite;
    }

    & svg {
        ${tw`w-5 h-5`};
    }
`;

const ScreenBlock = ({ title, image, message, onBack, onRetry }: ScreenBlockProps) => (
    <PageContentBlock>
        <div css={tw`flex justify-center`}>
            <div
                css={tw`w-full sm:w-3/4 md:w-1/2 p-12 md:p-20 border bg-zinc-100 border-zinc-300 dark:(bg-zinc-800 border-zinc-600) rounded-lg shadow-lg text-center relative`}
            >
                {(typeof onBack === 'function' || typeof onRetry === 'function') && (
                    <div css={tw`absolute left-0 top-0 ml-4 mt-4`}>
                        <ActionButton
                            onClick={() => (onRetry ? onRetry() : onBack ? onBack() : null)}
                            className={onRetry ? 'hover:spin' : undefined}
                        >
                            {onRetry ? <RefreshIcon /> : <ArrowLeftIcon />}
                        </ActionButton>
                    </div>
                )}
                <img src={image} css={tw`w-2/3 h-auto select-none mx-auto`} />
                <h2 css={tw`mt-10 text-zinc-900 dark:text-zinc-100 font-bold text-4xl`}>{title}</h2>
                <p css={tw`text-sm text-zinc-700 dark:text-zinc-300 mt-2`}>{message}</p>
            </div>
        </div>
    </PageContentBlock>
);

type ServerErrorProps = (Omit<PropsWithBack, 'image' | 'title'> | Omit<PropsWithRetry, 'image' | 'title'>) & {
    title?: string;
};

const ServerError = ({ title, ...props }: ServerErrorProps) => (
    <ScreenBlock title={title || 'Что-то пошло не так'} image={ServerErrorSvg} {...props} />
);

const NotFound = ({ title, message, onBack }: Partial<Pick<ScreenBlockProps, 'title' | 'message' | 'onBack'>>) => (
    <ScreenBlock
        title={title || '404'}
        image={NotFoundSvg}
        message={message || 'Запрашиваемый ресурс не найден.'}
        onBack={onBack}
    />
);

export { ServerError, NotFound };
export default ScreenBlock;
