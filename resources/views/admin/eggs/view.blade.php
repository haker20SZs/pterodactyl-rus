@extends('layouts.admin')

@section('title')
    Сервис &rarr; Ядро: {{ $egg->name }}
@endsection

@section('content-header')
    <h1>{{ $egg->name }}<small>{{ str_limit($egg->description, 50) }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nests') }}">Сервис</a></li>
        <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
        <li class="active">{{ $egg->name }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{ route('admin.nests.egg.view', $egg->id) }}">Конфигурация</a></li>
                <li><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">Переменные</a></li>
                <li><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">Установка скрипта</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group no-margin-bottom">
                                <label for="pName" class="control-label">Файл ядра</label>
                                <div>
                                    <input type="file" name="import_file" class="form-control" style="border: 0;margin-left:-10px;" />
                                    <p class="text-muted small no-margin-bottom">Если вы хотите изменить настройки этого ядра, загрузив новый файл JSON, просто выберите его здесь и нажмите «Обновить Ядро». Это не изменит существующие строки запуска или образы Docker для существующих серверов.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PUT" class="btn btn-sm btn-danger pull-right">Обновить Ядро</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
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
                                <label for="pName" class="control-label">Название <span class="field-required"></span></label>
                                <input type="text" id="pName" name="name" value="{{ $egg->name }}" class="form-control" />
                                <p class="text-muted small">Простое, понятное для человека имя, которое можно использовать в качестве идентификатора этого ядра.</p>
                            </div>
                            <div class="form-group">
                                <label for="pUuid" class="control-label">UUID</label>
                                <input type="text" id="pUuid" readonly value="{{ $egg->uuid }}" class="form-control" />
                                <p class="text-muted small">Это глобальный уникальный идентификатор этого ядра, который Демон использует в качестве идентификатора.</p>
                            </div>
                            <div class="form-group">
                                <label for="pAuthor" class="control-label">Автор</label>
                                <input type="text" id="pAuthor" readonly value="{{ $egg->author }}" class="form-control" />
                                <p class="text-muted small">Автор этой версии Ядра. Загрузка новой конфигурации ядра от другого автора изменит это.</p>
                            </div>
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Образ Докера <span class="field-required"></span></label>
                                <textarea id="pDockerImages" name="docker_images" class="form-control" rows="4">{{ implode(PHP_EOL, $images) }}</textarea>
                                <p class="text-muted small">
                                    Образы докера, доступные серверам, использующим это ядро. Введите по одному в строке. Пользователи
                                    смогут выбирать образ из этого списка, если указано более одного значения.
                                    При необходимости отображаемое имя можно указать, указав перед изображением имя, 
                                    за которым следует символ вертикальной черты, а затем URL-адрес образа. Пример: <code>Отображаемое имя|ghcr.io/my/egg</code>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" @if($egg->force_outgoing_ip) checked @endif />
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
                                <label for="pDescription" class="control-label">Описание</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ $egg->description }}</textarea>
                                <p class="text-muted small">Описание этого ядра, которое будет отображаться на Панели по мере необходимости.</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">Команды запуска <span class="field-required"></span></label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="8">{{ $egg->startup }}</textarea>
                                <p class="text-muted small">Команда запуска по умолчанию, которую следует использовать для новых серверов, использующих это ядро..</p>
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
                                <p>Следующие параметры конфигурации не следует редактировать, если вы не понимаете, как работает эта система. При неправильном изменении демон может сломаться.</p>
                                <p>Все поля являются обязательными, если вы не выберете отдельный параметр в раскрывающемся списке «Копировать настройки из». В этом случае поля можно оставить пустыми, чтобы использовать значения из этого ядра.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">Копировать настройки из</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">Нет</option>
                                    @foreach($egg->nest->eggs as $o)
                                        <option value="{{ $o->id }}" {{ ($egg->config_from !== $o->id) ?: 'selected' }}>{{ $o->name }} &lt;{{ $o->author }}&gt;</option>
                                    @endforeach
                                </select>
                                <p class="text-muted small">Если вы хотите использовать настройки по умолчанию из другого ядра, выберите его в меню выше.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">Команда остановки</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ $egg->config_stop }}" />
                                <p class="text-muted small">Команда, которую следует отправить серверным процессам, чтобы корректно остановить их. Если вам нужно отправить <code>SIGINT</code> вы должны ввести <code>^C</code> здесь.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">Конфигурация журнала</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ ! is_null($egg->config_logs) ? json_encode(json_decode($egg->config_logs), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление того, где хранятся файлы журналов, и должен ли демон создавать собственные журналы.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">Файлы конфигурации</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ ! is_null($egg->config_files) ? json_encode(json_decode($egg->config_files), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление файлов конфигурации, которые нужно изменить, и тех частей, которые следует изменить.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">Начать настройку</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ ! is_null($egg->config_startup) ? json_encode(json_decode($egg->config_startup), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление того, какие значения демон должен искать при загрузке сервера, чтобы определить завершение.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" name="_method" value="PATCH" class="btn btn-primary btn-sm pull-right">Сохранить</button>
                    <a href="{{ route('admin.nests.egg.export', $egg->id) }}" class="btn btn-sm btn-info pull-right" style="margin-right:10px;">Экспорт</a>
                    <button id="deleteButton" type="submit" name="_method" value="DELETE" class="btn btn-danger btn-sm muted muted-hover">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pConfigFrom').select2();
    $('#deleteButton').on('mouseenter', function (event) {
        $(this).find('i').html(' Удалить Ядро');
    }).on('mouseleave', function (event) {
        $(this).find('i').html('');
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
