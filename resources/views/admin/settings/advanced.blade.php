@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'advanced'])

@section('title')
    Дополнительные настройки
@endsection

@section('content-header')
    <h1>Дополнительные настройки<small>Настройте дополнительные параметры для WestalHost.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Администратор</a></li>
        <li class="active">Настройки</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <form action="" method="POST">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">reCAPTCHA</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Статус</label>
                                <div>
                                    <select class="form-control" name="recaptcha:enabled">
                                        <option value="true">Включить</option>
                                        <option value="false" @if(old('recaptcha:enabled', config('recaptcha.enabled')) == '0') selected @endif>Отключить</option>
                                    </select>
                                    <p class="text-muted small">Если эта функция включена, формы входа в систему и сброса пароля будут выполнять тихую проверку капчи и при необходимости отображать видимую капчу.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Ключ к сайту</label>
                                <div>
                                    <input type="text" required class="form-control" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Секретный ключ</label>
                                <div>
                                    <input type="text" required class="form-control" name="recaptcha:secret_key" value="{{ old('recaptcha:secret_key', config('recaptcha.secret_key')) }}">
                                    <p class="text-muted small">Используется для связи между вашим сайтом и Google. Обязательно держите его в секрете.</p>
                                </div>
                            </div>
                        </div>
                        @if($showRecaptchaWarning)
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="alert alert-warning no-margin">
                                        В настоящее время вы используете ключи reCAPTCHA, поставляемые вместе с этой панелью. Для повышения безопасности рекомендуется <a href="https://www.google.com/recaptcha/admin">генерировать новые невидимые ключи reCAPTCHA</a>, привязанные именно к вашему сайту.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">HTTP-соединения</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Таймаут соединения</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:guzzle:connect_timeout" value="{{ old('pterodactyl:guzzle:connect_timeout', config('pterodactyl.guzzle.connect_timeout')) }}">
                                    <p class="text-muted small">Количество времени в секундах, в течение которого следует ждать открытия соединения, прежде чем выдать ошибку.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Таймаут запроса</label>
                                <div>
                                    <input type="number" required class="form-control" name="pterodactyl:guzzle:timeout" value="{{ old('pterodactyl:guzzle:timeout', config('pterodactyl.guzzle.timeout')) }}">
                                    <p class="text-muted small">Количество времени в секундах, в течение которого следует ждать завершения запроса, прежде чем выдать ошибку.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Автоматическое создание распределений</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">Статус</label>
                                <div>
                                    <select class="form-control" name="pterodactyl:client_features:allocations:enabled">
                                        <option value="false">Отключить</option>
                                        <option value="true" @if(old('pterodactyl:client_features:allocations:enabled', config('pterodactyl.client_features.allocations.enabled'))) selected @endif>Включить</option>
                                    </select>
                                    <p class="text-muted small">Если эта функция включена, пользователи получат возможность автоматически создавать новые распределения для своего сервера через фронтенд.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Начальный порт</label>
                                <div>
                                    <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_start" value="{{ old('pterodactyl:client_features:allocations:range_start', config('pterodactyl.client_features.allocations.range_start')) }}">
                                    <p class="text-muted small">Начальный порт в диапазоне, который может быть автоматически выделен.</p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Конечный порт</label>
                                <div>
                                    <input type="number" class="form-control" name="pterodactyl:client_features:allocations:range_end" value="{{ old('pterodactyl:client_features:allocations:range_end', config('pterodactyl.client_features.allocations.range_end')) }}">
                                    <p class="text-muted small">Конечный порт в диапазоне, который может быть автоматически выделен.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-footer">
                        {{ csrf_field() }}
                        <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
