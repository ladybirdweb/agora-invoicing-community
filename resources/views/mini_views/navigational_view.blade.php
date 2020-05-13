<div class="row">
    <div class="col col-md-3">
        <div class="tabs tabs-vertical tabs-left">
            <ul class="nav nav-tabs">
                @foreach($navigations as $navigation)
                    <li class="nav-item {{isset($navigation['active'])? 'active': ''}}">
                        <a class="nav-link" href="#{{$navigation['id']}}" data-toggle="tab">{{$navigation['name']}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col col-md-9">
        <div class="tab-content" style="overflow-x: auto">
            @foreach($navigations as $index => $navigation)
                <div id="{{$navigation['id']}}" class="tab-pane {{isset($navigation['active'])? 'active': ''}}">
                    <h4>{{$navigation['name']}}</h4>
                    {{-- making number as variable name so that slots can be injected using @slot('1'), @slot('2') --}}
                    @php
                        $slotVariableName = $navigation['slot']
                    @endphp

                    {{$$slotVariableName}}
                </div>
            @endforeach
        </div>
    </div>
</div>