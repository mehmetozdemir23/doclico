<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Override;
use Tests\Factories\UserFactory;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'company_name',
        'siret',
        'address',
        'phone',
        'mentions_legales',
        'numero_tva',
        'logo',
        'consent_accepted_at',
        'consent_policy_version',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    #[Override]
    protected static function booted(): void
    {
        static::deleting(function (self $user): void {
            $user->documents()->get()->each->delete();
            $user->clients()->delete();
            $user->documentSequences()->delete();
            $user->tokens()->delete();
        });
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentModel::class, 'user_id');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(ClientModel::class, 'user_id');
    }

    public function documentSequences(): HasMany
    {
        return $this->hasMany(DocumentSequenceModel::class, 'user_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'consent_accepted_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
