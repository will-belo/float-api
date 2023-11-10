<?php

namespace App\Traits;

use App\Webhooks\Webhook;

trait SkinObserver
{
    protected static function boot()
    {
        parent::boot();
        
        static::created(function () {
            $webhook = new Webhook;
            
            $webhook->send('Novo registro realizado.');
        });
    }
}