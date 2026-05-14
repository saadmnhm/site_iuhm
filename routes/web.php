<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServicesController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::post('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('actualite', [ActualiteController::class, 'index'])->name('actualite.index');
Route::get('actualite/{slug}', [ActualiteController::class, 'show'])->name('actualite.show');
Route::get('services', [ServicesController::class, 'index'])->name('services.index');
Route::get('resources', [ResourcesController::class, 'index'])->name('resources.index');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
});

require __DIR__.'/settings.php';
