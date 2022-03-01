@extends('visitstats::layout')

@section('visitortracker_content')
<div class="row">
	<div class="col-md-12">
		<h5>{{ $visitortrackerSubtitle }}</h5>

		<table class="visitortracker-table table table-sm table-striped fs-1">
			<thead>
				<th>Browser</th>
				<th>Unique Visitors</th>
				<th>Visits</th>
				<th>Last Visit</th>
			</thead>

			<tbody>
				@foreach ($visits as $visit)
					<tr>
						<td>
							@if ($visit->browser_family)
								@if (file_exists(public_path('vendor/visitortracker/icons/browsers/'.$visit->browser_family.'.png')))
									<img class="visitortracker-icon"
										src="{{ asset('/vendor/visitortracker/icons/browsers/'.$visit->browser_family.'.png') }}"
										title="{{ ucwords(str_replace('-', ' ', $visit->browser_family)) }}"
										alt="{{ ucwords(str_replace('-', ' ', $visit->browser_family)) }}">
								@endif

                                {{ ucwords(str_replace('-', ' ', $visit->browser_family)) }}
                            @else
                                <span>Unknown</span>
                            @endif
						</td>
							
						<td>
							{{ $visit->visitors_count }}
						</td>

						<td>
							{{ $visit->visits_count }}
						</td>

						<td>
							@include('visitstats::_last_visit')
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{{ $visits->links() }}
	</div>
</div>
@endsection