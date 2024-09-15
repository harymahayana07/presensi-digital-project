<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kantor',
        'alamat_kantor',
        'email_kantor',
        'deskripsi_kantor',
        'latitude',
        'longitude',
        'radius'
    ];
   
}
