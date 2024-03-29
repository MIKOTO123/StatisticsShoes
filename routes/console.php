<?php

use App\Custom\Utils\ConnectionUtils;
use App\Models\Credit;
use App\Models\User;
use App\Notifications\PaidSuccess;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');



//方便调试命令
Artisan::command('test', function () {

    Schema::table('statistics_singles', function (Blueprint $table) {
        $table->string('size')->comment('码数');
    });

    $this->comment("test sent");
})->describe('Send news');

