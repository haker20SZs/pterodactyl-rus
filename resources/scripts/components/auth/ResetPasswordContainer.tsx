import React, { useState } from 'react';
import { RouteComponentProps } from 'react-router';
import { Link } from 'react-router-dom';
import performPasswordReset from '@/api/auth/performPasswordReset';
import { httpErrorToHuman } from '@/api/http';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import { Formik, FormikHelpers } from 'formik';
import { object, ref, string } from 'yup';
import Field from '@/components/elements/Field';
import Input from '@/components/elements/Input';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';

interface Values {
    password: string;
    passwordConfirmation: string;
}

export default ({ match, location }: RouteComponentProps<{ token: string }>) => {
    const [email, setEmail] = useState('');

    const { clearFlashes, addFlash } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const parsed = new URLSearchParams(location.search);
    if (email.length === 0 && parsed.get('email')) {
        setEmail(parsed.get('email') || '');
    }

    const submit = ({ password, passwordConfirmation }: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();
        performPasswordReset(email, { token: match.params.token, password, passwordConfirmation })
            .then(() => {
                // @ts-expect-error this is valid
                window.location = '/';
            })
            .catch((error) => {
                console.error(error);

                setSubmitting(false);
                addFlash({ type: 'error', title: 'Ошибка', message: httpErrorToHuman(error) });
            });
    };

    return (
        <Formik
            onSubmit={submit}
            initialValues={{
                password: '',
                passwordConfirmation: '',
            }}
            validationSchema={object().shape({
                password: string()
                    .required('Необходимо ввести новый пароль.')
                    .min(8, 'Ваш новый пароль должен состоять не менее чем из 8 символов.'),
                passwordConfirmation: string()
                    .required('Ваш новый пароль не совпадает.')
                    // @ts-expect-error this is valid
                    .oneOf([ref('password'), null], 'Ваш новый пароль не совпадает.'),
            })}
        >
            {({ isSubmitting }) => (
                <LoginFormContainer title={'Сброс пароля'} css={tw`w-full flex`}>
                    <div>
                        <label>Электронная почта</label>
                        <Input value={email} disabled />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field
                            label={'Новый пароль'}
                            name={'password'}
                            type={'password'}
                            description={'Длина пароля должна составлять не менее 8 символов.'}
                        />
                    </div>
                    <div css={tw`mt-6`}>
                        <Field label={'Подтвердите новый пароль'} name={'passwordConfirmation'} type={'password'} />
                    </div>
                    <div css={tw`mt-6`}>
                        <Button size={'xlarge'} type={'submit'} disabled={isSubmitting} isLoading={isSubmitting}>
                            Сброс пароля
                        </Button>
                    </div>
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/login'}
                            css={tw`text-xs text-zinc-600 hover:text-zinc-800 dark:(text-zinc-400 hover:text-zinc-100) tracking-wide no-underline uppercase`}
                        >
                            Вернуться к входу в систему
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};
