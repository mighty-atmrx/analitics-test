<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiServiceTokenType extends Model
{
    use HasFactory;

    protected $table = 'api_service_token_types';

    protected $guarded = [];

    public function apiServices(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    public function tokenTypes(): BelongsTo
    {
        return $this->belongsTo(TokenType::class);
    }
}
