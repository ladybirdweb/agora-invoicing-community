
        <div class="modal fade" id="demo-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" style="margin-top: 15%;" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Book For Demo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                

                    <div class="card-body">
      
                        <div class="box-content">

                           
                        {!! Form::open(['url'=>'demo-request','method' => 'post']) !!}

                            

                         
                              <div class="form-row">
                <div class="form-group col-lg-6">
                  
                        <label class="required">Name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                   <div class="form-group col-lg-6">
                        <label class="required">Email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required>
                    </div>
                </div>
           
            <div class="form-row">
                    <div class="form-group col">
                         <label class="required">Mobile No</label>
                                                            {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden','name'=>'country_code']) !!}
                                                            <input class="form-control input-lg" id="mobilenum" name="Mobile" type="tel">
                                                            {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                            <span id="valid-msg" class="hide"></span>
                                                            <span id="error-msg" class="hide"></span>
                                                            <span id="mobile_codecheck"></span>
                       
                    
                </div>
            </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
              <div class="form-row">
                <div class="form-group col">
                    <input type="submit" style="width: 100%;" value="Book a Demo" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                </div>
            </div>
        

          <p style="text-align: center;font-size: 17px;"><a style="color: green;font-weight: bold !important;" href="{{url('login')}}">Or sign up for a {{$days}}-day free trial</a></p>
                            {!! Form::close() !!}
                        </div>
                    </div>


                </div>
            </div>
        </div>
        
        <script type="text/javascript">
    
    
            var telInput = $('#mobilenum'),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg"),
            addressDropdown = $("#country");
        var errorMap = [ "Invalid number", "Invalid country code", "Number Too short", "Number Too long", "Invalid number"];

        telInput.intlTelInput({
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            initialCountry: "auto",
            separateDialCode: true,
            utilsScript: "{{asset('js/intl/js/utils.js')}}"
        });
        var reset = function() {
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
        };

        $('.intl-tel-input').css('width', '100%');

        telInput.on('blur', function () {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color","");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheck').html("");

                    $('#mobilenum').css("border-color","red");
                    $('#error-msg').css({"color":"red","margin-top":"5px"});
                    errorMsg.classList.remove("hide");
                    $('#register').attr('disabled',true);
                }
            }
        });
        $('input').on('focus', function () {
            $(this).parent().removeClass('has-error');
        });
        addressDropdown.change(function() {
            telInput.intlTelInput("setCountry", $(this).val());
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#mobilenum').css("border-color","");
                    $("#error-msg").html('');
                    errorMsg.classList.add("hide");
                    $('#register').attr('disabled',false);
                } else {
                    var errorCode = telInput.intlTelInput("getValidationError");
                    errorMsg.innerHTML = errorMap[errorCode];
                    $('#mobile_codecheck').html("");

                    $('#mobilenum').css("border-color","red");
                    $('#error-msg').css({"color":"red","margin-top":"5px"});
                    errorMsg.classList.remove("hide");
                    $('#register').attr('disabled',true);
                }
            }
        });

        $('form').on('submit', function (e) {
            $('input[name=country_code]').attr('value', $('.selected-dial-code').text());
        });

</script>