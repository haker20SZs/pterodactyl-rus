<?php

namespace Pterodactyl\Http\ViewComposers;

use Illuminate\View\View;
use Pterodactyl\Services\Helpers\AssetHashService;

class AssetComposer
{
    /**
     * @var \Pterodactyl\Services\Helpers\AssetHashService
     */
    private $assetHashService;

    /**
     * AssetComposer constructor.
     */
    public function __construct(AssetHashService $assetHashService)
    {
        $this->assetHashService = $assetHashService;
    }

    /**
     * Provide access to the asset service in the views.
     */
    public function compose(View $view)
    {
        $view->with('asset', $this->assetHashService);
        $view->with('siteConfiguration', [
            'name' => config('app.name') ?? 'WestalHost',
            'logo' => config('app.logo') ?? '/favicons/favicon-96x96.png',
            'locale' => config('app.locale') ?? 'ru',
            'recaptcha' => [
                'enabled' => config('recaptcha.enabled', false),
                'siteKey' => config('recaptcha.website_key') ?? '',
            ],
            'register' => config('pterodactyl.auth.register', false),
            'registered' => '1369'
        ]);
    }
}
