<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Bot
 * @package App\Models
 * 
 * @property string $name
 * @property string|null $avatar
 * @property string|null $system_prompt
 * @property string $ai_provider
 * @property string $ai_model
 * @property string|null $api_key
 * @property float $temperature
 * @property int $max_tokens
 * @property string|null $welcome_message
 * @property array|null $allowed_domains
 * @property bool $is_active
 */
class Bot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'system_prompt',
        'ai_provider',
        'ai_model',
        'api_key',
        'temperature',
        'max_tokens',
        'welcome_message',
        'allowed_domains',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'allowed_domains' => 'array',
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'is_active' => 'boolean',
        'api_key' => 'encrypted',
    ];

    /**
     * Get the conversations for the bot.
     *
     * @return HasMany
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get the training data for the bot.
     *
     * @return HasMany
     */
    public function trainingData(): HasMany
    {
        return $this->hasMany(TrainingData::class);
    }
}
