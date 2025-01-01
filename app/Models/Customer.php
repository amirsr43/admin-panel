<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'logo', 'kategori_id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

}
