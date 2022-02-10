<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permision extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function permisionChildren () {
        return $this->hasMany(Permision::class, 'parent_id');
    }
}
