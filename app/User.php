<?php

namespace App;

use App\CommunityLink;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isTrusted()
    {
        return !! $this->trusted;
    }

    public function votes()
    {
        return $this->belongsToMany(CommunityLink::class, 'community_links_votes')
            ->withTimestamps();
    }

    public function votedFor(CommunityLink $link)
    {
        return $link->votes->contains('user_id', $this->id);
    }

    public function toggleVoteFor(CommunityLink $link)
    {
        return CommunityLinkVote::firstOrNew([
            'user_id' => $this->id,
            'community_link_id' => $link->id
        ])->toggle();
    }
}
