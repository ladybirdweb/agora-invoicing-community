@extends('themes.default1.layouts.master')
@section('content')



<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total users</span>
                <span class="info-box-number">{{$count_users}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-trophy"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Pro Edition</span>
                <span class="info-box-number">{{$pro_editions}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-briefcase"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Community Edition</span>
                <span class="info-box-number">{{$community}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Productc/Services Registered</span>
                <span class="info-box-number">{{$product_count}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Search</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {!! Form::open(['method'=>'get']) !!}

        <div class="row">

            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('name','Name') !!}
                {!! Form::text('name',null,['class' => 'form-control']) !!}

            </div>
            
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('username','Username') !!}
                {!! Form::text('username',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('company','Company Name') !!}
                {!! Form::text('company',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('mobile','Mobile') !!}
                {!! Form::text('mobile',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('email','Email') !!}
                {!! Form::text('email',null,['class' => 'form-control']) !!}

            </div>
            <div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('country','Country') !!}
                {!! Form::select('country',[''=>'select','Countries'=>DB::table('countries')->lists('country_name','country_code_char2')],null,['class' => 'form-control']) !!}

            </div>
<div class="col-md-2 form-group">
                <!-- first name -->
                {!! Form::label('industry','Industries') !!}
<?php $old = ['agriculture_forestry'=>'Agriculture Forestry','safety_security_legal'=>'Safety Security Legal','business_information'=>'Business Information','finance_insurance'=>'Finance Insurance','gaming'=>'Gaming','real_estate_housing'=>'Real Estate Housing','health_services'=>'Health Services','education'=>'Education','food_hospitality'=>'Food Hospitality','personal_services'=>'Personal Services','transportation'=>'Transportation','construction_utilities_contracting'=>'Construction Utilities Contracting','motor_vehicle'=>'Motor Vehicle','animals_pets'=>'Animals & Pets','art_design'=>'Art & Design','auto_transport'=>'Auto & Transport','food_beverage'=>'Food & Beverage','beauty_fashion'=>'Beauty & Fashion','education_childcare'=>'Education & Childcare','environment_green_tech'=>'Environment & Green Tech','events_weddings'=>'Events & Weddings','finance_legal_consulting'=>'Finance, Legal & Consulting','government_municipal'=>'Government & Municipal','home_garden'=>'Home & Garden','internet_technology'=>'Internet & Technology','local_service_providers'=>'Local Service Providers','manufacturing_wholesale'=>'Manufacturing & Wholesale','marketing_advertising'=>'Marketing & Advertising','media_communication'=>'Media & Communication','medical_dental'=>'Medical & Dental','music_bands'=>'Music & Bands','non_profit_charity'=>'Non-Profit & Charity','real_estate'=>'Real Estate','religion'=>'Religion','retail_e-Commerce'=>'Retail & E-Commerce','sports_recreation'=>'Sports & Recreation','travel_hospitality'=>'Travel & Hospitality','other'=>'Other',]; ?>
                {!! Form::select('industry',[''=>'select','New'=>DB::table('bussinesses')->lists('name','short'),'old'=>$old],null,['class' => 'form-control']) !!}

            </div>
</div>
<div class='row'>

            <div class="col-md-4 col-md-offset-4">
                <div class="col-md-6">
                    {!! Form::submit('Search',['class'=>'btn btn-primary']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::submit('Reset',['class'=>'btn btn-danger']) !!}
                </div>
            </div>
</div>

        



        {!! Form::close() !!}
    </div>
</div>


<div class="box box-primary">

    <div class="box-header">

        <h4>{{Lang::get('message.users')}}
            <a href="{{url('clients/create')}}" class="btn btn-primary pull-right   ">{{Lang::get('message.create')}}</a></h4>
    </div>

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
        <i class="fa fa-ban"></i>
        <b>{{Lang::get('message.alert')}}!</b> {{Lang::get('message.success')}}.
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
    <div id="response"></div>

    <div class="box-body">
        <div class="row">

            <div class="col-md-12">
                {!! Datatable::table()
                ->addColumn('<input type="checkbox" class="checkbox-toggle">','Name','Email','Registered On','Status','Action')
                ->setUrl("get-clients?name=$name&username=$username&company=$company&mobile=$mobile&email=$email&country=$country&industry=$industry")
                ->setOptions([
                "order"=> [ 3, "desc" ],
                "dom" => "Bfrtip",
                "buttons" => [
                [
                "text" => "Delete",
                "action" => "function ( e, dt, node, config ) {
                e.preventDefault();
                    var answer = confirm ('Are you sure you want to delete from the database?');
                    if(answer){
                $.ajax({
                url: 'clients-delete',
                type: 'GET',
                data: $('#check:checked').serialize(),
                beforeSend: function () {
                $('#gif').show();
                },
                success: function (data) {
                $('#gif').hide();
                $('#response').html(data);
                location.reload();
                }

                });
                }
                }"
                ]
                ],

                ])
                

                
                ->render() !!}

            </div>
        </div>

    </div>

</div>



@stop

@section('icheck')
<script>
    $(function () {


        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });


    });
</script>
@stop