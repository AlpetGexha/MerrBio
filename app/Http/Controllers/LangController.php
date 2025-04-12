<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LangController extends Controller
{
    public function setLocale($lang){
        if(in_array($lang,['en','al'])){
            App::setLocale($lang);
            Session::put('locale',$lang);
        }
        return redirect()->back();
      }
}
