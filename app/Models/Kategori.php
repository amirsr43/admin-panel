<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['name'];
    protected $table = 'kategoris';

    public function customers()
    {
        return $this->hasMany(Customer::class, 'kategori_id');
    }
}
