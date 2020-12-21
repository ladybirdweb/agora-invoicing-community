<a href="#tenant" class="btn  btn-primary btn-xs" data-toggle="modal" data-target="#tenant"><i class="fa fa-refresh"></i>&nbsp;Deploy</a>
<div class="modal fade" id="tenant" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open() !!}
            <div class="modal-header">
                 <h4 class="modal-title">Create an instance</h4>
            </div>
             
            <div class="modal-body">
                <div id="success">
                 </div>
                  <div id="error">
                 </div>
                <!-- Form  -->
                
            <div class="container">
                
                <form action="" method="post" style="width:500px; margin: auto auto;" class="card card-body">
                    <input type="hidden" id="orderNo" name="order" value={{$orderNumber}}>
                    <div class="form-group">
                        <label>Domain</label>
                        <div class="row" style="margin-left: 2px; margin-right: 2px;">
                            <input  type="text" name="domain" autocomplete="off" id= "userdomain"  class="form-control col col-4" placeholder="Domain">
                            <input type="text" class="form-control col col-8" value=".faveo-homestead.com" disabled="true">
                        </div>
                    </div>
                   <!--  <div class="form-group">
                        <label>Passphrase</label>
                        <input name="passphrase" autocomplete="off" type="password" id="password" class="form-control" placeholder="Passphrase">
                    </div> -->
                   
                </form>
            </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                 <button type="submit"  class="btn btn-primary" id="createTenant"><i class="fa fa-check">&nbsp;&nbsp;</i>Submit</button>
                {!! Form::close()  !!}
            </div>
            <!-- /Form -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
  
<script>
    $('.closebutton').on('click',function(){
        location.reload();
    });

    $('#createTenant').on('click',function(){
        $("#createTenant").html("<i class='fas fa-circle-notch fa-spin'></i>Please Wait...");
        var domain = $('#userdomain').val();
        var password = $('#password').val();
        var order = $('#orderNo').val();
        $.ajax({
            url: "{{url('create/tenant')}}",
            type: "POST",
            data: {'domain': domain, 'password': password, 'orderNo': order},
            success: function (data) {
                console.log(data)
                $("#createTenant").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                $('#success').show();
                $('#error').hide();
                var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong><i class="fa fa-check"></i>Success! </strong>'+data.message+'!</div>';
                    $('#success').html(result);

            },error: function (response) {
                 $("#generate").html("<i class='fas fa-sync-alt'>&nbsp;&nbsp;</i>Generate");
                if(response.responseJSON.success == false) {
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                    html += '<li>' + response.responseJSON.message[0] + '</li>'
                } else {
                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                for (var key in response.responseJSON.errors)
                {
                    html += '<li>' + response.responseJSON.errors[key][0] + '</li>'
                }
                 
                }
                
                 html += '</ul></div>';
                 $('#error').show();
                  $('#success').hide();
                  document.getElementById('error').innerHTML = html;
                
            }

        })
    })
</script>