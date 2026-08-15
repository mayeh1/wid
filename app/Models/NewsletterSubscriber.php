<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'is_confirmed'];

    protected function casts(): array
    {
        return [
            'is_confirmed' => 'boolean',
        ];
    }
}
