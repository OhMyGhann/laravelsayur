<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingWeb extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_1',
        'logo_2',
        'logo_3',
        'warna_1',
        'warna_2',
        'warna_3',
        'phone',
        'social_media',
        'deskripsi_web'
    ];

    protected $cast = [
        'social_media' => 'json'
    ];
}
