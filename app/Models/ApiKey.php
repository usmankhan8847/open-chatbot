<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ApiKey
 * @package App\Models
 * 
 * @property string $provider
 * @property string $api_key
 * @property string $model
 * @property bool $is_active
 */
class ApiKey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'provider',
        'api_key',
        'model',
        'is_active',
    ];
}
