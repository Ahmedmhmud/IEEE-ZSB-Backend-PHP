<?php

use Core\Response;

function dd($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function urlIs($url){
    return $_SERVER['REQUEST_URI'] === $url;
}

function abort($code = 404) {
    http_response_code($code);
        view("{$code}.php", [
            'heading' => "Go Back"
        ]);
        die();
}

function authorize($condition, $status = Response::FORBIDDEN){
    if($condition){
        abort($status);
    }
}

function base_path($path){
    return BASE_PATH . $path;
}

function view($path, $attributes = []){
    extract($attributes);
    require base_path('views/' . $path);
}

function redirect($location) {
    header("location: {$location}");
    exit();
}

function old($key, $default) {
    return Core\Session::get('old')[$key] ?? $default;
}