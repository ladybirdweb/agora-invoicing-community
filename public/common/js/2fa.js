     $(document).ready(function(){
     	 var status = $('.checkbox').val();
         if(status ==1) {
         $('#2fa').prop('checked',true)
         } else if(status ==0) {
          $('#2fa').prop('checked',false)
         }
     	});

         $('.closeandrefresh').on('click',function(){
            location.reload();
        })

         function copyRecoveryCode()
         {
            $('#next_rec_code').attr('disabled',false);
             $("#loader").css("display", "block");
            $("#copyBtn").css('display', 'none');
             var copyText = document.getElementById("recoverycode");
              copyText.select();
              copyText.setSelectionRange(0, 99999)
              document.execCommand("copy");
               $("#copyBtn").css('display', 'block');
                $("#loader").css("display", "none");
              $("#copied").css("display", "block");
      
              $('#copied').fadeIn("slow","swing");
              $('#copied').fadeOut("slow","swing");
             
         }



        $('#2fa').change(function () {
        if ($(this).prop("checked")) {
            // checked
            $('#2fa-modal1').modal('show');
            $('#verify_password').on('click',function(){
                var password = $('#user_password').val();
                if(password.length == 0) {
                $("#verify_password").html("<i class='fa fa-check'></i> Validate");
                $('#passerror').show();
                $('#passerror').html("Please Enter Password");
                $('#passerror').focus();
                $('#user_password').css("border-color","red");
                $('#passerror').css({"color":"red"});
                return false;
                }
                $("#verify_password").attr('disabled',true);
                $("#verify_password").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                
                $.ajax({
                    url : "verify-password",
                    method : 'GET',
                    data : {
                        "user_password" : password,
                    },
                    success: function(response) {
                        $('#2fa-modal1').modal('hide');
                        $.ajax({
                            url : "2fa-recovery-code",
                            method : 'post',
                            success: function(response){
                                $('#next_rec_code').attr('disabled',true);
                                $('#alertMessage').hide(); 
                                $('#2fa-recover-code-modal').modal('show');
                                 $('#recoverycode').val(response.message.code);
                                 $('#next_rec_code').on('click',function(){
                                    $('#2fa-recover-code-modal').modal('hide');
                                    $.ajax({
                                        url : "2fa/enable",
                                        method : 'post',
                                        success: function(response) {
                                               $('#2fa-modal2').modal('show');
                                             $(".bar-code").attr("hidden",false);
                                             $(".secret-key").attr("hidden",true);
                                             document.querySelectorAll('[id="image"]')[0].src = response.data.image;
                                             $('#cantscanit').on('click',function(){
                                                $(".bar-code").attr("hidden",true);
                                                $("#secretkeyid").val(response.data.secret);
                                                $(".secret-key").attr("hidden",false)

                                             })
                                             $('#scanbarcode').on('click',function(){
                                                $('#alertMessage2').hide();
                                                $(".bar-code").attr("hidden",false);
                                                document.querySelectorAll('[id="image"]')[0].src = response.data.image;
                                                $(".secret-key").attr("hidden",true)

                                             })

                                             $('#scan_complete').on('click',function(){
                                                $('#2fa-modal2').modal('hide');
                                                 $('#2fa-modal3').modal('show');
                                                 $('#pass_btn').on('click',function(){
                                                    var code = $('#passcode').val();
                                                    if(code.length == 0) {
                                                        $('#passcodeerror').show();
                                                        $('#passcodeerror').html("Please Enter the code");
                                                        $('#passcodeerror').focus();
                                                      $('#passcode').css("border-color","red");
                                                      $('#passcodeerror').css({"color":"red"});
                                                      return false;
                                                    }
                                                    $("#pass_btn").attr('disabled',true);
                                                    $("#pass_btn").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Verifying...");
                                                    $.ajax({
                                                        url : "2fa/setupValidate",
                                                        method : 'POST',
                                                        data : {
                                                            'totp' : code,
                                                        },
                                                    success : function(response) {
                                                        $("#pass_btn").attr('disabled',false);
                                                       $('#2fa-modal3').modal('hide');
                                                        $('#2fa-modal4').modal('show');
                                                        setTimeout(function(){
                                                            location.reload();
                                                        },2000);
                                                    },
                                                    error: function (data) {
                                                        $("#pass_btn").attr('disabled',false);
                                                    $("#pass_btn").html("<i class='fa fa-check'></i> Verify");
                                                    $('#passcodeerror').show();
                                                    $('#passcodeerror').html("Wrong Code. Try again");
                                                     $('#passcodeerror').focus();
                                                      $('#passcode').css("border-color","red");
                                                     $('#passcodeerror').css({"color":"red"});
                                                    },
                                                    })
                                                    
                                                 })
                                                 $('#prev_button').on('click',function(){
                                                    $('#2fa-modal3').modal('hide');
                                                    $('#2fa-modal2').modal('show');
                                                 })
                                             })
                                        },
                                    });
                                 })
                            },
                           
                        })
                        }, 
                        error: function (data) {
                                        $("#verify_password").attr('disabled',false);
                                        $("#verify_password").html("<i class='fa fa-check'></i> Validate");
                                        $('#passerror').show();
                                        $('#passerror').html("Incorrect Password. Try again");
                                         $('#passerror').focus();
                                          $('#user_password').css("border-color","red");
                                         $('#passerror').css({"color":"red"});
                            },
                    })
                        
            
                }) 
   
        } else {
            $('#2fa-modal5').modal('show');
            $('#turnoff2fa').on('click',function(){
                $("#turnoff2fa").attr('disabled',true);
                $("#turnoff2fa").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait..");
                $.ajax({
                    url : "2fa/disable",
                    method : 'post',
                    success: function(response){
                        $("#turnoff2fa").attr('disabled',false);
                        $("#turnoff2fa").html("<i class='fa fa-power-off'></i>TURNED OFF");
                        var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong>'+response.message+'.</div>';
                            $('#alertMessage').html(result+ ".");
                            setTimeout(function(){
                                location.reload();
                            },2000);
                    },
                })
            })
        }
    })

     $('#viewRecCode').on('click',function(){
        
        $.ajax({
            url: "get-recovery-code",
            method: 'get',
            success: function(response){
                 $('#alertMessage4').hide();
                $('#2fa-view-code-modal').modal('show');
                $('#newrecoverycode').val(response.message.code);
            }
        })
        $('#generateNewCode').on('click',function(){

            $("#generateNewCode").attr('disabled',true)
            $("#generateNewCode").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
            $.ajax({
                url: "2fa-recovery-code",
                method: 'post',
                success: function(response){
                    $('#newrecoverycode').val(response.message.code)
                    $('#alertMessage4').hide();
                    $('#alertMessage3').show();

                    $("#generateNewCode").attr('disabled',false);
                    $("#generateNewCode").html("Generate New");
                     var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong>New recovery code generated successfully.</div>';
                        $('#alertMessage3').html(result);

                    },
            })
        })
     })


     function copyNewRecoveryCode()
     {
            $("#newloader").css("display", "block");
            $("#copyNewCodeBtn").css('display', 'none');
             var copyText = document.getElementById("newrecoverycode");
              copyText.select();
              copyText.setSelectionRange(0, 99999)
              document.execCommand("copy");
               $("#copyNewCodeBtn").css('display', 'block');
                $("#newloader").css("display", "none");
                 $("#copied-new").css("display", "block");
      
              $('#copied-new').fadeIn("slow","swing");
              $('#copied-new').fadeOut("slow","swing");
     }