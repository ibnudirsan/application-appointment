<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

function setting($key) {
    $setting = Cache::rememberForever("setting", function () {
        return Setting::pluck('value', 'key')->all();
    });
        if(! $setting) {
            $setting = config('setting.default');
        }
            return $setting[$key] ?? false;
}

