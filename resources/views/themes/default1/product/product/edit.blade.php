@extends('themes.default1.layouts.master')
@section('title')
Edit Product
@stop
@section('content-header')
<h1>
Edit Product
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('products')}}">All Products</a></li>
        <li class="active">Edit Product</li>
      </ol>
@stop
@section('content')
<style>
    .more-text{
     display:none;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/js/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
    $(function () {
        $("#chkYes").click(function () {
            if ($("#chkYes").is(":checked")) {
                $("#git").show();
                $("#uploads").hide();
                $("#hide").hide();
            }
            else{
               $("#git").hide();
                $("#uploads").hide();
                $("#hide").hide();
            } 
        });
    });

    $(function () {
        $("#chkNo").click(function () {
            if ($("#chkNo").is(":checked")) {
                $("#git").hide();
                $("#uploads").show();
                $("#hide").show();
            } else {
                $("#git").hide();
                $("#uploads").hide();
                $("#hide").hide();
            } 
        });
    });

</script>
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #1b1818 !important;
</style>
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
                     <i class="fa fa-check"></i>
                     <b>{{Lang::get('message.success')}}!</b> 
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
                {!! Form::model($product,['url'=>'products/'.$product->id,'method'=>'patch','files' => true,'id'=>'editproduct']) !!}
                <h4>{{Lang::get('message.product')}}	
                    <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button>
                </h4>

       </div>
       <div class="box-body">
         <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">{{Lang::get('message.details')}}</a></li>
                        <li><a href="#tab_2" data-toggle="tab">{{Lang::get('message.plans')}}</a></li>
                        </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-3 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <!-- first name -->
                                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                                    {!! Form::text('name',null,['class' => 'form-control', 'id'=>'productname']) !!}
                                    <h6 id= "namecheck"></h6>

                                </div>
                   
                                <div class="col-md-3 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('type',Lang::get('message.lic_type')) !!}
                                    {!! Form::select('type',['Types'=>$type],null,['class' => 'form-control']) !!}

                                </div>
                                <?php
                               $groups = DB::table('product_groups')->pluck('name', 'id')->toarray();
                                ?>
                                <div class="col-md-3 form-group {{ $errors->has('group') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('group',Lang::get('message.group')) !!}
                                        <select name="group"  class="form-control">
                            <option>Choose</option>
                            @foreach($groups as $key=>$group)
                                   <option value="{{$key}}" <?php  if (in_array($group, $selectedGroup)) {
                                    echo "selected";
                                } ?>>{{$group}}</option>
                           
                             @endforeach
                              </select>
                                </div>
                                <?php
                               $types = DB::table('product_categories')->pluck('category_name')->toarray();
                                ?>
                                <div class="col-md-3 form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('category',Lang::get('message.category')) !!}

                                   <!--  {!! Form::select('category',['helpdesk'=>'Helpdesk','servicedesk'=>'ServiceDesk','service'=>'Service','satellite helpdesk'=>'Satellite Helpdesk','helpdeskvps'=>'HelpDesk VPS','servicedesk vps'=>'ServiceDesk VPS'],null,['class' => 'form-control']) !!} -->
                            <select name="category"  class="form-control">
                            <option value="">Choose</option>
                            @foreach($types as $key=>$type)
                                   <option value="{{$type}}" <?php  if (in_array($type, $selectedCategory)) {
                                    echo "selected";
                                } ?>>{{$type}}</option>
                           
                             @endforeach
                              </select>


                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6 form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    
                                    {!! Form::label('description',Lang::get('message.description'),['class'=>'required']) !!}
                                    {!! Form::textarea('description',null,['class' => 'form-control','id'=>'textarea1']) !!}
                                     <h6 id= "descheck"></h6>
                                     </div>
                                   <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('sku',Lang::get('message.sku'),['class'=>'required']) !!}
                                                {!! Form::text('product_sku',null,['class' => 'form-control']) !!}

                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('parent',Lang::get('message.parent')) !!}
                                                {!! Form::select('parent[]',['Products'=>$products],null,['class' => 'form-control']) !!}

                                            </div>
                                        </li>

                                        <li>
                                        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                {!! Form::label('image',Lang::get('message.image')) !!}
                                                {!! Form::file('image') !!}

                                        </div>
                                                </li>
                                      
                                       
                                            <table class="table table-responsive">
                                                <input type="hidden" value="{{$checkowner}}" id="checkowner">
                                              <span>Where do you want to retrieve your files from?</span>
                                             </br>
                                             <input type="hidden" value="{{$githubStatus}}" id="gitstatus">    
                                                <tr>  
                                                      
                                                    <td>
                                                        <label for="chkYes" style="display: block;">
                                                        <input type="radio" id="chkYes" name="chkTax" />
                                                        Github
                                                    </label>
                                                      <div class="col-md-10 gitstatus" id="git" style="display:none">
                                                <li>
                                                    <div class="form-group {{ $errors->has('github_owner') ? 'has-error' : '' }}">
                                                        <!-- first name -->
                                                        {!! Form::label('github_owner',Lang::get('message.github-owner')) !!}
                                                         {!! Form::text('github_owner',null,['class'=>'form-control','id'=>'owner']) !!}
                                                        
                                                    </div>
                                                    <div class="form-group {{ $errors->has('github_repository') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                 {!! Form::label('github_repository',Lang::get('message.github-repository-name')) !!}
                                                     {!! Form::text('github_repository',null,['class'=>'form-control','id'=>'repo']) !!}
                                                        

                                                    </div>  
                                                </li>
                                            
                                                <li>
                                                    <div class="form-group {{ $errors->has('version') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                        {!! Form::label('version',Lang::get('message.version')) !!}
                                                         {!! Form::text('version',null,['class'=>'form-control','id'=>'version']) !!}


                                                      </div>
                                                </li>
                                                      </div>
                                                   </td>
                                                 </tr>
                                                <tr>
                                                    <td><label for="chkNo">
                                                        <input type="radio" id="chkNo" name="chkTax" />
                                                            Filesystem
                                                        </label>
                                                    </td>
                                                </tr>
                                             </table>
                                       
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
                                         <li>
                                            <div class="row">

                                               <div class="form-group {{ $errors->has('hidden') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('hidden',Lang::get('message.hidden')) !!}
                                                {!! Form::hidden('hidden', 0) !!}
                                                <?php 
                                                $value=  "";
                                                if ($product->hidden==1) {
                                                    $value = 'true';
                                                }
                                                ?>
                                                <p>{!! Form::checkbox('hidden',1,$value) !!}  {{Lang::get('message.tick-to-hide-from-order-form')}}</p>

                                            </div>

                                            </div>
                                        </li>
                                   
                                </div>

                            </div>

                  
                        </div>

                         <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <table class="table table-responsive">

                                    <br/>
                                <span>Show on Cart Page</span>
                                 <tr>
                                    <div class="row">
                                    <td>
                                          
                                        <div><label>
                                             {!! Form::radio('show_agent',1,null,['id'=>'agent']) !!}
                                              <!-- <input type ="radio" id="agent" value="0" name="cartquantity" hidden>   -->
                                            Agents
                                        </label></div>
                                   
                                    <br/> 
                                    <div class="col-md-10" id="allowmulagent" style="display:none">
                                       <p>{!! Form::checkbox('can_modify_agent',1,null,['id'=>'agent_multiple_quantity']) !!} {{Lang::get('message.allow_multiple_agents_quantity')}} </p>
                                    </div>

                                   </td>
                                  </div>
                                </tr>
                                <tr>
                                    <td>
                                        <div><label>
                                             {!! Form::radio('show_agent',0,null,['id'=>'quantity']) !!}
                                            Product Quantity
                                         </label>
                                     </div>
                                         <br/>
                                     <div class="col-md-10" id="allowmulproduct" style="display:none">
                                       <p>{!! Form::checkbox('can_modify_quantity',1,null,['id'=>'product_multiple_quantity']) !!}  {{Lang::get('message.allow_multiple_product_quantity')}} </p>
                                    </div>
                                  
                                    </td>
                                      </div>
                                </tr>
                                  </table>
                                
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
                                                } else {
                                                    $saved=[];
                                                }
                                               
                                                if (count($saved) > 0) {
                                                    foreach ($saved as $key => $value) {
                                                        $savedkey[]=$key;
                                                    }
                                                    $saved1=$savedkey?$savedkey:[];
                                                } else {
                                                    $saved1=[];
                                                }
                                                  
                                        
                                                ?>
                                                
                                                        
                                                <div class="col-md-2">
                                                   <select id="editTax" placeholder="Select Taxes" name="tax[]" style="width:500px;" class="select2" multiple="true">
                                                      
                                                       @foreach($taxes as $taxkey => $taxvalue)
                                                  
                                                        <option value={{$taxkey}} <?php echo (in_array($taxkey, $savedTaxes)) ?  "selected" : "" ;  ?>>{{$taxvalue}}</option> 
                                                        
                                                       @endforeach
                                                    </select>
                                                    
                                                   
                                                   
                                                </div>
                                               
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                           
                      
                <!-- nav-tabs-custom -->

           

          {!! Form::close() !!}

           <h3>  Plans &nbsp;
            <!-- <a href="#create-plan-option" data-toggle="modal" data-target="#create-plan-option" class="btn btn-default">Add new</a> -->
        </h3>
                           
                            @include('themes.default1.product.plan.create') 

                              @if($product->plan())
                            <table class="table table-responsive">
                                
                                <tr>
                                    <th>Name</th>
                                    <th>Months</th>
                                    <th>Action</th>
                                </tr>
                                 @foreach($product->plan()->where('product',$product->id)->get() as $plan)
                                <tr>

                                    <td>{{$plan->name}}</td> 
                                    <?php
                                    if ($plan->days != '') {
                                        $months = $plan->days / 30;
                                    } else {
                                         $months = 'No Period Selected';
                                    } 
                                   
                                    ?>
                                    <td>{{round($months)}}</td> 
                                    <td><a href="{{url('plans/'.$plan->id.'/edit')}}" class="btn btn-primary btn-xs"><i class='fa fa-edit' style='color:white;'></i>&nbsp;&nbsp;Edit</a></td> 
                                </tr>
                                @endforeach
                            </table>
                            @else
                            <td>No Plans Created</td>
                            @endif
                              </div>
                       
                           <!-- /.tab-pane -->
                        

                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                </div>
                 </div>
                   </div>
                   </div>
     
        <div class="row" id="hide" style="display:none">
        <div class="col-md-12">
        <div class="box box-primary" id="uploads">
            <div class="box-header with-border" >
                <h3 class="box-title">Upload Files</h3>
                
                 <a href="#create-upload-option" id="create" class="btn btn-primary  btn-sm pull-right" data-toggle="modal" data-target="#create-upload-option"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;{{Lang::get('message.add-file')}}</a>
                            @include('themes.default1.product.product.create-upload-option')
                            
             
            </div>
            <!-- <div id="response"></div> -->
            <div class="box-body" >
                <div class="row" >
                    <div class="col-md-12" >
                         <table id="upload-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                          <button  value="" class="btn btn-danger btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp; {{Lang::get('message.delmultiple')}}</button><br /><br />
                    <thead><tr>
                         <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                        <th>Title</th>
                        <th style="width:210px;">Description</th>
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



<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
                                        $(function(){
                                          tinymce.init({
                                         selector: '#product-description',
                                         height: 200,
                                       //  theme: 'modern',
                                         relative_urls: true,
                                         remove_script_host: false,
                                         convert_urls: false,
                                         plugins: [
                                          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                          'searchreplace wordcount visualblocks visualchars code fullscreen',
                                          'insertdatetime media nonbreaking save table contextmenu directionality',
                                          'emoticons template paste textcolor colorpicker textpattern imagetools'
                                          ],
                                         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                          toolbar2: 'print preview media | forecolor backcolor emoticons',
                                          image_advtab: true,
                                          templates: [
                                              {title: 'Test template 1', content: 'Test 1'},
                                              {title: 'Test template 2', content: 'Test 2'}
                                          ],
                                          content_css: [
                                              '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                              '//www.tinymce.com/css/codepen.min.css'
                                          ]
                                          });
                                          tinymce.init({
                                         selector: '#textarea1',
                                         height: 200,
                                       //  theme: 'modern',
                                         relative_urls: true,
                                         remove_script_host: false,
                                         convert_urls: false,
                                         plugins: [
                                          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                          'searchreplace wordcount visualblocks visualchars code fullscreen',
                                          'insertdatetime media nonbreaking save table contextmenu directionality',
                                          'emoticons template paste textcolor colorpicker textpattern imagetools'
                                          ],
                                         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                          toolbar2: 'print preview media | forecolor backcolor emoticons',
                                          image_advtab: true,
                                          templates: [
                                              {title: 'Test template 1', content: 'Test 1'},
                                              {title: 'Test template 2', content: 'Test 2'}
                                          ],
                                          content_css: [
                                              '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                              '//www.tinymce.com/css/codepen.min.css'
                                          ]
                                    });
                                              tinymce.init({
                                         selector: '#textarea3',
                                         height: 200,
                                       //  theme: 'modern',
                                         relative_urls: true,
                                         remove_script_host: false,
                                         convert_urls: false,
                                         plugins: [
                                          'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                          'searchreplace wordcount visualblocks visualchars code fullscreen',
                                          'insertdatetime media nonbreaking save table contextmenu directionality',
                                          'emoticons template paste textcolor colorpicker textpattern imagetools'
                                          ],
                                         toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                          toolbar2: 'print preview media | forecolor backcolor emoticons',
                                          image_advtab: true,
                                          templates: [
                                              {title: 'Test template 1', content: 'Test 1'},
                                              {title: 'Test template 2', content: 'Test 2'}
                                          ],
                                          content_css: [
                                              '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                              '//www.tinymce.com/css/codepen.min.css'
                                          ]
                                    });
                                });
                                    </script>
<script type="text/javascript">
        function readmore(){
                        var maxLength = 300;
                        $("#upload-table tbody tr td").each(function(){
                            var myStr = $(this).text();
                            if($.trim(myStr).length > maxLength){
                                var newStr = myStr.substring(0, maxLength);
                                 $(this).empty().html(newStr);
                                var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                                $(this).append('<span class="more-text">' + removedStr + '</span>');
                                $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
                            }
                          }); 
                         }
        $('#upload-table').DataTable({
              destroy: true,
            "initComplete": function(settings, json) {
                         readmore();
            },
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
                bindEditButton();
                $('.loader').css('display', 'none');
            },
            "fnPreDrawCallback": function(oSettings, json) {
                $('.loader').css('display', 'block');
            },
        });
        
  
     
        function bindEditButton() {
        $('.editUploadsOption').click(function(){
        var upload_id = $(this).attr('data-id');
        var title =  $(this).attr('data-title');
        var version=  $(this).attr('data-version');
        var description =  $(this).attr('data-description');
        tinymce.get('product-description').setContent(description);
         $('#edit-uplaod-option').modal('show');
          $("#uploadid").val(upload_id);
         $("#product-title").val(title);
        $("#product-version").val(version);
    })
      }

         $("#editProductUpload").on('click',function(){
      $("#editProductUpload").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
    var upload_id = $('#uploadid').val();
    var productname = $('#editName').val();
    var producttitle = $('#product-title').val();
    var description = tinyMCE.get('product-description').getContent()
    var version = $('#product-version').val();
    $.ajax({
       type : "PATCH",
       url  :  "{{url('upload/')}}"+"/"+upload_id,
       data :  {'productname': productname , 'producttitle': producttitle, 
       'description': description,'version':version},
       success: function(response) {
         $("#editProductUpload").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        $('#alertMessage2').show();
        $('#error1').hide();
        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-check"></i> Success! </strong>'+response.message+'.</div>';
        $('#alertMessage2').html(result+ ".");
       } ,
       error: function(ex) {
         $("#editProductUpload").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
        for (key in ex.responseJSON.errors) {
           html += '<li>'+ ex.responseJSON.errors[key][0] + '</li>'
        }
          html += '</ul></div>';
           $('#error1').show(); 
           document.getElementById('error1').innerHTML = html;
       }
    });

 })
      

    
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


      
   
  
   


 <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
  <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script>
        $(document).on('click','#upload-table tbody tr td .read-more',function(){
        var text=$(this).siblings(".more-text").text().replace('read more...','');
        $(this).siblings(".more-text").html(text);
        $(this).siblings(".more-text").contents().unwrap();
        $(this).remove();
    });
</script>
<script>
       // Jquery validation for Product Creation
    $(document).ready(function(){
        $('#namecheck').hide();
        $('#descheck').hide();

        var nameErr= true;
        var desErr = true;

        $('#editproduct').submit(function(){
            function name_check(){
                var name = $('#productname').val();
                if (name.length == ''){
                   $('#namecheck').show(); 
                   $('#namecheck').html('This field is required'); 
                   $('#namecheck').focus();
                   $('#productname').css("border-color","red");
                   $('#namecheck').css({"color":"red","margin-top":"5px"});
                }
                 else{
                     $('#namecheck').hide();
                      $('#productname').css("border-color","");
                     return true;
                     }
            }

            function des_check(){
                var des = $('#textarea1').val();
                if (des.length == ''){
                    $('#descheck').show();
                    $('#descheck').html('This field is required');
                    $('#descheck').focus();
                    $('#textarea1').css("border-color","red");
                    $('#descheck').css({"color":"red","margin-top":"5px"});
                }
                else{
                     $('#descheck').hide();
                     $('#textarea1').css("border-color","");
                     return true;
                }
            }
            name_check();
            des_check();
             if(name_check() && des_check()){
                return true;
             }
            else{
            return false;
          }
        });
    });
</script>

<script>
    $(document).ready(function(){
        var githubstatus =  $('#gitstatus').val();
        
        if( $("input[type=radio][name='show_agent']:checked").val() == 1) {
            $('#agent').prop('checked',true);
            $('#allowmulagent').show();
             if($('#agent_multiple_qty').val() == 1) {
                $('#agent_multiple_qty').prop('checked',true);
             }
        }
           if($("input[type=radio][name='show_agent']:checked").val() == 0) {
            $('#quantity').prop('checked',true);
            $('#allowmulproduct').show();
             if($('#product_multiple_qty').val() == 1) {
                $('#product_multiple_qty').prop('checked',true);
             }
        }

        if($('#checkowner').val() != '') {
            $('#chkYes').prop('checked',true);
            $('#git').show();
             if(githubstatus == 0) {
            $("#owner").attr('disabled',true);
             $("#repo").attr('disabled',true);
              $("#version").attr('disabled',true);
        } else {
              $("#owner").attr('enabled',true);
             $("#repo").attr('enabled',true);
              $("#version").attr('enabled',true);
        }
        } else if($('#checkowner').val() == '') {
            $('#chkNo').prop('checked',true);
            document.getElementById("hide").style.display="block";
            $("#uploads").show();
        }
        
    // })

});


       $(function() {
        $('#agent').click(function(){
            if($('#agent').is(":checked")) {
               $("#allowmulagent").show();
               $("#allowmulproduct").hide();
            } 
        })

    })

    $(function() {
        $('#quantity').click(function(){
            if($('#quantity').is(":checked")) {
               $("#allowmulagent").hide();
               $("#allowmulproduct").show();
            } 
        })

    })

</script>


<script>
    $(document).ready(function() {
    $("#editTax").select2({
        placeholder: 'Select Taxes',
        tags:true
    });
});
</script>
<script>
 $(document).ready(function(){
     
var $ = window.$; // use the global jQuery instance

var $fileUpload = $('#resumable-browse');
var $fileUploadDrop = $('#resumable-drop');
var $uploadList = $("#file-upload-list");

if ($fileUpload.length > 0 && $fileUploadDrop.length > 0) {
    var resumable = new Resumable({
        // Use chunk size that is smaller than your maximum limit due a resumable issue
        // https://github.com/23/resumable.js/issues/51
        chunkSize: 1 * 1024 * 1024, // 1MB
        simultaneousUploads: 3,
        testChunks: false,
        throttleProgressCallbacks: 1,
        // Get the url from data-url tag
        target: $fileUpload.data('url'),
        // Append token to the request - required for web routes
        query:{_token : $('input[name=_token]').val()}
    });

// Resumable.js isn't supported, fall back on a different method
    if (!resumable.support) {
        $('#resumable-error').show();
    } else {
        // Show a place for dropping/selecting files
        $fileUploadDrop.show();
        resumable.assignDrop($fileUpload[0]);
        resumable.assignBrowse($fileUploadDrop[0]);

        // Handle file add event
        resumable.on('fileAdded', function (file) {
            // Show progress pabr
            $uploadList.show();
            // Show pause, hide resume
            $('.resumable-progress .progress-resume-link').hide();
            $('.resumable-progress .progress-pause-link').show();
            // Add the file to the list
            $uploadList.append('<li class="resumable-file-' + file.uniqueIdentifier + '">Uploading <span class="resumable-file-name"></span> <span class="resumable-file-progress"></span>');
            $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-name').html(file.fileName);
            // Actually start the upload
            resumable.upload();
        });
        resumable.on('fileSuccess', function (file, message) {
            // Reflect that the file upload has completed
            $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(completed)');
             $("#file_ids").val(JSON.parse(message).name);
        });
        resumable.on('fileError', function (file, message) {
            // Reflect that the file upload has resulted in error
            $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html('(file could not be uploaded: ' + message + ')');
        });
        resumable.on('fileProgress', function (file) {
            // Handle progress for both the file and the overall upload
            $('.resumable-file-' + file.uniqueIdentifier + ' .resumable-file-progress').html(Math.floor(file.progress() * 100) + '%');
            $('.progress-bar').css({width: Math.floor(resumable.progress() * 100) + '%'});
        });
    }

}
})

 //------------------------------------------------------------------------------------------------------------//
 


 $("#uploadVersion").on('click',function(){
      $("#uploadVersion").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
     var filename = $('#file_ids').val();
    var productname = $('#productname').val();
    var producttitle = $('#producttitle').val();
    var description = tinyMCE.get('textarea3').getContent()
    var version = $('#productver').val();
    $.ajax({
       type : "POST",
       url  :  "{!! route('upload/save') !!}",
       data :  {'filename': filename , 'productname': productname , 'producttitle': producttitle, 
       'description': description,'version':version},
       success: function(response) {
         $("#uploadVersion").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        $('#alertMessage1').show();
        $('#error').hide();
        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-check"></i> Success! </strong>'+response.message+'.</div>';
        $('#alertMessage1').html(result+ ".");
       } ,
       error: function(ex) {
         $("#uploadVersion").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        var html = '<div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<br><br><ul>';
        for (key in ex.responseJSON.errors) {
           html += '<li>'+ ex.responseJSON.errors[key][0] + '</li>'
        }
          html += '</ul></div>';
           $('#error').show(); 
           document.getElementById('error').innerHTML = html;
       }
    });

 })
</script>


@stop