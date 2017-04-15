<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App;

class LanguageController extends Controller
{
    //
    public function change($locale)
    {
        if(!Session::has('locale'))
        {
            Session::put('locale', $locale);
        } else {
            Session::put('locale', $locale);
        }
        return Redirect::back();
    }
}
