@extends('layouts.admin')

@section('title')
    Database Hosts &rarr; View &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <h1>{{ $host->name }}<small> Просмотр связанных баз данных и сведений об этом хосте базы данных.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.databases') }}">Хосты базы данных</a></li>
        <li class="active">{{ $host->name }}</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Сведения о хосте</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Имя</label>
                        <input type="text" id="pName" name="name" class="form-control" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div class="form-group">
                        <label for="pHost" class="form-label">Хост</label>
                        <input type="text" id="pHost" name="host" class="form-control" value="{{ old('host', $host->host) }}" />
                        <p class="text-muted small">IP-адрес или полное доменное имя, которое следует использовать при попытке подключения к этому хосту MySQL <em>из панели</em> для добавления новых баз данных.</p>
                    </div>
                    <div class="form-group">
                        <label for="pPort" class="form-label">Порт</label>
                        <input type="text" id="pPort" name="port" class="form-control" value="{{ old('port', $host->port) }}" />
                        <p class="text-muted small">Порт, на котором MySQL работает для этого хоста.</p>
                    </div>
                    <div class="form-group">
                        <label for="pNodeId" class="form-label">Связанный узел</label>
                        <select name="node_id" id="pNodeId" class="form-control">
                            <option value="">Нет</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-muted small">Этот параметр не делает ничего, кроме значения по умолчанию для этого хоста базы данных при добавлении базы данных на сервер на выбранном узле.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Данные пользователя</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pUsername" class="form-label">Имя пользователя</label>
                        <input type="text" name="username" id="pUsername" class="form-control" value="{{ old('username', $host->username) }}" />
                        <p class="text-muted small">Имя пользователя учетной записи, имеющей достаточные разрешения для создания новых пользователей и баз данных в системе.</p>
                    </div>
                    <div class="form-group">
                        <label for="pPassword" class="form-label">Пароль</label>
                        <input type="password" name="password" id="pPassword" class="form-control" />
                        <p class="text-muted small">Пароль к аккаунту определения. Оставьте поле пустым, чтобы продолжить использование назначенного пароля.</p>
                    </div>
                    <hr />
                    <p class="text-danger small text-left">Учетная запись, определенная для этого хоста базы данных, <strong>должна</strong> иметь <code>WITH GRANT OPTION</code> разрешения. Если определенная учетная запись не имеет этого разрешения, запросы на создание баз данных <em>будут </em> выполнены. <strong>Не используйте те же данные учетной записи MySQL, которые вы определили для этой панели.</strong></p>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">Сохранить</button>
                    <button name="_method" value="DELETE" class="btn btn-sm btn-danger pull-left muted muted-hover"><i class="fa fa-trash-o"></i></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">База данных</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>Сервер</th>
                        <th>Название Базыданных</th>
                        <th>Пользователь</th>
                        <th>Соединения от</th>
                        <th>Максимальное количество подключений</th>
                        <th></th>
                    </tr>
                    @foreach($databases as $database)
                        <tr>
                            <td class="middle"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="middle">{{ $database->database }}</td>
                            <td class="middle">{{ $database->username }}</td>
                            <td class="middle">{{ $database->remote }}</td>
                            @if($database->max_connections != null)
                                <td class="middle">{{ $database->max_connections }}</td>
                            @else
                                <td class="middle">Безлимитный</td>
                            @endif
                            <td class="text-center">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}">
                                    <button class="btn btn-xs btn-primary">Управлять</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if($databases->hasPages())
                <div class="box-footer with-border">
                    <div class="col-md-12 text-center">{!! $databases->render() !!}</div>
                </div>
            @endif
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
