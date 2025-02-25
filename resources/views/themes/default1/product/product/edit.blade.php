@extends('themes.default1.layouts.master')
@section('title')
Edit Product
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Edit Product</h1>
      
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('products')}}"><i class="fa fa-dashboard"></i> Products</a></li>
            <li class="breadcrumb-item active">Edit Product</li>
        </ol>
    </div><!-- /.col -->
@stop

@section('content')
<style>
    .more-text{
     display:none;
}
</style>
<link rel="stylesheet" href="{{asset('admin/css/select2.min.css')}}">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
    background-color: #1b1818 !important;}
</style>
<div class="card card-secondary card-tabs">

    {!! Form::model($product,['url'=>'products/'.$product->id,'method'=>'patch','files' => true,'id'=>'editproduct']) !!}
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-detail-tab" data-toggle="pill" href="#custom-tabs-detail" role="tab" aria-controls="custom-tabs-detail" aria-selected="true">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-plan-tab" data-toggle="pill" href="#custom-tabs-plan" role="tab" aria-controls="custom-tabs-plan" aria-selected="false">Plans</a>
            </li>
        </ul>
    </div>
       <div class="card-body table-responsive">

                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-detail" Role="tabpanel" aria-labelledby="custom-tabs-detail-tab">
                            <div class="row">
                                <div class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <!-- first name -->
                                    {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                                    {!! Form::text('name',null,['class' => 'form-control', 'id'=>'productname']) !!}
                                    @error('name')
                                    <span class="error-message"> {{$message}}</span>
                                    @enderror
                                    <div class="input-group-append">
                                    </div>
                                </div>

                                <div class="col-md-4 form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('type',Lang::get('message.lic_type'),['class'=>'required']) !!}
                                    {!! Form::select('type',[''=>'Choose','Types'=>$type],null,['class' => 'form-control']) !!}
                                    @error('type')
                                    <span class="error-message"> {{$message}}</span>
                                    @enderror
                                    <div class="input-group-append">
                                    </div>
                                </div>
                                <?php
                               $groups = DB::table('product_groups')->pluck('name', 'id')->toarray();
                                ?>
                                <div class="col-md-4 form-group {{ $errors->has('group') ? 'has-error' : '' }}">
                                    <!-- last name -->
                                    {!! Form::label('group',Lang::get('message.group'),['class'=>'required']) !!}
                                    {!! Form::select('group',[''=>'Choose','Groups'=>$groups],null,['class' => 'form-control','id'=>'groups']) !!}
                                    @error('group')
                                    <span class="error-message"> {{$message}}</span>
                                    @enderror
                                    <div class="input-group-append">
                                    </div>
                                </div>
                                
                                

                            </div>

                            <div class="row">

                                <div class="col-md-6 form-group {{ $errors->has('description') ? 'has-error' : '' }}">

                                    <script src="https://cdn.tiny.cloud/1/oiio010oipuw2n6qyq3li1h993tyg25lu28kgt1trxnjczpn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

                                    <script>
                                        tinymce.init({
                                            selector: 'textarea',
                                            height: 500,
                                            theme: 'silver',
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
                                            ],
                                            setup: function(editor) {
                                                editor.on('init', function () {
                                                    document.querySelector(".tox-tinymce").style.border = "1px solid silver"; // Change 'green' to any color
                                                });
                                            },
                                        });
                                    </script>
                                    {!! Form::label('description',Lang::get('message.description'),['class'=>'required']) !!}
                                    {!! Form::textarea('description',null,['class' => 'form-control','id'=>'text']) !!}
                                    @error('description')
                                    <span class="error-message"> {{$message}}</span>
                                    @enderror
                                     <h6 id= "descheck"></h6>
                                     </div>
                                   <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li>
                                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('sku',Lang::get('message.sku'),['class'=>'required']) !!}
                                                {!! Form::text('product_sku',null,['class' => 'form-control','id'=>'product_sku']) !!}
                                                @error('product_sku')
                                                <span class="error-message"> {{$message}}</span>
                                                @enderror
                                                <div class="input-group-append">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group {{ $errors->has('parent') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('parent',Lang::get('message.parent')) !!}
                                                {!! Form::select('parent[]',[''=>'Choose','Products'=>$products],null,['class' => 'form-control']) !!}
                                                @error('parent[]')
                                                <span class="error-message"> {{$message}}</span>
                                                @enderror
                                            </div>
                                        </li>

                                        <li>
                                        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                                        <!-- last name -->
                                                {!! Form::label('image',Lang::get('message.image')) !!}
                                                {!! Form::file('image') !!}
                                                <br>
                                                @if($product->image)
                                               <img src="{{$product->image }}" width="100px" alt="slider Image">
                                               @endif
                                            @error('image')
                                            <span class="error-message"> {{$message}}</span>
                                            @enderror
                                        </div>
                                                </li>
                                      
                                       
                                            <table class="table">
                                                <input type="hidden" value="{{$checkowner}}" id="checkowner">
                                              <span>Where do you want to retrieve your files from?</span>
                                             </br>
                                             <input type="hidden" value="{{$githubStatus}}" id="gitstatus">    
                                                <tr>  
                                                      
                                                    <td>
                                                        <label for="chkYes" style="">
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
                                                @error('chkTax')
                                                <span class="error-message"> {{$message}}</span>
                                                @enderror
                                             </table>
                                       
                                        <li>
                                            <div class="form-group {{ $errors->has('require_domain') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('require_domain',Lang::get('message.require_domain')) !!}
                                                {!! Form::hidden('require_domain', 0) !!}
                                                <p>{!! Form::checkbox('require_domain',1) !!} {{Lang::get('message.tick-to-show-domain-registration-options')}}</p>
                                                @error('require_domain')
                                                <span class="error-message"> {{$message}}</span>
                                                @enderror
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-group {{ $errors->has('shoping_cart_link') ? 'has-error' : '' }}">
                                                <!-- last name -->
                                                {!! Form::label('shoping_cart_link',Lang::get('message.shoping-cart-link')) !!}
                                                {!! Form::text('shoping_cart_link',null,['class'=>'form-control']) !!}
                                                @error('shoping_cart_link')
                                                <span class="error-message"> {{$message}}</span>
                                                @enderror
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
                                          <li>
                                            <div class="row">

                                               <div class="form-group {{ $errors->has('highlight') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('highlight',Lang::get('message.highlight')) !!}
                                                {!! Form::hidden('highlight', 0) !!}
                                                <?php 
                                                $value=  "";
                                                if ($product->highlight==1) {
                                                    $value = 'true';
                                                }
                                                ?>
                                                <p>{!! Form::checkbox('highlight',1,$value) !!}  {{Lang::get('message.tick-to-highlight-product')}}</p>
                                                   @error('highlight')
                                                   <span class="error-message"> {{$message}}</span>
                                                   @enderror
                                            </div>

                                            </div>
                                        </li>

                                            <li>
                                            <div class="row">

                                               <div class="form-group {{ $errors->has('add_to_contact') ? 'has-error' : '' }}">
                                                <!-- first name -->
                                                {!! Form::label('add_to_contact',Lang::get('Contact to sales')) !!}
                                                {!! Form::hidden('add_to_contact', 0) !!}
                                                <?php 
                                                $value=  "";
                                                if ($product->add_to_contact==1) {
                                                    $value = 'true';
                                                }
                                                ?>
                                                <p>{!! Form::checkbox('add_to_contact',1,$value) !!}  {{Lang::get('message.tick-to-add_to_contact-product')}}</p>
                                                   @error('add_to_contact')
                                                   <span class="error-message"> {{$message}}</span>
                                                   @enderror
                                            </div>

                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                  
                        </div>

                         <!-- /.tab-pane -->
                        <div class="tab-pane fade" id="custom-tabs-plan" role="tabpanel"  aria-labelledby="custom-tabs-plan-tab">
                            <table class="table">

                                <span class="required">Show on Cart Page</span>
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
                                        <div>
                                            <label>
                                             {!! Form::radio('show_agent',0,null,['id'=>'quantity']) !!}
                                            Product Quantity
                                         </label>
                                        </div>
                                         <br/>
                                     <div class="col-md-10" id="allowmulproduct" style="display:none">
                                       <p>{!! Form::checkbox('can_modify_quantity',1,null,['id'=>'product_multiple_quantity']) !!}  {{Lang::get('message.allow_multiple_product_quantity')}} </p>
                                    </div>
                                  
                                    </td>
                                </tr>
                                @error('show_agent')
                                <span class="error-message"> {{$message}}</span>
                                @enderror
                        </table>
                                
                                <tr>
                                    <td><b>{!! Form::label('tax',Lang::get('message.taxes')) !!}</b></td>
                                    <td>
                                        <div class="form-group {{ $errors->has('taxes') ? 'has-error' : '' }}">
                                            <div class="row">
                                                
                                                <div class="col-md-2">
                                                   <select id="editTax" placeholder="Select Taxes" name="tax[]" style="width:500px;" class="select2" multiple="true">
                                                    
                                                       @foreach($taxes as $value)
                                                        <option value={{$value['id']}} <?php echo (in_array($value['id'], $savedTaxes)) ?  "selected" : "" ;  ?>>{{$value['name'].'('.$value['name'].')'}}</option> 
                                                        
                                                       @endforeach
                                                    </select>


                                                    @error('tax[]')
                                                    <span class="error-message"> {{$message}}</span>
                                                    @enderror
                                                </div>

                                               
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                                 
                                       

                                            <br>
                                            <h3>  Plans &nbsp;
                                                <!-- <a href="#create-plan-option" data-toggle="modal" data-target="#create-plan-option" class="btn btn-default">Add new</a> -->
                                            </h3>

                                            @if($product->plan())
                                                <table class="table">

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
                                                            <td>{{round((int) $months)}}</td>
                                                             <td><a href="{{url('plans/'.$plan->id.'/edit')}}" class="btn btn-secondary btn-xs".{!! tooltip('Edit') !!}<i class='fa fa-edit' style='color:white;'></i></a></td>
                                                        </tr>
                                                     @endforeach
                                                </table>
                                    @else
                                        <td>No Plans Created</td>
                            @endif

                                        </div>
                                   

       </div>
        <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fas fa-sync-alt">&nbsp;</i>{!!Lang::get('message.update')!!}</button>

          {!! Form::close() !!}
                      
                <!-- nav-tabs-custom -->
            </div>
       </div>
</div>

        <div class="row" id="hide" style="display:none">
        <div class="col-md-12">
        <div class="card card-secondary card-outline" id="uploads">
            <div class="card-header">
                <h3 class="card-title">Upload Files</h3>

                <div class="card-tools">
                    <a href="#create-upload-option" id="create" class="btn btn-default  btn-sm pull-right" data-toggle="modal" data-target="#create-upload-option"><span class="fa fa-plus"></span>&nbsp;&nbsp;{{Lang::get('message.add-file')}}</a>
                    @include('themes.default1.product.product.create-upload-option')

                </div>
            </div>

            <!-- <div id="response"></div> -->
            <div id="product-alert-container"></div>
            <div class="card-body table-responsive">
                <div class="row" >
                    <div class="col-md-12" >
                         <table id="upload-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
                          <button  value="" class="btn btn-secondary btn-sm btn-alldell" id="bulk_delete"><i class="fa fa-trash"></i>&nbsp;&nbsp; {{Lang::get('message.delmultiple')}}</button><br /><br />
                    <thead><tr>
                         <th class="no-sort"><input type="checkbox" name="select_all" onchange="checking(this)"></th>
                        <th>Title</th>
                        <th style="width:210px;">Description</th>
                        <th>Version</th>
                        <th>ReleaseType</th>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Added modal-dialog-centered -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected files?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'all_product';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'all_product';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            container : 'body'
        });
    });
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<script>

    $(document).ready(function() {

        $('#text').on('change',function(){
            $('.tox-tinymce').css('border', '1px solid silver');
            removeErrorMessage(this);
        });

        const userRequiredFields = {
            name:@json(trans('message.product_details.add_name')),
            type:@json(trans('message.product_details.add_license_type')),
            group:@json(trans('message.product_details.add_group')),
            product_sku:@json(trans('message.product_details.add_product_sku')),
            description:@json(trans('message.product_details.add_description')),
        };

        $('#editproduct').on('submit', function (e) {

            if ($('#text').val() === '') {
                console.log(24);
                let editorContainer = document.querySelector(".tox-tinymce");
                editorContainer.style.border = "1px solid #dc3545";
            }
            else if($('#text').val() !== ''){
                let editorContainer = document.querySelector(".tox-tinymce");
                editorContainer.style.border = "1px solid silver";
            }else{
                let editorContainer = document.querySelector(".tox-tinymce");
                editorContainer.style.border = "1px solid silver";
            }

            const userFields = {
                name:$('#productname'),
                type:$('#type'),
                group:$('#groups'),
                product_sku:$('#product_sku'),
                description:$('#text'),

            };


            // Clear previous errors
            Object.values(userFields).forEach(field => {
                field.removeClass('is-invalid');
                field.next().next('.error').remove();

            });

            let isValid = true;

            const showError = (field, message) => {
                field.addClass('is-invalid');
                field.next().after(`<span class='error invalid-feedback'>${message}</span>`);
            };

            // Validate required fields
            Object.keys(userFields).forEach(field => {
                if (!userFields[field].val()) {
                    showError(userFields[field], userRequiredFields[field]);
                    isValid = false;
                }
            });

            // If validation fails, prevent form submission
            if (!isValid) {
                e.preventDefault();
            }
        });
        // Function to remove error when input'id' => 'changePasswordForm'ng data
        const removeErrorMessage = (field) => {
            field.classList.remove('is-invalid');
            const error = field.nextElementSibling;
            if (error && error.classList.contains('error')) {
                error.remove();
            }
        };

        // Add input event listeners for all fields
        ['name','type','group','product_sku','text'].forEach(id => {

            document.getElementById(id).addEventListener('input', function () {
                removeErrorMessage(this);

            });
        });
    });

</script>

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

            // function des_check(){
            //     var des = $('#textarea1').val();
            //     if (des.length == ''){
            //         $('#descheck').show();
            //         $('#descheck').html('This field is required');
            //         $('#descheck').focus();
            //         $('#textarea1').css("border-color","red");
            //         $('#descheck').css({"color":"red","margin-top":"5px"});
            //     }
            //     else{
            //          $('#descheck').hide();
            //          $('#textarea1').css("border-color","");
            //          return true;
            //     }
            // }
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

function privateRelease()
{
    // val = $('#p_release').val();
    if ($('#p_release').attr('checked',true)) {
        $('#p_release').val('1');
    } else {
        $('#p_release').val('0');
    }
}

function resrictedRelease()
{
    // val = $('#p_release').val();
    if ($('#r_release').attr('checked',true)) {
        $('#r_release').val('1');
    } else {
        $('#r_release').val('0');
    }
}

function preRelease()
{
    if ($('#pre_release').attr('checked',true)) {
        $('#pre_release').val('1');
    } else {
        $('#pre_release').val('0');
    }
}


 $("#uploadVersion").on('click',function(){
      $("#uploadVersion").html("<i class='fas fa-circle-notch fa-spin'></i>  Please Wait...");
     var filename = $('#file_ids').val();
    var productname = $('#productname').val();
    var producttitle = $('#producttitle').val();
    var description = tinyMCE.get('textarea3').getContent()
    var version = $('#productver').val();
    var dependencies = $('#dependencies').val();
    var private = $('#p_release').val();
    var restricted = $('#r_release').val();
    var releaseType = $('#release_type').val();
    $.ajax({
       type : "POST",
       url  :  "{!! route('upload/save') !!}",
       data :  {'filename': filename , 'productname': productname , 'producttitle': producttitle,
       'description': description,'dependencies':dependencies,'version':version,'is_private': private,'is_restricted': restricted,'release_type': releaseType,'_token': '{!! csrf_token() !!}'},
       success: function(response) {
         $("#uploadVersion").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
        $('#alertMessage1').show();
        $('#error').hide();
        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="far fa-check"></i> Success! </strong>'+response.message+'.</div>';
        $('#alertMessage1').html(result+ ".");
       } ,
       error: function(ex) {
         $("#uploadVersion").html("<i class='fa fa-save'>&nbsp;&nbsp;</i>Save");
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