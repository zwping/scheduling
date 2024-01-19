<?php

use Encore\Admin\Scheduling\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('scheduling', Controllers\SchedulingController::class.'@index');