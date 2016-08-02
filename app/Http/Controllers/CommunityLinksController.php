<?php

namespace App\Http\Controllers;

use App\Channel;
use App\CommunityLink;
use App\Exceptions\CommunityLinkAlreadySubmitted;
use App\Http\Requests;
use App\Http\Requests\CommunityLinkForm;
use Illuminate\Http\Request;

class CommunityLinksController extends Controller
{
    public function index(Channel $channel = null)
    {
        $links = CommunityLink::forChannel($channel)
            ->where('approved', 1)
            ->latest('updated_at')
            ->paginate(3);
        $channels = Channel::orderBy('title', 'asc')->get();

        return view('community.index', compact('links','channels', 'channel'));
    }

    public function store(CommunityLinkForm $form)
    {
        try {
            $form->persist();

            if (auth()->user()->isTrusted()) {
                flash('Thanks for the contribution!', 'success');
            } else {
                flash()->overlay('This contribution will be reviewed shorly.', 'Thanks!');
            }
        } catch (CommunityLinkAlreadySubmitted $e) {
            flash()->overlay("We'll update the timestamps and bring it to the top. Thanks!", 'Link Already Submitted');
        }

        return back();
    }
}
