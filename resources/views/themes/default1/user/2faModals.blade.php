<style>
   .hide {


display: none;


}
</style>
</style>
 @php
$user = DB::table('users')->where('id',Auth::id())->first(); 
@endphp


@if($user->password)
<div class="modal fade" id="2fa-modal1" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content">
   <div class="modal-header">
     <h4 class="modal-title">Set up Authenticator</h4>
   </div>
   <div class="modal-body">
              
     <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
         {!! Form::label('name',Lang::get('message.varify_password'),['class'=>'required']) !!}
         <input type="password" name="password" id="user_password" placeholder="Enter Password" class="form-control" required="required">
          <input type="hidden" name="login_type" id="login_type" value="login">
     </div>
     <span id="passerror"></span>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
     <button type="button" id="verify_password" class="btn btn-primary"><i class="fa fa-check">&nbsp;&nbsp;</i>Validate</button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
@else
<div class="modal fade" id="2fa-modal1" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content">
   <div class="modal-header">
     <h4 class="modal-title">Set up Authenticator</h4>
   </div>
   <div class="modal-body">
              
     <form>
         <input type="hidden" name="user_password" id="user_password" value="">
         <input type="hidden" name="login_type" id="login_type" value="social">
           <h5>Hi {{$user->first_name}},</h5>
      <p><b>To continue,Please click on Verify to verify it's you </b></p>
     </form>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-bs-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
     <button type="button" id="verify_password" class="btn btn-primary"><i class="fa fa-check">&nbsp;&nbsp;</i>Validate</button>
   </div>
 </div>
 </div>
 </div>
 <!-- /.modal-content -->

<!-- /.modal-dialog -->

@endif




<div class="modal fade" id="2fa-recover-code-modal" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Recovery Code</h4>
   </div>
   <div class="modal-body">
       <div id="alertMessagecopied"></div>    
      <p>Recovery codes are used to access your account in the event you cannot receive two-factor authentication code.
        Copy your recovery code before continuing two-factor authentication setup.</p>


        <div class="row">
      
        <div class="col-md-2"><b>Recovery Code:</b></div>
         <div class="col-md-8">
         
           <input type="text" id="recoverycode" readonly="readonly" class="form-control">


         </div>
           <div class="col-md-2">


                    @component('mini_views.copied_flash_text',[
                                'navigations'=>[
                                   [ 'btnName'=>'rec_code','slot'=>'recovery','style'=>'<span style="pointer-events: initial; cursor: pointer; display: block;" id="copyBtn" title="Click to copy to clipboard" onclick="copyRecoveryCode()"><i class="fa fa-clipboard"></i></span><span class="badge badge-success badge-xs pull-right" id="copied" style="display:none;margin-top:-40px;margin-left:-20px;position: absolute;">Copied</span>'],
                                   ]
                                  
                               ])
                             
                               @slot('recovery')
                               <span class="badge badge-success badge-xs pull-right" id="copied" style="display:none;margin-top:-15px;margin-left:-20px;position: absolute;">Copied</span>
                            
                               @endslot
                               @endcomponent


                   <span style="font-size: 20px; display: none;" id="loader"><i class="fa fa-circle-o-notch fa-spin"></i></span>
               </div>
       
     
     </div>
     <br>
     <div>
      <p>
       Treat your recovery codes with the same level of attention as you would your password! We recommend saving them with a password manager such as <a href="https://lastpass.com" target="_blank">Lastpass</a>, <a href="https://1Password.com" target="_blank">1Password</a>, or <a href="https://keepersecurity.com.com" target="_blank">Keeper</a>.
     </p>
   </div>
      <span id="passerror"></span>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
     <button type="button" id="next_rec_code" class="btn btn-primary">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>






<div class="modal fade" id="2fa-modal2" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Set up Authenticator</h4>
   </div>
   <div class="text-center">
   <div class="modal-body bar-code">
           <ul class="col-sm-offset-3 offset-sm-2 text-left">
             <li>Get the Authenticator App from the Play Store.</li>
             <li>In the App select <b>Set up account.</b></li>
             <li>Choose <b>Scan a barcode.</b></li>
           </ul>    
     <div id="barcode">
        <!--<img id="image"/>-->
       
         <div id="svgshow">
                
             </div>
 
     </div>
     <a href="javascript:;" id="cantscanit">CAN'T SCAN IT?</a>
   </div>
   <div class="modal-body secret-key">
         <div id="alertMessage2"></div>
           <ul class="col-sm-offset-3 offset-sm-2 text-left">
             <li>Tap <b>Menu</b>, then <b>Set up account.</b></li>
             <li>Tap <b>Enter provided key.</b></li>
             <li>Enter your email address and this key :</li>
             <br>
         
           <div class="col-md-6">


             <input type="text" id="secretkeyid" readonly="readonly" class="form-control" style="width: auto;">
         
           </div>
             <br><br>
             <li>Make sure <b>Time based</b> is turned on, and tap <b>Add</b> to finish.</li>
           </ul>    
     <a href="javascript:;" id="scanbarcode">SCAN BARCODE?</a>
   </div>
 </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
     <button type="button" id="scan_complete" class="btn btn-primary">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>




<div class="modal fade" id="2fa-modal3" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Set up Authenticator</h4>
   </div>
   <div class="modal-body modal-body-spacing">
       {!! Form::label('name',Lang::get('message.enter_6_digit_code'),['class'=>'required']) !!}
      <div class="row">        
  
       <div class="col-sm-8 text-left form-group form-field-template">
      
         <input type="text" name="password" id="passcode"  placeholder="Enter Passcode..." class="form-control" required="required">
       <span id="passcodeerror"></span>
       </div>
         <div class="col-sm-4">
         
         <button type="button" id="pass_btn" class="btn btn-primary pull-right float-right">
           <i class="fa fa-check"></i> Verify
         </button>
       </div>
   
   </div>
    
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
     <button type="button" id="prev_button" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>




<div class="modal fade" id="2fa-modal4" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Set up Authenticator</h4>
   </div>
   <div class="modal-body">
              
     <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
         You're all set. From now on, you'll use Authenticator to sign in to your Faveo Billing Account.
     </div>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>


<div class="modal fade" id="2fa-modal5" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Turn off Two-Factor-Authentication</h4>
   
   </div>
  
   <div class="modal-body">
      <div id="alertMessage"></div>
     <div class= "form-group {{ $errors->has('name') ? 'has-error' : '' }}">
         Turning off 2-Step Verification will remove the extra security on your account, and you’ll only use your password to sign in.
     </div>
   </div>
   <div class="modal-footer">
     <button class="btn btn-danger pull-right float-right" id="turnoff2fa"><i class="fa fa-power-off"></i> TURN OFF</button>
     <button type="button" class="btn btn-default pull-left closeandrefresh" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>




<div class="modal fade" id="2fa-view-code-modal" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
 <div class="modal-content" style="width:700px;">
   <div class="modal-header">
     <h4 class="modal-title">Recovery Code</h4>
   </div>
   <div class="modal-body">
      <p>Recovery code can be used only once. Make sure to generate a new one each time you use the code to login.</p>


        <div class="row">
      
        <div class="col-md-2"><b>Recovery Code:</b></div>
         <div class="col-md-4">
         
           <input type="text" id="newrecoverycode" readonly="readonly" class="form-control" style="height: 40px;">


         </div>
         <div class="col-md-4">
         
          <button class="btn btn-dark" id="generateNewCode">Generate New</button>


         </div>
           <div class="col-md-2">
               <span class="badge badge-success badge-xs pull-right" id="copied-new" style="margin-left:20px;display:none;margin-top:-15px;position: absolute;">Copied</span>




                @component('mini_views.copied_flash_text',[
                                'navigations'=>[
                                   [ 'btnName'=>'rec_code','slot'=>'recovery','style'=>' <span class="btn btn-light-scale-2 text-black btn-sm ms-4" style="margin-top:5px; pointer-events: initial; cursor: pointer; display: block;position: absolute;right: 150px;" id="copyNewCodeBtn" data-bs-toggle="tooltip" title="Click to copy to clipboard" onclick="copyNewRecoveryCode()"><i class="fa fa-clipboard"></i></span><span class="badge badge-success badge-xs pull-right" id="copied" style="display:none;margin-top:-40px;margin-left:-20px;position: absolute;">Copied</span>'],
                                   ]
                                  
                               ])
                               @endcomponent


                 


                   <span style="font-size: 20px; display: none;" id="newloader"><i class="fa fa-circle-o-notch fa-spin"></i></span>
           </div>
       
     
     </div>
     <br>
     <div>
   </div>
      <span id="passerror"></span>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default pull-left" data-bs-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
   </div>
 </div>
 <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>





