<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $connection = 'iuhm';
    protected $table = 'newsletter_subscribers';

    protected $fillable = [
        'email', 'name', 'is_active', 'subscribed_at',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'subscribed_at' => 'datetime',
    ];
}
