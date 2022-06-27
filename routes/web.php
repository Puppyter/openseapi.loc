<?php

use App\Http\Controllers\OpenSeaListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [OpenSeaListController::class,'index'])->name('index');
Route::post('/get/nft',[OpenSeaListController::class,'display'])->name('getNfts');
