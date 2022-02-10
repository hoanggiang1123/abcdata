<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyCode extends Model
{
    use HasFactory;

    public $crudNotAccepted = [
        'created_at', 'updated_at', 'id'
    ];
    protected $guarded = [];
}
