<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApiService extends Model
{
    use HasFactory;

    protected $table = 'api_services';

    protected $guarded = [];

    public function tokenTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            TokenType::class,
            'api_service_token_types',
            'api_service_id',
            'token_type_id'
        );
    }
}
