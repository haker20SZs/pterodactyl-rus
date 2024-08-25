import React, { useCallback, useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import getServerSchedule from '@/api/server/schedules/getServerSchedule';
import Spinner from '@/components/elements/Spinner';
import FlashMessageRender from '@/components/FlashMessageRender';
import EditScheduleModal from '@/components/server/schedules/EditScheduleModal';
import NewTaskButton from '@/components/server/schedules/NewTaskButton';
import DeleteScheduleButton from '@/components/server/schedules/DeleteScheduleButton';
import Can from '@/components/elements/Can';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { Button } from '@/components/elements/button/index';
import ScheduleTaskRow from '@/components/server/schedules/ScheduleTaskRow';
import isEqual from 'react-fast-compare';
import { format } from 'date-fns';
import ScheduleCronRow from '@/components/server/schedules/ScheduleCronRow';
import RunScheduleButton from '@/components/server/schedules/RunScheduleButton';

interface Params {
    id: string;
}

const CronBox = ({ title, value }: { title: string; value: string }) => (
    <div css={tw`bg-zinc-200 border border-zinc-400 dark:(bg-zinc-700 border-zinc-500) shadow-md rounded p-3`}>
        <p css={tw`text-zinc-700 dark:text-zinc-200 text-sm`}>{title}</p>
        <p css={tw`text-xl font-medium text-zinc-900 dark:text-zinc-100`}>{value}</p>
    </div>
);

const ActivePill = ({ active }: { active: boolean }) => (
    <span
        css={[
            tw`rounded-full px-2 py-px text-xs ml-4 uppercase`,
            active ? tw`bg-green-600 text-green-100` : tw`bg-red-600 text-red-100`,
        ]}
    >
        {active ? 'Active' : 'Inactive'}
    </span>
);

export default () => {
    const history = useHistory();
    const { id: scheduleId } = useParams<Params>();

    const id = ServerContext.useStoreState((state) => state.server.data!.id);
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const [isLoading, setIsLoading] = useState(true);
    const [showEditModal, setShowEditModal] = useState(false);

    const schedule = ServerContext.useStoreState(
        (st) => st.schedules.data.find((s) => s.id === Number(scheduleId)),
        isEqual
    );
    const appendSchedule = ServerContext.useStoreActions((actions) => actions.schedules.appendSchedule);

    useEffect(() => {
        if (schedule?.id === Number(scheduleId)) {
            setIsLoading(false);
            return;
        }

        clearFlashes('schedules');
        getServerSchedule(uuid, Number(scheduleId))
            .then((schedule) => appendSchedule(schedule))
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ error, key: 'schedules' });
            })
            .then(() => setIsLoading(false));
    }, [scheduleId]);

    const toggleEditModal = useCallback(() => {
        setShowEditModal((s) => !s);
    }, []);

    return (
        <PageContentBlock title={'Schedules'}>
            <FlashMessageRender byKey={'schedules'} css={tw`mb-4`} />
            {!schedule || isLoading ? (
                <Spinner size={'large'} centered />
            ) : (
                <>
                    <ScheduleCronRow cron={schedule.cron} css={tw`sm:hidden bg-zinc-700 rounded mb-4 p-3`} />
                    <div>
                        <div css={tw`sm:flex items-center p-3 sm:p-6 border-b-4 border-zinc-400 dark:border-zinc-600`}>
                            <div css={tw`flex-1`}>
                                <h3 css={tw`flex items-center text-zinc-900 dark:text-zinc-100 text-2xl`}>
                                    {schedule.name}
                                    {schedule.isProcessing ? (
                                        <span
                                            css={tw`flex items-center rounded-full px-2 py-px text-xs ml-4 uppercase bg-zinc-600 text-white`}
                                        >
                                            <Spinner css={tw`w-3! h-3! mr-2`} />
                                            Обработка
                                        </span>
                                    ) : (
                                        <ActivePill active={schedule.isActive} />
                                    )}
                                </h3>
                                <p css={tw`mt-1 text-sm text-zinc-700 dark:text-zinc-200`}>
                                    Последний запуск в:&nbsp;
                                    {schedule.lastRunAt ? (
                                        format(schedule.lastRunAt, "MMM do 'at' h:mma")
                                    ) : (
                                        <span css={tw`text-zinc-700 dark:text-zinc-200`}>n/a</span>
                                    )}
                                    <span css={tw`ml-4 pl-4 border-l-2 border-zinc-400 dark:border-zinc-600 py-px`}>
                                        Следующий запуск в:&nbsp;
                                        {schedule.nextRunAt ? (
                                            format(schedule.nextRunAt, "MMM do 'at' h:mma")
                                        ) : (
                                            <span css={tw`text-zinc-700 dark:text-zinc-200`}>n/a</span>
                                        )}
                                    </span>
                                </p>
                            </div>
                            <div css={tw`flex sm:block mt-3 sm:mt-0`}>
                                <Can action={'schedule.update'}>
                                    <Button.Text className={'flex-1 mr-4'} onClick={toggleEditModal}>
                                        Редактировать
                                    </Button.Text>
                                    <NewTaskButton schedule={schedule} />
                                </Can>
                            </div>
                        </div>
                        <div css={tw`hidden sm:grid grid-cols-5 gap-4 mb-4 mt-4`}>
                            <CronBox title={'Минута'} value={schedule.cron.minute} />
                            <CronBox title={'Час'} value={schedule.cron.hour} />
                            <CronBox title={'День (месяц)'} value={schedule.cron.dayOfMonth} />
                            <CronBox title={'Месяц'} value={schedule.cron.month} />
                            <CronBox title={'День (неделя)'} value={schedule.cron.dayOfWeek} />
                        </div>
                        <div css={tw`bg-neutral-700 rounded-b`}>
                            {schedule.tasks.length > 0
                                ? schedule.tasks
                                      .sort((a, b) =>
                                          a.sequenceId === b.sequenceId ? 0 : a.sequenceId > b.sequenceId ? 1 : -1
                                      )
                                      .map((task) => (
                                          <ScheduleTaskRow
                                              key={`${schedule.id}_${task.id}`}
                                              task={task}
                                              schedule={schedule}
                                          />
                                      ))
                                : null}
                        </div>
                    </div>
                    <EditScheduleModal visible={showEditModal} schedule={schedule} onModalDismissed={toggleEditModal} />
                    <div css={tw`mt-6 flex sm:justify-end`}>
                        <Can action={'schedule.delete'}>
                            <DeleteScheduleButton
                                scheduleId={schedule.id}
                                onDeleted={() => history.push(`/server/${id}/schedules`)}
                            />
                        </Can>
                        {schedule.tasks.length > 0 && (
                            <Can action={'schedule.update'}>
                                <RunScheduleButton schedule={schedule} />
                            </Can>
                        )}
                    </div>
                </>
            )}
        </PageContentBlock>
    );
};
