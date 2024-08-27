
@extends('layouts.admin')

@section('title')
    Подключения
@endsection

@section('content-header')
    <h1>Подключения<small>Настраивайте и управляйте дополнительными точками монтирования для серверов.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li class="active">Подключения</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Список подключений</h3>

                    <div class="box-tools">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newMountModal">Создать Новое</button>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Источник</th>
                                <th>Цель</th>
                                <th class="text-center">Ядро</th>
                                <th class="text-center">Узел</th>
                                <th class="text-center">Сервера</th>
                            </tr>

                            @foreach ($mounts as $mount)
                                <tr>
                                    <td><code>{{ $mount->id }}</code></td>
                                    <td><a href="{{ route('admin.mounts.view', $mount->id) }}">{{ $mount->name }}</a></td>
                                    <td><code>{{ $mount->source }}</code></td>
                                    <td><code>{{ $mount->target }}</code></td>
                                    <td class="text-center">{{ $mount->eggs_count }}</td>
                                    <td class="text-center">{{ $mount->nodes_count }}</td>
                                    <td class="text-center">{{ $mount->servers_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newMountModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.mounts') }}" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color: #FFFFFF">&times;</span>
                        </button>

                        <h4 class="modal-title">Создать подключение</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="pName" class="form-label">Название</label>
                                <input type="text" id="pName" name="name" class="form-control" />
                                <p class="text-muted small">Уникальное имя, используемое для отделения этого средства передвижения от другого.</p>
                            </div>

                            <div class="col-md-12">
                                <label for="pDescription" class="form-label">Описание</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="4"></textarea>
                                <p class="text-muted small">Более подробное описание этого средства передвижения должно содержать менее 191 символа.</p>
                            </div>

                            <div class="col-md-6">
                                <label for="pSource" class="form-label">Источник</label>
                                <input type="text" id="pSource" name="source" class="form-control" />
                                <p class="text-muted small">Путь к файлу в хост-системе для подключения к контейнеру.</p>
                            </div>

                            <div class="col-md-6">
                                <label for="pTarget" class="form-label">Цель</label>
                                <input type="text" id="pTarget" name="target" class="form-control" />
                                <p class="text-muted small">Где крепление будет доступно внутри контейнера.</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Только чтение</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pReadOnlyFalse" name="read_only" value="0" checked>
                                        <label for="pReadOnlyFalse">Нет</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pReadOnly" name="read_only" value="1">
                                        <label for="pReadOnly">Да</label>
                                    </div>
                                </div>

                                <p class="text-muted small">Является ли монтирование доступным только для чтения внутри контейнера?</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Устанавливается пользователем</label>

                                <div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="pUserMountableFalse" name="user_mountable" value="0" checked>
                                        <label for="pUserMountableFalse">Нет</label>
                                    </div>

                                    <div class="radio radio-warning radio-inline">
                                        <input type="radio" id="pUserMountable" name="user_mountable" value="1">
                                        <label for="pUserMountable">Да</label>
                                    </div>
                                </div>

                                <p class="text-muted small">Должны ли пользователи иметь возможность установить это самостоятельно?</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {!! csrf_field() !!}
                        <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success btn-sm">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
