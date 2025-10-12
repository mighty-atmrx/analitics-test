<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companies';

    protected $guarded = [];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
