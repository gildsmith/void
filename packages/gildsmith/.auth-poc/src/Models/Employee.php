<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Models;

use Carbon\CarbonInterface;
use Gildsmith\Auth\Database\Factories\EmployeeFactory;
use Gildsmith\Contract\User\EmployeeInterface;
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
class Employee extends Model implements EmployeeInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id'];

    protected static function newFactory(): EmployeeFactory
    {
        return EmployeeFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserInterface::class);
    }
}
