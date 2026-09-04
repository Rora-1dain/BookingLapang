<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinHistory extends Model
{
    protected $table = 'poin_histories';

    protected $fillable = ['user_id', 'jumlah', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
