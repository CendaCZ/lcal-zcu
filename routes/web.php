<?php

use App\Http\Controllers\AttendingEventItemController;
use App\Http\Controllers\CreditsController;
use App\Http\Controllers\DeleteCommentController;
use App\Http\Controllers\EventIndexController;
use App\Http\Controllers\EventItemController;
use App\Http\Controllers\EventItemShowController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GalleryIndexController;
use App\Http\Controllers\LikedEventItemController;
use App\Http\Controllers\LikeSystemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedEventController;
use App\Http\Controllers\SavedEventItemSystemController;
use App\Http\Controllers\StoreCommentController;
use App\Http\Controllers\UvodController;
use App\Models\Country;
use App\Models\EventItem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/countries/{id}', [CountryController::class, 'getCities']);

// Route::get('/e', EventIndexController::class)->name('eventIndex');
// Route::get('/e/{id}', EventItemShowController::class)->name('eventShow');
Route::get('/gallery', GalleryIndexController::class)->name('galleryIndex');

Route::get('/credits', CreditsController::class)->name('credits');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Route::resource('image', EventItemController::class)->except(['show', 'edit', 'update', 'delete']);
    //Route::get('events/{event}', 'EventItemController@show')->name('events.show');
    // Route::get('events/{event}/img', [EventItemController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/img', function (EventItem $event) {
        return Storage::response("public/{$event->image}");
    })->name('events.displayImage');
    

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Route::get('/events', function () {
        //     return view('events.index');
        // })->name('events');
    Route::resource('/events', EventItemController::class);
    Route::resource('/galleries', GalleryController::class);
    Route::get('/images', [GalleryController::class, 'index']);
    Route::post('/images/upload', [GalleryController::class, 'store']);

    Route::get('/liked-events', LikedEventItemController::class)->name('likedEvents');
    Route::get('/attending-events', AttendingEventItemController::class)->name('attendingEvents');
    Route::get('/saved-events', SavedEventController::class)->name('savedEvents');
    Route::post(
        '/events-like/{id}',
        LikeSystemController::class
    )->name('events.like');
    Route::post(
        '/events-saved/{id}',
        SavedEventItemSystemController::class
    )->name('events.saved');
    Route::post('/events-attending/{id}', AttendingEventItemController::class)->name('events.attending');

    Route::post('/events/{id}/comments', StoreCommentController::class)->name('events.comments');
    Route::delete('/events/{id}/comments/{comment}', DeleteCommentController::class)->name('events.comments.destroy');
    Route::get('/countries/{country}', function (Country $country) {
        return response()->json($country->cities);
    });
    Route::get(
        '/uvod',
        [UvodController::class, 'vypis']
    )->name('uvod');
});
