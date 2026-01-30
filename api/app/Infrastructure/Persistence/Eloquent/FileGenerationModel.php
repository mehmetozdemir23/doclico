<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Factories\FileGenerationFactory;

class FileGenerationModel extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function newFactory(): FileGenerationFactory
    {
        return FileGenerationFactory::new();
    }

    protected $table = 'file_generations';

    protected $fillable = [
        'template_id',
        'user_id',
        'data',
        'format',
        'status',
        'file_path',
        'error',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateModel::class, 'template_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
