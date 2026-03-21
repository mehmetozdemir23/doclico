<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Tests\Factories\DocumentFactory;

class DocumentModel extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function newFactory(): DocumentFactory
    {
        return DocumentFactory::new();
    }

    protected $table = 'documents';

    protected $fillable = [
        'template_id',
        'user_id',
        'client_id',
        'name',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateModel::class, 'template_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(ShareModel::class, 'document_id');
    }

    #[Override]
    protected static function booted(): void
    {
        static::deleting(fn (self $document) => $document->shares()->delete());
    }
}
