@section('settings::notice')
    @if(config('pterodactyl.load_environment_only', false))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger">
                    В настоящее время ваша панель настроена только на чтение настроек из среды.
                    Вам нужно установить значение <code>APP_ENVIRONMENT_ONLY=false</code> в вашем файле окружения, чтобы загружать настройки динамически.
                </div>
            </div>
        </div>
    @endif
@endsection
