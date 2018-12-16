<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    protected function ok($data = null) {
        return response()
            ->json($data)
            ->setStatusCode(200);
    }

    protected function error($error, $code = 500) {
        return response()
            ->json($error)
            ->setStatusCode($code);
    }
}