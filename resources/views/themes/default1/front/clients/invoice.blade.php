@extends('themes.default1.layouts.front.master')
@section('title')
Invoice
@stop
@section('nav-invoice')
active
@stop
@section('page-heading')
 My Invoices
@stop
@section('breadcrumb')
 @if(Auth::check())
<li><a href="{{url('my-invoices')}}">Home</a></li>
  @else
  <li><a href="{{url('login')}}">Home</a></li>
  @endif
<li class="active">My Invoices</li>
@stop

@section('content')
<style>
[type=search] {
        padding-right: 20px;
}
.modal {
   position: absolute;
   top: 65%;
   left: 50%;
   transform: translate(-50%, -50%);
   width: 500px;
   height: 500px;
}

</style>

<div class="col-md-12 pull-center">
  <table id="invoice-table" class="table display" cellspacing="0" width="100%" styleClass="borderless">
    <thead>
      <tr>
        <th>Invoice No</th>
        <th>Date</th>
        <th>Order No</th>
        <th>Total</th>
        <th>Paid</th>
        <th>Balance</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
  </table>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Required Details</h5>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ url('store-basic-details') }}">
          @csrf
          <div class="form-group">
            <label for="recipient-name" class="col-form-label"><b>Company Name</b><span style="color: red;">*</span></label>
            <input type="text" class="form-control required" id="company" name="company">
             
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label"><b>Address</b><span style="color: red;">*</span></label>
            <textarea class="form-control required" id="address" name="address"></textarea>
             

          </div>
        </form>
      </div>
      <div class="modal-footer">
  <button type="submit" class="btn btn-primary" id="submit">
    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    <span class="button-text"> <i class="fa fa-save">&nbsp;&nbsp;</i>Save</span>
  </button>
</div>

    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
<script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-d29Fujug+OENvFj6HuYvD9+cwr+kyXHuVxX1BfX+2jR4x0nXj/p4pJbnhvHaRyC4XNLXmU4OxQanMF6Z6hF4sQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-0S5w6wzFQ59Qz2IBn9NahL6FgH3qCMjwdJ0bZL2nBBap6Y69UqY0WVCjAq9Lc0qTg0OwIDP4MmPjxEgAkqE+Jg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!----save basicx details---->
<script type="text/javascript">
  $(document).ready(function() {
    $('#submit').click(function(event) {
      event.preventDefault();
      const isFirstLogin = !localStorage.getItem('isLoggedIn');
      const isModalFilled = localStorage.getItem('isModalFilled');

      if (isFirstLogin && !isModalFilled) {
        var btn = $(this);
        btn.prop('disabled', true);
        btn.find('.spinner-border').removeClass('d-none');
        btn.find('.button-text').text('Saving...');

        var formData = $('#myModal form').serialize();

        $.ajax({
          url: $('#myModal form').attr('action'),
          type: 'POST',
          data: formData,
          success: function(response) {
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('.button-text').text('Save');
            $('#myModal').modal('hide');
      localStorage.setItem('isModalFilled', 'true');
          },
          error: function(xhr, status, error) {
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
            btn.find('.button-text').text('Save');
          }
        });
      }
    });
  });
</script>

<script>
  $(window).on('load', function() {
    const isFirstLogin = !localStorage.getItem('isLoggedIn');
    const isModalFilled = localStorage.getItem('isModalFilled');

    if (isFirstLogin && !isModalFilled) {
      $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
      });
    }

    $('#myModal form').on('submit', function() {
      localStorage.setItem('isModalFilled', 'true');
    });
  });
</script>

@stop
