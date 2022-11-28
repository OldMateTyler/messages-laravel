<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'sent_at',
        'read_at',
        'body',
        'author',
        'recipient',
        'thread_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
