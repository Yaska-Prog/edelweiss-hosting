<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaun extends Model
{
    use HasFactory;
    protected $table = 'gauns';
    protected $guarded = [];
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
}
