import React, { useEffect, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { useStoreState } from 'easy-peasy';
import { Formik, FormikHelpers } from 'formik';
import { object, ref, string } from 'yup';
import Field from '@/components/elements/Field';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import Reaptcha from 'reaptcha';
import useFlash from '@/plugins/useFlash';
import register from '@/api/auth/register';

interface Values {
    email: string;
    username: string;
    nameFirst: string;
    nameLast: string;
    password: string;
    passwordConfirm: string;
}

const RegisterContainer = () => {
    const recaptchaRef = useRef<Reaptcha>(null);
    const [ token, setToken ] = useState('');

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { enabled: recaptchaEnabled, siteKey } = useStoreState(state => state.settings.data!.recaptcha);

    useEffect(() => {
        clearFlashes();
    }, []);

    const onSubmit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();

        // If there is no token in the state yet, request the token and then abort this submit request
        // since it will be re-submitted when the recaptcha data is returned by the component.
        if (recaptchaEnabled && !token) {
            recaptchaRef.current!.execute().catch(error => {
                console.error(error);

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });

            return;
        }

        register({ ...values, recaptchaData: token })
            .then(response => {
                if (response.complete) {
                    // @ts-ignore
                    window.location = response.intended || '/';
                }
            })
            .catch(error => {
                console.error(error);

                setToken('');
                if (recaptchaRef.current) recaptchaRef.current.reset();

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });
    };

    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={{ email: '', username: '', nameFirst: '', nameLast: '', password: '', passwordConfirm: '' }}
            validationSchema={object().shape({
                email: string().email().required('Необходимо указать электронную почту.'),
                username: string().matches(
                    /^((?:[a-zA-Z0-9])+(?:[a-zA-Z0-9._-])+(?:[a-zA-Z0-9])+)$/,
                    'Имя пользователя должно состоять не менее чем из 3 символов, начинаться и заканчиваться буквенно-цифровыми символами (A-Z, a-z, 0-9) и содержать только буквы, цифры, тире, знаки подчеркивания и точки.'
                ).required('Необходимо указать имя пользователя.'),
                nameFirst: string().required('Пожалуйста, введите ваше имя.'),
                nameLast: string().required('Пожалуйста, введите свою фамилию.'),
                password: string().required('Необходимо ввести пароль.')
                    .min(8, 'Ваш пароль должен состоять не менее чем из 8 символов.'),
                passwordConfirm: string()
                    .required('Ваш пароль не совпадает.')
                    // @ts-ignore
                    .oneOf([ ref('password'), null ], 'Ваш пароль не совпадает.'),
            })}
        >
            {({ isSubmitting, setSubmitting, submitForm }) => (
                <LoginFormContainer title={'Зарегистрироваться'} css={tw`w-full flex`}>
                    <Field
                        light
                        type={'email'}
                        label={'Электронная почта'}
                        name={'email'}
                        disabled={isSubmitting}
                    />
                    <div css={tw`mt-6`}>
                        <Field
                            light
                            type={'text'}
                            label={'Имя пользователя'}
                            name={'username'}
                            disabled={isSubmitting}
                        />
                    </div>
                    <div css={tw`flex flex-col lg:flex-row mt-6 gap-6 w-full`}>
                        <div css={tw`w-full`}>
                            <Field
                                light
                                type={'text'}
                                label={'Имя'}
                                name={'nameFirst'}
                                disabled={isSubmitting}
                            />
                        </div>
                        <div css={tw`w-full`}>
                            <Field
                                light
                                type={'text'}
                                label={'Фамилия'}
                                name={'nameLast'}
                                disabled={isSubmitting}
                            />
                        </div>
                    </div>
                    <div css={tw`flex flex-col lg:flex-row mt-6 gap-6 w-full`}>
                        <div css={tw`w-full`}>
                            <Field
                                light
                                type={'password'}
                                label={'Пароль'}
                                name={'password'}
                                disabled={isSubmitting}
                            />
                        </div>
                        <div css={tw`w-full`}>
                            <Field
                                light
                                type={'password'}
                                label={'Подтвердите пароль'}
                                name={'passwordConfirm'}
                                disabled={isSubmitting}
                            />
                        </div>
                    </div>
                    <div css={tw`mt-6`}>
                        <Button type={'submit'} size={'xlarge'} isLoading={isSubmitting} disabled={isSubmitting}>
                            Регистрация
                        </Button>
                    </div>
                    {recaptchaEnabled &&
                    <Reaptcha
                        ref={recaptchaRef}
                        size={'invisible'}
                        sitekey={siteKey || '_invalid_key'}
                        onVerify={response => {
                            setToken(response);
                            submitForm();
                        }}
                        onExpire={() => {
                            setSubmitting(false);
                            setToken('');
                        }}
                    />
                    }
                    <div css={tw`mt-6 text-center`}>
                        <Link
                            to={'/auth/password'}
                            css={tw`text-xs text-zinc-600 hover:text-zinc-800 dark:(text-zinc-400 hover:text-zinc-100) tracking-wide no-underline uppercase`}
                        >
                            Забыли пароль?
                        </Link>
                        <Link
                            to={'/auth/login'}
                            css={tw`text-xs text-zinc-600 hover:text-zinc-800 dark:(text-zinc-400 hover:text-zinc-100) tracking-wide no-underline uppercase mt-2 block`}
                        >
                            У вас уже есть аккаунт?
                        </Link>
                    </div>
                </LoginFormContainer>
            )}
        </Formik>
    );
};

export default RegisterContainer;
