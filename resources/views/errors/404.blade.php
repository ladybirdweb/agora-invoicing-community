@extends('themes.default1.layouts.front.master')
@section('title')
404
@stop
@section('page-heading')
404 - Page Not Found
@stop
@section('breadcrumb')
<li><a href="{{url('my-invoices')}}">Home</a></li>
<li class="active">404</li>
@stop
@section('main-class') "main shop" @stop
@section('content')
			<div class="container">

					<section class="http-error">
						<div class="row justify-content-center py-3">
							<div class="col-md-7 text-center">
								<div class="http-error-main">
									<h2>404!</h2>
									<p>We're sorry, but the page you were looking for doesn't exist.</p>
								</div>
							</div>
							<div class="col-md-4 mt-4 mt-md-0">
								<h4 class="text-primary">Here are some useful links</h4>
								<ul class="nav nav-list flex-column">
									<li class="nav-item"><a class="nav-link" href="{{url('my-invoices')}}">My Invoices</a></li>
									<li class="nav-item"><a class="nav-link" href="{{url('my-orders')}}">My Orders</a></li>
									<li class="nav-item"><a class="nav-link" href="{{url('my-profile')}}">My Profile</a></li>
									<li class="nav-item"><a class="nav-link" href="{{url('contact-us')}}">Contact Us</a></li>
								</ul>
							</div>
						</div>
					</section>

				</div>


@stop
