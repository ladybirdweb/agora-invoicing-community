@extends('themes.default1.layouts.master')
@section('title')
System Setting
@stop
@section('content-header')
    <div class="col-sm-6">
        <h1>Company Details</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('settings')}}"><i class="fa fa-dashboard"></i> Settings</a></li>
            <li class="breadcrumb-item active">System Settings</li>
        </ol>
    </div><!-- /.col -->
@stop
@section('content')

    <div id="alertMessage3"></div>
    <div id="error2"></div>

<div class="row">

    <div class="col-md-12">
        <div class="card card-secondary card-outline">
            <div class="box-header">

            </div>

            <div class="card-body">
                {!! Form::model($set,['url'=>'settings/system','method'=>'patch','files'=>true, 'enctype' => 'multipart/form-data']) !!}
                <div class="row">
                 <div class="col-md-6">
              

                  

                    <tr>

                        <td><b>{!! Form::label('company',Lang::get('message.company-name'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">


                                {!! Form::text('company',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('company_email',Lang::get('message.company-email'),['class'=>'required']) !!}</b></td>
                       
                        <td>
                            <div class="form-group {{ $errors->has('company_email') ? 'has-error' : '' }}">


                                {!! Form::text('company_email',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>
                      <tr>

                        <td><b>{!! Form::label('title',Lang::get('message.app-title')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">


                                {!! Form::text('title',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('website',Lang::get('message.website'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('website') ? 'has-error' : '' }}">


                                {!! Form::text('website',null,['class' => 'form-control']) !!}
                               

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('phone',Lang::get('message.phone'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">


                                {!! Form::text('phone',null,['class' => 'form-control selected-dial-code', 'type'=>'tel','id'=>'phone']) !!}
                                
                                 {!! Form::hidden('phone_code',null,['id'=>'phone_code_hidden']) !!}
     
                                <span id="valid-msg" class="hide"></span>
                                <span id="error-msg" class="hide"></span>
                               

                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td><b>{!! Form::label('address',Lang::get('message.address'),['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">

                                {!! Form::textarea('address',null,['class' => 'form-control','size' => '128x10','id'=>'address']) !!}
                               
                            </div>
                        </td>

                    </tr>
                     <tr>

                        <td><b>{!! Form::label('City',Lang::get('message.city')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">


                                {!! Form::text('city',null,['class' => 'form-control']) !!}
                                

                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('zip','Zip') !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('zip') ? 'has-error' : '' }}">


                                {!! Form::text('zip',null,['class' => 'form-control']) !!}
                                

                            </div>
                        </td>

                    </tr>
             
            </div>
            <div class="col-md-6">
            
                    <tr>

                        <td><b>{!! Form::label('country',Lang::get('message.country') ,['class'=>'required']) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">


                                <!-- {!! Form::text('country',null,['class' => 'form-control']) !!} -->
                                <!-- <p><i> {{Lang::get('message.country')}}</i> </p> -->
                                  <?php $countries = \App\Model\Common\Country::pluck('nicename', 'country_code_char2')->toArray(); ?>

                     <select name="country" value= "Choose" id="country" onChange="getCountryAttr(this.value)" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                             <option value="">Choose</option>
                           @foreach($countries as $key=>$country)
                              <option value="{{$key}}" <?php  if(in_array($country, $selectedCountry) ) { echo "selected";} ?>>{{$country}}</option>
                          @endforeach
                          </select>


                    </div>

                           
                        </td>

                    </tr>

                     <tr class="form-group ">
                              
                               

                             <div class="form-group cin">
                                  <td>
                                    {!! Form::label('CIN No.',Lang::get('CIN')) !!}
                                </td>

                                 <td>
                                {!! Form::text('cin_no',null,['class' => 'form-control','id'=>'cin']) !!}

                            </div>
                                     
                                 </td>
                          
                        </tr>

                     <tr class="form-group ">
                              <div class="form-group gstin">
                                 <td>
                                    {!! Form::label('GSTIN',Lang::get('GSTIN')) !!}
                                </td>

                                 <td>
                                     
                                    {!! Form::text('gstin',null,['class' => 'form-control','id'=>'gstin']) !!}
                                 </div>
                                 </td>
                          
                        </tr>

                        

                    <tr>

                        <td><b>{!! Form::label('state',Lang::get('message.state') ,['class'=>'required']) !!}</b></td>
                        <td>
                        <select name="state" id="state-list" class="form-control">
                                @if($set->state)
                             <option value="{{$state['id']}}">{{$state['name']}}</option>
                            @endif
                            <option value="">Choose</option>
                            @foreach($states as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach

                        </select>
                        </td>
                    </tr>
                    <br>
                        <tr>

                        <td><b>{!! Form::label('default_currency',Lang::get('message.default-currency') ,['class'=>'required']) !!}</b></td>
                        <td>
                             <?php $currencies = \App\Model\Payment\Currency::where('status',1)->pluck('name','code')->toArray(); 
                             ?>
                         <select name="default_currency" value= "Choose"  class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-dropup-auto="false" data-size="10">
                               <option value="">Choose</option>
                           @foreach($currencies as $key=>$currency)
                              <option value="{{$key}}" <?php  if(in_array($currency, $selectedCurrency) ) { echo "selected";} ?>>{{$currency}}</option>
                          @endforeach

                        </select>
                        </td>
                    </tr>
                    <br>
                      <tr>
                     
                        <td><b>{!! Form::label('logo',Lang::get('message.admin-logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                
                                <p><i> {{Lang::get('Upload Application logo')}}</i> </p>
                                @if($set->admin_logo) 
                                <img src='{{ asset("admin/images/$set->admin_logo")}}' class="img-thumbnail" style="height: 50px;">&nbsp;&nbsp;
                                

                                 <button  type="button"  id="{{$set->id}}" data-url=""  data-toggle="tooltip"  value="admin" class="btn btn-sm btn-secondary show_confirm " label="" style="font-weight:500;" name="logo" value="client_logo" title="Delete  logo." style="background-color: #6c75c7d;">
                                <i class="fa fa-trash"></i></button>
                                @else
                                {!! Form::file('admin-logo') !!}
                                @endif
                            </div>
                        </td>
                       
                    </tr>

                     <tr>

                        <td><b>{!! Form::label('icon',Lang::get('message.fav-icon')) !!}</b></td>

                        <td>
                            <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">

                               
                                <p><i> {{Lang::get('Upload favicon for Admin and Client Panel')}}</i> </p>
                                @if($set->fav_icon) 
                                <img src='{{asset("common/images/$set->fav_icon")}}' class="img-thumbnail" style="height: 50px;">&nbsp;&nbsp;

                      

                                      <button  type="button"  id="{{$set->id}}" data-url=""  data-toggle="tooltip"  value="fav" class="btn btn-sm btn-secondary show_confirm " label="" style="font-weight:500;" name="logo" value="client_logo" title="Delete  logo." style="background-color: #6c75c7d;">
                                <i class="fa fa-trash"></i></button>
                                @else
                                 {!! Form::file('fav-icon') !!}
                                @endif
                            </div>
                        </td>
                       
                    </tr>

                     <tr>

                        <td><b>{!! Form::label('favicon_title',Lang::get('message.fav-title-admin')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('favicon_title') ? 'has-error' : '' }}">


                                {!! Form::text('favicon_title',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                     <tr>

                        <td><b>{!! Form::label('favicon_title_client',Lang::get('message.fav-title-client')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('favicon_title_client') ? 'has-error' : '' }}">


                                {!! Form::text('favicon_title_client',null,['class' => 'form-control']) !!}
                                


                            </div>
                        </td>

                    </tr>

                    <tr>

                        <td><b>{!! Form::label('logo',Lang::get('message.client-logo')) !!}</b></td>
                        <td>
                            <div class="form-group {{ $errors->has('logo') ? 'has-error' : '' }}">

                                
                                <p><i> {{Lang::get('Upload the company logo')}}</i> </p>
                                @if($set->logo) 
                                <img src='{{asset("images/$set->logo")}}' class="img-thumbnail" style="height: 50px;"> &nbsp;&nbsp;
                                 
                                 <button  type="button"  id="{{$set->id}}" data-url=""  data-toggle="tooltip"  value="logo" class="btn btn-sm btn-secondary show_confirm " label="" style="font-weight:500;" name="logo" value="client_logo" title="Delete  logo." style="background-color: #6c75c7d;">
                                <i class="fa fa-trash"></i></button>
                                @else
                                {!! Form::file('logo') !!}

                                @endif
                            </div>
                        </td>

                    </tr>


            </div>

                </div>
                <button type="submit" class="btn btn-primary" id="submit" name="submit" value="save" ><i class="fa fa-sync-alt">&nbsp;</i>{!!Lang::get('message.update')!!}</button>
               

                {!! Form::close() !!}
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
 
      $(document).ready(function(){
         $(function () {
             //Initialize Select2 Elements
             $('.select2').select2()
         });
    var country = $('#country').val();
    if(country == 'IN') {
        $('#gstin').show()
    } else {
        $('#gstin').hide();
    }
    getCode(country);
    var telInput = $('#phone');
    addressDropdown = $("#country");
     errorMsg = document.querySelector("#error-msg"),
    validMsg = document.querySelector("#valid-msg");
    var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];
     let currentCountry="";
    telInput.intlTelInput({
        initialCountry: "auto",
        geoIpLookup: function (callback) {

            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                resp.country = country;

                var countryCode = (resp && resp.country) ? resp.country : "";
                    currentCountry=countryCode.toLowerCase()
                    callback(countryCode);
            });
        },
        separateDialCode: true,
       utilsScript: "{{asset('js/intl/js/utils.js')}}"
    });
     var reset = function() {
      errorMsg.innerHTML = "";
      errorMsg.classList.add("hide");
      validMsg.classList.add("hide");
    };
    setTimeout(()=>{
         telInput.intlTelInput("setCountry", currentCountry);
    },500)
     $('.intl-tel-input').css('width', '100%');
    telInput.on('blur', function () {
      reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#phone').css("border-color","");
              validMsg.classList.remove("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#phone').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });

     addressDropdown.change(function() {
     telInput.intlTelInput("setCountry", $(this).val());
             if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
              $('#phone').css("border-color","");
              errorMsg.classList.add("hide");
              $('#submit').attr('disabled',false);
            } else {
              var errorCode = telInput.intlTelInput("getValidationError");
             errorMsg.innerHTML = errorMap[errorCode];
             $('#phone').css("border-color","red");
             $('#error-msg').css({"color":"red","margin-top":"5px"});
             errorMsg.classList.remove("hide");
             $('#submit').attr('disabled',true);
            }
        }
    });
    $('input').on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    $('form').on('submit', function (e) {
        $('input[name=mobileds]').attr('value', $('.selected-dial-code').text());
    });


});
 
 
     $('.show_confirm').click(function(event) {
          var id = $(this).attr('id');
          var column = $(this).attr('value');
        

        
            if (confirm("{{Lang::get('message.confirm') }}")) 
        {
                $.ajax({
               
                type: 'POST',
                url: "{{url('changeLogo')}}",
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {id:id,column:column,"_token": "{{ csrf_token() }}"},
               success: function (response) {
                    $('#alertMessage3').show();
                    var result =  '<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="far fa-thumbs-up"></i>Well Done! </strong>'+response.message+'!</div>';
                    $('#alertMessage3').html(result+ ".");
                    setTimeout(function(){
                       window.location.reload(1);
                    }, 3000); 
                               
               },
               error: function (ex) {
        
                    var myJSON = JSON.parse(ex.responseText);
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Oh Snap! </strong>Something went wrong<br><br><ul>';
                    for (var key in myJSON)
                    {
                        html += '<li>' + myJSON[key][0] + '</li>'
                    }
                    html += '</ul></div>';

                    $('#error2').show();
                    document.getElementById('error2').innerHTML = html;
               }
              
            });
            }
            return false;
      });
  
</script>
<script>
     $('ul.nav-sidebar a').filter(function() {
        return this.id == 'setting';
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.id == 'setting';
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
</script>
<script>
    $(document).ready(function(){
        var country = $('#country').val();
        if (country == 'IN')
        {
            $('.cin').show();
            $('.gstin').show();
        } else {
            $('.cin').hide();
            $('.gstin').hide();
        }
    })

    $('#country').on('change', function (){
       if($(this).val() == 'IN'){
        $('.cin').show();
        $('.gstin').show();
       } else {
         $('.cin').hide();
        $('.gstin').hide();
        $('#cin').val('');
        $('#gstin').val('');
       }
    })


     function getCountryAttr(val) {
        getState(val);
    
    }

     function getState(val) {

        $.ajax({
            type: "GET",
              url: "{{url('get-state')}}/" + val,
            data: 'country_id=' + val,
            success: function (data) {
                $("#state-list").html(data);
            }
        });
    }
    
        function getCode(val) {
        $.ajax({
            type: "GET",
            url: "{{url('get-code')}}",
            data: 'country_id=' + val,
            success: function (data) {
                // $("#mobile_code").val(data);
                $("#phone_code_hidden").val(data);
            }
        });
    }
</script>


        

@stop