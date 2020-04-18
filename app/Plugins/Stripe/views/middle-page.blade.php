@extends('themes.default1.layouts.front.master')
@section('title')
Invoice
@stop
@section('nav-invoice')
active
@stop
@section('page-heading')
 <h1>Payment </h1>
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Account</li>
<li class="active">Payment</li>
@stop
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Payment
                 <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
             </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                    <div class="custom-alerts alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {!! $message !!}
                    </div>
                    <?php Session::forget('success');?>
                    @endif
                    @if ($message = Session::get('error'))
                    <div class="custom-alerts alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {!! $message !!}
                    </div>
                    <?php Session::forget('error');?>
                    @endif
                    <form method="POST" id="submit_total" action="{{ route('paywithstripe') }}" >
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="card_no" type="text" class="form-control @error('card_no') is-invalid @enderror" name="card_no" value="{{ old('card_no') }}" required autocomplete="card_no" placeholder="Card No." autofocus>
                                @error('card_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <input id="exp_month" type="text" class="form-control @error('exp_month') is-invalid @enderror" name="exp_month" value="{{ old('exp_month') }}" required autocomplete="exp_month" placeholder="Exp. Month (Eg. 02)" autofocus>
                                @error('exp_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input id="exp_year" type="text" class="form-control @error('exp_year') is-invalid @enderror" name="exp_year" value="{{ old('exp_year') }}" required autocomplete="exp_year" placeholder="Exp. Year (Eg. 2020)" autofocus>
                                @error('exp_year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="cvv" type="password" class="form-control @error('cvv') is-invalid @enderror" name="cvv" required autocomplete="current-password" placeholder="CVV">
                                @error('cvv')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="amount" type="text" value={{Session::get('amount')}} class="form-control @error('amount') is-invalid @enderror" required autocomplete="current-password" name="amount" placeholder="Amount" readonly>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" id="pay_now" class="btn btn-primary btn-block">
                                    {{ __('PAY NOW') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
  

  
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#submit_total').submit(function(){
     $("#pay_now").html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Processing...Please Wait..")
    $("#pay_now").prop('disabled', true);

  });
$(function() {
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
 
        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });
  
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
    }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
  
});
</script>

@stop