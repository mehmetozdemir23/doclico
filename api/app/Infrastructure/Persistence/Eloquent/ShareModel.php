<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Factories\ShareFactory;

class ShareModel extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function newFactory(): ShareFactory
    {
        return ShareFactory::new();
    }

    protected $table = 'document_shares';

    protected $fillable = [
        'id',
        'document_id',
        'token',
        'expires_at',
        'downloads_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
    ];

    protected $appends = ['share_url', 'is_expired'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id');
    }

    public function shareUrl(): Attribute
    {
        return Attribute::get(fn (): string => config('app.url')."/api/share/{$this->token}");
    }

    public function isExpired(): Attribute
    {
        return Attribute::get(fn (): bool => $this->expires_at !== null && $this->expires_at->isPast());
    }
}
