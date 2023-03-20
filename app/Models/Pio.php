<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Events\PioCreated;

class Pio extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'message',
    ];

    protected $dispatchesEvents=[
        'created'=>PioCreated::class,
    ];
    /**
     * returns user owner
     *
     * @return User
     */
    function user(){
        return $this->belongsTo(User::class);
    }

    function tags(){
        return $this->belongsToMany(Tag::class, 'post_has_tags', 'pio_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function addLike(User $user)
    {
        if (!$this->id) {
            throw new \Exception("Cannot add like to Pio without ID");
        }

        $this->likes()->create([
            'pio_id' => $this->id,
            'user_id' => $user->id
        ]);
    }

    public function removeLike(User $user)
    {
        $this->likes()->where('user_id', $user->id)->delete();
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    public function likesCount()
{
    return $this->likes()->count();
}
}
