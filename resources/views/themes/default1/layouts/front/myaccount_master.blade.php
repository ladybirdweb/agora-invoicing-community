<!DOCTYPE html>
<?php $setting = \App\Model\Common\Setting::where('id', 1)->first();
$script = \App\Model\Common\ChatScript::where('id', 1)->first(); 
if($script){
  $script = $script->script;
}else{
  $script = null;
}
 ?>
<html>

    <head>
  
          <!-- Basic -->
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  
            <title>@yield('title') | {{$setting->favicon_title_client}}</title>
  
          <meta name="keywords" content="HTML5 Template" />
          <meta name="description" content="Register, signup here to start using Faveo Helpdesk or signin to your existing account">
          <meta name="author" content="okler.net">
  
          <!-- Favicon -->
         <link rel="shortcut icon" href='{{asset("common/images/$setting->fav_icon")}}' type="image/x-icon" />
          <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
  
          <!-- Mobile Metas -->
          <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
  
          <!-- Web Fonts  -->
          <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
  

            <link rel="stylesheet" href="{{asset('client/css/bootstrap.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/fontawesome-all.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/font-awesome.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/animate.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/simple-line-icons.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/owl.carousel.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/owl.theme.default.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/magnific-popup.min.css')}}">
          <!-- Theme CSS -->
          <link rel="stylesheet" href="{{asset('client/css/theme.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/theme-elements.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/theme-blog.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/theme-shop.css')}}">

          
          <!-- Demo CSS -->
            <link rel="stylesheet" href="{{asset('client/css/demo-construction.css')}}">
  
          <!-- Skin CSS -->
           <link rel="stylesheet" href="{{asset('client/css/skin-construction.css')}}"> 
            <link rel="stylesheet" href="{{asset('common/css/intlTelInput.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/default.css')}}">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

  
          <!-- Theme Custom CSS -->
          <link rel="stylesheet" href="{{asset('client/css/custom.css')}}">
  
          <!-- Head Libs -->
          <script src="{{asset('client/js/modernizr.min.js')}}"></script>

            <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
        <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
         
    
  
      </head>
      <style>
        .table {
          font-weight: bolder;
        }
      </style>

    <body>
         <?php 
        $domain=[];
        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);
        ?>

        <div class="body">
            <header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 55, 'stickySetTop': '-55px', 'stickyChangeLogo': true}" style="margin-bottom:-4px;">
        <div class="header-body">
                <div class="header-body">
                    <div class="header-container container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-row">
                                <div class="header-logo">
                                     @if(Auth::check())
                                    <a href="{{url('my-invoices')}}">
                                      @else
                                      <a href="{{url('login')}}">
                                        @endif
                                        <img alt="Logo" width="111" height="54" data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('common/images/'.$setting->logo)}}">
                                    </a>
                                </div>
                              </div>
                            </div>
                             <div class="header-column justify-content-end">
                                <div class="header-row pt-3">
                                     <nav class="header-nav-top">
                                          <ul class="nav nav-pills">
                                            @if($set->company_email != NULL)
                                              <li class="nav-item d-none d-sm-block">
                                                  <a class="nav-link" href="mailto:{{$set->company_email}}"><i class="fas fa-envelope"></i> {{$set->company_email}}</a>
                                              </li>
                                              @endif
                                              @if($set->phone != NULL)
                                              <li class="nav-item">
                                                  <span class="ws-nowrap"><i class="fas fa-phone"></i>{{$set->phone}}</span>
                                              </li>
                                              @endif
                                          </ul>
                                      </nav>
                                    <!--                                    <nav class="header-nav-top">
                                                                            <ul class="nav nav-pills">
                                                                                <li class="hidden-xs">
                                                                                    <a href="about-us.html"><i class="fa fa-angle-right"></i> About Us</a>
                                                                                </li>
                                                                                <li class="hidden-xs">
                                                                                    <a href="contact-us.html"><i class="fa fa-angle-right"></i> Contact Us</a>
                                                                                </li>
                                                                                <li>
                                                                                    <span class="ws-nowrap"><i class="fa fa-phone"></i> (123) 456-789</span>
                                                                                </li>
                                                                            </ul>
                                                                        </nav>-->
                                </div>
                                <div class="header-row">
                                    <div class="header-nav">
                                        <button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
                                            <i class="fa fa-bars"></i>
                                        </button>
                                        
                                       <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1">
                                            <nav class="collapse">
                                                <ul class="nav nav-pills" id="mainNav">
                                                      <?php 
                                                $groups = \App\Model\Product\ProductGroup::where('hidden','!=', 1)->get();
                                              ?>
                                                    
                                                     <li class="dropdown">
                                                      <a class="dropdown-item dropdown-toggle" href="#">
                                                        Store
                                                      </a>
                                                      <ul class="dropdown-menu">
                                                        @if(count($groups)>0)
                                                        @foreach($groups as $group)
                                                        
                                                        <li><a class="dropdown-item" href="{{url('group/'.$group->pricing_templates_id.'/'.$group->id)}}">{{$group->name}}</a></li>
                                                        @endforeach
                                                        @else
                                                         <li><a class="dropdown-item">No Groups Added</a></li>
                                                         @endif
                                                      </ul>
                                                    </li>
                                                   

                                                    <?php $pages = \App\Model\Front\FrontendPage::where('publish', 1)->orderBy('created_at','asc')->get(); ?>
                                                    @foreach($pages as $page)
                                                    <li class="dropdown">

                                                        @if($page->parent_page_id==0)
                                                        <?php
                                                        $ifdrop = \App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->count();
                                                        if ($ifdrop > 0) {
                                                            $class = 'nav-link dropdown-toggle';
                                                        } else {
                                                            $class = 'nav-link';
                                                        }
                                                        ?>
                                                        @if($page->type == 'contactus')
                                                         <a class="nav-link" href="{{url('contact-us')}}">
                                                        @else
                                                        <a class="{{$class}}" href="{{$page->url}}">
                                                          @endif
                                                            {{ucfirst($page->name)}}
                                                        </a>
                                                        @endif
                                                        @if(\App\Model\Front\FrontendPage::where('publish',1)->where('parent_page_id',$page->id)->count()>0)


                                                        <?php $childs = \App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->get(); // dd($childs); ?>
                                                        <ul class="dropdown-menu">

                                                            @foreach($childs as $child)
                                                            <li>
                                                                <a href="{{$child->url}}">
                                                                    {{ucfirst($child->name)}}
                                                                </a>
                                                            </li>

                                                            @endforeach 




                                                        </ul>
                                                        @endif

                                                    </li>
                                                    @endforeach



                                                    @if(!Auth::user())
                                                    <li class="dropdown">
                                                        <a  class="nav-link"  href="{{url('auth/login')}}">
                                                            Login
                                                        </a>
                                                    </li>

                                                    @else 
                                                    <li class="dropdown">
                                                        <a class="nav-link" class="dropdown-toggle" href="#">
                                                            {{Auth::user()->first_name}}
                                                            &nbsp;<i class="fas fa-caret-down"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            @if(Auth::user()->role=='admin')
                                                            <li><a class="nav-link" href="{{url('/')}}">My Account</a></li>
                                                            @else 
                                                            <li><a class="nav-link" href="{{url('my-invoices')}}">My Account</a></li>
                                                            @endif
                                                            <li><a class="nav-link" href="{{url('auth/logout')}}">Logout</a></li>
                                                        </ul>
                                                    </li>
                                                    @endif

                                                    <li class="dropdown dropdown-mega dropdown-mega-shop" id="headerShop">
                                                        <a class="dropdown-item dropdown-toggle" href="{{url('show/cart')}}">
                                                            <i class="fa fa-user mr-1"></i> Cart ({{Cart::getTotalQuantity()}})
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <div class="dropdown-mega-content">
                                                                    <table class="cart">
                                                                        <tbody>
                                                                            @forelse(Cart::getContent() as $key=>$item)
                                                                            <?php
                                                                            $product = App\Model\Product\Product::where('id', $item->id)->first();
                                                                            if ($product->require_domain == 1) {
                                                                                $domain[$key] = $item->id;
                                                                            }
                                                                            $cart_controller = new \App\Http\Controllers\Front\CartController();
                                                                            $currency = $cart_controller->currency();
                                                                            ?>
                                                                            <tr>

                                                                                <td class="product-thumbnail">
                                                                                    <img width="100" height="100" alt="{{$product->name}}" class="img-responsive" src="{{$product->image}}">
                                                                                </td>

                                                                                <td class="product-name">

                                                                                  <?php
                                                                                  $controller = new App\Http\Controllers\Front\CartController();

                                                                                  $price = $controller->rounding($item->getPriceSumWithConditions());

                                                                                  ?>
                                                                                    <a>{{$item->name}}<br><span class="amount"><strong>{{currency_format($price,$code = $currency['currency'])}}</strong></span></a>

                                                                                </td>

                                                                                <td class="product-actions">
                                                                                    <a title="Remove this item" class="remove" href="#" onclick="removeItem('{{$item->id}}');">
                                                                                        <i class="fa fa-times"></i>
                                                                                    </a>
                                                                                </td>

                                                                            </tr>
                                                                            @empty 

                                                                            <tr>
                                                                               <td>
                                                                                @if(Auth::check())
                                                                               
                                                                              <a href="{{url('my-invoices')}}">Choose a Product
                                                                                @else
                                                                                <a href="{{url('login')}}">Choose a Product
                                                                                  @endif
                                                                                  </a></td>
                                                                            </tr>


                                                                            @endforelse


                                                                            @if(!Cart::isEmpty())
                                                                            <tr>
                                                                                <td class="actions" colspan="6">
                                                                                    <div class="actions-continue">
                                                                                        <a href="{{url('show/cart')}}"><button class="btn btn-default pull-left">View Cart</button></a>


                                                                                        @if(count($domain)>0)
                                                                                        <a href="#domain" data-toggle="modal" data-target="#domain"><button class="btn btn-primary pull-right">Proceed to Checkout</button></a>
                                                                                        @else
                                                                                        <a href="{{url('checkout')}}"><button class="btn btn-primary pull-right">Proceed to Checkout</button></a>
                                                                                        @endif
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </li>


                                                </ul>
                                            </nav>

                                        </div>
                                        <ul class="header-social-icons social-icons hidden-xs">
                                            <?php
                                            $social = App\Model\Common\SocialMedia::get();
                                            ?>
                                            @foreach($social as $media)
                                            <li class="{{$media->class}}"><a href="{{$media->link}}" target="_blank" title="{{ucfirst($media->name)}}"><i class="{{$media->fa_class}}"></i></a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div role="main" class=@yield('main-class')>

               <section class="page-header page-header-light page-header-more-padding">
                    <div class="container">
                         <div class="row align-items-center">
                            <div class="col-lg-6">
                              
                                    @yield('page-heading')
                                    <!--<li><a href="#">Home</a></li>
                                    <li class="active">Pages</li>-->
                               
                            </div>
                            <div class="col-lg-6">
                                  <ul class="breadcrumb">
                                        @yield('breadcrumb')
                                  </ul>
                              </div>
                        </div>
                       <!--  <div class="row">
                            <div class="col-md-12">
                                <h1>@yield('page-heading')</h1>
                            </div>
                        </div> -->
                    </div>
                </section>
                @if(Session::has('warning'))
                    <div class="alert alert-warning alert-dismissable">
                        
    
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{Session::get('warning')}}
                    </div>
                    @endif
                <div class="container">
                    @if(Session::has('success'))
                <div class="alert alert-success">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong><i class="far fa-thumbs-up"></i> Well done!</strong>
                   
                    {!!Session::get('success')!!}
                </div>
                @endif
                <!-- fail message -->
                @if(Session::has('fails'))
                 <div class="alert alert-danger alert-dismissable" role="alert">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.
                   {{Lang::get('message.alert')}}! {{Lang::get('message.failed')}}.
                  
                    {{Session::get('fails')}}
                </div>
                @endif
                    @if (count($errors) > 0)
                     <div class="alert alert-danger alert-dismissable" role="alert">
                                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong> Change a few things up and try submitting again.

                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                    @endif
                    <div class="row">
                       <div class="col-lg-3 order-2 order-lg-1">
                            <aside class="sidebar">
                       <!--  <div class="tabs tabs-vertical tabs-left tabs-navigation">
                          <ul class="nav nav-tabs col-sm-3">
                            <li class="@yield('nav-orders')">
                              <a class="nav-link" href="{{url('my-orders')}}" data-toggle="tab"><i class="fas fa-users"></i> My Orders</a>
                            </li>
                            <li class="@yield('nav-invoice')">
                              <a class="nav-link" href="{{url('my-invoices')}}" data-toggle="tab"><i class="fas fa-file"></i> My Invoices</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#tabsNavigation3" data-toggle="tab"><i class="fab fa-google-plus-g"></i> 500+ Google Fonts</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#tabsNavigation4" data-toggle="tab"><i class="fas fa-adjust"></i> Colors</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#tabsNavigation5" data-toggle="tab"><i class="fas fa-film"></i> Sliders</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#tabsNavigation6" data-toggle="tab"><i class="fas fa-user"></i> Icons</a>
                            </li>
                          </ul>
                        </div> -->
                                
                                <ul class="nav nav-list flex-column mb-4 sort-source" data-sort-id="portfolio" data-option-key="filter" data-plugin-options="{'layoutMode': 'fitRows', 'filter': '*'}">
                                    <li class="@yield('nav-orders')"><a class="nav-link" href="{{url('my-orders')}}">My Orders</a></li>

                                    <li class="@yield('nav-invoice')"><a class="nav-link" href="{{url('my-invoices')}}">My Invoices</a></li>
                                    <li class="@yield('nav-profile')"><a class="nav-link" href="{{url('my-profile')}}">Profile</a></li>
                                    <!-- <li class="@yield('nav-profile')"><a class="nav-link" href="https://support.faveohelpdesk.com/category-list/installation-and-upgrade-guide" target="_blank">Faveo Installation Instructions</a></li> -->
                                    <li><a class="nav-link" href="{{url('auth/logout')}}">Logout</a></li>
                                </ul>

                                <hr class="invisible mt-5 mb-2">


                               
                            </aside>
                        </div>

                        <div class="col-lg-9 order-1 order-lg-2">

                            @include('themes.default1.front.domain')
                            @yield('content')

                        </div>
                    </div>



                </div>

            </div>

                     <footer id="footer" style="margin-top:20px;">
                <div class="container">
                    <div class="row">
                         <?php
                         
                        
                          $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer1')->select('name','content','allow_tweets','allow_mailchimp')->first(); 
                          if ($widgets) {
                            $tweetDetails = $widgets->allow_tweets ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                           }
                            $mailchimpKey = \App\Model\Common\Mailchimp\MailchimpSetting::find(1);
                            ?>
                           @if($widgets != null)
                        <div class="col-md-3">
                           
                          <div class="newsletter">
                                <h4>{{ucfirst($widgets->name)}}</h4>
                                <p> {!! $widgets->content !!}</p>
                                  {!! $tweetDetails !!}
                                <div class="alert alert-success d-none" id="newsletterSuccess">
                                    <strong>Success!</strong> You've been added to our email list.
                                </div>
                                <div class="alert alert-danger d-none" id="newsletterError"></div>
                                @if($mailchimpKey != null && $widgets->allow_mailchimp ==1)
                                {!! Form::open(['url'=>'mail-chimp/subcribe','method'=>'GET']) !!}
                                <div class="input-group">
                                    <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go!</button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                               
                                @endif
                            </div>
                        </div>
                        @endif
                          <?php 
                       
                          $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer2')->select('name','content','allow_tweets','allow_mailchimp')->first(); 
                          if ($widgets) {
                           $tweetDetails =  $widgets->allow_tweets ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                          }
                            ?>
                           @if($widgets != null)
                        <div class="col-md-3">
                            <h4>{{ucfirst($widgets->name)}}</h4>
                             <p> {!! $widgets->content !!}</p>
                               {!! $tweetDetails !!}  
                                   <div class="alert alert-success d-none" id="newsletterSuccess">
                                    <strong>Success!</strong> You've been added to our email list.
                                </div>
                                <div class="alert alert-danger d-none" id="newsletterError"></div>
                                @if($mailchimpKey != null && $widgets->allow_mailchimp ==1)
                                {!! Form::open(['url'=>'mail-chimp/subcribe','method'=>'GET']) !!}
                                <div class="input-group">
                                    <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go!</button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                                 @endif
                        </div>
                        
                        @endif
                        <?php
                         $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer3')->select('name','content','allow_tweets','allow_mailchimp')->first(); 
                        if ($widgets) {
                           $tweetDetails = $widgets->allow_tweets   ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                        }

                        
                            ?>
                       @if($widgets != null)
                        <div class="col-md-3">
                            <div class="contact-details">
                                <h4>{{ucfirst($widgets->name)}}</h4>
                                {!! $widgets->content !!}
                                
                                 {!! $tweetDetails !!}
                                 <div class="alert alert-success d-none" id="newsletterSuccess">
                                    <strong>Success!</strong> You've been added to our email list.
                                </div>
                                <div class="alert alert-danger d-none" id="newsletterError"></div>
                                  @if($mailchimpKey != null && $widgets->allow_mailchimp ==1)
                                {!! Form::open(['url'=>'mail-chimp/subcribe','method'=>'GET']) !!}
                                <div class="input-group">
                                    <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go!</button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                                 @endif
                            </div>
                        </div>
                        
                         @endif
                         <?php 
                        
                         $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer4')->select('name','content','allow_tweets','allow_mailchimp')->first();
                         if ($widgets) {
                          $tweetDetails = $widgets->allow_tweets   ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                          }
                            ?>
                         
                        <div class="col-md-2">
                          @if($widgets != null)
                            <h4>{{ucfirst($widgets->name)}}</h4>
                             <p> {!! $widgets->content !!}</p>
                               <p>{!! $tweetDetails !!}   </p>
                               <div class="alert alert-success d-none" id="newsletterSuccess">
                                    <strong>Success!</strong> You've been added to our email list.
                                </div>
                                <div class="alert alert-danger d-none" id="newsletterError"></div>
                                @if($mailchimpKey != null && $widgets->allow_mailchimp ==1)
                                {!! Form::open(['url'=>'mail-chimp/subcribe','method'=>'GET']) !!}
                                <div class="input-group">
                                    <input class="form-control" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Go!</button>
                                    </span>
                                </div>
                                {!! Form::close() !!}
                                 @endif
                           
                           
                          @endif
                            <br>
                       
                      <!--   <a href="https://secure.comodo.com/ttb_searcher/trustlogo?v_querytype=W&v_shortname=CL1&v_search=https://www.billing.faveohelpdesk.com/&x=6&y=5"><img class="img-responsive" alt="" title="" src="https://www.faveohelpdesk.com/wp-content/uploads/2017/07/comodo_secure_seal_113x59_transp.png" /></a>
                        <br/> <br/>
                        <a href="https://monitor203.sucuri.net/m/verify/?r=ce48118f19b0feaecb9d46ac593fd041b2a8e31e15"><img class="img-responsive" alt="" title="" src="https://www.faveohelpdesk.com/wp-content/uploads/2017/07/index.gif" /></a> -->
                  </div>
                  
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        <div class="row">


                            <div class="col-md-12">
                                <p>Copyright © <?php echo date('Y') ?> · <a href="{{$set->website}}" target="_blank">{{$set->company}}</a>. All Rights Reserved.Powered by 
                                    <a href="https://www.ladybirdweb.com/" target="_blank"><img src="{{asset('common/images/Ladybird1.png')}}" alt="Ladybird"></a></p>
                            </div>


                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Vendor -->
         <!-- <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script> -->
         <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

          <script src="{{asset('client/js/jquery.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.appear.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.easing.min.js')}}"></script>
          <script src="{{asset('client/js/jquery-cookie.min.js')}}"></script>
          <script src="{{asset('client/js/popper.min.js')}}"></script>
          <script src="{{asset('client/js/bootstrap.min.js')}}"></script>
          <script src="{{asset('client/js/common.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.stellar.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.validation.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.easy-pie-chart.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.gmap.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.lazyload.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.isotope.min.js')}}"></script>
          <script src="{{asset('client/js/owl.carousel.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.magnific-popup.min.js')}}"></script>
          <script src="{{asset('client/js/vide.min.js')}}"></script>
          <!-- Theme Base, Components and Settings -->
          <script src="{{asset('client/js/theme.js')}}"></script>
          <!-- Theme Custom -->
          <script src="{{asset('client/js/custom.js')}}"></script>
          
          <!-- Theme Initialization Files -->
          <script src="{{asset('client/js/theme.init.js')}}"></script>
          <script src="{{asset('common/js/intlTelInput.js')}}"></script>
           <script src="{{asset('client/js/theme1.init.js')}}"></script>
       
        
        <script>
            function removeItem(id) {

            $.ajax({
            type: "GET",
                    data:"id=" + id,
                    url: "{{url('cart/remove/')}}",
                    success: function (data) {
                    location.reload();
                    }
            });
            }
$.ajax({
    type: 'GET',
    dataType: "html",
    url: '{{url('twitter')}}',
    success: function (returnHTML) {
        $('#tweets').html(returnHTML);
    }
});
        </script>
<script type="text/javascript">
 {!! html_entity_decode($script) !!}

</script>
        <!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
        <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        
                ga('create', 'UA-12345678-1', 'auto');
                ga('send', 'pageview');
        </script>
        -->

    </body>
</html>
