<?php

namespace App\Providers;

use App\Models\MailSetting;
use App\Models\ContactSetting;
use App\Models\PaymentSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!app()->runningInConsole()) {
            $request = request();
            $requestUri = $request->getRequestUri();
            $scriptName = $request->getScriptName();
            $scriptDir = str_replace('\\', '/', dirname($scriptName));
            if ($scriptDir === '/') {
                $scriptDir = '';
            }

            if ($scriptDir !== '') {
                $decodedRequestUri = rawurldecode($requestUri);
                $targetBaseUrl = null;

                if (strpos($decodedRequestUri, $scriptDir) === 0) {
                    $targetBaseUrl = str_replace('%2F', '/', rawurlencode($scriptDir));
                } else {
                    $parentDir = str_replace('\\', '/', dirname($scriptDir));
                    if ($parentDir === '/') {
                        $parentDir = '';
                    }
                    if ($parentDir !== '' && strpos($decodedRequestUri, $parentDir) === 0) {
                        $targetBaseUrl = str_replace('%2F', '/', rawurlencode($parentDir));
                    }
                }

                if ($targetBaseUrl !== null) {
                    $request->server->set('SCRIPT_NAME', $targetBaseUrl . '/index.php');
                    try {
                        $ref = new \ReflectionProperty(\Symfony\Component\HttpFoundation\Request::class, 'baseUrl');
                        $ref->setAccessible(true);
                        $ref->setValue($request, $targetBaseUrl);
                    } catch (\Throwable $e) {
                    }
                }
            }

            // Run auto migration if there are pending migrations
            try {
                $migrator = app('migrator');
                $paths = array_merge($migrator->paths(), [database_path('migrations')]);
                
                $shouldMigrate = false;
                if (!$migrator->repositoryExists()) {
                    $shouldMigrate = true;
                } else {
                    $files = $migrator->getMigrationFiles($paths);
                    $ran = $migrator->getRepository()->getRan();
                    $pending = array_diff(array_keys($files), $ran);
                    if (count($pending) > 0) {
                        $shouldMigrate = true;
                    }
                }

                if ($shouldMigrate) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Prevent app boot failures due to database/connection issues
            }
        }

        Schema::defaultStringLength(191);

        try {
            if (Schema::hasTable('mail_settings')) {
                $mailSettings = MailSetting::query()->first();
                if ($mailSettings && $mailSettings->host) {
                    $password = null;
                    if ($mailSettings->password) {
                        try {
                            $password = Crypt::decryptString($mailSettings->password);
                        } catch (Throwable $e) {
                            $password = null;
                        }
                    }

                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $mailSettings->host,
                        'mail.mailers.smtp.port' => $mailSettings->port ?: 587,
                        'mail.mailers.smtp.encryption' => $mailSettings->encryption ?: null,
                        'mail.mailers.smtp.username' => $mailSettings->username ?: null,
                        'mail.mailers.smtp.password' => $password,
                    ]);

                    if ($mailSettings->from_address) {
                        config([
                            'mail.from.address' => $mailSettings->from_address,
                            'mail.from.name' => $mailSettings->from_name ?: config('app.name'),
                        ]);
                    }
                }
            }
        } catch (Throwable $e) {
        }

        try {
            if (Schema::hasTable('payment_settings')) {
                $paymentSettings = PaymentSetting::query()->first();
                if ($paymentSettings && $paymentSettings->paystack_enabled) {
                    $secret = null;
                    if ($paymentSettings->paystack_secret_key) {
                        try {
                            $secret = Crypt::decryptString($paymentSettings->paystack_secret_key);
                        } catch (Throwable $e) {
                            $secret = null;
                        }
                    }

                    config([
                        'services.paystack' => [
                            'enabled' => true,
                            'public_key' => $paymentSettings->paystack_public_key ?: null,
                            'secret_key' => $secret,
                            'trial_days' => (int) ($paymentSettings->trial_days ?? 3),
                            'auth_amount_kobo' => (int) ($paymentSettings->paystack_auth_amount_kobo ?? 10000),
                        ],
                    ]);
                } else {
                    config([
                        'services.paystack' => [
                            'enabled' => false,
                        ],
                    ]);
                }
            }
        } catch (Throwable $e) {
        }

        View::composer('*', function ($view) {
            $siteSettings = null;
            try {
                if (Schema::hasTable('site_settings')) {
                    $siteSettings = Cache::remember('site_settings.first', 300, function () {
                        return SiteSetting::query()->first();
                    });
                }
            } catch (Throwable $e) {
                $siteSettings = null;
            }

            $contactSettings = null;
            try {
                if (Schema::hasTable('contact_settings')) {
                    $contactSettings = Cache::remember('contact_settings.first', 300, function () {
                        return ContactSetting::query()->first();
                    });
                }
            } catch (Throwable $e) {
                $contactSettings = null;
            }

            $view->with('siteSettings', $siteSettings);
            $view->with('contactSettings', $contactSettings);
        });
    }
}
