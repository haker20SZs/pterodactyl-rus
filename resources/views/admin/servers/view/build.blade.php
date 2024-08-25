{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    Сервер — {{ $server->name }}: Детали Конфигурации
@endsection

@section('content-header')
    <h1>{{ $server->name }}<small>Управляйте расположениями и системными ресурсами для этого сервера.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Администрация</a></li>
        <li><a href="{{ route('admin.servers') }}">Сервера</a></li>
        <li><a href="{{ route('admin.servers.view', $server->id) }}">{{ $server->name }}</a></li>
        <li class="active">Детали конфигурации</li>
    </ol>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="row">
    <form action="{{ route('admin.servers.view.build', $server->id) }}" method="POST">
        <div class="col-sm-5">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Менеджер ресурсов</h3>
                </div>
                <div class="box-body">
                <div class="form-group">
                        <label for="cpu" class="control-label">Лимит ЦПУ</label>
                        <div class="input-group">
                            <input type="text" name="cpu" class="form-control" value="{{ old('cpu', $server->cpu) }}"/>
                            <span class="input-group-addon">%</span>
                        </div>
                        <p class="text-muted small">Каждое <em>физическое</em> ядро в системе считается <code>100%</code>. Установив это значение на <code>0</code> позволит серверу использовать процессорное время без ограничений.</p>
                    </div>
                    <div class="form-group">
                        <label for="threads" class="control-label">Ядра ЦПУ</label>
                        <div>
                            <input type="text" name="threads" class="form-control" value="{{ old('threads', $server->threads) }}"/>
                        </div>
                        <p class="text-muted small"><strong>Расширенный:</strong> Введите конкретные ядра процессора, на которых может работать этот процесс, или оставьте пустым, чтобы разрешить все ядра. Это может быть одно число или список, разделенный запятыми. Пример: <code>0</code>, <code>0-1,3</code>, или <code>0,1,3,4</code>.</p>
                    </div>
                    <div class="form-group">
                        <label for="memory" class="control-label">Выделенная память</label>
                        <div class="input-group">
                            <input type="text" name="memory" data-multiplicator="true" class="form-control" value="{{ old('memory', $server->memory) }}"/>
                            <span class="input-group-addon">МегаБайт</span>
                        </div>
                        <p class="text-muted small">Максимальный объем памяти, допустимый для этого контейнера. Установка этого значения в <code>0</code> позволит использовать неограниченное количество памяти в контейнере.</p>
                    </div>
                    <div class="form-group">
                        <label for="swap" class="control-label">Распределенный своп</label>
                        <div class="input-group">
                            <input type="text" name="swap" data-multiplicator="true" class="form-control" value="{{ old('swap', $server->swap) }}"/>
                            <span class="input-group-addon">MB</span>
                        </div>
                        <p class="text-muted small">Установите это значение на <code>0</code> отключит пространство подкачки на этом сервере. Установка в <code>-1</code> позволит осуществлять неограниченный обмен.</p>
                    </div>
                    <div class="form-group">
                        <label for="cpu" class="control-label">Ограничение дискового пространства</label>
                        <div class="input-group">
                            <input type="text" name="disk" class="form-control" value="{{ old('disk', $server->disk) }}"/>
                            <span class="input-group-addon">MB</span>
                        </div>
                        <p class="text-muted small">Этому серверу будет запрещено загружаться, если он использует больше указанного объема пространства. Если сервер превысит этот лимит во время работы, он будет безопасно остановлен и заблокирован до тех пор, пока не освободится достаточно места. Установите на <code>0</code> чтобы разрешить неограниченное использование диска.</p>
                    </div>
                    <div class="form-group">
                        <label for="io" class="control-label">Блок IO Пропорция</label>
                        <div>
                            <input type="text" name="io" class="form-control" value="{{ old('io', $server->io) }}"/>
                        </div>
                        <p class="text-muted small"><strong>Расширенный</strong>: Производительность ввода-вывода этого сервера по сравнению с другими <em>работающими</em>. контейнеров в системе. Значение должно быть между <code>10</code> и <code>1000</code>.</code></p>
                    </div>
                    <div class="form-group">
                        <label for="cpu" class="control-label">Убийца ООМ</label>
                        <div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pOomKillerEnabled" value="0" name="oom_disabled" @if(!$server->oom_disabled)checked @endif>
                                <label for="pOomKillerEnabled">Включить</label>
                            </div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pOomKillerDisabled" value="1" name="oom_disabled" @if($server->oom_disabled)checked @endif>
                                <label for="pOomKillerDisabled">Отключить</label>
                            </div>
                            <p class="text-muted small">
                                Включение OOM killer может привести к неожиданному завершению серверных процессов.
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
                            <h3 class="box-title">Ограничения характеристик приложения</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="database_limit" class="control-label">Предел базы данных</label>
                                    <div>
                                        <input type="text" name="database_limit" class="form-control" value="{{ old('database_limit', $server->database_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество баз данных, которые пользователь может создать для этого сервера.ы</p>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="allocation_limit" class="control-label">Лимит распределения</label>
                                    <div>
                                        <input type="text" name="allocation_limit" class="form-control" value="{{ old('allocation_limit', $server->allocation_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество распределений, которое пользователь может создать для этого сервера.</p>
                                </div>
                                <div class="form-group col-xs-6">
                                    <label for="backup_limit" class="control-label">Предел резервного копирования</label>
                                    <div>
                                        <input type="text" name="backup_limit" class="form-control" value="{{ old('backup_limit', $server->backup_limit) }}"/>
                                    </div>
                                    <p class="text-muted small">Общее количество резервных копий, которые могут быть созданы для этого сервера.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Менеджер Распределений</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="pAllocation" class="control-label">Игровые порты</label>
                                <select id="pAllocation" name="allocation_id" class="form-control">
                                    @foreach ($assigned as $assignment)
                                        <option value="{{ $assignment->id }}"
                                            @if($assignment->id === $server->allocation_id)
                                                selected="selected"
                                            @endif
                                        >{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted small">Этот адрес стоит по умолчанию для данного сервера</p>
                            </div>
                            <div class="form-group">
                                <label for="pAddAllocations" class="control-label">Добавить дополнительные Распределения</label>
                                <div>
                                    <select name="add_allocations[]" class="form-control" multiple id="pAddAllocations">
                                        @foreach ($unassigned as $assignment)
                                            <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-muted small">Пожалуйста, обратите внимание, что из-за ограничений программного обеспечения вы не можете назначить одинаковые порты на разных IP-адресах одному и тому же серверу.</p>
                            </div>
                            <div class="form-group">
                                <label for="pRemoveAllocations" class="control-label">Удалить дополнительный Распределения</label>
                                <div>
                                    <select name="remove_allocations[]" class="form-control" multiple id="pRemoveAllocations">
                                        @foreach ($assigned as $assignment)
                                            <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-muted small">Просто выберите, какие порты вы хотели бы удалить из списка выше. Если вы хотите назначить порт на другом IP-адресе, который уже используется, вы можете выбрать его слева и удалить здесь.</p>
                            </div>
                        </div>
                        <div class="box-footer">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-primary pull-right">Обновить конфигурацию запуска</button>
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
