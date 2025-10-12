<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $table = 'accounts';

    protected $guarded = [];

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
