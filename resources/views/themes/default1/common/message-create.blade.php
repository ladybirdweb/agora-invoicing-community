<div class="modal fade" id="create-an-announcement" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create an announcement</h4>
            </div>
            {!! Form::open(['url'=>'message-announcement']) !!}
            <div class="modal-body">

                {!! Form::hidden('user',\Auth::user()->user_name) !!}

                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name','Message Type',['class'=>'required']) !!}
                    <select name="is_closeable"  class="form-control" id="is_closeable">
                            <option value=0 selected>Non-Closeable</option>
                            <option value=1>Closeable</option>
                    </select>
                </div>
                <?php $conditions=\App\Model\Common\AnnouncementConditions::all() ?>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}" id="cond">
                    {!! Form::label('name','Condition',['class'=>'required']) !!}
                    <select name="condition"  class="form-control" id="condition">
                        @foreach($conditions as $condition)
                        <option value={!! $condition->name !!}>{!! $condition->condition !!}</option>
                        @endforeach
                    </select>

                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <?php $version = \Illuminate\Support\Facades\DB::table('product_uploads')->pluck('version')->toArray();
                      array_unshift($version,'Every version available');
                      $version=array_unique($version);
                    ?>

                    {!! Form::label('name','Version',['class'=>'required']) !!}
                    <select name="version[]"  class="form-control" id="version" multiple="multiple" style="width:468px;" class="select2" required>
                        @foreach($version as $key=> $value)
                        <option value="{{$value}}">{!! $value !!}</option>>
                        @endforeach
                    </select>

                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('from','Expiry From') !!}
                    <div class="input-group date" id="expiry_from" data-target-input="nearest">
                        <input type="text" name="from" class="form-control datetimepicker-input" autocomplete="off"  data-target="#expiry_from"/>

                        <div class="input-group-append" data-target="#expiry_from" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>

                    </div>


                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">

                    {!! Form::label('till','Expiry Till') !!}
                    <div class="input-group date" id="expiry_till" data-target-input="nearest">
                        <input type="text" name="till" class="form-control datetimepicker-input" autocomplete="off"  data-target="#expiry_till"/>

                        <div class="input-group-append" data-target="#expiry_till" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>

                    </div>

                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <?php $products = \Illuminate\Support\Facades\DB::table('products')->pluck('name')->toArray();
                    array_unshift($products,'Every Product available');
                    $products=array_unique($products);
                    ?>
                    {!! Form::label('name','Products',['class'=>'required']) !!}
                    <select name="products[]"  class="form-control" id="products" multiple="multiple" style="width:468px;" class="select2" required>
                        @foreach($products as $key=> $value)
                            <option value="{{$value}}">{!! $value !!}</option>>
                        @endforeach
                    </select>

                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name','Message',['class'=>'required']) !!}
                    <input type="text" class="form-control message" name="message" id="message" required>
                    <span class="appnamecheck"></span>
                </div>
                <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}" id="reappears">
                    {!! Form::label('name','Should the message keep reappearing?') !!}
                    {!! Form::text('reappear',null,['class' => 'form-control message', 'id'=>'reappear','placeholder'=> '7 Days']) !!}
                    <span class="appnamecheck"></span>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" id="close" class="btn btn-default pull-left closebutton" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Close</button>
                <button type="submit" class="btn btn-primary submit " id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-save">&nbsp;</i>{!!Lang::get('Save')!!}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>
@section('datepicker')

    <script type="text/javascript">
        $('#expiry_from').datetimepicker({
            format: 'L'
        });
        $('#expiry_till').datetimepicker({
            format: 'L'
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#version").select2({
                placeholder: 'Select versions',
                tags:true
            });
        });
    </script>
    <script>
        $('#reappears').hide();
        $(document).ready(function() {
            $("#products").select2({
                placeholder: 'Select products',
                tags:true
            });
        });
        $('#is_closeable').on('change',function() {
            val = $('#is_closeable').val();
            if(val == 0){
                $('#cond').show();
                $('#reappears').hide();

            }
            else{
                $('#cond').hide();
                $('#reappears').show();
            }
        });
    </script>
@stop
