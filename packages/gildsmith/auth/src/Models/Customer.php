<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Models;

use Carbon\CarbonInterface;
use Gildsmith\Auth\Database\Factories\CustomerFactory;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property-read User $user
 */
class Customer extends Model implements CustomerInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id'];

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserInterface::class);
    }
}
