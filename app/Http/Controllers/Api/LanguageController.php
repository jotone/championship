<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BasicApiController;
use Illuminate\Http\Request;

class LanguageController extends BasicApiController
{
    public function show(string $lang, Request $request)
    {
        dd($lang, $request->all());
    }
}