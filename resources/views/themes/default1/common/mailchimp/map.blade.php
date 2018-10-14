@extends('themes.default1.layouts.master')
@section('content-header')
<h1>
Mailchimp Mapping
</h1>
  <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{url('settings')}}">Settings</a></li>
        <li><a href="{{url('mailchimp')}}"><i class="fa fa-dashboard"></i> Mailchimp Setting</a></li>
        <li class="active">Mailchimp Mapping</li>
        </ol>
@stop
@section('content')

<div class="row">

    <div class="col-md-12">
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
                {!! Form::model($model,['url'=>'mail-chimp/mapping','method'=>'patch','files'=>true]) !!}
               
                       
                    
              
               </div>   
            <div class="box-body">
                 <h3 class="box-title" style="margin-top:0px;margin-left: 10px;">{{Lang::get('message.list-fields')}}</h3>
                           <button type="submit" class="btn btn-primary pull-right" id="submit"  style="margin-top:-40px;
                        margin-right:15px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                <table class="table table-hover">
                    <tr>
                        <th>{{Lang::get('message.agora-fields')}}</th>
                        <th>{{Lang::get('message.mailchimp-fields')}}</th>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.first_name')}}</td>
                        <td>{!! Form::select('first_name',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.last_name')}}</td>
                        <td>{!! Form::select('last_name',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.company')}}</td>
                        <td>{!! Form::select('company',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.mobile')}}</td>
                        <td>{!! Form::select('mobile',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.address')}}</td>
                        <td>{!! Form::select('address',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.town')}}</td>
                        <td>{!! Form::select('town',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.state')}}</td>
                        <td>{!! Form::select('state',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.zip')}}</td>
                        <td>{!! Form::select('zip',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.active')}}</td>
                        <td>{!! Form::select('active',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.role')}}</td>
                        <td>{!! Form::select('role',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>
                    <tr>
                        <td>{{Lang::get('message.app-title')}}</td>
                        <td>{!! Form::select('source',[''=>'Select','Fields'=>$mailchimp_fields],null,['class'=>'form-control']) !!}</td>
                    </tr>


                </table>
                {!! Form::close() !!}


            </div>



        </div>
        <!-- /.box -->
         <div class="box box-primary">

            <div class="box-header">
 
               
              
               </div>   
                        
            <div class="box-body">
                 <select name="group"  class="form-control" id="group" onChange="getGroup(this.value)">
                            <option value="">Choose</option>
                @foreach ($display as $key=>$value)
               <option value="{{$value['id']}}">{{$value['title']}}</option>
                 
            
                @endforeach
            </select>
            {!! Form::model($model2,['url'=>'mailchimp-group/mapping','method'=>'patch','files'=>true]) !!}
               
                 <h3 class="box-title" style="margin-top:0px;margin-left: 10px;">{{Lang::get('message.group-fields')}}</h3>
                           <button type="submit" class="btn btn-primary pull-right" id="submit"  style="margin-top:-40px;
                        margin-right:15px;"><i class="fa fa-refresh">&nbsp;&nbsp;</i>{!!Lang::get('message.update')!!}</button>
                <table class="table table-hover">
                    <tr>
                        <th>{{Lang::get('message.agora-products')}}</th>
                        <th>{{Lang::get('Mapped With')}}</th>
                        <th>{{Lang::get('message.mailchimp-product')}}</th>
                    </tr>

                    <tr>
                        <td>
                              <select name="row[1][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[0]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)

                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                          <td>
                            @if(count($categoryList)>0)
                             <input type="text" name="map[1][]" value="{{$categoryList[0]}}" readonly>
                             @else
                            <input type="text" name="map[1][]" value="" readonly>
                             @endif
                        </td>
                         <td>
                              <select name="row[1][]" id="fields" class="form-control field">
                              </select>
                        </td>
                   
                    </tr>

                    <tr>
                        <td>
                              <select name="row[2][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[1]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                             
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                         <td>
                             @if(count($categoryList)>0)
                             <input type="text" name="map[2][]" value="{{$categoryList[1]}}" readonly>
                             @else
                            <input type="text" name="map[2][]" value="" readonly>
                             @endif
                        </td>
                         <td>
                              <select name="row[2][]" id="fields" class="form-control field">
                             </select>
                        </td>
                   
                    </tr>



                     <tr>
                        <td>
                              <select name="row[3][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[2]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                             
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                         <td>
                             @if(count($categoryList)>0)
                             <input type="text" name="map[3][]" value="{{$categoryList[2]}}" readonly>
                             @else
                             <input type="text" name="map[3][]" value="" readonly>
                             @endif
                        </td>
                         <td>
                              <select name="row[3][]" id="fields" class="form-control field">
                                <option value=""></option>
                            </select>
                        </td>
                   
                    </tr>



                     <tr>
                        <td>
                              <select name="row[4][]" id="plan" class="form-control ">
                                @if(count($productList)>0)
                                <option value="">{{$productList[3]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                             
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                         <td>
                             @if(count($categoryList)>0)
                             <input type="text" name="map[4][]" value="{{$categoryList[3]}}" readonly>
                             @else
                             <input type="text" name="map[4][]" value="" readonly>
                             @endif
                        </td>
                         <td>
                              <select name="row[4][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>

   
                    <tr>
                        <td>
                              <select name="row[5][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[4]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                             
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                        
                         <td>
                              @if(count($categoryList)>0)
                             <input type="text" name="map[5][]" value="{{$categoryList[4]}}" readonly>
                             @else
                             <input type="text" name="map[5][]" value="" readonly>
                             @endif
                           
                        </td>
                          <td>
                              <select name="row[5][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>


                      <tr>
                        <td>
                              <select name="row[6][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[5]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                             
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                         <td>
                            @if(count($categoryList)>0)
                             <input type="text" name="map[6][]" value="{{$categoryList[5]}}" readonly>
                             @else
                             <input type="text" name="map[6][]" value="" readonly>
                             @endif
                        </td>
                         <td>
                              <select name="row[6][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>


                     <tr>
                        <td>
                              <select name="row[7][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[6]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                         <td>
                         @if(count($categoryList)>0)
                             <input type="text" name="map[7][]" value="{{$categoryList[6]}}" readonly>
                             @else
                             <input type="text" name="map[7][]" value="" readonly>
                             @endif
                         </td>
                         <td>
                              <select name="row[7][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>

                     <tr>
                        <td>
                              <select name="row[8][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[7]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                        <td>
                         @if(count($categoryList)>0)
                             <input type="text" name="map[8][]" value="{{$categoryList[7]}}" readonly>
                             @else
                             <input type="text" name="map[8][]" value="" readonly>
                             @endif
                         </td>
                         <td>
                              <select name="row[8][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>

                      <tr>
                        <td>
                              <select name="row[9][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[8]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                        <td>
                         @if(count($categoryList)>0)
                             <input type="text" name="map[9][]" value="{{$categoryList[8]}}" readonly>
                             @else
                             <input type="text" name="map[9][]" value="" readonly>
                             @endif
                         </td>
                         <td>
                              <select name="row[9][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>

                     <tr>
                        <td>
                              <select name="row[10][]" id="plan" class="form-control">
                                @if(count($productList)>0)
                                <option value="">{{$productList[9]}}</option>
                                @endif
                                  <option value="">Choose</option>
                              @foreach($agoraProducts as $key=>$product)
                                   <option value="{{$key}}">{{$product}}</option>
                           
                             @endforeach
                                </select>
                        </td>
                        <td>
                         @if(count($categoryList)>0)
                             <input type="text" name="map[10][]" value="{{$categoryList[9]}}" readonly>
                             @else
                             <input type="text" name="map[10][]" value="" readonly>
                             @endif
                         </td>
                         <td>
                              <select name="row[10][]" id="fields" class="form-control field">
                            </select>
                        </td>
                   
                    </tr>

          

             

               

          
                   
                  

                </table>
                {!! Form::close() !!}


            </div>

            

        </div>

    </div>


</div>
<script> 

    $(document).ready(function () {
    var val = $("#group").val();
    getGroup(val);
    });
    function getGroup(val)
    { 
    if(val!=""){
     getInterestGroup(val)
    }
      else{
        $(".field").html('<option value=>Please select a Group</option>').val('');
    }
   }

    function getInterestGroup(val)
    {
       $.ajax({
        type : "GET",
        url : "{{url('get-group-field')}}/" + val,
       
        success: function(data) {
             $(".field").html(data);
      //      var options = '';
      // for (var i = 0; i < j.length; i++) {
      //   options += '<option  value="' + j[i].catId + '">' + j[i].catName + '</option>';
      //    $(".field").html(options);
      // }
     
             // $.each(response,function(key, value)
             //    {
             //        console.log(value.catName);
             //         $(".field").append('<option selected="selected"' + key + '>' + value.selectedCat + '</option>');
             //        $(".field").append('<option value=' + key + '>' + value.catName + '</option>');
             //    });
            // for(var i = 0; i < data.length; i++) {
            //     $('.field').eq(i).html(response[i].catName);
            //     $('.field').eq(i).val(response[i].catId);
            //     $('.field').eq(i).html(response[i].selectedCat);
            //      // $(".field[i]").html('<option value="response[i].catId">response[i].catName</option>');
            //     // echo '<option value='.response[i].catId.'>'.response[i].catName.'</option>'
            // }

            // $(".field1").html(data);
            // $(".field2").html(data);
            //  $(".field3").html(data);
            //   $(".field4").html(data);
            //    $(".field5").html(data);
            //     $(".field6").html(data);
            //      $(".field7").html(data);
            //       $(".field8").html(data);
            //        $(".field9").html(data);
            //         $(".field10").html(data);
        // }
       // });
            }
        });
     }

</script>
@stop