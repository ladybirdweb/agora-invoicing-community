<!DOCTYPE html>
<html>
<head>
    <style>
        .list-styled.columns-lg-2.px-2 li a {
            color: #777 !important; /* Set the anchor text color to black */
        }
        .product-thumbnail-remove, .btn-remove{
            cursor: pointer;
        }
    .page-transition-active {
    opacity: 1 !important; /* Setting the opacity to 1 for full visibility */
}
.custom-input {
    border: 1px solid transparent;
    border-radius: 4px;
    padding: 11.2px 16px;
    color: #777;
    width: 84%;

}

.custom-input:focus {
    border-color: #777;
    outline: none; 
}


</style>
  <html>
<?php 
 $dataCenters = \App\Model\CloudDataCenters::all();
?>
<?php 
$setting = \App\Model\Common\Setting::where('id', 1)->first();
$everyPageScripts = ''; 
$scripts = \App\Model\Common\ChatScript::where('on_every_page', 1)->get();

foreach($scripts as $script) {
    if (strpos($script->script, '<script>') === false && strpos($script->script, '</script>') === false) {
        $everyPageScripts .= "<script>{$script->script}</script>";
    } else {
        $everyPageScripts .= $script->script;
    }

}
?>
            <!-- Basic -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | {{$setting->favicon_title_client}}</title>

    <meta name="keywords" content="Faveo" />
    <meta name="description" content="Faveo">
    <meta name="author" content="Sakthivel Munusami">

    <!-- Favicon -->
    @if($setting->fav_icon)
        <link rel="shortcut icon"  href='{{asset("storage/common/images/$setting->fav_icon")}}' type="image/x-icon" />
    @endif
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Web Fonts  -->
    <link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('client/porto/css-2/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/animate.compat.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/simple-line-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('client/porto/css-2/magnific-popup.min.css')}}">

    <!-- Theme CSS -->
    <link id="default-styles" rel="stylesheet" href="{{asset('client/porto/css-2/theme.css')}}">
    <link id="default-styles-2" rel="stylesheet" href="{{asset('client/porto/css-2/theme-elements.css')}}">
    <link id="default-styles-3" rel="stylesheet" href="{{asset('client/porto/css-2/theme-blog.css')}}">
    <link id="default-styles-4" rel="stylesheet" href="{{asset('client/porto/css-2/theme-shop.css')}}">



    <!-- Demo CSS -->
    <link rel="stylesheet" href="{{asset('client/porto/css-2/demo-transportation-logistic.css')}}">

    <!-- Skin CSS -->
    <link id="skinCSS" rel="stylesheet" href="{{asset('client/porto/css-2/skin-transportation-logistic.css')}}">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('client/porto/css-2/custom.css')}}">

    <link rel="stylesheet" href="{{asset('common/css/intlTelInput.css')}}">

    <!-- Head Libs -->
    <script src="{{asset('client/porto/js-2/modernizr.min.js')}}"></script>
    <script src="{{asset('client/js/modernizr.min.js')}}"></script>

    <!--<script src="{{asset('common/js/jquery-2.1.4.js')}}" type="text/javascript"></script>-->

    <style>

        .sticky-header-active .d-sticky-header-negative-none {
            display: block !important;
        }
        #footer h1, #footer h2, #footer h3, #footer h4, #footer h5, #footer h6
        {
            color: black !important;
        }
        html .border-color-hover-dark:hover {
            border-color: lightgrey !important;
        }
    </style>

</head>
<?php
$domain = [];
$set = new \App\Model\Common\Setting();
$set = $set->findOrFail(1);
$pay = new \App\Model\Payment\Plan();
$days = $pay->where('product','117')->value('days');
?>
@include('themes.default1.front.demoForm')
<body>
<?php
$bussinesses = App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
$status =  App\Model\Common\StatusSetting::select('recaptcha_status', 'msg91_status', 'emailverification_status', 'terms')->first();
$apiKeys = App\ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck', 'msg91_auth_key', 'terms_url')->first();
$analyticsTag = App\Model\Common\ChatScript::where('google_analytics', 1)->where('on_registration', 1)->value('google_analytics_tag');
$location = getLocation();
?>

<?php
$domain = [];
$set = new \App\Model\Common\Setting();
$set = $set->findOrFail(1);
$pay = new \App\Model\Payment\Plan();
$days = $pay->where('product','117')->value('days');
?>
@php
    $social = App\Model\Common\SocialMedia::get();
@endphp

<div class="body p-relative bottom-1">

    <header id="header" class="header-effect-reveal" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'reveal', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': false, 'stickyStartAt': 200, 'stickySetTop': '-44px'}">

        <div class="header-body border-0 border-bottom-light">

            <div class="header-container container-fluid p-0">

                <div class="header-row">

                    <div class="header-column header-column-border-right flex-grow-0 d-sticky-header-active-none">

                        <div class="header-row">

                            <div id="main-logo" class="header-logo p-relative top-sm-40 top-30 m-0" style="width: 250px;height: 150px;text-align: center;">

                                <a href="{{Auth::check() ? url('client-dashboard') : url('login')}}">

                                    <img alt="Porto" width="130" height="75" src="{{asset('storage/images/'.$setting->logo)}}">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="header-column">

                        <div class="border-bottom-light w-100">

                            <div class="hstack gap-4 px-4 py-2 font-weight-semi-bold d-none d-lg-flex">
                                @if($set->phone != NULL)
                                    <div class="d-none d-lg-inline-block ps-1">

                                        <a class="text-color-default text-color-hover-primary text-2" href="tel: +{{$set->phone_code}} {{$set->phone}}">

                                            <i class="fas fa-phone text-4 p-relative top-2"></i> &nbsp;+{{$set->phone_code}} {{$set->phone}}
                                        </a>
                                    </div>
                                @endif
                                <div class="vr d-lg-inline-block opacity-2 d-none d-xl-inline-block"></div>
                                @if($set->company_email != NULL)
                                    <div class="d-none d-xl-inline-block">

                                        <a class="text-color-default text-color-hover-primary text-2" href="mailto:{{$set->company_email}}">

                                            <i class="fas fa-envelope text-4 p-relative top-2"></i> &nbsp;{{$set->company_email}}
                                        </a>
                                    </div>
                                @endif
                                <div class="ms-auto d-none d-lg-inline-block">

                                </div>
                                @php
                                    $social = App\Model\Common\SocialMedia::get();
                                @endphp

                                <div class="vr opacity-2 d-none d-lg-inline-block"></div>

                                <div class="d-none d-lg-inline-block">

                                    <ul class="nav nav-pills me-1">
                                        @foreach($social as $media)
                                            <li class="nav-item pe-2 mx-1">
                                                <a href="{{$media->link}}" target="_blank" data-bs-toggle="tooltip" title="{{$media->name}}" class="text-color-default text-color-hover-primary text-4">
                                                    @if ($media->name === 'Facebook')
                                                        <i class="fab fa-facebook-f"></i>
                                                    @elseif ($media->name === 'Twitter')
                                                        <i class="fab fa-twitter"></i>
                                                    @elseif ($media->name === 'Linkedin')
                                                        <i class="fab fa-linkedin-in"></i>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        $groups = \App\Model\Product\ProductGroup::where('hidden','!=', 1)->get();
                        ?>

                        <div class="header-row h-100">

                            <div class="hstack h-100 w-100">

                                <div class="h-100 w-100 w-xl-auto">

                                    <div class="header-nav header-nav-links h-100 justify-content-end justify-content-lg-start me-4 me-lg-0 ms-lg-3">

                                        <div class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-text-capitalize header-nav-main-text-size-4 header-nav-main-arrows header-nav-main-full-width-mega-menu header-nav-main-mega-menu-bg-hover header-nav-main-effect-5">

                                            <nav class="collapse">

                                                <ul class="nav nav-pills" id="mainNav">

                                                    <li class="d-sticky-header-negative-none" style="display:none">

                                                        <a class="nav-link" href="{{Auth::check() ? url('client-dashboard') : url('login')}}">

                                                            <img alt="Porto" width="75" height="50" src="{{asset('storage/images/'.$setting->logo)}}">
                                                        </a>
                                                    </li>


                                                    <li class="dropdown">

                                                        <a class="nav-link dropdown-toggle {{ strpos(request()->url(), 'group') !== false ? 'active' : '' }}" href="javascript:;">
                                                            &nbsp;Store&nbsp;
                                                        </a>

                                                        <ul class="dropdown-menu border-light mt-n1">
                                                            @foreach($groups as $group)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{url("group/$group->pricing_templates_id/$group->id")}}">{{$group->name}}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                    <?php $pages = \App\Model\Front\FrontendPage::where('publish', 1)->orderBy('created_at','asc')->get();
                                                    ?>

                                                    @foreach($pages as $page)
                                                        @if($page->parent_page_id==0)

                                                            <li class="dropdown">
                                                                    <?php
                                                                    $ifdrop = \App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->count();
                                                                    $class = $ifdrop > 0 ? 'nav-link dropdown-toggle' : 'nav-link';
                                                                    ?>
                                                                @if($page->type == 'contactus')
                                                                    <a class="dropdown-item {{ Request::url() == url('contact-us') ? $class.' active' : $class }}" href="{{ url('contact-us') }}">
                                                                        @else
                                                                            <a class="dropdown-item {{ Request::url() == $page->url ? $class.' active' : $class }}" href="{{ $page->url }}">
                                                                                @endif
                                                                                {{ ucfirst($page->name) }}
                                                                            </a>

                                                                            @if(\App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->count() > 0)
                                                                                    <?php $childs = \App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->get(); ?>
                                                                                <ul class="dropdown-menu border-light mt-n1">
                                                                                    @foreach($childs as $child)
                                                                                        <li>
                                                                                            <a href="{{ $child->url }}" class="dropdown-item {{ Request::url() == $child->url ? 'active' : '' }}">
                                                                                                {{ ucfirst($child->name) }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach


                                                    @if(Auth::user())

                                                        <li class="dropdown">

                                                            <a class="nav-link dropdown-toggle {{ Request::is('client-dashboard', 'my-orders', 'my-invoices', 'my-profile') ? 'active' : '' }}" href="javascript:;">
                                                                &nbsp;My Account&nbsp;
                                                            </a>

                                                            <ul class="dropdown-menu border-light mt-n1">
                                                                @if(Auth::user()->role == 'admin')
                                                                    <li>
                                                                        <a href="{{url('/')}}" class="dropdown-item">Admin Dashboard</a>
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    <a href="{{url('client-dashboard')}}" class="dropdown-item">Dashboard</a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{url('my-orders')}}" class="dropdown-item">My Orders</a>
                                                                </li>

                                                                <li>
                                                                    <a href="{{url('my-invoices')}}" class="dropdown-item">My Invoices</a>
                                                                </li>

                                                                <li>
                                                                    <a href="{{url('my-profile')}}" class="dropdown-item">My Profile</a>
                                                                </li>

                                                                <li>
                                                                    <a href="{{url('auth/logout')}}" class="dropdown-item">Logout</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    @else

                                                        <li>
                                                            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{url('login')}}">Sign Up</a>
                                                        </li>
                                                    @endif
                                                    <?php
                                                    $cloud = \App\Model\Common\StatusSetting::where('id','1')->value('cloud_button');
                                                    $Demo_page = App\Demo_page::first();
                                                    ?>
                                                    @if($cloud == 1)
                                                        <li class="demo-icons">
                                                            <a class="nav-link open-createTenantDialog startFreeTrialBtn">START FREE TRIAL</a>
                                                        </li>
                                                        @endif
                                                        </li>
                                                        @if($Demo_page->status)
                                                            <li class="demo-icons">
                                                                <a class="nav-link" id="demo-req">REQUEST FOR DEMO</a>
                                                            </li>
                                                        @endif
                                                </ul>
                                            </nav>
                                        </div>

                                        <div class="header-nav-features header-nav-features-no-border header-nav-features-lg-show-border order-1 order-lg-2 me-2 me-lg-0">
                                            <div class="header-nav-feature header-nav-features-cart d-inline-flex ms-2 mx-3">
                                                <a href="{{ url('show/cart') }}" class="header-nav-features-toggle text-decoration-none">
                                                    <span class="text-dark opacity-8 font-weight-bold text-color-hover-primary"> Cart</span>
                                                    <img src="{{asset('client/porto/fonts/icon-cart.svg')}}" width="14" alt="" class="header-nav-top-icon-img">
                                                    <span class="cart-info">
                                                <span class="cart-qty">{{ Cart::getTotalQuantity() }}</span>
                                            </span>
                                                </a>
                                                <div class="header-nav-features-dropdown right-15" id="headerTopCartDropdown">
                                                    <ol class="mini-products-list">
                                                        @forelse(Cart::getContent() as $key => $item)
                                                                <?php
                                                                $product = App\Model\Product\Product::where('id', $item->id)->first();
                                                                if ($product->require_domain == 1) {
                                                                    $domain[$key] = $item->id;
                                                                }
                                                                $currency = $item->attributes['currency'];
                                                                $total = rounding($item->getPriceSumWithConditions());
                                                                ?>
                                                            <li class="item">
                                                                <a href="#" data-bs-toggle="tooltip" title="{{ $product->name }}" class="product-image">
                                                                    <img src="{{ $product->image }}" alt="{{ $product->name }}"  width="70">
                                                                </a>
                                                                <div class="product-details">
                                                                    <p class="product-name">
                                                                        <a href="#">{{ $item->name }}</a><br>
                                                                        <span class="amount"><strong>{{ currencyFormat($total, $code = $currency) }}</strong></span>
                                                                    </p>
                                                                    <a onclick="removeItem('{{$item->id}}');"data-bs-toggle="tooltip" title="Remove This Item" class="btn-remove">
                                                                        <i class="fas fa-times"></i>
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        @empty


                                                            @php
                                                                $data = \App\Model\Product\ProductGroup::where('hidden','!=', 1)->first();
                                                            @endphp                                                                      
                                                  
                                                           <div class="product-details d-flex justify-content-between align-items-center" style="margin-bottom: 20px;font-weight: 500;font-size: 13px;font-family: Poppins,sans-serif;letter-spacing: -0.12px;">
                                                            <span class="text-muted">0 ITEMS</span>
                                                            @if (Auth::check())
                                                            <a class="text-v-dark text-uppercase" style="color: black;font-family: Poppins,sans-serif;font-weight: 700;font-size: 13px;letter-spacing: -0.12px;" href="{{url("group/$data->pricing_templates_id/$data->id")}}">View Store</a>
                                                            @else
                                                             <a class="text-v-dark text-uppercase" href="{{ url('login') }}">View Store</a>
                                                            @endif
                                                        </div>

                                                        <hr style="border-top: 0.5px solid #ccc;">

                                                        <span  style="display: block; text-align: center;">No products in the cart.</span>

                                                          
                                                        @endforelse
                                                        @if (!Cart::isEmpty())
                                                            <div class="totals">
                                                                <span class="label">Total:</span>
                                                                <span class="price-total"><span class="price">{{ currencyFormat(Cart::getTotal(), $code = $currency) }}</span></span>
                                                            </div>

                                                            <li>
                                                                <div class="actions">
                                                                    <a class="btn btn-dark btn-modern text-uppercase font-weight-semi-bold"
                                                                       href="{{ url('show/cart') }}">View Store</a>
                                                                    @if (count($domain) > 0)
                                                                        <a href="#domain" data-toggle="modal" data-target="#domain"
                                                                           class="btn btn-primary">Proceed to Checkout</a>
                                                                    @else
                                                                        <a href="{{ url('checkout') }}" class="btn btn-primary">Checkout</a>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        @endif
                                                    </ol>

                                                </div>
                                            </div>
                                        </div>


                                        <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse" data-bs-target=".header-nav-main nav">

                                            <i class="fas fa-bars"></i>
                                        </button>
                                    </div>
                                </div>


                                <div class="vr opacity-2 ms-auto d-none d-lg-inline-block"></div>

                                <div class="px-4 d-none d-lg-inline-block ws-nowrap">
                                    @if($cloud == 1)
                                        <a class="btn border-0 px-4 py-2 line-height-9 btn-tertiary me-2 open-createTenantDialog startFreeTrialBtn" style="color: white;">START FREE TRIAL</a>
                                    @endif
                                    @if($Demo_page->status)
                                        <a id="demo-req" class="btn border-0 px-4 py-2 line-height-9 btn-primary" style="color: white;">REQUEST FOR DEMO</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div role="main" class=@yield('main-class')>

        <div class="custom-page-header border-bottom-light">
            <section class="page-header page-header-modern bg-color-light-scale-1 border-0 my-0">
                <div class="container my-3">
                    <div class="row">
                        <div class="col-md-12 align-self-center p-static order-2 text-center">
                            <h1 class="font-weight-bold text-10 text-dark">@yield('page-heading')</h1>
                        </div>
                        <div class="col-md-12 align-self-center order-1">
                            <ul class="breadcrumb breadcrumb-light d-block text-center">
                                @yield('breadcrumb')
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <br>

        <div class="container">
            @if(request()->has('message'))
                <div class="alert alert-info alert-dismissible fade show alert-cloud" role="alert">
                    {{ urldecode(request('message')) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if(Session::has('warning'))

                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{Session::get('warning')}}
                </div>
            @endif

            @if(Session::has('Success'))

                <div class="container">

                    {!!Session::get('Success')!!}
                </div>

            @endif
            @if(!Session::has('Success'))
                @if(Session::has('success'))

                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                        <strong><i class="far fa-thumbs-up"></i> Well done!</strong>

                        {!!Session::get('success')!!}
                    </div>
                @endif
            @endif

            <!--fail message -->
            @if(Session::has('fails') )

                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{Session::get('fails')}}
                </div>

            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                    @if ($errors->count() > 1)
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    @else
                        {!! $errors->first() !!}
                    @endif
                </div>
            @endif


            @include('themes.default1.front.domain')
            @yield('content')

        </div>

    </div>



    @auth

        <div class="modal fade" id="tenant" tabindex="-1" role="dialog" aria-labelledby="trialModalLabel" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">
                    {!! Form::open() !!}

                    <div class="modal-header">

                        <h4 class="modal-title" id="trialModalLabel">{{optional(cloudPopUpDetails())->cloud_top_message}}</h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" style="width:500px; margin: auto auto;" class="card card-body">

                            <div id="cloudsuccess">
                            </div>
                            <div id="clouderror">
                            </div>

                            <div class="row">

                                <div class="form-group col">

                                    <label class="form-label">{!! optional(cloudPopUpDetails())->cloud_label_field !!}</label>

                                    <div class="input-group mb-2">

                                        <input type="text" name="domain" autocomplete="off" id="userdomain" class="form-control col col-7 rounded-0" placeholder="Domain" required>
                                        <input type="text" class="form-control col col-5 rounded-0" value=".{{cloudSubDomain()}}" disabled="true" style="background-color: #4081B5; color:white; border-color: #0088CC">
                                        <p id="validationMessage"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col">

                                    <label class="form-label">{!!optional(cloudPopUpDetails())->cloud_label_radio !!}</label>

                                    <br>
                                    <?php $cloudProducts = \App\Model\Product\CloudProducts::get(); ?>
                                    @foreach($cloudProducts as $cloudProduct)
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" name="option" class="product" value="{!! $cloudProduct->cloud_product_key !!}" checked>
                                            {!! \DB::table('products')->where('id',$cloudProduct->cloud_product)->value('name') !!}
                                        </label>
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                            <hr>
                            @if($dataCenters->count()==1)
                                <div class="text-center">
                                    <p>Your data center location is <b data-nearest-center="">{!! array_first($dataCenters)->cloud_countries !!} </b><!--<a role="button" href="javascript:void(0)" data-center-link="" aria-labelledby="data-center-text-label-dataCenter119678097062480"><b>Change</b></a>--></p>
                                </div>
                            @else
                                <label style="margin-top: 2px; text-align: left;"><b>Choose your data center</b></label>
                                <div class="row">
                                    <div class="col col-12">
                                        <div class="input-group"> <!-- Wrap select and icon within input-group -->
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                            </div>
                                            <select name="cloud-data-centers" id="cloud-data-centers" class="form-control rounded-0">
                                                @foreach($dataCenters as $data)
                                                    <option value="{{$data->id}}">{{$data->cloud_countries}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                    <script>

                        $(document).ready(function() {
                            $.ajax({
                                url: '{{url("/api/domain")}}',
                                method: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    if(data.data.length !== 0){
                                        $('.createTenant').attr('disabled', false);
                                    }
                                    $('#userdomain').val(data.data);
                                },
                                error: function(error) {
                                }
                            });
                        });
                    </script>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                        <button type="submit"  class="btn btn-primary createTenant" id="createTenant" onclick="firstlogin({{Auth::user()->id}})"><i class="fa fa-check">&nbsp;&nbsp;</i>Submit</button>

                        {!! Form::close()  !!}
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <div class="modal fade" id="tenancy" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open() !!}
                <div class="modal-header">
                    <h4 class="modal-title">{{optional(cloudPopUpDetails())->cloud_top_message}}</h4>
                </div>

                <div class="modal-body">
                    <div id="success">
                    </div>
                    <div id="error">
                    </div>
                    <!-- Form  -->

                    <div class="container">
                        <form action="" method="post" style="width:500px; margin: auto auto;" class="card card-body">
                            <input type="hidden" id="orderNo" name="order" value="117">
                            <div class="form-group">
                                <label><b>{!! optional(cloudPopUpDetails())->cloud_label_field !!}</b></label>
                                <div class="input-group mb-2">
                                    <input type="hidden"  name="order" id="orderId"/>
                                    <input type="text" name="domain" autocomplete="off" id="userdomainPurchase" class="form-control col col-7 rounded-0" placeholder="Domain" required>
                                    <input type="text" class="form-control col col-5 rounded-0" value=".{{cloudSubDomain()}}" disabled="true" style="background-color: #4081B5; color:white; border-color: #0088CC">

                                </div>
                                <p id="validationMessagePurchase"></p>
                            </div>
                                <div class="row data-center">
                                    <div class="col col-12">
                                        @if($dataCenters->count()==1)
                                            <div class="text-center">
                                                <p>Your data center location is <b data-nearest-center="">{!! array_first($dataCenters)->cloud_countries !!} </b><!--<a role="button" href="javascript:void(0)" data-center-link="" aria-labelledby="data-center-text-label-dataCenter119678097062480"><b>Change</b></a>--></p>
                                            </div>
                                        @else
                                            <label style="margin-top: 2px; text-align: left;"><b>Choose your data center</b></label>
                                            <div class="row">
                                                <div class="col col-12">
                                                    <div class="input-group"> <!-- Wrap select and icon within input-group -->
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                                        </div>
                                                        <select name="cloud-data-centers" id="cloud-data-centers" class="form-control rounded-0">
                                                            @foreach($dataCenters as $data)
                                                                <option value="{{$data->id}}">{{$data->cloud_countries}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#tenancy').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var myData = button.data('mydata'); // Extract info from data-* attributes

                            // Update the modal content with the data
                            $('#orderId').val(myData);
                        });
                    });
                </script>
                <script>
                    $(document).ready(function() {
                        $.ajax({
                            url: '{{url("/api/domain")}}',
                            method: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if(data.data.length !== 0){
                                    $('.createtenancy').attr('disabled', false);
                                }
                                $('#userdomainPurchase').val(data.data);
                            },
                            error: function(error) {
                            }
                        });
                    });
                </script>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left closebutton" id="closebutton" data-dismiss="modal"><i class="fa fa-times">&nbsp;&nbsp;</i>Close</button>
                    <button type="submit"  class="btn btn-primary createtenancy" id="createtenancy" onclick="createtenancy()"><i class="fa fa-check">&nbsp;&nbsp;</i>Submit</button>
                    {!! Form::close()  !!}
                </div>
                <!-- /Form -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




    <footer id="footer" class="position-relative bg-color-light-scale-1 border-top-0">

        <div class="container pt-5 pb-3">
            <div class="row">
                <?php
                function renderWidget($widget, $set, $social, $mailchimpKey)
                {
                    $tweetDetails = $widget->allow_tweets == 1 ? '<div id="tweets" class="twitter"></div>' : '';

                    $socialMedia = '';
                    if ($widget->allow_social_media) {
                        // Social Media Icons
                        $socialMedia .= '<ul class="list list-unstyled">';
                        if ($set->company_email) {
                            $socialMedia .= '<li class="d-flex align-items-center mb-4">
                                    <i class="fa-regular fa-envelope fa-xl"></i>&nbsp;&nbsp;
                                    <a href="mailto:' . $set->company_email . '" class="d-inline-flex align-items-center text-decoration-none text-color-grey text-color-hover-primary font-weight-semibold text-4-5">' . $set->company_email . '</a>
                                </li>';
                        }
                        if ($set->phone) {
                            $socialMedia .= '<li class="d-flex align-items-center mb-4">
                                    <i class="fas fa-phone text-4 p-relative top-2"></i>&nbsp;
                                    <a href="tel:' . $set->phone . '" class="d-inline-flex align-items-center text-decoration-none text-color-grey text-color-hover-primary font-weight-semibold text-4-5">+' . $set->phone_code . ' ' . $set->phone . '</a>
                                </li>';
                        }
                        $socialMedia .= '</ul>';

                        // Social Icons
                        $socialMedia .= '<ul class="social-icons social-icons-clean social-icons-medium">';
                        foreach ($social as $media) {
                            $socialMedia .= '<li class="social-icons-' . strtolower($media->name) . '">
                                    <a href="' . $media->link . '" target="_blank" data-bs-toggle="tooltip" title="' . ucfirst($media->name) . '">
                                        <i class="fab fa-' . strtolower($media->name) . ' text-color-grey-lighten"></i>
                                    </a>
                                </li>';
                        }
                        $socialMedia .= '</ul>';
                    }

                    $mailchimpSection = '';
                    if ($mailchimpKey !== null && $widget->allow_mailchimp == 1) {
                        // Mailchimp Subscription Form
                        $mailchimpSection .= '<div id="mailchimp-message" style="width: 100%;"></div>
                                    <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center">
                                        <form id="newsletterForm" class="form-style-3 w-100" action="../php/newsletter-subscribe.php" method="POST" novalidate="novalidate">
                                            <div class="input-group">
                                                <input class="custom-input newsletterEmail" placeholder="Email Address" name="newsletterEmail" id="newsletterEmail" type="email">
                                                <button class="btn btn-primary" id="mailchimp-subscription" type="submit"><strong>GO!</strong></button>
                                            </div>
                                        </form>
                                    </div>';
                    }

                    // Check if the 'menu' class exists in the widget content
                    $hasMenuClass = strpos($widget->content, 'menu') !== false;

                    // Add class if 'menu' class exists in the widget content
                    if ($hasMenuClass) {
                        $widget->content = str_replace('<ul', '<ul class="list list-styled columns-lg-2 px-2"', $widget->content);
                    }

                    return '<div class="col-lg-4">
                    <div class="widget-container">
                        <h4 class="text-color-dark font-weight-bold mb-3">' . $widget->name . '</h4>
                        <div class="widget-content">
                            <p class="text-3-5 font-weight-medium pe-lg-2">' . $widget->content . '</p>
                            ' . $tweetDetails . '
                            ' . ($widget->allow_social_media ? $socialMedia : '') . '
                        </div>
                        ' . $mailchimpSection . '
                    </div>
                </div>';
                }

                $footerWidgetTypes = ['footer1', 'footer2', 'footer3'];
                foreach ($footerWidgetTypes as $widgetType) {
                    $widget = \App\Model\Front\Widgets::where('publish', 1)->where('type', $widgetType)->select('name', 'content', 'allow_tweets', 'allow_mailchimp', 'allow_social_media')->first();
                    $mailchimpKey = \App\Model\Common\Mailchimp\MailchimpSetting::value('api_key');

                    if ($widget) {
                        echo renderWidget($widget, $set, $social, $mailchimpKey);
                    }
                }
                ?>
            </div>
        </div>


        <div class="footer-copyright bg-transparent">

            <div class="container">

                <hr class="bg-color-dark opacity-1">

                <div class="row">
                    <div class="col mt-4 mb-4 pb-5">

                        <p class="text-center text-3 mb-0 text-color-grey">Copyright © <?php echo date('Y') ?> .

                            <a href="{{$set->website}}" class="text-color-grey text-color-hover-primary font-weight-bold">{{$set->company}}. </a>

                            All Rights Reserved. Powered by

                            <a href="{{$set->website}}" class="text-color-grey text-color-hover-primary font-weight-bold" target="_blank">Faveo</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>


<!-- Vendor -->
<script src="{{asset('client/porto/js-2/plugins.min.js')}}"></script>


<!--<script src="{{asset('client/js/jquery.min.js')}}"></script>-->
<script src="{{asset('client/js/jquery.appear.min.js')}}"></script>
<script src="{{asset('client/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('client/js/jquery-cookie.min.js')}}"></script>
<!-- <script src="{{asset('client/js/popper.js')}}"></script> -->
<!-- <script src="{{asset('client/js/popper.min.js')}}"></script> -->
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!--<script src="{{asset('client/js/bootstrap.min.js')}}"></script>-->
<script src="{{asset('client/js/common.min.js')}}"></script>
<script src="{{asset('client/js/jquery.easy-pie-chart.min.js')}}"></script>
<script src="{{asset('client/js/jquery.gmap.min.js')}}"></script>
<script src="{{asset('client/js/jquery.lazyload.min.js')}}"></script>
<script src="{{asset('client/js/jquery.isotope.min.js')}}"></script>
<script src="{{asset('client/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('client/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('client/js/vide.min.js')}}"></script>

<!-- Theme Base, Components and Settings -->
<script src="{{asset('client/porto/js-2/theme.js')}}"></script>

<!-- any custom js/effects can be defined in this -->
<script src="{{asset('client/porto/js/custom.js')}}"></script>

<!-- Theme Initialization Files -->
<script src="{{asset('client/porto/js-2/theme.init.js')}}"></script>
<script src="{{asset('common/js/intlTelInput.js')}}"></script>
<!-- Current Page Vendor and Views -->
<script src="{{asset('client/porto/js-2/view.contact.js')}}"></script>



<script type="text/javascript">

    var csrfToken = $('[name="csrf_token"]').attr('content');

    setInterval(refreshToken, 360000); // 1 hour

    function refreshToken(){
        $.get('refresh-csrf').done(function(data){
            csrfToken = data; // the new token
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


<script>


    $(document).ready(function() {
        var emailInput = $('.newsletterEmail');

        $('#newsletterForm').submit(function(e) {
            e.preventDefault(); 
            var email = emailInput.val();

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                $('#mailchimp-message').html('<br><div class="alert alert-danger"><strong></strong>Please enter a valid email address!<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                return;
            }

            $('#mailchimp-subscription').html("Wait...");

            $.ajax({
                type: 'POST',
                url: '{{url("mail-chimp/subcribe")}}',
                data: {'email': email, '_token': "{!! csrf_token() !!}"},
                success: function(data) {
                     emailInput.val('');
                    $("#mailchimp-subscription").html("Go");
                    $('#mailchimp-message').show();
                    var result = '<br><div class="alert alert-success"><strong><i class="fa fa-check"></i> Success! </strong>' + data.message + ' <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    $('#mailchimp-message').html(result + ".");
                },
                error: function(response) {
                     emailInput.val('');
                    $("#mailchimp-subscription").html("Go");

                    if (response.status == 400) {
                        var myJSON = response.responseJSON.message;
                        var html = '<br><div class="alert alert-warning"><strong> Whoops! </strong>' + myJSON + '.<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        $('#mailchimp-message').html(html);
                         $('#mailchimp-message').show();
                    } else {
                        emailInput.val('');
                        var myJSON = response.responseJSON.errors;
                        var html = '<br><button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<ul>';
                        for (var key in myJSON) {
                            html += '<li>' + myJSON[key][0] + '</li>';
                        }
                        html += '</ul></div>';
                        $('#mailchimp-message').html(html);
                        $('#mailchimp-message').show();
                    }
                }
            });
        });
    });

    function removeItem(id) {
        $.ajax({
            type: "post",
            data:{
                "id": id,
                "_token": "{!! csrf_token() !!}",
            },
            url: "{{url('cart/remove/')}}",
            success: function (data) {
                location.reload();
            }
        });
    }


    $(document).ready(function(){
        $('.createTenant').attr('disabled',true);
        $('#userdomain').keyup(function(){
            if($(this).val().length ==0 || $(this).val().length>28)
                $('.createTenant').attr('disabled', true);
            else
                $('.createTenant').attr('disabled',false);
        })
    });



    function firstlogin(id)
    {
        $('#createTenant').attr('disabled',true)
        $("#createTenant").html("<i class='fas fa-circle-notch fa-spin'></i>  Please Wait...");
        var domain = $('#userdomain').val();
        var password = $('#password').val();
        var product = $('input[name="option"]:checked').val();

        $.ajax({
            type: 'POST',
            data: {'id':id,'password': password,'domain' : domain,'product':product},
            url: "{{url('first-login')}}",
            success: function (data) {
                $('#createTenant').attr('disabled',false)
                $("#createTenant").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                if(data.status == 'validationFailure') {

                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                    for (var key in data.message)
                    {
                        html += '<li>' + data.message[key][0] + '</li>'
                    }
                    html += '</ul></div>';
                    $('#clouderror').show();
                    $('#cloudsuccess').hide();
                    document.getElementById('error').innerHTML = html;
                } else if(data.status == 'false') {
                    $('#clouderror').show();
                    $('#cloudsuccess').hide();
                    var result =  '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Whoops! </strong>Something went wrong!!<br><ul><li>'+data.message+'</li></ul></div>';
                    $('#clouderror').html(result);
                } else if(data.status == 'success_with_warning') {
                    console.log('here');
                    $('#clouderror').show();
                    $('#cloudsuccess').hide();
                    var result =  '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Whoops! </strong><br><ul><li>'+data.message+'</li></ul></div>';
                    $('#clouderror').html(result);
                } else {
                    $('#clouderror').hide();
                    $('#cloudsuccess').show();
                    var result =  '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success! </strong>'+data.message+'!</div>';
                    $('#cloudsuccess').html(result);
                }
            },error: function (response) {
                $('#createTenant').attr('disabled',false)
                $("#createTenant").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                $("#generate").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                if(response.status == 422) {

                    var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                    for (var key in response.responseJSON.errors)
                    {
                        html += '<li>' + response.responseJSON.errors[key][0] + '</li>'
                    }

                } else {
                    var html = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                    html += '<li>' + response.responseJSON.message + '</li>'
                }

                html += '</ul></div>';
                $('#clouderror').show();
                $('#cloudsuccess').hide();
                document.getElementById('error').innerHTML = html;

            }


        }) ;
    }


    $(document).on("click", ".open-createTenantDialog", function () {

        $('#tenant').modal('show');
    });
    $('.closebutton').on('click',function(){
        location.reload();
    });

    $(document).on("click", "#demo-req", function () {
        $('#demo-req').modal('show');
    });
    $('.closebutton').on('click',function(){
        location.reload();
    });

    const domainInput = document.getElementById("userdomain");
    const validationMessage = document.getElementById("validationMessage");

    domainInput.addEventListener("input", function() {
        const domain = domainInput.value;

        if (domain.length > 28) {
            validationMessage.textContent = "Domain must be 28 characters or less.";
            validationMessage.style.color = "red";
        } else {
            validationMessage.textContent = "";
            validationMessage.style.color = "";
        }
    });
</script>
@yield('script')

<!--Start of Tawk.to Script-->
<!--Start of Tawk.to Script-->
{!! $everyPageScripts !!}




@if(request()->path() !== 'my-profile' && request()->path() !== 'verify')

    <script type="text/javascript">
        var demotelInput = $('#mobilenumdemo'),
            errorMsgdemo = document.querySelector("#error-msgdemo"),
            validMsgdemo = document.querySelector("#valid-msgdemo"),
            addressDropdowndemo = $("#country");

        demotelInput.intlTelInput({
            geoIpLookup: function (callback) {
                $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
                    var countryCodedemo = (resp && resp.country) ? resp.country : "";
                    callback(countryCodedemo);
                });
            },
            initialCountry: "auto",
            separateDialCode: true,
            utilsScript: "{{asset('js/intl/js/utils.js')}}"
        });
        var resetdemo = function() {
            errorMsgdemo.innerHTML = "";
            errorMsgdemo.classList.add("hide");
            validMsgdemo.classList.add("hide");
        };

        $('.intl-tel-input').css('width', '100%');

        demotelInput.on('input blur', function () {
            resetdemo();
            if ($.trim(demotelInput.val())) {

                if (demotelInput.intlTelInput("isValidNumber")) {
                    $('#mobilenumdemo').css("border-color","");
                    $("#error-msgdemo").html('');
                    errorMsgdemo.classList.add("hide");
                    $('#demoregister').attr('disabled',false);
                } else {
                    errorMsgdemo.classList.remove("hide");
                    errorMsgdemo.innerHTML = "Please enter a valid number";
                    $('#mobilenumdemo').css("border-color","red");
                    $('#error-msgdemo').css({"color":"red","margin-top":"5px"});
                    $('#demoregister').attr('disabled',true);
                }
            }
        });
        $('input').on('focus', function () {
            $(this).parent().removeClass('has-error');
        });
        addressDropdowndemo.change(function() {
            demotelInput.intlTelInput("setCountry", $(this).val());
            if ($.trim(demotelInput.val())) {
                if (demotelInput.intlTelInput("isValidNumber")) {
                    $('#mobilenumdemo').css("border-color","");
                    $("#error-msgdemo").html('');
                    errorMsgdemo.classList.add("hide");
                    $('#demoregister').attr('disabled',false);
                } else {
                    errorMsgdemo.classList.remove("hide");
                    errorMsgdemo.innerHTML = "Please enter a valid number";
                    $('#mobilenumdemo').css("border-color","red");
                    $('#error-msgdemo').css({"color":"red","margin-top":"5px"});
                    $('#demoregister').attr('disabled',true);
                }
            }
        });

       $('form').on('submit', function (e) {
        var selectedCountry = demotelInput.intlTelInput('getSelectedCountryData');
        var countryCode = '+' + selectedCountry.dialCode;
        $('#mobile_code_hiddenDemo').val(countryCode);

        });

        $(document).ready(function() {
            $('#tenancy').on('shown.bs.modal', function () {
                $('#userdomainPurchase').focus();
            });
        });

        const domainInputPurchase = document.getElementById("userdomainPurchase");
        const validationMessagePurchase = document.getElementById("validationMessagePurchase");

        domainInputPurchase.addEventListener("input", function() {
            const domainName = domainInputPurchase.value;

            if (domainName.length > 28) {
                validationMessagePurchase.textContent = "Domain must be 28 characters or less.";
                validationMessagePurchase.style.color = "red";
            } else {
                validationMessagePurchase.textContent = "";
                validationMessagePurchase.style.color = "";
            }
        });
        $(document).ready(function(){
            $('.createtenancy').attr('disabled',true);
            $('#userdomainPurchase').keyup(function(){
                if($(this).val().length ==0 || $(this).val().length>28)
                    $('.createtenancy').attr('disabled', true);
                else
                    $('.createtenancy').attr('disabled',false);
            })
        });

        function createtenancy(){
            $('#createtenancy').attr('disabled',true)
            $("#createtenancy").html("<i class='fas fa-circle-notch fa-spin'></i> Please Wait...");
            var domain = $('#userdomainPurchase').val();
            var order = $('#orderId').val();
            $.ajax({
                url: "{{url('create/tenant/purchase')}}",
                type: "POST",
                data: {'domain': domain, 'id': order},
                success: function (data) {
                    $('#createtenancy').attr('disabled',false)
                    $("#createtenancy").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                    if(data.status == 'validationFailure') {

                        var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                        for (var key in data.message)
                        {
                            html += '<li>' + data.message[key][0] + '</li>'
                        }
                        html += '</ul></div>';
                        $('#error').show();
                        $('#success').hide();
                        document.getElementById('error').innerHTML = html;
                    } else if(data.status == 'false') {
                        $('#error').show();
                        $('#success').hide();
                        var result =  '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Whoops! </strong>Something went wrong!!<br><ul><li>'+data.message+'</li></ul></div>';
                        $('#error').html(result);
                    } else if(data.status == 'success_with_warning') {
                        console.log('here');
                        $('#error').show();
                        $('#success').hide();
                        var result =  '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Whoops! </strong><br><ul><li>'+data.message+'</li></ul></div>';
                        $('#error').html(result);
                    } else {
                        window.location.href = data.redirectTo;
                    }
                },error: function (response) {
                    $('#createtenancy').attr('disabled',false)
                    $("#createtenancy").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                    $("#generate").html("<i class='fa fa-check'>&nbsp;&nbsp;</i>Submit");
                    if(response.status == 422) {

                        var html = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                        for (var key in response.responseJSON.errors)
                        {
                            html += '<li>' + response.responseJSON.errors[key][0] + '</li>'
                        }

                    } else {
                        var html = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Whoops! </strong>Something went wrong<ul>';
                        html += '<li>' + response.responseJSON.message + '</li>'
                    }

                    html += '</ul></div>';
                    $('#error').show();
                    $('#success').hide();
                    document.getElementById('error').innerHTML = html;

                }

            })
        }
        $(document).ready(function () {

            // Check if the user is authenticated
            @auth
            // If authenticated, check if localStorage indicates a click
            var freeTrialClicked = localStorage.getItem('freeTrialClicked');
            if (freeTrialClicked === 'true') {
                // If localStorage indicates a click, open the free trial dialog
                openFreeTrialDialog();
                localStorage.removeItem('freeTrialClicked');
            }

            // Attach a click event handler to the "START FREE TRIAL" button
            $('.startFreeTrialBtn').on('click', function () {
                // If the button is clicked, open the free trial dialog
                openFreeTrialDialog();
            });
            @else
            // If not authenticated, redirect to the login/register page only if localStorage indicates a click
            var freeTrialClicked = localStorage.getItem('freeTrialClicked');
            // Attach a click event handler to the "START FREE TRIAL" button
            $('.startFreeTrialBtn').on('click', function () {
                // If not authenticated, remember that the button was clicked
                localStorage.setItem('freeTrialClicked', 'true');
                var message = "Please log in to start your free trial. If you don't have an account, you can register here!";
                var baseUrl = "{{ env('APP_URL') }}";

                // Redirect to the login/register page
                window.location.href =  `${baseUrl}/login?message=${encodeURIComponent(message)}`;
            });
            @endauth

            // Function to open the free trial dialog
            function openFreeTrialDialog() {
                // Check if the modal is already open (to prevent multiple opens)
                if (!$('#tenant').hasClass('show')) {
                    $('#tenant').modal('show');
                }
            }
        });


        setTimeout(function () {
            $(".alert-cloud").alert('close');
        }, 10000);
    </script>
@endif
<script>
    $(document).ready(function() {
        $.fn.modal.Constructor.Default.backdrop = 'static';
    });
</script>

<style>
    .custom-line {
        border: none;
        border-top: 1px solid #ccc;
        margin: 10px 0;
    }
    #validationMessage {
        position: absolute;
        top: 40px; /* Adjust this value to align the error message properly */
        left: 0;
        font-size: 12px;
        color: red;
    }



</style>
</body>
</html>
@yield('end')