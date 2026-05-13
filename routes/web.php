<?php

use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\EditorImageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DeliverablesController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\NewslettersController;
use App\Http\Controllers\ObservateurController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ServicesController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::post('locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('news', [PostController::class, 'index'])->name('posts.index');
Route::get('news/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('services', [ServicesController::class, 'index'])->name('services.index');
Route::get('resources', [ResourcesController::class, 'index'])->name('resources.index');

// Admin ──────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', 'admin/pages');
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('sections', AdminSectionController::class)->except(['show']);

    // API settings
    Route::get('api-settings', [ApiSettingsController::class, 'show'])->name('api-settings.show');
    Route::put('api-settings', [ApiSettingsController::class, 'update'])->name('api-settings.update');
    Route::get('api-settings/ping', [ApiSettingsController::class, 'ping'])->name('api-settings.ping');

    // Media Module ─────────────────────────────────────────────────────────
    Route::post('editor/image-upload', [EditorImageController::class, 'upload'])->name('editor.image-upload');

    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', \App\Livewire\Media\MediaDashboard::class)->name('dashboard');
        Route::get('blog', \App\Livewire\Media\BlogManagement::class)->name('blog');
        Route::get('news', \App\Livewire\Media\NewsManagement::class)->name('news');
        Route::get('deliverables', \App\Livewire\Media\DeliverableManagement::class)->name('deliverables');
        Route::get('newsletters', \App\Livewire\Media\NewsletterManagement::class)->name('newsletters');
        Route::get('contacts', \App\Livewire\Media\ContactManagement::class)->name('contacts');
    });
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
