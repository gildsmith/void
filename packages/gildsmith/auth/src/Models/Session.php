<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Models;

use Carbon\CarbonInterface;
use Gildsmith\Contract\Auth\SessionInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string $token_hash
 * @property bool $remember
 * @property CarbonInterface|null $last_used_at
 * @property CarbonInterface $expires_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property-read User $user
 */
class Session extends Model implements SessionInterface
{
    use HasAbstractRelationships;

    protected $table = 'sessions';

    protected $fillable = [
        'user_id',
        'name',
        'token_hash',
        'remember',
        'last_used_at',
        'expires_at',
    ];

    protected $hidden = [
        'token_hash',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserInterface::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at->isFuture();
    }

    public function revoke(): bool
    {
        return (bool) $this->delete();
    }

    /**
     * @param Builder<self> $query
     *
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'remember' => 'boolean',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
