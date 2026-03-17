<?php

namespace App\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class DocumentSequenceModel extends Model
{
    public $timestamps = false;

    protected $table = 'document_sequences';

    protected $fillable = ['user_id', 'type', 'year', 'last_number'];
}
