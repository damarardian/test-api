<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;

class Product extends Model
{
    use HasFactory, MediaAlly;

    protected $fillable = [
        'nama',
        'jenis',
        'harga',
    ];
}
