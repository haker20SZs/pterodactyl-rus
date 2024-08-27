@extends('layouts.admin')

@section('title')
    Сервис &rarr; Новое Ядро
@endsection

@section('content-header')
    <h1>Новое Ядро<small>Создайте новое ядро для назначения серверам.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nests') }}">Сервис</a></li>
        <li class="active">Новое Ядро</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Конфигурация</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pNestId" class="form-label">Связанный сервис</label>
                                <div>
                                    <select name="nest_id" id="pNestId">
                                        @foreach($nests as $nest)
                                            <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted small">Подумайте о сервисе как о категории. Вы можете поместить несколько ядер в сервис, но рассмотрите возможность размещения в каждом сервисе только тех ядер, которые связаны друг с другом.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pName" class="form-label">Название</label>
                                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="form-control" />
                                <p class="text-muted small">Простое, понятное для человека имя, которое можно использовать в качестве идентификатора этого ядра. Это то, что пользователи будут видеть в качестве типа игрового сервера.</p>
                            </div>
                            <div class="form-group">
                                <label for="pDescription" class="form-label">Описание</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ old('description') }}</textarea>
                                <p class="text-muted small">Описание этого ядра.</p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" {{ \Pterodactyl\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
                                    <label for="pForceOutgoingIp" class="strong">Принудительно исходящий IP-адрес</label>
                                    <p class="text-muted small">
                                        Принудительно преобразует исходный IP-адрес всего исходящего сетевого трафика в IP-адрес основного IP-адреса сервера.
                                        Требуется для правильной работы некоторых игр, когда узел имеет несколько общедоступных IP-адресов.
                                        <br>
                                        <strong>
                                            Включение этой опции отключит внутреннюю сеть для всех серверов, использующих это ядро,
                                            в результате чего они не смогут получить внутренний доступ к другим серверам на том же узле.
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Образ Докера</label>
                                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="quay.io/pterodactyl/service" class="form-control">{{ old('docker_images') }}</textarea>
                                <p class="text-muted small">Образы докера, доступные серверам, использующим это ядро. Введите по одному в строке. Пользователи смогут выбирать образы из этого списка, если указано более одного значения.</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">Команда Запуска</label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="10">{{ old('startup') }}</textarea>
                                <p class="text-muted small">Команда запуска по умолчанию, которую следует использовать для новых серверов, созданных с помощью этого ядра. При необходимости вы можете изменить это значение для каждого сервера.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Управление процессом</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <p>Все поля являются обязательными, если только вы не выберете отдельный параметр в раскрывающемся списке «Копировать настройки из». В этом случае поля можно оставить пустыми, чтобы использовать значения из этого параметра.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">Копировать настройки из</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">Нет</option>
                                </select>
                                <p class="text-muted small">Если вы хотите использовать настройки по умолчанию из другого Egg, выберите его из раскрывающегося списка выше.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">Команда остановки</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ old('config_stop') }}" />
                                <p class="text-muted small">Команда, которую следует отправить серверным процессам, чтобы корректно остановить их. Если вам нужно отправить <code>SIGINT</code> вы должны ввести <code>^C</code> здесь.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">Конфигурация журнала</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ old('config_logs') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление того, где хранятся файлы журналов, и должен ли демон создавать собственные журналы.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">Файлы конфигурации</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ old('config_files') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление файлов конфигурации, которые нужно изменить, и тех частей, которые следует изменить.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">Начать настройку</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ old('config_startup') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление того, какие значения демон должен искать при загрузке сервера, чтобы определить завершение.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success btn-sm pull-right">Создать</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('#pNestId').select2().change();
        $('#pConfigFrom').select2();
    });
    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">Нет</option>').select2({
            data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
    });
    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    </script>
@endsection
