
<div class="modal fade" id="demo-req" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Book a Demo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                

                    <div class="card-body">
     
                        <div class="box-content">

                        @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                        {!! Form::open(['url'=>'demo-request','method' => 'post','onsubmit'=>'return validateRecaptcha()']) !!}
                        @else
                        {!! Form::open(['url'=>'demo-request','method' => 'post']) !!}
                        @endif

                <div class="form-row">
                <div class="form-group col-lg-6">
                  
                        <label class="required">Name</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required>
                    </div>
                   <div class="form-group col-lg-6">
                        <label class="required">Email address</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="demoemail" id="demoemail" required>
                    </div>
                </div>
           
            <div class="form-row">
                    <div class="form-group col">
                         <label class="required">Mobile No</label>
                                                            {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden','name'=>'country_code']) !!}
                                                            <input class="form-control input-lg" id="mobilenumdemo" name="Mobile" type="tel" required>
                                                             {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                                            <span id="valid-msgdemo" class="hide"></span>
                                                            <span id="error-msgdemo" class="hide"></span>
                                                            <span id="mobile_codecheckdemo"></span>
                       
                    
                </div>
            </div>
                <div class="form-row">
                    <div class="form-group col">
                    <label>Product</label>
                    <select id="demoType" name="product" class="form-control">
                    <option value="online">Select</option>
                    <option value="ServiceDesk">ServiceDesk</option>
                    <option value="HelpDesk">HelpDesk</option>
                </select>
                    </div>
                </div>
           <div class="form-row">
               <div class="form-group col">
                    
                        <label class="required">Message</label>
                        <textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message" required></textarea>
                    </div>
                
            </div>
            @if ($status->recaptcha_status == 1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                {!! NoCaptcha::display() !!}
                <div class="demo-verification"></div>
            @endif

            <br />
                  <div class="form-row">
                    <div class="form-group col">
                        <input type="submit" style="width: 100%;" value="Book a Demo" class="btn btn-primary btn-lg mb-xlg" data-loading-text="Loading...">
                    </div>
                </div>
  
                            {!! Form::close() !!}
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
      function validateRecaptcha() {
    var response = grecaptcha.getResponse(); // Get the reCAPTCHA response
    if (response.length == 0) {
        $('.demo-verification').html("<p style='color:red'>Robot verification failed, please try again.</p>");
        return false;
    } else {
        $('.demo-verification').hide();
        $('.demo-verification').css("border-color", "");
        // reCAPTCHA validation succeeded
        return true;
    }
}

    </script>

     



 
