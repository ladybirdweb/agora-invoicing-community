<table class="visitortracker-table table table-sm table-striped fs-1">
    <thead>
        <th>Request</th>
        <th>Referrer</th>
        <th>Visitor</th>
    </thead>

    <tbody>
        @foreach ($visits as $visit)
            <tr>
                <td class="visitortracker-cell-break-words">
                    {{ \Carbon\Carbon::parse($visit->created_at)
                        ->tz(config('visitortracker.timezone', 'UTC'))
                        ->format(config('visitortracker.datetime_format')) }}
                    
                    <br>

                    {{ $visit->is_ajax ? 'AJAX' : '' }}
                    
                    @if ($visit->is_login_attempt)
                        <img class="visitortracker-icon"
                            src="{{ asset('/vendor/visitortracker/icons/login_attempt.png') }}"
                            title="Login attempt">
                    @endif

                    {{ $visit->method }} 

                    <a href="{{ $visit->url }}"
                        title="{{ $visit->url }}"
                        target="_blank">{{ $visit->url }}</a>
                </td>

                <td class="visitortracker-cell-break-words">
                    {!!
                        $visit->referer
                        ? '<a href="' . $visit->referer . '" title="' . $visit->referer . '" target="_blank">' . $visit->referer . '</a>'
                        : '-'
                    !!}
                </td>

                <td class="visitortracker-cell-nowrap">
                    @include('visitstats::_visitor')
                </td>
            </tr>
        @endforeach
    </tbody>
</table>