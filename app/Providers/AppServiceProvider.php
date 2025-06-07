<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use App\Models\City;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
         // Получаем первую запись настроек сайта
        $siteSettings = SiteSetting::first();

        // Форматируем номер телефона
        $phone = $this->formatPhoneNumber($siteSettings->contacts);
        
        $cities = City::all();

        // Делаем переменные доступными для всех представлений
        View::share('siteSettings', $siteSettings);
        View::share('phone', $phone);
        View::share('cities', $cities);
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        return (new MailMessage)
            ->subject('Підтвердьте адресу електронної пошти')
            ->line('Натисніть кнопку нижче, щоб підтвердити свою адресу електронної пошти.')
            ->action('Підтвердьте адресу електронної пошти', $url)
            ->greeting('Вітаю, ' . $notifiable->name)
            ->salutation('З повагою, ' . config('app.name'));
        });
    }

    // Метод для форматирования номера телефона
    public function formatPhoneNumber($phone)
    {
        // Удаляем префикс +380 и извлекаем код оператора и номер
        if (strpos($phone, '+380') === 0) {
            $operatorCode = substr($phone, 3, 3); // Код оператора
            $number = substr($phone, 6);          // Остальная часть номера

            // Форматируем номер
            return "+38({$operatorCode}) {$number[0]}{$number[1]}{$number[2]} {$number[3]}{$number[4]} {$number[5]}{$number[6]}";
        }
        return $phone;
    }

}
