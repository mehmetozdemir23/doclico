<?php

namespace App\Infrastructure\Persistence\Eloquent;

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
        'shared_at',
        'last_downloaded_at',
        'reminded_at',
        'views_count',
        'first_viewed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'shared_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'reminded_at' => 'datetime',
        'first_viewed_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(DocumentModel::class, 'document_id');
    }
}
