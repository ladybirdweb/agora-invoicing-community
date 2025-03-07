<div class="modal fade" id="create-plan-option" style="overflow-y: auto !important">
  <div class="modal-dialog">
    <div class="modal-content" style="width:700px;">

      <div class="modal-header">
      <h4 class="modal-title">Create Plans</h4>
    </div>
      <div class="modal-body">

        @if (count($errors) > 0)

                        <div class="alert alert-danger alert-dismissable">
                            <strong>Whoops!</strong> There were some problems with your input.
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

        {!! Form::open(['url'=>'plans','method'=>'post','id'=> 'plan']) !!}


        <div class="box-body">

          <div class="row">

              <div class="col-md-12">

              <div class="row">

                <div
                  class="col-md-4 form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                  <!-- name -->
                  {!! Form::label('name',Lang::get('message.name'),['class'=>'required']) !!}
                  {!! Form::text('name',null,['class' => 'form-control','id'=>'planname']) !!}
                    @error('name')
                    <span class="error-message"> {{$message}}</span>
                    @enderror
                  <h6 id="plannamecheck"> </h6>
                    <div class="input-group-append">
                    </div>
                </div>
                <div
                  class="col-md-4 form-group {{ $errors->has('product') ? 'has-error' : '' }}">
                  <!-- value -->
                  {!! Form::label('product',Lang::get('message.product'),['class'=>'required']) !!}
                  <select name="product" value="Choose" class="form-control" id="planproduct" onchange="myProduct()">
                    <option value="">Choose</option>
                    @foreach($products as $key=>$product)
                     @if (Request::old('product') == $key)
                     <option value={{$key}} selected>{{$product}}</option>
                     @else
                     <option value={{$key}}>{{$product}}</option>
                     @endif
                    @endforeach
                  </select>
                    @error('product')
                    <span class="error-message"> {{$message}}</span>
                    @enderror
                    <div class="input-group-append">
                    </div>
                  <h6 id="productcheck"></h6>


                </div>
                <div
                  class="col-md-4 form-group plandays {{ $errors->has('days') ? 'has-error' : '' }}">
                  <!-- days -->
                  {!! Form::label('days','Periods',['class'=>'required']) !!}
                  <div class="input-group">
                    <select name="days" value="Choose" class="form-control" id="plandays">
                      <option value="">Choose</option>
                      @foreach($periods as $key=>$period)
                       @if (Request::old('days') == $key)
                     <option value={{$key}} selected>{{$period}}</option>
                     @else
                     <option value={{$key}}>{{$period}}</option>
                     @endif
                      @endforeach
                    </select>&nbsp;
                      @error('days')
                      <span class="error-message"> {{$message}}</span>
                      @enderror
                      <div class="input-group-append">
                      </div>
                    <span class="input-group-text" id="period"><i class="fa fa-plus"></i></span>
                  </div>
                  <h6 id="dayscheck"></h6>

                </div>
                <div class="col-md-12">

                  <table class="table table-responsive table-bordered table-hover" id="dynamic_table">
                    <thead>
                    <tr>
                      <th class="col-sm-3" style="width:18%">{{ Lang::get('message.country') }} <span class="text-red">*</span></th>
                      <th class="col-sm-3" style="width:20%">{{ Lang::get('message.currency') }} <span class="text-red">*</span></th>
                      <th class="col-sm-3" style="width:20%">{{ Lang::get('message.price') }} <span class="text-red">*</span></th>
                      <th class="col-sm-3" style="width:20%">
                        {{ Lang::get('Offer Price') }} <span class="text-bold">(%)</span>
                      </th>
                      <th class="col-sm-3" style="width:20%">
                        {{ Lang::get('message.renew-price') }} <span class="text-red">*</span>
                      </th>
                      
                    </tr>
                    </thead>

                    <tbody>
                      <tr>
                        <td>
                          <select name="country_id[]" class="form-control" id="country">
                            <option value="0">Default</option>
                              <div class="input-group-append">
                              </div>
                          </select>
                        </td>
                        <td>
                          <select name="currency[]" class="form-control" id="currency">
                             <option value="">
                                Choose
                              </option>
                            @foreach ($currency as $code => $name)
                            @if (Request::old('currency') && in_array($code, Request::old('currency')))
                             <option value={{$code}} selected>{{$name}}</option>
                             @else
                              <option value="{{ $code }}">
                                {{ $name }}
                              </option>
                               @endif
                             
                            @endforeach
                          </select>

                        </td>
                        <td>
                          <input type="number" class="form-control" name="add_price[]" class="{{ $errors->has('add_prices') ? 'has-error' : '' }}" value="{{old('add_price.0')}}" id="regular_prices">

                        </td>

                         <td>
                            <input type="number" class="form-control" value="{{old('offer_price.0')}}" name="offer_price[]">

                        </td>

                        <td>
                            <input type="number" class="form-control" value="{{old('renew_price.1')}}" name="renew_price[]" id="renew_prices">

                        </td>

                       


                      </tr>

                    </tbody>
                  </table>
                </div>
                <div class="col-sm-12" style="margin-bottom: 10px;">
                  <button class="btn btn-sm btn-default add-more"><i class="fa fa-plus"></i>&nbsp;{{ trans('message.add_price_for_country') }}</button>
                </div>


                <div class="col-md-12 form-group">
                  <!-- description -->
                  {!! Form::label('description','Price Description') !!}
                  {!! Form::text("price_description",null,['class' => 'form-control' ,'placeholder'=>'Enter Price Description to be Shown on Pricing Page. eg: Yearly,Monthly,One-Time']) !!}
                  <h6 id="dayscheck"></h6>

                  <!-- {!! Form::select('days',[''=>'Select','Periods'=>$periods],null,['class' => 'form-control','id'=>'plandays']) !!} -->
                </div>

                <div class="col-md-6 form-group">
                  <!-- product_quantity -->
                  {!! Form::label('product_quantity','Product Quantity',['class'=>'required'])!!}
                  {!! Form::number("product_quantity",null,['class' =>
                  'form-control','disabled'=>'disabled','id'=>'prodquant','placeholder'=>'Pricing for No. of Products'])
                  !!}
                    @error('product_quantity')
                    <span class="error-message"> {{$message}}</span>
                    @enderror
                    <div class="input-group-append">
                    </div>
                </div>

                <div class="col-md-6 form-group">
                  <!-- agents -->
                  <i class='fa fa-info-circle' style='cursor: help; font-size: small; color: rgb(60, 141, 188)'<label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="If '0' Agents Selected, Plan will be for Unlimited Agents.">
                        </label></i>
                  
                    {!! Form::label('agents','No. of Agents',['class'=>'required']) !!}
                  {!! Form::number("no_of_agents",null,['class' => 'form-control'
                  ,'disabled'=>'disabled','id'=>'agentquant','placeholder'=>'Pricing for No. of Agents']) !!}
                    @error('no_of_agents')
                    <span class="error-message"> {{$message}}</span>
                    @enderror
                    <div class="input-group-append">
                    </div>
                </div>
              </div>
            </div>

          </div>

        </div>
         <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default " data-dismiss="modal" id="close-plan"><i class="fa fa-times"></i>&nbsp;Close</button>
                <button type="submit" id="planButton" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Save</button>

            </div>
       


      </div>



      {!! Form::close() !!}

    </div>
  </div>
</div>
<script>

    $(document).ready(function() {
        const userRequiredFields = {
            planname:@json(trans('message.plan_details.planname')),
            planproduct:@json(trans('message.plan_details.planproduct')),
            productquant:@json(trans('message.plan_details.productquant')),
            agentquant:@json(trans('message.plan_details.agentquant')),


        };

        $('#planButton').on('click', function (e) {
            const userFields = {
                planname:$('#planname'),
                planproduct:$('#planproduct'),
                regular_price:$('#regular_prices'),
                renew_price:$('#renew_prices'),
                currency:$('#currency'),
                country:$('#country'),
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

        document.querySelector('#agentquant').addEventListener('input', function () {
            let prodquant=document.querySelector('#prodquant');
            removeErrorMessage(this);
            removeErrorMessage(prodquant);
        });

        document.querySelector('#prodquant').addEventListener('input', function () {
            let agentquant=document.querySelector('#agentquant');
            removeErrorMessage(this);
            removeErrorMessage(agentquant);
        });

        // Add input event listeners for all fields
        ['planname','planproduct','country','currency','renew_prices','plandays','regular_prices'].forEach(id => {

            document.getElementById(id).addEventListener('input', function () {
                removeErrorMessage(this);

            });
        });


    });


$("#close-plan").click(function() {
   location.reload();
});
      
  $(document).ready(function(){
    myProduct();
  })
  /**
   * Add Periods In the same Modal as Create Plan
   *
   * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
   *
   * @date   2019-01-08T10:35:38+0530
   *
   */

  $(function () {
    var i = 1;
    $(".add-more").click(function (e) {
      e.preventDefault();
      i++;
      $('#dynamic_table tr:last').after(`
        <tr id="row` + i + `">
          <td>
            <select name="country_id[]" class="form-control" >
              <option value="" selected disabled>Choose Country</option>
              @foreach ($countries as $country)
                <option value="{{$country['country_id']}}">
                  {{ $country['country_name'] }}
                </option>
              @endforeach
            </select>
          </td>

          <td>
            <select name="currency[]" class="form-control">
            <option value="">
                  Choose
                </option>
              @foreach ($currency as $code => $name)
                <option value="{{ $code }}">
                  {{ $name }}
                </option>
              @endforeach
            </select>
          </td>

          <td>
            <input type="text" class="form-control" name="add_price[]">
          </td>
           <td>
            <input type="text" class="form-control" name="offer_price[]">
          </td>

          <td>
            <div class="input-group">
              <input type="text" class="form-control" style="width:25%" name="renew_price[]">&nbsp;&nbsp;
              <span id="` + i + `" class="input-group-text btn_remove"><i class="fa fa-minus"></i></span>
            </div>
          </td>

        </tr>`)
    });

    $(document).on('click', '.btn_remove', function () {
      var button_id = $(this).attr("id");
      $('#row' + button_id + '').remove();
    });


    $("#period").on('click', function () {
      $("#period-modal-show").modal();
    })
    $('.save-periods').on('click', function () {
      $("#submit1").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
      $.ajax({
        type: 'POST',
        url: "{{ url('postInsertPeriod') }}",
        data: {
          "name": $('#new-period').val(),
          "days": $('#new-days').val(),
          'select-period': $('#select-period').val(),
        },
        success: function (data) {
          $('#plandays').append($("<option/>", {
            value: data.id,
            text: data.name,
          }))
          $('#new-period').val("");
          $('#new-days').val("");
          $('#select-period').val("");
          var result =
            '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><i class="fa fa-check"></i> Success! </strong>Period Added Successfully</div>';
          $('#error').hide();
          $('#alertMessage').show();
          $('#alertMessage').html(result + ".");
          $("#submit1").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        },
        error: function (error) {
          var html =
            '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<br><br><ul>';
          for (key in error.responseJSON.errors) {
            html += '<li>' + error.responseJSON.errors[key][0] + '</li>'

          }
          html += '</ul></div>';
          $('#alertMessage').hide();
          $('#error').show();
          document.getElementById('error').innerHTML = html;
          $("#submit1").html("<i class='fa fa-floppy-o'>&nbsp;&nbsp;</i>Save");
        }

      })
    })
  })

  function myProduct() {
    var product = document.getElementById('planproduct').value;
    $.ajax({
      type: 'get',
      url: "{{ url('get-period') }}",
      data: {
        'product_id': product
      },
      success: function (data) {
        if (data.subscription != 1) { //Check if Periods to be shown or not
          $('.plandays').hide();
        } else {
          $('.plandays').show();
        }
        if (data.agentEnable != 1) { //Check if Product quantity to be shown or No. of Agents
          document.getElementById("prodquant").disabled = false;
          document.getElementById("agentquant").disabled = true;

        } else if (data.agentEnable == 1) {
          document.getElementById("agentquant").disabled = false;
          document.getElementById("prodquant").disabled = true;
        }
      }
    });
  }
</script>