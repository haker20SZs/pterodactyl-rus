@extends('layouts.admin')

@section('title')
    Узел &rarr; Новый
@endsection

@section('content-header')
    <h1>Новый Узел<small>Создайте новый локальный или удаленный узел для установки серверов.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nodes') }}">Узлы</a></li>
        <li class="active">Новый</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Основные детали</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Имя</label>
                        <input type="text" name="name" id="pName" class="form-control" value="{{ old('name') }}"/>
                        <p class="text-muted small">Ограничения символов: <code>a-zA-Z0-9_.-</code> и <code>[Пробел]</code> (мин 1, максимум 100 символов).</p>
                    </div>
                    <div class="form-group">
                        <label for="pDescription" class="form-label">Описание</label>
                        <textarea name="description" id="pDescription" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pLocationId" class="form-label">Локация</label>
                        <select name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Видимость узла</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> Общественная </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> Приватная </label>
                            </div>
                        </div>
                        <p class="text-muted small">Установив для узла значение <code>Приватная</code>, вы запретите возможность автоматического развертывания на этом узле.
                    </div>
                    <div class="form-group">
                        <label for="pFQDN" class="form-label">FQDN</label>
                        <input type="text" name="fqdn" id="pFQDN" class="form-control" value="{{ old('fqdn') }}"/>
                        <p class="text-muted small">Пожалуйста, введите доменное имя (например <code>node.example.com</code>) которое будет использоваться для подключения к демону. IP-адрес может использоваться <em>только</em> если Вы не используете SSL для этого узла.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Общайтесь через SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> Использовать SSL Подключение</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> Использовать HTTP Подключение</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">Ваша Panel в настоящее время настроена на использование безопасного соединения. Чтобы браузеры могли подключиться к вашему узлу, он <strong>должен</strong> использовать SSL-соединение.</p>
                        @else
                            <p class="text-muted small">В большинстве случаев вам следует выбрать использование SSL-соединения. Если вы используете IP-адрес или вообще не хотите использовать SSL, выберите HTTP-соединение.</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">За прокси</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> Не За Прокси </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> За Прокси </label>
                            </div>
                        </div>
                        <p class="text-muted small">Если вы запускаете демон через прокси-сервер, например Cloudflare, выберите этот вариант, чтобы демон не искал сертификаты при загрузке.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Конфигурация</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonBase" class="form-label">Каталог файлов демон-сервера</label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="form-control" value="/var/lib/pterodactyl/volumes" />
                            <p class="text-muted small">Введите каталог, в котором должны храниться файлы сервера. <strong>Если вы используете OVH, вам следует проверить схему разделов. Возможно, вам придется использовать <code>/home/daemon-data</code> чтобы иметь достаточно места.</strong></p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemory" class="form-label">Общая память</label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="form-control" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemoryOverallocate" class="form-label">Перераспределение памяти</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="form-control" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Введите общий объем памяти, доступной для новых серверов. Если вы хотите разрешить перераспределение памяти, введите процент, который вы хотите разрешить. Чтобы отключить проверку на перераспределение, введите в поле <code>-1</code>. Ввод <code>0</code> предотвратит создание новых серверов, если это приведет к превышению лимита узла.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDisk" class="form-label">Общее дисковое пространство</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="form-control" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">MiB</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDiskOverallocate" class="form-label">Перераспределение диска</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="form-control" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Введите общий объем дискового пространства, доступного для новых серверов. Если вы хотите разрешить перераспределение дискового пространства, введите процент, который вы хотите разрешить. Чтобы отключить проверку на перераспределение, введите в поле <code>-1</code>. Ввод <code>0</code> предотвратит создание новых серверов, если это приведет к превышению лимита для узла.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonListen" class="form-label">Порт демона</label>
                            <input type="text" name="daemonListen" class="form-control" id="pDaemonListen" value="8080" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDaemonSFTP" class="form-label">SFTP Порт демона</label>
                            <input type="text" name="daemonSFTP" class="form-control" id="pDaemonSFTP" value="2022" />
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Демон запускает собственный контейнер управления SFTP и не использует процесс SSHd на главном физическом сервере. <Strong>Не используйте тот же порт, который вы назначили для процесса SSH вашего физического сервера.</strong> Если вы будете запускать демон за CloudFlare&reg; вам следует установить порт демона на <code>8443</code> чтобы разрешить прокси-сервер веб-сокета через SSL.</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success pull-right">Создать Узел</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
