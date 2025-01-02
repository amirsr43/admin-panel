<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];
    protected $table = 'groups';

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }
}
