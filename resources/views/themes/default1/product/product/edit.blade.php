@extends('themes.default1.layouts.master')
@section('content')
<div class="box box-primary">

    <div class="box-header">
        @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('success')}}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
                <div class="alert alert-danger alert-dismissable">
                    <i class="fa fa-ban"></i>
                    <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.failed')}}.
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('fails')}}
                </div>
                @endif
        {!! Form::model($product,['url'=>'products/'.$product->id,'method'=>'patch','files' => true]) !!}
        <h4>{{Lang::get('message.product')}}	{!! Form::submit(Lang::get('message.save'),['class'=>'form-group btn btn-primary pull-right'])!!}</h4>

    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-md-12">

                

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">{{Lang::get('message.details')}}</a></li>
                        <li><a href="#tab_2" data-toggle="tab">{{Lang::get('message.price')}}</a></li>
                        <li><a href="#tab_3" data-toggle="tab">Plans</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">

                                <div class="col-md-3 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <!-- first name -->
                                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                                    {!! Form::text('name',null,['class' => 'form-control']) !!}

                                </div>

                                <div class="col-md-3 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('type',Lang::get('message.type'),['class'=>'required']) !!}
                                    {!! Form::select('type',['Types'=>$type],null,['class' => 'form-control']) !!}

                                </div>

                                <div class="col-md-3 form-group {{ $errors->has('group') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('group',Lang::get('message.group')) !!}
                                    {!! Form::select('group',['Groups'=>$group],null,['class' => 'form-control']) !!}

                                </div>
                                <div class="col-md-3 form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('category',Lang::get('message.category')) !!}
                                    {!! Form::select('category',['helpdesk'=>'Helpdesk','servicedesk'=>'ServiceDesk','service'=>'Service'],null,['class' => 'form-control']) !!}

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                                    <script>
    tinymce.init({
    selector: 'textarea',
    plugins: "code",
    toolbar: "code",
    menubar: "tools"
});
</script>

                                    {!! Form::label('description',Lang::get('message.description')) !!}
                                    {!! Form::textarea('description',null,['class' => 'form-control','id'=>'textarea']) !!}




                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('parent',Lang::get('message.parent')) !!}
                                                {!! Form::select('parent[]',['Products'=>$products],null,['class' => 'form-control']) !!}

                                            </div>
                                        </li>
                                        <div class="row">
                                            <div class="col-md-5">
                                            
                                                <li>
                                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('image',Lang::get('message.image')) !!}
                                                        {!! Form::file('image') !!}

                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('version',Lang::get('message.version')) !!}
                                                        {!! Form::text('version',null,['class'=>'form-control']) !!}

                                                    </div>
                                                </li>
                                            </div>
                                            <div class="col-md-2">
                                                <p>
                                                    <b>OR</b>
                                                </p>
                                            </div>
                                            <div class="col-md-5">
                                                <li>
                                                    <div class="form-group {{ $errors->has('github_owner') ? 'has-error' : '' }}">
                                                        <!-- first name -->
                                                        {!! Form::label('github_owner',Lang::get('message.github-owner')) !!}
                                                        {!! Form::text('github_owner',null,['class'=>'form-control']) !!}

                                                    </div>  
                                                </li>
                                                <li>
                                                    <div class="form-group {{ $errors->has('github_repository') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('github_repository',Lang::get('message.github-repository-name')) !!}
                                                        {!! Form::text('github_repository',null,['class'=>'form-control']) !!}

                                                    </div>
                                                </li>
                                            </div>
                                        </div>

                                        <li>
                                            <div class="form-group {{ $errors->has('require_domain') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('require_domain',Lang::get('message.require_domain')) !!}
                                                {!! Form::hidden('require_domain', 0) !!}
                                                <p>{!! Form::checkbox('require_domain',1) !!} {{Lang::get('message.tick-to-show-domain-registration-options')}}</p>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group {{ $errors->has('shoping_cart_link') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('shoping_cart_link',Lang::get('message.shoping-cart-link')) !!}
                                                {!! Form::text('shoping_cart_link',null,['class'=>'form-control']) !!}

                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                            <div class="form-group {{ $errors->has('stock_control') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('stock_control',Lang::get('message.stock_control')) !!}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        {!! Form::hidden('stock_control', 0) !!}
                                                        <p>{!! Form::checkbox('stock_control',1) !!}     {{Lang::get('message.enable-quantity-in-stock')}}       
                                                            {!! Form::text('stock_qty',null) !!} </p>
                                                    </div>
                                                    <!--                                        <div class="col-md-3">
                                                                                                {!! Form::text('stock_qty',null,['class'=>'form-control']) !!}
                                                                                            </div>-->

                                                </div>
                                        </li>
                                        <li>
                                            <div class="row">

                                                <div class="col-md-4 form-group {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                                                    <!-- first name -->
                                                    {!! Form::label('sort_order',Lang::get('message.sort_order')) !!}
                                                    {!! Form::text('sort_order',null,['class'=>'form-control']) !!}

                                                </div>

                                                <div class="col-md-8 form-group {{ $errors->has('tax_apply') ? 'has-error' : '' }}">
                                                    <!-- last name -->
                                                    {!! Form::label('tax_apply',Lang::get('message.apply_tax')) !!}
                                                    {!! Form::hidden('tax_apply', 0) !!}
                                                    <p>{!! Form::checkbox('tax_apply',1) !!}  {{Lang::get('message.tick-this-box-to-charge-tax-for-this-product')}}</p>

                                                </div>

                                            </div>
                                        </li>


                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                            <div class="form-group {{ $errors->has('hidden') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('hidden',Lang::get('message.hidden')) !!}
                                                {!! Form::hidden('hidden', 0) !!}
                                                <?php 
                                                $value=  "";
                                                if($product->hidden==1){
                                                 $value = 'true';   
                                                }
                                                ?>
                                                <p>{!! Form::checkbox('hidden',1,$value) !!}  {{Lang::get('message.tick-to-hide-from-order-form')}}</p>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group {{ $errors->has('retired') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('retired','Description') !!}
                                                {!! Form::hidden('retired', 0) !!}
                                                <p>{!! Form::checkbox('retired',1) !!}  Tick to allow description to add invoice</p>

                                            </div>  
                                        </li>
                                        

                                    </ul>
                                </div>


                            </div>
                        </div>





                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <table class="table table-responsive">

                                <tr>
                                    <td><b>{!! Form::label('subscription',Lang::get('message.subscription')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('subscription') ? 'has-error' : '' }}">
                                            <div class="row">
                                                 <div class="col-md-6">
                                                    {!! Form::hidden('subscription',0) !!}
                                                    {!! Form::checkbox('subscription',1) !!}
                                                    {!! Form::label('subscription',Lang::get('message.subscription')) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::hidden('deny_after_subscription',0) !!}
                                                    {!! Form::checkbox('deny_after_subscription',1) !!}
                                                    {!! Form::label('deny_after_subscription',Lang::get('message.deny_after_subscription')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('currency',Lang::get('message.currency')) !!}</b></td>
                                    <td>

                                        <table class="table table-responsive">
                                            <tr>
                                                <th></th>
                                                <th>{{Lang::get('message.regular-price')}}</th>
                                                <th>{{Lang::get('message.sales-price')}}</th>
                                            </tr>

                                            @foreach($currency as $key=>$value)

                                            <!-- dd($currency); -->
                                            <tr>
                                                <td>

                                                    <input type="hidden" name="currency[{{$key}}]" value="{{$key}}">
                                                    <p>{{$value}}</p>

                                                </td>

                                                <td>

                                                    {!! Form::text('price['.$key.']',$regular[$key]) !!}

                                                </td>
                                                <td>

                                                    {!! Form::text('sales_price['.$key.']',$sales[$key]) !!}

                                                </td>
                                            </tr>
                                            @endforeach


                                        </table>

                                    </td>
                                </tr>




                                <tr>
                                    <td><b>{!! Form::label('multiple_qty',Lang::get('message.allow-multiple-quantities')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('multiple_qty') ? 'has-error' : '' }}">
                                            <?php
                                            if ($product) {
                                                $multiple = $product->multiple_qty;
                                                if ($multiple == 1) {
                                                    $value = true;
                                                } else {
                                                    $value = '';
                                                }
                                            } else {
                                                $value = '';
                                            }
                                            //dd($multiple);
                                            ?>

                                            <p>{!! Form::checkbox('multiple_qty',1,$value) !!}  {{Lang::get('message.tick-this-box-to-allow-customers-to-specify-if-they-want-more-than-1-of-this-item-when-ordering')}} </p>

                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>{!! Form::label('auto-terminate',Lang::get('message.auto-terminate')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('auto_terminate') ? 'has-error' : '' }}">

                                            <p>{!! Form::text('auto_terminate',null) !!} {{Lang::get('message.enter-the-number-of-days-after-activation-to-automatically-terminate')}}</p>

                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{!! Form::label('tax',Lang::get('message.taxes')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('taxes') ? 'has-error' : '' }}">
                                            <div class="row">
                                                <?php
                                                if (count($saved_taxes) > 0) {
                                                    foreach ($saved_taxes as $tax) {

                                                        $saved[$tax->tax_class_id] = 'true';
                                                    }
                                                }
                                                
                                                ?>
                                                @forelse($taxes as $key=>$value)

                                                <div class="col-md-2">
                                                    @if(count($saved_taxes) != 0)
                                                    @if(key_exists($key,$saved))
                                                    <b>{{ucfirst($value)}}  <input type="radio"  name="tax" value="1" {{$key == '$saved[$key]' ? 'checked' : ''}}></b>
                                                    @else
                                                    <b>{{ucfirst($value)}}  <input type="radio"  name="tax" value="1" {{$key == '1' ? 'checked' : ''}}></b>
                                                    @endif
                                                    @else 
                                                    <b>{{ucfirst($value)}}  <input type="radio"  name="tax" value="1" {{$key == '1' ? 'checked' : ''}}></b>
                                                    @endif
                                                </div>
                                                @empty 
                                                <p>No taxes</p>
                                                @endforelse
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </div>
                       
                           <!-- /.tab-pane -->
                         <div class="tab-pane" id="tab_3">
                            <h3>
                                Plans &nbsp;<a href="{{url('plans/create')}}" class="btn btn-default">Add new</a>
                            </h3>
                            @if($product->plan())
                            <table class="table table-responsive">
                                
                                <tr>
                                    <th>Name</th>
                                    <th>Months</th>
                                    <th>Action</th>
                                </tr>
                                
                                <tr>
                                    <td>{{$product->plan()->name}}</td> 
                                    <?php
                                    $months = $product->plan()->days / 30;
                                    ?>
                                    <td>{{round($months)}}</td> 
                                    <td><a href="{{url('plans/'.$product->plan()->id.'/edit')}}" class="btn btn-primary">Edit</a></td> 
                                </tr>
                               
                            </table>
                            @endif
                        </div>

                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->

           

          {!! Form::close() !!}
          @if($product->name=='Help Desk Smart' || $product->name=='Faveo helpdesk pro')
        <div class="row">
        <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Upload Files</h3>
                 
                 <a href="#create-upload-option" id="create" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create-upload-option">Add Files</a>
                            @include('themes.default1.product.product.create-upload-option')
             
            </div>
            <div id="response"></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                         <table id="upload-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                          <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete">Delete Selected</button><br /><br />
                    <thead><tr>
                         <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>File</th>
                        <th>Action</th>
                        </tr></thead>
                     </table>

 </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

 @include('themes.default1.product.product.edit-upload-option')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
        $('#upload-table').DataTable({
            processing: true,
            serverSide: true,
             stateSave: true,
               order: [[ 0, "desc" ]],
               
             ajax: '{!! route('get-upload',$product->id) !!}',
            "oLanguage": {
                "sLengthMenu": "_MENU_ Records per page",
                "sSearch"    : "Search: ",
                "sProcessing": '<img id="blur-bg" class="backgroundfadein" style="top:40%;left:50%; width: 50px; height:50 px; display: block; position:    fixed;" src="{!! asset("lb-faveo/media/images/gifloader3.gif") !!}">'
            },
                columnDefs: [
                { 
                    targets: 'no-sort', 
                    orderable: false,
                    order: []
                }
            ],
            columns: [
                {data: 'checkbox', name: 'checkbox'},
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'version', name: 'version'},
                {data: 'file', name: 'file'},
                {data: 'action', name: 'action'}
            ],
            "fnDrawCallback": function( oSettings ) {
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
        
          $('#edit-upload-option').on('show.bs.modal', function(e){
        
    })
     function openEditPopup(e){
         console.log(e)
         $('#edit-uplaod-option').modal('toggle');
         var upload_id = $(e).data('id')
         var title = $(e).data('title')
        var description = $(e).data('description')
        var version= $(e).data('version')
        console.log(title,description,version)

         $("#product-title").val(title)
        $("#product-description").val(description)
        $("#product-version").val(version)
         var url = "{{url('upload/')}}"+"/"+upload_id
          $("#upload-edit-form").attr('action', url)




     }
        </script>
        <script>
        function checking(e){
           $('#upload-table').find("td input[type='checkbox']").prop('checked', $(e).prop('checked'));
     }
    
     $(document).on('click','#bulk_delete',function(){
      var id=[];
      if (confirm("Are you sure you want to delete this?"))
        {
            $('.upload_checkbox:checked').each(function(){
              id.push($(this).val())
            });
            if(id.length >0)
            {
               $.ajax({
                      url:"{!! Url('uploads-delete') !!}",
                      method:"get",
                      data: $('#checks:checked').serialize(),
                      beforeSend: function () {
                $('#gif').show();
                },
                success: function (data) {
                  
                $('#gif').hide();
                $('#response').html(data);
                location.reload();
                }
               })
            }
            else
            {
                alert("Please select at least one checkbox");
            }
        }  

     });
   </script>


      
    </div>
    </div>
     </div>

</div>

</div>



@stop