@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'mail'])

@section('title')
    Настройки почты
@endsection

@section('content-header')
    <h1>Настройки почты<small>Настройте, как Pterodactyl должен обрабатывать отправку электронной почты.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Администратор</a></li>
        <li class="active">Настройки</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Настройки электронной почты</h3>
                </div>
                @if($disabled)
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-info no-margin-bottom">
                                    Tего интерфейс ограничен экземплярами, использующими SMTP в качестве почтового драйвера. Пожалуйста, используйте <code>php artisan p:environment:mail</code> чтобы обновить настройки электронной почты, или установите <code>MAIL_DRIVER=smtp</code> в файле окружения.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="control-label">SMTP хост</label>
                                    <div>
                                        <input required type="text" class="form-control" name="mail:host" value="{{ old('mail:host', config('mail.host')) }}" />
                                        <p class="text-muted small">Введите адрес SMTP-сервера, через который будет отправляться почта.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="control-label">SMTP порт</label>
                                    <div>
                                        <input required type="number" class="form-control" name="mail:port" value="{{ old('mail:port', config('mail.port')) }}" />
                                        <p class="text-muted small">Введите порт SMTP-сервера, через который будет отправляться почта.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="control-label">Шифрование</label>
                                    <div>
                                        @php
                                            $encryption = old('mail:encryption', config('mail.encryption'));
                                        @endphp
                                        <select name="mail:encryption" class="form-control">
                                            <option value="" @if($encryption === '') selected @endif>Нету</option>
                                            <option value="tls" @if($encryption === 'tls') selected @endif>Безопасность транспортного уровня (TLS)</option>
                                            <option value="ssl" @if($encryption === 'ssl') selected @endif>Уровень защищенных сокетов (SSL)</option>
                                        </select>
                                        <p class="text-muted small">Имя пользователяВыберите тип шифрования, который будет использоваться при отправке почты.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Имя пользователя <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:username" value="{{ old('mail:username', config('mail.username')) }}" />
                                        <p class="text-muted small">Имя пользователя, которое будет использоваться при подключении к SMTP-серверу.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Пароль <span class="field-optional"></span></label>
                                    <div>
                                        <input type="password" class="form-control" name="mail:password"/>
                                        <p class="text-muted small">Пароль для использования в сочетании с именем пользователя SMTP. Оставьте пустым, чтобы продолжить использовать существующий пароль. Для установки пустого значения пароля введите <code>!e</code> в поле.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <hr />
                                <div class="form-group col-md-6">
                                    <label class="control-label">Почта от</label>
                                    <div>
                                        <input required type="email" class="form-control" name="mail:from:address" value="{{ old('mail:from:address', config('mail.from.address')) }}" />
                                        <p class="text-muted small">Введите адрес электронной почты, с которого будут отправляться все исходящие сообщения.</p>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="control-label">Почта от имени <span class="field-optional"></span></label>
                                    <div>
                                        <input type="text" class="form-control" name="mail:from:name" value="{{ old('mail:from:name', config('mail.from.name')) }}" />
                                        <p class="text-muted small">Имя, от которого должны приходить электронные письма.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            {{ csrf_field() }}
                            <div class="pull-right">
                                <button type="button" id="testButton" class="btn btn-sm btn-success">Тест</button>
                                <button type="button" id="saveButton" class="btn btn-sm btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @parent

    <script>
        function saveSettings() {
            return $.ajax({
                method: 'PATCH',
                url: '/admin/settings/mail',
                contentType: 'application/json',
                data: JSON.stringify({
                    'mail:host': $('input[name="mail:host"]').val(),
                    'mail:port': $('input[name="mail:port"]').val(),
                    'mail:encryption': $('select[name="mail:encryption"]').val(),
                    'mail:username': $('input[name="mail:username"]').val(),
                    'mail:password': $('input[name="mail:password"]').val(),
                    'mail:from:address': $('input[name="mail:from:address"]').val(),
                    'mail:from:name': $('input[name="mail:from:name"]').val()
                }),
                headers: { 'X-CSRF-Token': $('input[name="_token"]').val() }
            }).fail(function (jqXHR) {
                showErrorDialog(jqXHR, 'save');
            });
        }

        function testSettings() {
            swal({
                type: 'info',
                title: 'Настройки тестовой почты',
                text: 'Нажмите "Тест", чтобы начать тест.',
                showCancelButton: true,
                confirmButtonText: 'Тест',
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'POST',
                    url: '/admin/settings/mail/test',
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }
                }).fail(function (jqXHR) {
                    showErrorDialog(jqXHR, 'test');
                }).done(function () {
                    swal({
                        title: 'Успех',
                        text: 'Тестовое сообщение было отправлено успешно.',
                        type: 'success'
                    });
                });
            });
        }

        function saveAndTestSettings() {
            saveSettings().done(testSettings);
        }

        function showErrorDialog(jqXHR, verb) {
            console.error(jqXHR);
            var errorText = '';
            if (!jqXHR.responseJSON) {
                errorText = jqXHR.responseText;
            } else if (jqXHR.responseJSON.error) {
                errorText = jqXHR.responseJSON.error;
            } else if (jqXHR.responseJSON.errors) {
                $.each(jqXHR.responseJSON.errors, function (i, v) {
                    if (v.detail) {
                        errorText += v.detail + ' ';
                    }
                });
            }

            swal({
                title: 'Упс!',
                text: 'Произошла ошибка при попытке ' + verb + ' настройки почты: ' + errorText,
                type: 'error'
            });
        }

        $(document).ready(function () {
            $('#testButton').on('click', saveAndTestSettings);
            $('#saveButton').on('click', function () {
                saveSettings().done(function () {
                    swal({
                        title: 'Успех',
                        text: 'Настройки почты были успешно обновлены, и рабочий очереди был перезапущен для применения этих изменений.',
                        type: 'success'
                    });
                });
            });
        });
    </script>
@endsection
