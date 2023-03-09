<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $table = 'officers';

    protected $fillable = [
        'nip',
        'username',
        'password',
        'nama_petugas',
        'role'
    ];
}
