<?php $setting = \App\Model\Common\Setting::where('id', 1)->first(); ?>
<!DOCTYPE html>
<html>
    <head>

        <!-- Basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">	

        <title>
            @yield('title')
        </title>	

        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="Porto - Responsive HTML5 Template">
        <meta name="author" content="okler.net">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{asset('cart/img/favicon.ico')}}" type="image/x-icon" />
        <link rel="apple-touch-icon" href="{{asset('cart/img/apple-touch-icon.png')}}">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="{{asset('cart/vendor/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/simple-line-icons/css/simple-line-icons.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/owl.carousel/assets/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/owl.carousel/assets/owl.theme.default.min.css')}}">
        <link rel="stylesheet" href="{{asset('cart/vendor/magnific-popup/magnific-popup.min.css')}}">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/theme.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-elements.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-blog.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-shop.css')}}">
        <link rel="stylesheet" href="{{asset('cart/css/theme-animate.css')}}">

        <!-- Skin CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/skins/default.css')}}">

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{asset('cart/css/custom.css')}}">

        <!-- Head Libs -->
        <script src="{{asset('cart/vendor/modernizr/modernizr.min.js')}}"></script>

    </head>
    <body>

        <div class="body">
            <header id="header" data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 57, "stickySetTop": "-57px", "stickyChangeLogo": true}'>
                <div class="header-body">
                    <div class="header-container container">
                        <div class="header-row">
                            <div class="header-column">
                                <div class="header-logo">
                                    <a href="{{url('home')}}">
                                        <img alt="Porto" width="111" height="54" data-sticky-width="82" data-sticky-height="40" data-sticky-top="33" src="{{asset('cart/img/logo/'.$setting->logo)}}">
                                    </a>
                                </div>
                            </div>
                            <div class="header-column">
                                <div class="header-row">
                                    <div class="header-search hidden-xs">
                                        {!! Form::open(['url'=>'page/search','method'=>'get']) !!}
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" id="q" placeholder="Search..." required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
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
                                        <ul class="header-social-icons social-icons hidden-xs">
                                            <li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                            <li class="social-icons-twitter"><a href="http://www.twitter.com/" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                            <li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>

                                        </ul>
                                        <div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
                                            <nav>
                                                <ul class="nav nav-pills" id="mainNav">

                                                    <?php $pages = \App\Model\Front\FrontendPage::where('publish', 1)->get(); ?>
                                                    @foreach($pages as $page)
                                                    <li class="dropdown">

                                                        @if($page->parent_page_id==0)
                                                        <?php
                                                        $ifdrop = \App\Model\Front\FrontendPage::where('publish', 1)->where('parent_page_id', $page->id)->count();
                                                        if ($ifdrop > 0) {
                                                            $class = 'dropdown-toggle';
                                                        } else {
                                                            $class = '';
                                                        }
                                                        ?>
                                                        <a class="{{$class}}" href="{{$page->url}}">
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


                                                    <li class="dropdown">
                                                        @if(!Auth::user())
                                                        <a  href="{{url('auth/login')}}">
                                                            Login
                                                        </a>
                                                        @else 
                                                    <li class="dropdown">
                                                        <a class="dropdown-toggle" href="#">
                                                            {{Auth::user()->first_name}}
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="{{url('/')}}">My Account</a></li>
                                                            <li><a href="{{url('auth/logout')}}">Logout</a></li>
                                                        </ul>
                                                    </li>
                                                    @endif
                                                    </li>

                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div role="main" class=@yield('main-class')>

                <section class="page-header">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="breadcrumb">
                                    @yield('breadcrumb')
                                    <!--<li><a href="#">Home</a></li>
                                    <li class="active">Pages</li>-->
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h1>@yield('page-heading')</h1>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="container">

                    <!--<div class="row">
                        <div class="col-md-12">

                            <div class="featured-boxes">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-md-offset-3">
                                        <div class="featured-box featured-box-primary align-left mt-xlg">
                                            <div class="box-content">
                                                <h4 class="heading-primary text-uppercase mb-md">I'm a Returning Customer</h4>
                                                <form action="/" id="frmSignIn" method="post">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label>Username or E-mail Address</label>
                                                                <input type="text" value="" class="form-control input-lg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <a class="pull-right" href="forgot.html">(Forgot Password?)</a>
                                                                <label>Forgot Password</label>
                                                                <input type="password" value="" class="form-control input-lg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <span class="remember-box checkbox">
                                                                <label for="rememberme">
                                                                    <input type="checkbox" id="rememberme" name="rememberme">Remember Me
                                                                </label>
                                                            </span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="submit" value="Login" class="btn btn-primary pull-right mb-xl" data-loading-text="Loading...">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>-->

                    @yield('content')

                </div>

            </div>

            <footer id="footer">
                <div class="container">
                    <div class="row">
                        <div class="footer-ribbon">
                            <span>Get in Touch</span>
                        </div>
                        <?php $widgets = \App\Model\Front\Widgets::where('publish', 1)->where('type', 'footer')->take(4)->get(); ?>
                        @foreach($widgets as $widget)
                        <div class="col-md-3">
                            <div class="contact-details">
                                <h4>{{ucfirst($widget->name)}}</h4>
                                {!! $widget->content !!}
                            </div>
                        </div>
                        @endforeach


                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="index.html" class="logo">
                                    <img alt="Porto Website Template" class="img-responsive" src="{{asset('cart/img/logo/'.$setting->logo)}}">
                                </a>
                            </div>
                            <div class="col-md-7">
                                <p>© Copyright <?php echo date('Y') ?>. All Rights Reserved.</p>
                            </div>
                            <!--                            <div class="col-md-4">
                                                            <nav id="sub-menu">
                                                                <ul>
                                                                    <li><a href="page-faq.html">FAQ's</a></li>
                                                                    <li><a href="sitemap.html">Sitemap</a></li>
                                                                    <li><a href="contact-us.html">Contact</a></li>
                                                                </ul>
                                                            </nav>
                                                        </div>-->
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Vendor -->
        <script src="{{asset('cart/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.appear/jquery.appear.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery-cookie/jquery-cookie.min.js')}}"></script>
        <script src="{{asset('cart/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('cart/vendor/common/common.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.validation/jquery.validation.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.stellar/jquery.stellar.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.gmap/jquery.gmap.min.js')}}"></script>
        <script src="{{asset('cart/vendor/jquery.lazyload/jquery.lazyload.min.js')}}"></script>
        <script src="{{asset('cart/vendor/isotope/jquery.isotope.min.js')}}"></script>
        <script src="{{asset('cart/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
        <script src="{{asset('cart/vendor/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
        <script src="{{asset('cart/vendor/vide/vide.min.js')}}"></script>

        <!-- Theme Base, Components and Settings -->
        <script src="{{asset('cart/js/theme.js')}}"></script>

        <!-- Theme Custom -->
        <script src="{{asset('cart/js/custom.js')}}"></script>

        <!-- Theme Initialization Files -->
        <script src="{{asset('cart/js/theme.init.js')}}"></script>

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
