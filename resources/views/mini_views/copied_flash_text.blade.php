<span class="badge badge-success badge-xs pull-right" id="copied" style="display:none;margin-top:-15px;margin-left:-20px;position: absolute;">Copied</span>
@foreach($navigations as $navigation)
@if($navigation['btnName'] == 'rec_code')
 @php
   $slotVariableName = $navigation['slot'];
   $style = $navigation['style']
@endphp
@elseif($navigation['btnName'] == 'lic_btn')
 @php
   $slotVariableName = $navigation['slot'];
	$style = $navigation['style']
@endphp
@endif
{!! $style !!}
@endforeach