<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $tabel = 'rombels';

    protected $fillable = [
        'nama_kelas',
        'kompetensi_keahlian',
    ];
}
