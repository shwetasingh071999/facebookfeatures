<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendship';

    protected $fillable = [
        'user_id',
        'friend_id',
        'accepted',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function friend()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAccepted($query)
    {
        return $query->where('accepted', true);
    }

    public function scopePending($query)
    {
        return $query->where('accepted', false);
    }

    public function scopeSentBy($query, $user)
    {
        return $query->where('user_id', $user->id);
    }
}
