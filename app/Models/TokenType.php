<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TokenType extends Model
{
    use HasFactory;

    protected $table = 'token_types';

    protected $guarded = [];

    public function tokens(): BelongsToMany
    {
        return $this->belongsToMany(
            ApiService::class,
            'api_service_token_types',
            'token_type_id',
            'api_service_id'
        );
    }
}
