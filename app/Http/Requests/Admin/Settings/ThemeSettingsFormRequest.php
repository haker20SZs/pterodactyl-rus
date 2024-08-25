<?php

namespace Pterodactyl\Http\Requests\Admin\Settings;

use Illuminate\Validation\Rule;
use Pterodactyl\Http\Requests\Admin\AdminFormRequest;

class ThemeSettingsFormRequest extends AdminFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'pterodactyl:auth:register' => 'required|in:true,false',
            'app:logo' => 'required|string|max:191',
            'pterodactyl:colors:button-background' => 'required|string|regex:/(#[0-9a-fA-F]{6})/',
            'pterodactyl:colors:button-border' => 'required|string|regex:/(#[0-9a-fA-F]{6})/',
            'pterodactyl:colors:button-hover-background' => 'required|string|regex:/(#[0-9a-fA-F]{6})/',
            'pterodactyl:colors:button-hover-border' => 'required|string|regex:/(#[0-9a-fA-F]{6})/',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'pterodactyl:auth:register' => 'Регистрация',
            'app:logo' => 'Логотип компании',
            'pterodactyl:colors:button-background' => 'Цвет фона кнопки',
            'pterodactyl:colors:button-border' => 'Цвет границы кнопки',
            'pterodactyl:colors:button-hover-background' => 'Цвет фона навешенной кнопки',
            'pterodactyl:colors:button-hover-border' => 'Цвет границы наведенной кнопки',
        ];
    }
}
