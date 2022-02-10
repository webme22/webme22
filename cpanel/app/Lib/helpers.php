<?php
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

if (! function_exists("menu_active")){
    function menu_active($routes){
        foreach($routes as $route){
            try {
                if (Request::fullUrl() != route($route) && Request::route()->getName() == $route) {
                    return true;
                }
            }
            catch (RouteNotFoundException $e){
                if (Request::fullUrl() != route(str_replace('*', 'index', $route)) && Route::is($route)){
                    return true;
                }
            }
        }
        return false;
    }
}
if (! function_exists("languages_list")){
    function languages_list(){
        $all_langs = LaravelLocalization::getSupportedLanguagesKeys();
        $default_lang = config('app.fallback_locale');
        $all_langs = array_filter($all_langs, function ($val) use ($default_lang) {return $val != $default_lang;});
        return $all_langs;
    }

}
