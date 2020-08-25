<!DOCTYPE html>
<html>
<?php $setting = \App\Model\Common\Setting::where('id', 1)->first();
$script = \App\Model\Common\ChatScript::where('id', 1)->first(); 
if($script){
  $script = $script->script;
}
 ?>

<style type="text/css">
    {{-- for making datatable side scrollable whenever it has way too many columns for screen to accomodate   --}}
    .dataTables_wrapper {
        overflow-x: auto;
    }
</style>
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
  
          <!-- Mobile Metas -->
          <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
          <meta name="csrf-token" content="{{ csrf_token() }}" />
          <!-- Web Fonts  -->
          <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">


           <link rel="stylesheet" href="{{asset('client/css/bootstrap.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/fontawesome-all.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/font-awesome.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/animate.min.css')}}">
          <link rel="stylesheet" href="{{asset('client/css/magnific-popup.min.css')}}">

          <link rel="stylesheet" href="{{asset('client/porto/css/theme.css')}}">
          <link rel="stylesheet" href="{{asset('client/porto/css/theme-elements.css')}}">
        <link rel="stylesheet" href="{{asset('client/porto/css/theme-shop.css')}}">

           <link rel="stylesheet" href="{{asset('common/css/intlTelInput.css')}}">

        {{-- this can be customised to any skin available --}}
        <link rel="stylesheet" href="{{asset('client/porto/css/skins/default.css')}}">
        {{--  any custom css can be defined in this  --}}
        <link rel="stylesheet" href="{{asset('client/porto/css/custom.css')}}">

        <!-- Head Libs -->
        <script src="{{asset('client/js/modernizr.min.js')}}"></script>

        <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
        <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>

   </head>
   <style>
     .alert {
      font-weight:bolder;
     }
   </style>
    <body>

        <?php
        $domain = [];
        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);
        ?>
        <div class="body">
            <header id="header" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 45, 'stickySetTop': '-45px', 'stickyChangeLogo': true}">
            <div class="header-body"  style="top: -50px;">
                    <div class="header-container container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-row">
                                    <div class="header-logo">
                                        <a href="{{Auth::check() ? url('my-invoices') : url('login')}}">
                                            <img alt="Logo" width="100" height="51" data-sticky-width="70" data-sticky-height="36" data-sticky-top="30" src="{{asset('common/images/'.$setting->logo)}}">
                                        </a>
                                    </div>
                              </div>
                            </div>
                             <div class="header-column justify-content-end">
                                <div class="header-row pt-3">
                                    <nav class="header-nav-top">
                                        <ul class="nav nav-pills">

                                            @if($set->company_email != NULL)
                                                <li class="nav-item nav-item-anim-icon d-none d-md-block">
                                                    <span class="ws-nowrap"><i class="fas fa-envelope"></i>
                                                        <a style="color: inherit" href="mailto:{{$set->company_email}}">{{$set->company_email}}</a>
                                                    </span>
                                                </li>
                                            @endif
                                            @if($set->phone != NULL)
                                                <li class="nav-item nav-item-left-border nav-item-left-border-remove nav-item-left-border-md-show">
                                                    <span class="ws-nowrap"><i class="fas fa-phone"></i>
                                                        <a style="color: inherit" href="tel:{{$set->phone}}">{{$set->phone}}</a>
                                                    </span>
                                                </li>
                                            @endif

                                        </ul>
                                    </nav>
                                </div>


                                  <div class="header-row">
                                    <div class="header-nav pt-1" style="margin-top: 0px; margin-bottom: -10px;">

                                        <button class="btn btn-sm header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main nav">
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
                                                        Store&nbsp;
                                                      </a>
                                                      <ul class="dropdown-menu">
                                                        @if(count($groups)>0)
                                                            @foreach($groups as $group)
                                                            <li>
                                                                <a class="dropdown-item" href="{{url('group/'.$group->pricing_templates_id.'/'.$group->id)}}">
                                                                    {{$group->name}}
                                                                </a>
                                                            </li>
                                                            @endforeach
                                                        @else
                                                         <li>
                                                             <a class="dropdown-item">
                                                                 No Groups Added
                                                             </a>
                                                         </li>
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
                                                        <a  class="nav-link"  href="{{url('login')}}">
                                                            Login
                                                        </a>
                                                    </li>

                                                    @else
                                                    <li class="dropdown">
                                                        <a class="dropdown-item dropdown-toggle" href="#">
                                                            My Account
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                          @if(Auth::user()->role == 'admin')
                                                          <li><a class="dropdown-item" href="{{url('/')}}">Go to Admin Panel</a></li>
                                                          @endif
                                                            <li><a class="dropdown-item" href="{{url('my-orders')}}">My Orders</a></li>
                                                            <li><a class="dropdown-item" href="{{url('my-invoices')}}">My Invoices</a></li>
                                                            <li><a class="dropdown-item" href="{{url('my-profile')}}">My Profile</a></li>
                                                            <li><a class="dropdown-item" href="{{url('auth/logout')}}">Logout</a></li>
                                                        </ul>
                                                    </li>
                                                    @endif

                                                    <li class="dropdown dropdown-mega dropdown-mega-shop" id="headerShop">
                                                        <a class="dropdown-item dropdown-toggle" href="{{url('show/cart')}}">
                                                            <i class="fa fa-shopping-cart"></i> Cart ({{Cart::getTotalQuantity()}})
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
                                                                            $currency =  $currency['currency'];
                                                                          ?>
                                                                            <tr>

                                                                                <td class="product-thumbnail">
                                                                                    <img width="100" height="100" alt="{{$product->name}}" class="img-responsive" src="{{$product->image}}">
                                                                                </td>

                                                                                <td class="product-name">
                                                                               <?php
                                                                                $total = \App\Http\Controllers\Front\CartController::rounding($item->getPriceSumWithConditions())
                                                                                ?>
                                                                                    <a>{{$item->name}}<br><span class="amount"><strong>{{currencyFormat($total,$code = $currency)}}</strong></span></a>
                                                                                </td>

                                                                                <td class="product-actions">
                                                                                    <a title="Remove this item" class="remove" href="#" onclick="removeItem('{{$item->id}}');">
                                                                                      <!--  @if(Session::has('items'))
                                                                                       {{Session::forget('items')}}
                                                                                       @endif -->
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

                                        <ul class="header-social-icons social-icons d-none d-sm-block">
                                            @php
                                                $social = App\Model\Common\SocialMedia::get();
                                            @endphp
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

                    <section class="page-header page-header-modern bg-color-light-scale-1 page-header-md">
                    <div class="container">
                         <div class="row">
                            <div class="col-md-12 align-self-center p-static order-2 text-center">
                                <h1 class="text-dark text-dark">
                                    <strong>
                                        @yield('page-heading')
                                    </strong>
                                </h1>
                            </div>
                            <div class="col-md-12 align-self-center order-1">
                              <ul class="breadcrumb d-block text-center" style="font-weight: initial;">
                                    @yield('breadcrumb')
                              </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="container">

                    @if(Session::has('warning'))
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{Session::get('warning')}}
                    </div>
                    @endif
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
                    <strong><i class="fas fa-exclamation-triangle"></i>Oh snap!</strong>
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
                      
                    @include('themes.default1.front.domain')
                    @yield('content')

                </div>

            </div>
          
            <footer id="footer">
              
                <div class="container">

                    <div class="footer-ribbon"><span>Get in Touch</span></div>
                     
                    <div class="row py-5 my-4">
                         <?php
                       $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer1')->select('name','content','allow_tweets','allow_mailchimp')->first();
                          if ($widgets) {
                              $tweetDetails = $widgets->allow_tweets ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                           }
                            $mailchimpKey = \App\Model\Common\Mailchimp\MailchimpSetting::find(1);
                            ?>
                           @if($widgets != null)
                                 @component('mini_views.footer_widget', ['title'=> $widgets->name, 'colClass'=>"col-md-6 col-lg-4 mb-4 mb-lg-0"])
                                     <p class="pr-1"> {!! $widgets->content !!}</p>
                                     {!! $tweetDetails !!}

                                     <div id="mailchimp-message"></div>

                                    
                                     <div class="alert alert-danger d-none" id="newsletterError"></div>
                                     @if($mailchimpKey != null && $widgets->allow_mailchimp ==1)
                                         <div class="input-group input-group-rounded">
                                             <input class="form-control form-control-sm" placeholder="Email Address" name="email" id="newsletterEmail" type="text">
                                             <span class="input-group-append">
                                    <button class="btn btn-light text-color-dark" id="mailchimp-subscription" type="submit"><strong>Go!</strong></button>
                                </span>
                                         </div>
                                     @endif
                                 @endcomponent
                            @endif
                          <?php

                          $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer2')->select('name','content','allow_tweets','allow_mailchimp')->first();
                          if ($widgets) {
                           $tweetDetails =  $widgets->allow_tweets ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                          }
                            ?>
                            @if($widgets != null)
                                 @component('mini_views.footer_widget', ['title'=> $widgets->name])
                                     {!! $tweetDetails !!}
                                 @endcomponent
                            @endif
                        <?php
                         $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer3')->select('name','content','allow_tweets','allow_mailchimp')->first();
                        if ($widgets) {
                           $tweetDetails = $widgets->allow_tweets   ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                        }


                            ?>
                       @if($widgets != null)
                            @component('mini_views.footer_widget', ['title'=> $widgets->name, 'ulClass'=>'list list-icons list-icons-lg'])

                                 @if($set->company_email != NULL)
                                     <li class="mb-1">
                                         <i class="fas fa-envelope"></i>
                                         <p class="m-0">
                                             <a style="color: inherit" href="mailto:{{$set->company_email}}">{{$set->company_email}}</a>
                                         </p>
                                     </li>
                                 @endif
                                 @if($set->phone != NULL)
                                     <li class="mb-1">
                                         <i class="fas fa-phone"></i>
                                         <p class="m-0">
                                             <a style="color: inherit" href="tel:{{$set->phone}}">{{$set->phone}}</a>
                                         </p>
                                     </li>
                                 @endif
                                 @if($set->address != NULL)
                                     <li class="mb-1">
                                         <i class="fa fa-map-marker"></i>
                                         <p class="m-0">{{$set->address}}</p>
                                     </li>
                                 @endif
                            @endcomponent
                        @endif

                         <?php

                         $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer4')->select('name','content','allow_tweets','allow_mailchimp')->first();
                         if ($widgets) {
                          $tweetDetails = $widgets->allow_tweets   ==1 ?  '<div id="tweets" class="twitter" >
                            </div>' : '';
                          }
                            ?>

                      @if($widgets != null)
                        @component('mini_views.footer_widget', ['title'=> $widgets->name, 'ulClass'=> 'social-icons', 'colClass'=>'col-md-6 col-lg-2'])
                            @foreach($social as $media)
                                <li class="{{$media->class}}"><a href="{{$media->link}}" target="_blank" title="{{ucfirst($media->name)}}"><i class="{{$media->fa_class}}"></i></a></li>
                            @endforeach
                        @endcomponent
                      @endif

                </div>
                </div>

                <div class="footer-copyright">
                    <div class="container py-2">
                        <div class="row py-4">
                            <div class="col-md-12 align-items-center justify-content-center justify-content-lg-start mb-2 mb-lg-0">
                              <p>Copyright © <?php echo date('Y') ?> · <a href="{{$set->website}}" target="_blank">{{$set->company}}</a>. All Rights Reserved.Powered by
                                    <a href="https://www.ladybirdweb.com/" target="_blank"><img src="{{asset('common/images/Ladybird1.png')}}" alt="Ladybird"></a></p>
                            </div>

                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Vendor -->
        <script src="{{asset('client/js/jquery.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.appear.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.easing.min.js')}}"></script>
          <script src="{{asset('client/js/jquery-cookie.min.js')}}"></script>
          <script src="{{asset('client/js/popper.min.js')}}"></script>
          <script src="{{asset('client/js/bootstrap.min.js')}}"></script>
          <script src="{{asset('client/js/common.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.validation.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.easy-pie-chart.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.gmap.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.lazyload.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.isotope.min.js')}}"></script>
          <script src="{{asset('client/js/owl.carousel.min.js')}}"></script>
          <script src="{{asset('client/js/jquery.magnific-popup.min.js')}}"></script>
          <script src="{{asset('client/js/vide.min.js')}}"></script>

         <!-- Theme Base, Components and Settings -->
          <script src="{{asset('client/porto/js/theme.js')}}"></script>

          <!-- any custom js/effects can be defined in this -->
          <script src="{{asset('client/porto/js/custom.js')}}"></script>
          
          <!-- Theme Initialization Files -->
          <script src="{{asset('client/porto/js/theme.init.js')}}"></script>
          <script src="{{asset('common/js/intlTelInput.js')}}"></script>
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

    $('#mailchimp-subscription').click(function(){
      var email = $('#newsletterEmail').val();
      $('#mailchimp-subscription').html("<i class='fa fa-circle-o-notch fa-spin fa-1x fa-fw'></i>Please Wait...");
      $.ajax({
        type: 'POST',
        url: '{{url("mail-chimp/subcribe")}}',
        data: {'email' : email,'_token': "{!! csrf_token() !!}"},
        success: function(data){
          $("#mailchimp-subscription").html("Go");
           $('#mailchimp-message').show();
            var result =  '<br><div class="alert alert-success "><strong><i class="fa fa-check"></i> Success! </strong>'+data.message+'.</div>';
            $('#mailchimp-message').show();
            $('#mailchimp-message').html(result+ ".");
              setInterval(function(){ 
                $('#mailchimp-message').slideUp(5000); 

            }, 2000);
        },
         error: function(response) {
          if(response.status == 400) {
            var myJSON = response.responseJSON.message
              $("#mailchimp-subscription").html("Go");
               var html =  '<br><div class="alert alert-warning"><strong> Whoops! </strong>'+myJSON+'.</div>';
                $('#mailchimp-message').show();
                document.getElementById('mailchimp-message').innerHTML = html;
                 setInterval(function(){ 
                $('#mailchimp-message').slideUp(5000); 

                 }, 2000);
               } else {
                  var myJSON = response.responseJSON.errors;
                      $("#mailchimp-subscription").html("Go");
                        var html = '<br><div class="alert alert-danger"><strong>Whoops! </strong>Something went wrong<ul>';
                                  for (var key in myJSON)
                                  {
                                      html += '<li>' + myJSON[key][0] + '</li>'
                                  }
                                 html += '</ul></div>';

                        $('#mailchimp-message').show();
                        document.getElementById('mailchimp-message').innerHTML = html;
                         setInterval(function(){ 
                        $('#mailchimp-message').slideUp(5000); 

                 }, 1000);
               }
       
        }
      })
    })     
    
   $.ajax({
    type: 'GET',
     url: "{{route('twitter')}}",
     dataType: "html",
       success: function (returnHTML) {
             $('#tweets').html(returnHTML);
              
          }
    });

     function removeItem(id) {
            $.ajax({
              type: "POST",
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


        </script>
        @yield('script')
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
<!--Start of Tawk.to Script-->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
 {!! html_entity_decode($script) !!}

</script>

<!--End of Tawk.to Script-->
<!--End of Tawk.to Script-->

<!--  -->

    </body>
</html>
@yield('end')
