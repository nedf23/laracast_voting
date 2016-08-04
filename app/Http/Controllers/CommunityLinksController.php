<?php

namespace App\Http\Controllers;

use App\Channel;
use App\CommunityLink;
use App\Exceptions\CommunityLinkAlreadySubmitted;
use App\Http\Requests;
use App\Http\Requests\CommunityLinkForm;
use App\Queries\CommunityLinksQuery;
use Illuminate\Http\Request;

class CommunityLinksController extends Controller
{
    public function index(Channel $channel = null)
    {
        $links = (new CommunityLinksQuery)->get(
            request()->exists('popular'), $channel
        );

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
