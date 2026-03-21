<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Factories\ClientFactory;

class ClientModel extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'nom',
        'adresse',
        'email',
        'telephone',
        'siret',
    ];

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
