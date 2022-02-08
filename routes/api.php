<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\PostController;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('logout', [RegisterController::class, 'logout'])->middleware('auth:api');

Route::middleware(['auth:api', 'cors'])->group( function () {
    Route::resource('products', ProductController::class)->except([
        'index'
    ]);
    Route::get('/products', function (Request $request) {
        $products = Product::paginate(5);
        return $products;
    });

    // @API /posts
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [PostController::class, 'index'])->name('list_posts');
        Route::get('/drafts', [PostController::class, 'drafts'])
            ->name('list_drafts');
        Route::get('/{id}', [PostController::class, 'show'])
            ->name('show_post');
        // Route::get('/create', [PostController::class, 'create')
        //     ->name('create_post')
        //     ->middleware('can:post.create');
        Route::post('/', [PostController::class, 'store'])
            ->name('store_post')
            ->middleware('can:post.create');
        // Route::get('/edit/{post}', [PostController::class, 'edit')
        //     ->name('edit_post')
        //     ->middleware('can:post.update,post');
        Route::put('/{post}', [PostController::class, 'update'])
            ->name('update_post')
            ->middleware('can:post.update,post');
        Route::put('/publish/{id}', [PostController::class, 'publish'])
            ->name('publish_post')
            ->middleware('can:post.publish');
        Route::delete('/delete/{id}', [PostController::class, 'destroy'])
            ->name('delete_post');
           // ->middleware('can:post.publish');    
        
        Route::post('/search', [PostController::class, 'search'])->name('search_posts');
    });
});
