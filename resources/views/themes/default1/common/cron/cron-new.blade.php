
{!! Form::model($status,['url' => 'post-scheduler', 'method' => 'PATCH','id'=>'Form']) !!}

    <div class="box-header with-border">
       <h4>{{Lang::get('message.cron')}}  <button type="submit" class="btn btn-primary pull-right" id="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'>&nbsp;</i> Saving..."><i class="fa fa-floppy-o">&nbsp;&nbsp;</i>{!!Lang::get('message.save')!!}</button></h4>
    </div>

    <div class="box-body table-responsive"style="overflow:hidden;">
 

        
         <div class="alert  alert-dismissable" style="background: #F3F3F3">
            <i class="fa  fa-info-circle"></i>&nbsp;{!! Lang::get('message.cron-set-info')!!}
            {!! $shared !!}<br>
           
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
                            {!! Form::text('expiry-dailyAt',$condition->getConditionValue('expiryMail')['at'],['class'=>'form-control']) !!}

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
                            {!! Form::text('activity-dailyAt',$condition->getConditionValue('deleteLogs')['at'],['class'=>'form-control']) !!}

                        </div>
                       
                    </div>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>
    
        
    </div>
 {!! Form::close() !!}

<script>
    $(document).ready(function () {


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
            } else {
                $("#fetching-daily-at").hide();
            }
        }

        // Ldap cron settings start //
        // hide if checked on ready
        if ( $("#ldapCron").is(':checked') ) {
            $("#ldapCronTimeSelect").show();
        } else {
            $("#ldapCronTimeSelect").hide();
        }

        // show fetch time on ready
        if ( $("#adUserFetchingCommand").val() === 'dailyAt') {
            $("#adUserFetchDailyAt").show();
        } else {
            $("#adUserFetchDailyAt").hide();
        }

        // on click of checkbox
        $("#ldapCron").on('click', function () {
            if ( $(this).is(':checked') ) {
                $("#ldapCronTimeSelect").show();
            } else {
                $("#ldapCronTimeSelect").hide();
            }
        });

        // show fetch time on change
        $("#adUserFetchingCommand").on('change', function () {
            if ( $(this).val() === 'dailyAt') {
            $("#adUserFetchDailyAt").show();
        } else {
            $("#adUserFetchDailyAt").hide();
        }
        });

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
//escalation
$(document).ready(function () {
        var checked = $("#escalation_cron").is(':checked');
        check(checked, 'escalation_cron');
        $("#escalation_cron").on('click', function () {
            checked = $("#escalation_cron").is(':checked');
            check(checked);
        });
        var command = $("#escalation-command").val();
        showDailyAt(command);
        $("#escalation-command").on('change', function () {
            command = $("#escalation-command").val();
            showDailyAt(command);
        });
        function check(checked, id) {
            if (checked) {
                $("#notification2").show();
            } else {
                $("#notification2").hide();
            }
        }
        function showDailyAt(command) {
            if (command === 'dailyAt') {
                $("#notification-daily-at2").show();
            } else {
                $("#notification-daily-at2").hide();
            }
        }
    });
</script>
