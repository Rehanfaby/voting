<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use App\Language;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        $allowed = ['en', 'fr'];
        if (!in_array($locale, $allowed, true)) {
            $locale = 'en';
        }
        setcookie('language', $locale, time() + (86400 * 365), '/', '', false, false);
        return Redirect::back();
    }
}
