<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['name', 'image', 'published', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
