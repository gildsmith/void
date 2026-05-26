<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Models;

use Carbon\CarbonInterface;
use Gildsmith\Auth\Database\Factories\UserFactory;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property CarbonInterface|null $email_verified_at
 * @property CarbonInterface|null $last_login_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property-read Customer|null $customer
 * @property-read Employee|null $employee
 */
class User extends Authenticatable implements HasValidationRulesInterface, UserInterface
{
    use HasAbstractRelationships;
    use HasApiTokens;
    use HasFactory;
    use HasValidationRules;
    use Notifiable;
    use SoftDeletes;

    protected array $rules = [
        'email' => ['string', 'email'],
        'password' => ['string', 'min:8'],
    ];

    protected array $requiredForCreate = ['email', 'password'];

    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function customer(): HasOne
    {
        return $this->hasOne(CustomerInterface::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(EmployeeInterface::class);
    }

    public function getCode(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
