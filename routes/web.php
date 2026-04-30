<?php

use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ObservateurController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::post('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('news', [PostController::class, 'index'])->name('posts.index');


Route::get('news/la-preservation-du-patrimoine-dans-la-modernite', function() {
    $post = new \App\Models\Post();
    $post->slug = 'la-preservation-du-patrimoine-dans-la-modernite';
    return view('pages.posts.show', compact('post'));
});


Route::get('news/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('services', [ServicesController::class, 'index'])->name('services.index');
Route::get('resources', [ResourcesController::class, 'index'])->name('resources.index');
Route::get('about', [AboutController::class, 'index'])->name('about.index');    
Route::get('observateur-urbain', [ObservateurController::class, 'index'])->name('observateur.index');



Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', 'admin/pages');
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('sections', AdminSectionController::class)->except(['show']);
});

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::livewire('invitations/{invitation}/accept', 'pages::teams.accept-invitation')->name('invitations.accept');
});

require __DIR__.'/settings.php';
