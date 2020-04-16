<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{$title}}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="scrollit">
                @if(!count($collection))
                    <tr>
                        <td><p class="text-center">No records found</p></td>
                    </tr>
                @elseif($layout == 'table')

                    {{--  table view --}}
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            @foreach($columns as $column)
                                <th>{{$column}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                            {{$slot}}
                        </tbody>
                    </table>

                @elseif($layout == 'list')
                    {{--  list view --}}
                    <ul class="products-list product-list-in-box">
                        {{$slot}}
                    </ul>

                @elseif($layout == 'custom')

                    {{$slot}}

                @endif
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <a href="{!! array_values($linkLeft)[0] !!}" class="btn btn-sm btn-info btn-flat pull-left">{!! array_keys($linkLeft)[0] !!}</a>
            <a href="{!! array_values($linkRight)[0] !!}" class="btn btn-sm btn-default btn-flat pull-right">{!! array_keys($linkRight)[0] !!}</a>
        </div>
        <!-- /.box-footer -->
    </div>
</div>