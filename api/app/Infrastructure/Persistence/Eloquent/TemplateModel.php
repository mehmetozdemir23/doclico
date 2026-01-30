<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Factories\TemplateFactory;

class TemplateModel extends Model
{
    use HasFactory;

    protected static function newFactory(): TemplateFactory
    {
        return TemplateFactory::new();
    }

    protected $table = 'templates';

    protected $fillable = [
        'type',
        'name',
        'category',
        'icon',
        'fields',
        'popular',
    ];

    protected $casts = [
        'fields' => 'array',
        'popular' => 'boolean',
    ];
}
