<?php
$status =  App\Model\Common\StatusSetting::select('recaptcha_status','v3_recaptcha_status', 'msg91_status', 'emailverification_status', 'terms')->first();
?>

    <div class="modal fade" id="demo-req" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <?php
                $apiKeys = \App\ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck', 'msg91_auth_key', 'terms_url')->first();
                ?>
    
            <form  id="demoForm" method="post">
            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="demoModalLabel">Book a Demo</h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div id="demosuccessMessage"></div>
                <div id="demoerrorMessage"></div>

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

                                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control text-3 h-auto py-2" name="demoname" id="demoname" required>
                                    </div>

                                    <div class="form-group col-lg-6">

                                        <label class="form-label mb-1 text-2">E-mail Address <span class="text-danger"> *</span></label>

                                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control text-3 h-auto py-2" name="demoemail" id="demoemail" required>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col">

                                        <label class="form-label mb-1 text-2">Mobile <span class="text-danger"> *</span></label>

                                         {!! Form::hidden('mobile',null,['id'=>'mobile_code_hiddenDemo','name'=>'country_code']) !!}
                                        <input class="form-control input-lg" id="mobilenumdemo" name="Mobile" type="tel" required>
                                         {!! Form::hidden('mobile_code',null,['class'=>'form-control input-lg','disabled','id'=>'mobile_codeDemo']) !!}
                                        <span id="valid-msgdemo" class="hide"></span>
                                        <span id="error-msgdemo" class="hide"></span>
                                        <span id="mobile_codecheckdemo"></span>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="form-group col">

                                        <label class="form-label mb-1 text-2">Message <span class="text-danger"> *</span></label>

                                   <textarea maxlength="5000" data-msg-required="Please enter your message." rows="3" class="form-control" name="demomessage" id="demomessage" required></textarea>
                                    </div>
                                </div>
                                
                                  <!-- Honeypot fields (hidden) -->
                                <div style="display: none;">
                                    <label>Leave this field empty</label>
                                    <input type="text" name="honeypot_field" value="">
                                </div>

                            @if ($status->recaptcha_status === 1)
                                <div id="demo_recaptcha"></div>
                                <div class="robot-verification" id="democaptcha"></div>
                                <span id="democaptchacheck"></span>
                            @elseif($status->v3_recaptcha_status === 1)
                                <input type="hidden" id="g-recaptcha-demo" class="g-recaptcha-token" name="g-recaptcha-response-1">
                            @endif
                                <br>
                            
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>&nbsp;&nbsp;&nbsp;

                    <button type="submit" class="btn btn-primary" name="demoregister" id="demoregister">Book a Demo</button>
                </div>
            </div>
            </form>
        </div>
    </div>

        <script>
            let demo_recaptcha_id;
            let recaptchaTokenDemo;

            recaptchaFunctionToExecute.push(() => {
                demo_recaptcha_id = grecaptcha.render('demo_recaptcha', { 'sitekey': siteKey });
            });

    
         function demovalidateRecaptcha() {
             @if($status->recaptcha_status === 1)
                 recaptchaTokenDemo = getRecaptchaTokenFromId(demo_recaptcha_id);

             if (getRecaptchaTokenFromId(demo_recaptcha_id) === '') {
                 $('#democaptchacheck').show();
                 $('#democaptchacheck').html("Robot verification failed, please try again.");
                 $('#democaptchacheck').focus();
                 $('#democaptcha').css("border-color", "red");
                 $('#democaptchacheck').css({"color": "red", "margin-top": "5px"});
                 return false;
             } else {
                 $('#democaptchacheck').hide();
                 return true;
             }
             @elseif($status->v3_recaptcha_status === 1)
             updateRecaptchaTokens();
                 return true;
             @endif
         }
    </script>

<script>
$(document).ready(function() {
    $('#demoForm').submit(function(event) {
        event.preventDefault();
        
        var recaptchaEnabled = '{{ $status->recaptcha_status }}';
        if (recaptchaEnabled == 1) {
            if (!demovalidateRecaptcha()) {
                $("#demoregister").attr('disabled', false);
                $("#demoregister").html("Send Message");
                return;
            }
        }
        $('#successMessage').empty();
        $('#errorMessage').empty();
        $("#demoregister").attr('disabled',true);
        $("#demoregister").html("<i class='fas fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");

        var formData = {
            "demoname": $('#demoname').val(),
            "demoemail": $('#demoemail').val(),
            "country_code": $('#mobile_code_hiddenDemo').val().replace(/[\. ,:-]+/g, ''),
            "Mobile": $('#mobilenumdemo').val().replace(/[\. ,:-]+/g, ''),
            "demomessage": $('#demomessage').val(),
            "honeypot_field": $('input[name=honeypot_field]').val(),
            "demo-recaptcha-response-1": recaptchaTokenDemo ?? $('#g-recaptcha-demo').val() ?? '',
            "_token": "{{ csrf_token() }}"
        };

        $.ajax({
            type: 'POST',
            url: 'demo-request',
            data: formData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#demoregister").attr('disabled',false);
                $("#demoregister").html("Send Message");
                $('#demosuccessMessage').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + response.message + '</div>');
                $('#demoForm')[0].reset();
                setTimeout(function() {
                    window.location.reload();
                }, 5000);

            },
            error: function(response) {
                $("#demoregister").attr('disabled', false);
                $("#demoregister").html("Send Message");
            
                var errorMessageHtml = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                
                if (response.responseJSON && response.responseJSON.errors) {

                    for (var key in response.responseJSON.errors) {
                        errorMessageHtml += '<li>' + response.responseJSON.errors[key][0] + '</li>';
                    }
            
                    errorMessageHtml += '</ul>';
                } else if (response.responseJSON && response.responseJSON.message) {
                    errorMessageHtml += response.responseJSON.message;
                }
            
                errorMessageHtml += '</div>';
            
                $('#demoerrorMessage').html(errorMessageHtml);
            }



        });
        
    });
});
</script>

     



 
