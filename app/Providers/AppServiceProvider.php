<?php

namespace App\Providers;

use App\Models\Page;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureViewData();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Share common data with Blade views.
     */
    protected function configureViewData(): void
    {
        View::composer(['layouts.site', 'partials.header'], function ($view): void {
            if (! Schema::hasTable('pages')) {
                $view->with('navigationPages', collect());

                return;
            }

            $view->with(
                'navigationPages',
                Page::query()
                    ->published()
                    ->ordered()
                    ->get(['id', 'slug', 'title']),
            );
        });
    }
}
