@extends('layouts.admin')

@section('title')
    Database Hosts
@endsection

@section('content-header')
    <h1>Хосты базы данных<small> Хосты баз данных, на которых серверы могут создавать базы данных.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li class="active">Хосты базы данных</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Список хостов</h3>
                <div class="box-tools">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newHostModal">Создавать новый</button>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Хост</th>
                            <th>Порт</th>
                            <th>Пользователь</th>
                            <th class="text-center">База Данных</th>
                            <th class="text-center">Нода</th>
                        </tr>
                        @foreach ($hosts as $host)
                            <tr>
                                <td><code>{{ $host->id }}</code></td>
                                <td><a href="{{ route('admin.databases.view', $host->id) }}">{{ $host->name }}</a></td>
                                <td><code>{{ $host->host }}</code></td>
                                <td><code>{{ $host->port }}</code></td>
                                <td>{{ $host->username }}</td>
                                <td class="text-center">{{ $host->databases_count }}</td>
                                <td class="text-center">
                                    @if(! is_null($host->node))
                                        <a href="{{ route('admin.nodes.view', $host->node->id) }}">{{ $host->node->name }}</a>
                                    @else
                                        <span class="label label-default">Нет</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newHostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.databases') }}" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Создать новый хост базы данных</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Имя</label>
                        <input type="text" name="name" id="pName" class="form-control" />
                        <p class="text-muted small">Короткий идентификатор, используемый для отличия этого местоположения от других. Должно быть от 1 до 60 символов, например: <code>us.nyc.lvl3</code>.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pHost" class="form-label">Хост</label>
                            <input type="text" name="host" id="pHost" class="form-control" />
                            <p class="text-muted small">IP-адрес или полное доменное имя, которое следует использовать при попытке подключения к этому хосту MySQL <em>из панели</em> для добавления новых баз данных.</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPort" class="form-label">Порт</label>
                            <input type="text" name="port" id="pPort" class="form-control" value="3306"/>
                            <p class="text-muted small">Порт, на котором MySQL работает для этого хоста.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="pUsername" class="form-label">Пользователь</label>
                            <input type="text" name="username" id="pUsername" class="form-control" />
                            <p class="text-muted small">Имя пользователя учетной записи, имеющей достаточные разрешения для создания новых пользователей и баз данных в системе.</p>
                        </div>
                        <div class="col-md-6">
                            <label for="pPassword" class="form-label">Пароль</label>
                            <input type="password" name="password" id="pPassword" class="form-control" />
                            <p class="text-muted small">Пароль к аккаунту определен.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">Связанная нода</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="">Нет</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">Этот параметр не делает ничего, кроме значения по умолчанию для этого хоста базы данных при добавлении базы данных на сервер на выбранном узле.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-danger small text-left">Учетная запись, определенная для этого хоста базы данных <strong>должна</strong> иметь <code>WITH GRANT OPTION</code> разрешение. Если определенная учетная запись не имеет этого разрешения, запросы на создание баз данных <em>не будут</em> выполнены. <strong>Не используйте те же данные учетной записи MySQL, которые вы определили для этой панели.</strong></p>
                    {!! csrf_field() !!}
                    <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-success btn-sm">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2();
    </script>
@endsection
