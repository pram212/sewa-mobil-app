<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function merek() 
    {
        return $this->belongsTo(Merek::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'penyewaan')->withPivot('tgl_mulai', 'tgl_selesai', 'status');
    }

}
