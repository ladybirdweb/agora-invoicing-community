<?php
$status =  App\Model\Common\StatusSetting::select('recaptcha_status', 'msg91_status', 'emailverification_status', 'terms')->first();
?>
    <div class="modal fade" id="demo-req" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <?php
                $apiKeys = \App\ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck', 'msg91_auth_key', 'terms_url')->first();
                ?>
    
            @if ($status->recaptcha_status==1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
            {!! Form::open(['url'=>'demo-request','method' => 'post','onsubmit'=>'return DemovalidateRecaptcha()']) !!}
            @else
            {!! Form::open(['url'=>'demo-request','method' => 'post']) !!}
            @endif


            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="demoModalLabel">Book a Demo</h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col">
   
                                <div class="contact-form-success alert alert-success d-none mt-4">

                                    <strong>Success!</strong> Your message has been sent to us.
                                </div>

                                <div class="contact-form-error alert alert-danger d-none mt-4">

                                    <strong>Error!</strong> There was an error sending your message.

                                    <span class="mail-error-message text-1 d-block"></span>
                                </div>

                                <div class="row">

                                    <div class="form-group col-lg-6">

                                        <label class="form-label mb-1 text-2">Name <span class="text-danger"> *</span> </label>

                                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="name" id="name" required>
                                    </div>

                                    <div class="form-group col-lg-6">

                                        <label class="form-label mb-1 text-2">E-mail Address <span class="text-danger"> *</span></label>

                                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="demoemail" id="demoemail" required>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col">

                                        <label class="form-label mb-1 text-2">Mobile <span class="text-danger"> *</span></label>

                                         {!! Form::hidden('mobile',null,['id'=>'mobile_code_hidden','name'=>'country_code']) !!}
                                        <input class="form-control input-lg" id="mobilenumdemo" name="Mobile" type="tel" required>
                                         {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_code']) !!}
                                        <span id="valid-msgdemo" class="hide"></span>
                                        <span id="error-msgdemo" class="hide"></span>
                                        <span id="mobile_codecheckdemo"></span>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col">

                                        <label class="form-label">Product</label>

                                        <div class="custom-select-1">

                                            <select id="demoType" name="product" class="form-control">
                                            <option value="online">Select</option>
                                            <option value="ServiceDesk">ServiceDesk</option>
                                            <option value="HelpDesk">HelpDesk</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col">

                                        <label class="form-label mb-1 text-2">Message <span class="text-danger"> *</span></label>

                                   <textarea maxlength="5000" data-msg-required="Please enter your message." rows="3" class="form-control" name="message" id="message" required></textarea>
                                    </div>
                                </div>

                                @if ($status->recaptcha_status == 1 && $apiKeys->nocaptcha_sitekey != '00' && $apiKeys->captcha_secretCheck != '00')
                                {!! NoCaptcha::display() !!}
                                <div class="demo-verification"></div>
                            @endif
                            
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>&nbsp;&nbsp;&nbsp;

                    <button type="submit" class="btn btn-primary" name="demoregister" id="demoregister">Book a Demo</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
      function DemovalidateRecaptcha() {
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

     



 
