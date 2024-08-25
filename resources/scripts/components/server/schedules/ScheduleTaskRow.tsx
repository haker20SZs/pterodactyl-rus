import React, { useState } from 'react';
import { Schedule, Task } from '@/api/server/schedules/getServerSchedules';
import deleteScheduleTask from '@/api/server/schedules/deleteScheduleTask';
import { httpErrorToHuman } from '@/api/http';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import TaskDetailsModal from '@/components/server/schedules/TaskDetailsModal';
import Can from '@/components/elements/Can';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import tw from 'twin.macro';
import ConfirmationModal from '@/components/elements/ConfirmationModal';
import Icon from '@/components/elements/Icon';
import {
    ArchiveIcon,
    ArrowCircleDownIcon,
    ClockIcon,
    CodeIcon,
    LightningBoltIcon,
    PencilIcon,
    QuestionMarkCircleIcon,
    TrashIcon,
} from '@heroicons/react/solid';
import styled from 'styled-components';

interface Props {
    schedule: Schedule;
    task: Task;
}

const getActionDetails = (action: string): [string, () => React.ReactElement] => {
    switch (action) {
        case 'command':
            return ['Отправить команду', () => <CodeIcon />];
        case 'power':
            return ['Отправить действие питания', () => <LightningBoltIcon />];
        case 'backup':
            return ['Создание резервной копии', () => <ArchiveIcon />];
        default:
            return ['Неизвестное действие', () => <QuestionMarkCircleIcon />];
    }
};

const IconWrapper = styled.span`
    ${tw`text-lg text-black dark:text-white hidden md:block`};
    & > svg {
        ${tw`w-5 h-5`};
    }
`;

export default ({ schedule, task }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { clearFlashes, addError } = useFlash();
    const [visible, setVisible] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const appendSchedule = ServerContext.useStoreActions((actions) => actions.schedules.appendSchedule);

    const onConfirmDeletion = () => {
        setIsLoading(true);
        clearFlashes('schedules');
        deleteScheduleTask(uuid, schedule.id, task.id)
            .then(() =>
                appendSchedule({
                    ...schedule,
                    tasks: schedule.tasks.filter((t) => t.id !== task.id),
                })
            )
            .catch((error) => {
                console.error(error);
                setIsLoading(false);
                addError({ message: httpErrorToHuman(error), key: 'schedules' });
            });
    };

    const [title, icon] = getActionDetails(task.action);
    const iconWrapper = { icon };

    return (
        <div
            css={tw`sm:flex items-center p-3 sm:p-6 mb-2 shadow-md rounded bg-zinc-200 border border-zinc-400 dark:(bg-zinc-700 border-zinc-500)`}
        >
            <SpinnerOverlay visible={isLoading} fixed size={'large'} />
            <TaskDetailsModal
                schedule={schedule}
                task={task}
                visible={isEditing}
                onModalDismissed={() => setIsEditing(false)}
            />
            <ConfirmationModal
                title={'Подтвердите удаление задачи'}
                buttonText={'Удалить задачу'}
                onConfirmed={onConfirmDeletion}
                visible={visible}
                onModalDismissed={() => setVisible(false)}
            >
                Вы уверены, что хотите удалить эту задачу? Это действие нельзя отменить.
            </ConfirmationModal>
            <IconWrapper>
                <iconWrapper.icon />
            </IconWrapper>
            <div css={tw`flex-none sm:flex-1 w-full sm:w-auto overflow-x-auto`}>
                <p css={tw`md:ml-6 text-zinc-700 dark:text-zinc-200 uppercase text-sm`}>{title}</p>
                {task.payload && (
                    <div css={tw`md:ml-6 mt-2`}>
                        {task.action === 'backup' && (
                            <p css={tw`text-xs uppercase text-zinc-600 dark:text-zinc-400 mb-1`}>
                                Игнорирование файлов и папок:
                            </p>
                        )}
                        <div
                            css={tw`font-mono bg-zinc-300 dark:bg-zinc-800 rounded py-1 px-2 text-sm w-auto inline-block whitespace-pre-wrap break-all`}
                        >
                            {task.payload}
                        </div>
                    </div>
                )}
            </div>
            <div css={tw`mt-3 sm:mt-0 flex items-center w-full sm:w-auto`}>
                {task.continueOnFailure && (
                    <div css={tw`mr-6`}>
                        <div css={tw`flex items-center p-1 pr-2 bg-yellow-500 text-yellow-800 text-sm rounded-full`}>
                            <Icon icon={<ArrowCircleDownIcon />} css={tw`w-5 h-5 mr-2`} />
                            Продолжение неудачи
                        </div>
                    </div>
                )}
                {task.sequenceId > 1 && task.timeOffset > 0 && (
                    <div css={tw`mr-6`}>
                        <div css={tw`flex items-center p-1 pr-2 bg-zinc-300 dark:bg-zinc-500 text-sm rounded-full`}>
                            <Icon icon={<ClockIcon />} css={tw`w-5 h-5 mr-2`} />
                            {task.timeOffset} позже
                        </div>
                    </div>
                )}
                <Can action={'schedule.update'}>
                    <button
                        type={'button'}
                        aria-label={'Редактирование запланированной задачи'}
                        css={tw`block text-sm p-2 text-zinc-600 hover:text-zinc-900 dark:(text-zinc-400 hover:text-zinc-100) transition-colors duration-150 mr-4 ml-auto sm:ml-0`}
                        onClick={() => setIsEditing(true)}
                    >
                        <PencilIcon css={tw`w-5 h-5`} />
                    </button>
                </Can>
                <Can action={'schedule.update'}>
                    <button
                        type={'button'}
                        aria-label={'Удаление запланированной задачи'}
                        css={tw`block text-sm p-2 text-zinc-600 hover:text-red-700 dark:(text-zinc-400 hover:text-red-400) transition-colors duration-150`}
                        onClick={() => setVisible(true)}
                    >
                        <TrashIcon css={tw`w-5 h-5`} />
                    </button>
                </Can>
            </div>
        </div>
    );
};
