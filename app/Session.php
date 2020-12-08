<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'session';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'name', 'description', 'start', 'duration', 'created', 'updated'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'userID', 'ID');
    }
}
