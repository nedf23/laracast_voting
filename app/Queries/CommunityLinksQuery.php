<?php

namespace App\Queries;

use App\CommunityLink;

class CommunityLinksQuery
{
    public function get($sortByPopular, $channel)
    {
        $orderBy = $sortByPopular ? 'vote_count' : 'updated_at';

        return CommunityLink::with('votes', 'creator', 'channel')
            ->forChannel($channel)
            ->leftJoin('community_links_votes', 'community_links_votes.community_link_id', '=', 'community_links.id')
            ->selectRaw(
                'community_links.*, count(community_links_votes.id) as vote_count'
            )
            ->where('approved', 1)
            ->groupBy('community_links.id')
            ->orderBy($orderBy, 'desc')
            ->paginate(5);
    }
}