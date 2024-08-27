@extends('layouts.admin')

@section('title')
    Server — {{ $server->name }}: Build Details
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Control allocations and system resources for this server.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.servers') }}">Сервера</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Конфигурация сборки</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <form action="{{ route('admin.servers.view.build', $server->id) }}" method="POST">
        <div class="col-sm-5">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Управление ресурсами</h3>
                </div>
                <div class="box-body">
                <div class="form-group">
                        <label for="cpu" class="control-label">Ограничение процессора</label>
                        <div class="input-group">
                            <input type="text" name="cpu" class="form-control" value="{{ old('cpu', $server->cpu) }}"/>
                            <span class="input-group-addon">%</span>
                        </div>
                        <p class="text-muted small">Каждое <em>виртуальное</em> ядро ​​(поток) в системе считается <code>100%</code>. Установка этого значения на <code>0</code> позволит серверу использовать процессорное время без ограничений.</p>
                    </div>
                    <div class="form-group">
                        <label for="threads" class="control-label">Закрепление процессора</label>
                        <div>
                            <input type="text" name="threads" class="form-control" value="{{ old('threads', $server->threads) }}"/>
                        </div>
                        <p class="text-muted small"><strong>Расширенные:</strong> Введите конкретные ядра ЦП, на которых может выполняться этот процесс, или оставьте пустым, чтобы разрешить все ядра. Это может быть одно число или список, разделенный запятыми. Пример <code>0</code>, <code>0-1,3</code>, или <code>0,1,3,4</code>.</p>
                    </div>
                    <div class="form-group">
                        <label for="memory" class="control-label">Выделенная память</label>
                        <div class="input-group">
                            <input type="text" name="memory" data-multiplicator="true" class="form-control" value="{{ old('memory', $server->memory) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted small">Максимальный объем памяти, разрешенный для этого контейнера. Установка значения <code>0</code> позволит использовать неограниченный объем памяти в контейнере.</p>
                    </div>
                    <div class="form-group">
                        <label for="swap" class="control-label">Выделенная подкачка</label>
                        <div class="input-group">
                            <input type="text" name="swap" data-multiplicator="true" class="form-control" value="{{ old('swap', $server->swap) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted small">Установка значения на <code>0</code> отключит подкачку на этом сервере. Установка значения на <code>-1</code> разрешит неограниченную подкачку.</p>
                    </div>
                    <div class="form-group">
                        <label for="cpu" class="control-label">Ограничение дискового пространства</label>
                        <div class="input-group">
                            <input type="text" name="disk" class="form-control" value="{{ old('disk', $server->disk) }}"/>
                            <span class="input-group-addon">MiB</span>
                        </div>
                        <p class="text-muted small">Этому серверу не будет разрешено загружаться, если он использует больше указанного объема пространства. Если сервер превысит этот предел во время работы, он будет безопасно остановлен и заблокирован до тех пор, пока не освободится достаточно места. Установите на <code>0</code> чтобы разрешить неограниченное использование диска.</p>
                    </div>
                    <div class="form-group">
                        <label for="io" class="control-label">Пропорция блока ввода-вывода</label>
                        <div>
                            <input type="text" name="io" class="form-control" value="{{ old('io', $server->io) }}"/>
                        </div>
                        <p class="text-muted small"><strong>Advanced</strong>: Производительность ввода-вывода этого сервера по сравнению с другими <em>работающими</em> контейнерами в системе. Значение должно быть от <code>10</code> до <code>1000</code>.</code></p>
                    </div>
                    <div class="form-group">
                        <label for="cpu" class="control-label">ООМ киллер</label>
                        <div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pOomKillerEnabled" value="0" name="oom_disabled" @if(!$server->oom_disabled)checked @endif>
                                <label for="pOomKillerEnabled">Включен</label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pOomKillerDisabled" value="1" name="oom_disabled" @if($server->oom_disabled)checked @endif>
                                <label for="pOomKillerDisabled">Выключен</label>
                            </div>
                            <p class="text-muted small">
                                Включение OOM Killer может привести к неожиданному завершению серверных процессов.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ограничения функций приложения</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="database_limit" class="control-label">Лимит баз данных</label>
                                    <div>
                                        <input type="text" name="database_limit" class="form-control" value="{{ old('database_limit', $server->database_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество баз данных, которые пользователю разрешено создать для этого сервера.</p>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="allocation_limit" class="control-label">Лимит распределения</label>
                                    <div>
                                        <input type="text" name="allocation_limit" class="form-control" value="{{ old('allocation_limit', $server->allocation_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество выделений, которые пользователю разрешено создать для этого сервера.</p>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="backup_limit" class="control-label">Лимит резервного копирования</label>
                                    <div>
                                        <input type="text" name="backup_limit" class="form-control" value="{{ old('backup_limit', $server->backup_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество резервных копий, которые можно создать для этого сервера.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Управление распределением</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="pAllocation" class="control-label">Игровой порт</label>
                                <select id="pAllocation" name="allocation_id" class="form-control">
                                    @foreach ($assigned as $assignment)
                                        <option value="{{ $assignment->id }}"
                                            @if($assignment->id === $server->allocation_id)
                                                selected="selected"
                                            @endif
                                        >{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted small">Адрес подключения по умолчанию, который будет использоваться для этого игрового сервера.</p>
                            </div>
                            <div class="form-group">
                                <label for="pAddAllocations" class="control-label">Назначьте дополнительные порты</label>
                                <div>
                                    <select name="add_allocations[]" class="form-control" multiple id="pAddAllocations">
                                        @foreach ($unassigned as $assignment)
                                            <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-muted small">Обратите внимание, что из-за ограничений программного обеспечения вы не можете назначать одинаковые порты на разных IP-адресах одному и тому же серверу.</p>
                            </div>
                            <div class="form-group">
                                <label for="pRemoveAllocations" class="control-label">Удалить дополнительные порты</label>
                                <div>
                                    <select name="remove_allocations[]" class="form-control" multiple id="pRemoveAllocations">
                                        @foreach ($assigned as $assignment)
                                            <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-muted small">Просто выберите порты, которые вы хотите удалить, из списка выше. Если вы хотите назначить порт на другом IP-адресе, который уже используется, вы можете выбрать его слева и удалить здесь.</p>
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-primary pull-right">Обновить конфигурацию сборки</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#pAddAllocations').select2();
    $('#pRemoveAllocations').select2();
    $('#pAllocation').select2();
    </script>
@endsection
