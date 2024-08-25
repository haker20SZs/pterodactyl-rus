{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Гнезда &rarr; Новое яйцо
@endsection

@section('content-header')
    <h1>Новое яйцо<small>Создайте новое яйцо, чтобы назначить его серверам.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Администратор</a></li>
        <li><a href="{{ route('admin.nests') }}">Гнезда</a></li>
        <li class="active">Новое яйцо</li>
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
                                <label for="pNestId" class="form-label">Ассоциированное гнездо</label>
                                <div>
                                    <select name="nest_id" id="pNestId">
                                        @foreach($nests as $nest)
                                            <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted small">Воспринимайте гнездо как категорию. Вы можете поместить в гнездо несколько яиц, но подумайте о том, чтобы поместить в каждое гнездо только те яйца, которые связаны друг с другом.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pName" class="form-label">Имя</label>
                                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="form-control" />
                                <p class="text-muted small">Простое, читаемое человеком имя, которое будет использоваться в качестве идентификатора для этого яйца. Это то, что пользователи будут видеть как тип их игрового сервера.</p>
                            </div>
                            <div class="form-group">
                                <label for="pDescription" class="form-label">Описание</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ old('description') }}</textarea>
                                <p class="text-muted small">Описание этого яйца.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Образы Docker</label>
                                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="quay.io/pterodactyl/service" class="form-control">{{ old('docker_images') }}</textarea>
                                <p class="text-muted small">Образы docker, доступные для серверов, использующих это яйцо. Введите по одному в каждой строке. Пользователи смогут выбирать из этого списка образов, если указано более одного значения.</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">Команда запуска</label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="10">{{ old('startup') }}</textarea>
                                <p class="text-muted small">Команда запуска по умолчанию, которая должна использоваться для новых серверов, созданных с помощью этого Egg. При необходимости вы можете изменить это значение для каждого сервера.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Process Management</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <p>Все поля обязательны для заполнения, если только вы не выбрали отдельный вариант в раскрывающемся списке 'Копировать настройки из', в этом случае поля можно оставить пустыми, чтобы использовать значения из этого варианта.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">Копирование настроек из</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">Нету</option>
                                </select>
                                <p class="text-muted small">Если вы хотите использовать по умолчанию настройки из другого яйца, выберите его из выпадающего списка выше.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStop" class="form-label">Команда "Стоп"</label>
                                <input type="text" id="pConfigStop" name="config_stop" class="form-control" value="{{ old('config_stop') }}" />
                                <p class="text-muted small">Команда, которая должна быть отправлена серверным процессам для их плавной остановки. Если вам нужно отправить <code>SIGINT</code> вы должны ввести <code>^C</code> здесь.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigLogs" class="form-label">Конфигурация журнала</label>
                                <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-control" rows="6">{{ old('config_logs') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление того, где хранятся файлы журналов, и должен ли демон создавать пользовательские журналы.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFiles" class="form-label">Конфигурационные файлы</label>
                                <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-control" rows="6">{{ old('config_files') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление конфигурационных файлов, которые нужно изменить и какие части должны быть изменены.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigStartup" class="form-label">Начальная конфигурация</label>
                                <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-control" rows="6">{{ old('config_startup') }}</textarea>
                                <p class="text-muted small">Это должно быть JSON-представление значений, которые демон должен искать при загрузке сервера для определения завершения.</p>
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
