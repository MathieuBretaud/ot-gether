<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->is_admin === true) {
                return true;
            }
        });
        
        verifyemail::createurlusing(function ($notifiable) {
            $params = [
                "expires" => carbon::now()
                    ->addminutes(60)
                    ->gettimestamp(),
                "id" => $notifiable->getkey(),
                "hash" => sha1($notifiable->getemailforverification()),
            ];

            ksort($params);

            // then create api url for verification. my api have `/api` prefix,
            // so i don't want to show that url to users
            $url = URL::route("verification.verify", $params, true);

            // get app_key from config and create signature
            $key = config("app.key");
            $signature = hash_hmac("sha256", $url, $key);

            // generate url for yous spa page to send it to user
            return config('app.frontend_url') .
                "/verify-email/" .
                $params["id"] .
                "/" .
                $params["hash"] .
                "?expires=" .
                $params["expires"] .
                "&signature=" .
                $signature;
        });


        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
