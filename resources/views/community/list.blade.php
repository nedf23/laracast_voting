<ul class="list-group">
    @if (count($links))
        @foreach ($links as $link)
            <li class="list-group-item">
                <form action="/votes/{{ $link->id }}" method="post">
                    {{ csrf_field() }}

                    <button class="btn {{ Auth::check() && Auth::user()->votedFor($link) ? 'btn-success' : 'btn-default' }}"
                        {{ Auth::guest() ? 'disabled' : '' }}
                    >
                        {{ $link->votes->count() }}
                    </button>
                </form>

                <a href="/community/{{ $link->channel->slug }}" class="label label-default"
                      style="background-color: {{  $link->channel->color }};">
                        {{ $link->channel->title }}
                </a>
                <a href="{{ $link->link }}" target="_blank">{{ $link->title }}</a>
                <small>
                    Contributed By {{ $link->creator->name }} {{ $link->updated_at->diffForHumans() }}
                </small>
            </li>
        @endforeach
    @else
        <li>No contributions yet.</li>
    @endif
</ul>

{{ $links->links() }}