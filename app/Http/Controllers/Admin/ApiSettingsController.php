<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContentApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ApiSettingsController extends Controller
{
    public function __construct(private readonly ContentApiService $api) {}

    public function show(): View
    {
        return view('admin.api-settings', [
            'currentUrl'         => config('api.base_url'),
            'currentTimeout'     => config('api.timeout'),
            'currentToken'       => config('api.token'),
            'currentPlatformUrl' => config('api.platform_url'),
            'currentCacheTtl'    => config('api.cache_ttl'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'api_base_url'    => ['required', 'url', 'max:255'],
            'api_timeout'     => ['required', 'integer', 'min:1', 'max:120'],
            'api_token'       => ['nullable', 'string', 'max:500'],
            'api_platform_url'=> ['required', 'url', 'max:255'],
            'api_cache_ttl'   => ['required', 'integer', 'min:0', 'max:86400'],
        ]);

        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            return back()->withErrors(['env' => 'Le fichier .env est introuvable.']);
        }

        $env = File::get($envPath);

        $updates = [
            'CONTENT_API_BASE_URL'    => rtrim($request->input('api_base_url'), '/'),
            'CONTENT_API_TIMEOUT'     => (string) (int) $request->input('api_timeout'),
            'CONTENT_API_TOKEN'       => $request->input('api_token', ''),
            'CONTENT_API_PLATFORM_URL'=> rtrim($request->input('api_platform_url'), '/'),
            'CONTENT_API_CACHE_TTL'   => (string) (int) $request->input('api_cache_ttl'),
        ];

        foreach ($updates as $key => $value) {
            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $env);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return back()->with('success', 'Configuration API mise à jour avec succès.');
    }

    public function ping(): JsonResponse
    {
        $result = $this->api->ping();

        return response()->json($result, $result['ok'] ? 200 : 503);
    }
}