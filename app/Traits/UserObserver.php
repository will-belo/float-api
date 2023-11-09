<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait UserObserver
{
    protected static function boot()
    {
        parent::boot();
        
        static::created(function (User $user) {
            
            $text = "Bem vindo ao float API\n";
            $text .= "Aqui estão suas credenciais\n";
            $text .= "Seu Client ID : '{$user->client_id}'\n";
            $text .= "Seu Client SECRET : '{$user->client_secret}'\n";
            $text .= "Grant Type : 'credential'\n";
            $text .= "Agora você pode usar nossos serviços!'\n";
            
            Mail::raw($text, function ($message) use ($user) {
                $message->from(env('MAIL_FROM_EMAIL','noreply@noreply.com'), env('MAIL_FROM_NAME','no-reply'));
                $message->subject("Bem vindo {$user->name}");
                $message->to($user->email);
            });
        });
    }
}