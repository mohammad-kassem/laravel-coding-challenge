<?php

use App\Http\Controllers\PullRequestsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'v1'], function() {
  Route::get('/old', [PullRequestsController::class, 'old']);
  Route::get('/review', [PullRequestsController::class, 'review']);
  Route::get('/status', [PullRequestsController::class, 'status']);
});
