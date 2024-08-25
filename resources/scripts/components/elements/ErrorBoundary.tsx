import React from 'react';
import tw from 'twin.macro';
import Icon from '@/components/elements/Icon';
import { ExclamationIcon } from '@heroicons/react/solid';

interface State {
    hasError: boolean;
}

// eslint-disable-next-line @typescript-eslint/ban-types
class ErrorBoundary extends React.Component<{}, State> {
    state: State = {
        hasError: false,
    };

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error: Error) {
        console.error(error);
    }

    render() {
        return this.state.hasError ? (
            <div css={tw`flex items-center justify-center w-full my-4`}>
                <div css={tw`flex items-center bg-zinc-900 rounded p-3 text-red-500`}>
                    <Icon icon={<ExclamationIcon />} css={tw`w-4 h-4 mr-2`} />
                    <p css={tw`text-sm text-zinc-100`}>
                        Приложение столкнулось с ошибкой во время рендеринга этого представления. Попробуйте обновить страницу.
                    </p>
                </div>
            </div>
        ) : (
            this.props.children
        );
    }
}

export default ErrorBoundary;
