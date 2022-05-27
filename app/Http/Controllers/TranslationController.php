<?php

namespace App\Http\Controllers;

use Alangiacomin\LaravelBasePack\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TranslationController extends Controller
{
    public function getTranslation(string $locale, string $namespace)
    {
        $fallbackFile = lang_path(config('app.fallback_locale') . "/" . $namespace.".php");
        $langFile = lang_path(($locale ?: App::currentLocale()) . "/" . $namespace . ".php");
        return include(file_exists($langFile) ? $langFile : $fallbackFile);
    }

    public function setLocale(Request $request)
    {
        App::setLocale($request->input('locale'));
        session()->put('locale', $request->input('locale'));
    }
}
