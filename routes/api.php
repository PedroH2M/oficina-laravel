<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/versao', function () {
        return response()->json([
            'sistema' => 'Oficina Laravel',
            'versao' => '1.0.0',
            'padrao_arquitetura' => 'SOLID',
            'padrao_docker' => 'bdsoftcom',
            'status' => 'operacional'
        ]);
    });
});
