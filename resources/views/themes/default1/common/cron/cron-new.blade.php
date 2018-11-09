@section('custom-css')

<style type="text/css">
.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
}
</style>
@stop
<div class="box-body table-responsive"style="overflow:hidden;">
      {{-- alert block --}}
        <div class="alert alert-success cron-success alert-dismissable" style="display: none;">
            <i class="fa  fa-check-circle"></i>
            <span class="alert-success-message"></span>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
        <div class="alert alert-danger cron-danger" style="display: none;">
            <i class="fa fa-ban"></i>
            <span class="alert-danger-message"></span>
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
        {{-- alert block end --}}
        @if(!$execEnabled)
        <div class="alert alert-warning">
            {{ trans('lang.please_enable_php_exec_for_cronjob_check') }}
        </div>
        @endif

{!! Form::model($status,['url' => 'post-scheduler', 'method' => 'PATCH','id'=>'Form']) !!}

    <div class="box-header with-border">
       <h4>{{Lang::get('message.cron')}}  <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>
    </div>

    <div class="box-body table-responsive"style="overflow:hidden;">
  <div class="row">
                <div class="col-md-12">
                   <p>{{ Lang::get('message.copy-cron-command-description')}} </p>
                </div>
        </div>

        
         <div class="alert  alert-dismissable" style="background: #F3F3F3">
            <div class="row">
            <div class="col-md-2 copy-command1">
                    <span style="font-size: 20px">*&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;*</span>
                </div>
             <div class="col-md-4">
                    <select class="form-control" id="phpExecutableList" onchange="checksome()">
                        <option value="0">{{ Lang::get('message.specify-php-executable')}}</option>
                        @foreach($paths as $path)
                            <option>{{$path}}</option>
                        @endforeach
                        <option value="Other">Other</option>
                    </select>
                    <div class="has-feedback" id='phpExecutableTextArea' style="display: none;">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" style=" padding:5px;height:34px" name="phpExecutableText" id="phpExecutableText" placeholder="{{Lang::get('message.specify-php-executable')}}">
                            <span class="fa fa-close form-control-feedback" style="pointer-events: initial; cursor: pointer; color: #74777a" onclick="checksome(false)"></span>
                        </div>
                    </div>
                </div>
                  <div class="col-md-5 copy-command2">
                   <span style="font-size: 15px">-q {{$cronPath}} schedule:run 2>&1 </span> 
                </div>
                <div class="col-md-1">
                    <span style="font-size: 20px" id="copyBtn" title="{{Lang::get('message.verify-and-copy-command')}}" onclick="verifyPHPExecutableAndCopyCommand()"><i class="fa fa-clipboard"></i></span>
                    <span style="font-size: 20px; display:none;" id="loader"><i class="fa fa-circle-o-notch fa-spin"></i></span>
                </div>
            </div>
        </div>
        
     
        <div class="col-md-6">
            <div class="info-box">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-icon bg-aqua" style="padding-top: 15px"><i class="fa  fa-envelope"></i></span>
                <div class="info-box-content">

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('email_fetching',Lang::get('message.expiry_mail')) !!}<br>
                            {!! Form::checkbox('expiry_cron',1,$condition->checkActiveJob()['expiryMail'],['id'=>'email_fetching']) !!}&nbsp;{{Lang::get('message.enable_expiry-cron')}}
                        </div>

                    </div>
                    <div class="col-md-6" id="fetching">
                        {!! Form::select('expiry-commands',$commands,$condition->getConditionValue('expiryMail')['condition'],['class'=>'form-control','id'=>'fetching-command']) !!}
                          <div id='fetching-daily-at'>
                            {!! Form::text('expiry-dailyAt',$condition->getConditionValue('expiryMail')['at'],['class'=>'form-control time-picker',"placeholder" => "HH:MM"]) !!}

                        </div>
                      
                    </div>
                </div>
            </div><!-- /.info-box-content -->

        </div><!-- /.info-box -->

        <div class="col-md-6">
            <div class="info-box">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-icon bg-aqua" style="padding-top: 15px"><i class="fa  fa-close"></i></span>
                <div class="info-box-content">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('activity',Lang::get('message.delete_activity')) !!}<br>
                            {!! Form::checkbox('activity',1,$condition->checkActiveJob()['deleteLogs'],['id'=>'auto_close']) !!}
                                   {{Lang::get('message.enable_activity_clean')}}
                        </div>
                    </div>
                    <div class="col-md-6" id="workflow">
                        {!! Form::select('activity-commands',$commands,$condition->getConditionValue('deleteLogs')['condition'],['class'=>'form-control','id'=>'workflow-command']) !!}
                         <div id='workflow-daily-at'>
                            {!! Form::text('activity-dailyAt',$condition->getConditionValue('deleteLogs')['at'],['class'=>'form-control time-picker',"placeholder" => "HH:MM"]) !!}

                        </div>
                       
                    </div>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
    
        </div>
    </div>
 {!! Form::close() !!}

<script>
    $(document).ready(function () {
$(".time-picker").datetimepicker({
        format: 'HH:ss',
        // useCurrent: false, //Important! See issue #1075
    });

$('#tab2url').click(function(){
    $('#tab1').removeClass('active')
    $("#tab2").addClass('active');
});

        var checked = $("#email_fetching").is(':checked');
        check(checked, 'email_fetching');
        $("#email_fetching").on('click', function () {
            checked = $("#email_fetching").is(':checked');
            check(checked);
        });
        var command = $("#fetching-command").val();
        showDailyAt(command);
        $("#fetching-command").on('change', function () {
            command = $("#fetching-command").val();
            showDailyAt(command);
        });
        function check(checked, id) {
            if (checked) {
                $("#fetching").show();
            } else {
                $("#fetching").hide();
            }
        }
        function showDailyAt(command) {
            if (command === 'dailyAt') {
                $("#fetching-daily-at").show();
                // $("input").prop('required',true);
            } else {
                $("#fetching-daily-at").hide();
            }
        }

      



        // Ldap cron settings end //
    });
    $(document).ready(function () {
        var checked = $("#notification_cron").is(':checked');
        check(checked, 'notification_cron');
        $("#notification_cron").on('click', function () {
            checked = $("#notification_cron").is(':checked');
            check(checked);
        });
        var command = $("#notification-command").val();
        showDailyAt(command);
        $("#notification-command").on('change', function () {
            command = $("#notification-command").val();
            showDailyAt(command);
        });
        function check(checked, id) {
            if (checked) {
                $("#notification").show();
            } else {
                $("#notification").hide();
            }
        }
        function showDailyAt(command) {
            if (command === 'dailyAt') {
                $("#notification-daily-at").show();
            } else {
                $("#notification-daily-at").hide();
            }
        }
    });
    $(document).ready(function () {
        var checked = $("#auto_close").is(':checked');
        check(checked, 'auto_close');
        $("#auto_close").on('click', function () {
            checked = $("#auto_close").is(':checked');
            check(checked);
        });
        var command = $("#workflow-command").val();
        showDailyAt(command);
        $("#workflow-command").on('change', function () {
            command = $("#workflow-command").val();
            showDailyAt(command);
        });
        function check(checked, id) {
            if (checked) {
                $("#workflow").show();
            } else {
                $("#workflow").hide();
            }
        }
        function showDailyAt(command) {
            if (command == 'dailyAt') {
                $("#workflow-daily-at").show();
            } else {
                $("#workflow-daily-at").hide();
            }
        }
    });
//follow up
     $(document).ready(function () {
        var checked = $("#notification_cron1").is(':checked');
        check(checked, 'notification_cron1');
        $("#notification_cron1").on('click', function () {
            checked = $("#notification_cron1").is(':checked');
            check(checked);
        });
        var command = $("#notification-command1").val();
        showDailyAt(command);
        $("#notification-command1").on('change', function () {
            command = $("#notification-command1").val();
            showDailyAt(command);
        });
        function check(checked, id) {
            if (checked) {
                $("#notification1").show();
            } else {
                $("#notification1").hide();
            }
        }
        function showDailyAt(command) {
            if (command === 'dailyAt') {
                $("#notification-daily-at1").show();
            } else {
                $("#notification-daily-at1").hide();
            }
        }
    });

//-------------------------------------------------------------//

    function checksome(showtext = true)
    {
        if (!showtext) {
            $("#phpExecutableList").css('display', "block");
            $("#phpExecutableList").val(0)
            $("#phpExecutableTextArea").css('display', "none");
        } else if($("#phpExecutableList").val() == 'Other') {
            $("#phpExecutableList").css('display', "none");
            $("#phpExecutableTextArea").css('display', "block");
        }
    }

    function verifyPHPExecutableAndCopyCommand()
    {
        copy = false;
        var path = ($("#phpExecutableList").val()=="Other")? $("#phpExecutableText").val(): $("#phpExecutableList").val();
        var text = "* * * * * "+path.trim()+" "+$(".copy-command2").text().trim();
        copyToClipboard(text);

        $.ajax({
            'method': 'POST',
            'url': "{{route('verify-cron')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                "path": path
            },
            beforeSend: function() {
                $("#loader").css("display", "block");
                $(".alert-danger, .alert-success, #copyBtn").css('display', 'none');
            },
            success: function (result,status,xhr) {
                $(".alert-success-message").html("{{Lang::get('message.cron-command-copied')}} "+result.message);
                $(".cron-success, #copyBtn").css('display', 'block');
                $("#loader").css("display", "none");
                copy = true
            },
            error: function(xhr,status,error) {
                $('#clearClipBoard').click();
                $(".cron-danger, #copyBtn").css('display', 'block');
                $("#loader").css("display", "none");
                $(".alert-danger-message").html("{{Lang::get('message.cron-command-not-copied')}} "+xhr.responseJSON.message);
            },
        });
    }

    function copyToClipboard(text = " ")
    {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
        } catch (err) {
        }
        console.log(msg);
        document.body.removeChild(textArea);
    }
</script>
