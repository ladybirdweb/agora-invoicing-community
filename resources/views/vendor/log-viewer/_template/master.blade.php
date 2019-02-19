<!DOCTYPE html>
  <?php 
    $set = new \App\Model\Common\Setting();
    $set = $set->findOrFail(1);
    ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
           <title>@yield('title') | {{$set->favicon_title}}</title>
        <link rel="shortcut icon" href='{{asset("images/favicon/$set->fav_icon")}}' type="image/x-icon" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.4 -->
       <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{asset('admin/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

        <!-- Custom style -->
        <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">

        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
         <link href="{{asset('admin/css/_all-skins.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- <link href="{!!asset('plugins/datatables/dataTables.bootstrap.css')!!}" rel="stylesheet" type="text/css" /> -->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"> -->
        <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <link href="{!!asset('admin/css/bill.css')!!}" rel="stylesheet" type="text/css" />
        


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- jQuery 2.1.4 -->
       <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
        <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
         <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/js/jquery.min.js"></script>
    <meta name="description" content="LogViewer">
    <meta name="author" content="ARCANEDEV">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->
   
    <!-- <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700|Source+Sans+Pro:400,600' rel='stylesheet' type='text/css'> -->




    @include('log-viewer::_template.style')
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
  <body class="hold-transition skin-blue sidebar-mini">
     <div class="wrapper">
    @include('log-viewer::_template.navigation')





                <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            
                            <img src="{{Auth::user()->profile_pic}}" class="img-circle" alt="User Image" />
                           
                        </div>
                        <div class="info">
                            <p>{{ucfirst(Auth::user()->first_name)}} {{ucfirst(Auth::user()->last_name)}}</p>
                        </div>
                    </div>

                    <ul class="sidebar-menu">


                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-user"></i> <span>{{Lang::get('message.users')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('clients')}}"><i class="fa fa-users"></i>{{Lang::get('message.all-users')}}</a></li>
                                <li><a href="{{url('clients/create')}}"><i class="fa fa-book"></i>{{Lang::get('message.add-new')}}</a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-paper-plane"></i> <span>{{Lang::get('message.orders')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('orders')}}"><i class="fa fa-paper-plane"></i>{{Lang::get('message.all-orders')}}</a></li>
                                  <li><a href="{{url('invoice/generate')}}"><i class="fa fa-book"></i>{{Lang::get('message.add-new')}}</a></li>
                               
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-paperclip"></i> <span>{{Lang::get('message.invoices')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('invoices')}}"><i class="fa fa-paperclip"></i>{{Lang::get('message.all-invoices')}}</a></li>
                                <li><a href="{{url('invoice/generate')}}"><i class="fa fa-book"></i>{{Lang::get('message.add-new')}}</a></li>
                            </ul>
                        </li>
                        
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-paragraph"></i> <span>{{Lang::get('message.pages')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('pages')}}"><i class="fa fa-paragraph"></i>{{Lang::get('message.all-pages')}}</a></li>
                                <li><a href="{{url('pages/create')}}"><i class="fa fa-book"></i>{{Lang::get('message.add-new')}}</a></li>
                            </ul>
                        </li>
                        
<!--                        <li>
                            <a href="{{url('widgets')}}">
                                <i class="fa fa-circle-o text-orange"></i> <span>{{Lang::get('message.widgets')}}</span>
                            </a>
                        </li>-->
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span>{{Lang::get('message.products')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('products')}}"><i class="fa fa-codepen"></i>{{Lang::get('message.all-products')}}</a></li>
                                 <li><a href="{{url('products/create')}}"><i class="fa fa-book"></i>{{Lang::get('message.add-products')}}</a></li>
                                  <li><a href="{{url('category')}}"><i class="fa fa-tasks"></i>{{Lang::get('message.category')}}</a></li>
                                   <li><a href="{{url('product-type')}}"><i class="fa fa-thumb-tack"></i>{{Lang::get('message.types')}}</a></li>
                                 <li><a href="{{url('plans')}}"><i class="fa fa-minus-circle"></i>Plans</a></li>
                                   <li><a href="{{url('promotions')}}"><i class="fa fa-usd"></i>{{Lang::get('message.coupons')}}</a></li>
                                <li><a href="{{url('groups')}}"><i class="fa fa-group"></i>{{Lang::get('message.groups')}}</a></li>

                                <!--<li><a href="{{url('addons')}}"><i class="fa fa-files-o"></i>{{Lang::get('message.addons')}}</a></li>-->
                                <!--<li><a href="{{url('bundles')}}"><i class="fa fa-code-fork"></i>{{Lang::get('message.bundles')}}</a></li>-->
                              
                            </ul>
                        </li>
                        <li>
                            <a href="{{url('settings')}}">
                                <i class="fa fa-gears"></i> <span>{{Lang::get('message.settings')}}</span>
                            </a>
                        </li>
                        
<!--                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-gears"></i> <span>{{Lang::get('message.settings')}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('settings')}}"><i class="fa fa-gears"></i>{{Lang::get('message.system-settings')}}</a></li>
                                <li><a href="{{url('currency')}}"><i class="fa fa-dollar"></i>{{Lang::get('message.currency')}}</a></li>
                                <li><a href="{{url('tax')}}"><i class="fa fa-money"></i>{{Lang::get('message.tax')}}</a></li>
                                <li><a href="{{url('templates')}}"><i class="fa fa-files-o"></i>{{Lang::get('message.templates')}}</a></li>
                                <li><a href="{{url('github')}}"><i class="fa fa-github"></i>{{Lang::get('message.github')}}</a></li>
                                <li><a href="{{url('mailchimp')}}"><i class="fa fa-mail-forward"></i>{{Lang::get('message.mailchimp')}}</a></li>
                                <li><a href="{{url('social-media')}}"><i class="fa fa-medium"></i>{{Lang::get('message.social-media')}}</a></li>
                                <li><a href="{{url('plugin')}}"><i class="fa fa-bank"></i>{{Lang::get('message.plugins')}}</a></li>

                            </ul>
                        </li>-->





                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

               <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">

                    @yield('content-header')
                </section>


                <!-- Main content -->
                <section class="content">
 

                    @yield('content')

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

     <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>{{Lang::get('message.version')}}</b> {{Config::get('app.version')}}
                </div>
                <strong>Copyright &copy; {{date('Y')}} <a href="{{$set->website}}" target="_blank">{{$set->company}}</a>.</strong> All rights reserved. Powered by <a href="http://ladybirdweb.com" target="_blank"><img src="{{asset('common/images/Ladybird1.png')}}" alt="Ladybird"></a>
            </footer>
        </div>

    <script src="{{asset('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js')}}"></script>
    <script src="{{asset('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js')}}"></script>
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js')}}"></script>
        <script src="{{asset('js/theme.init.js')}}"></script>
          <script src="{{asset('js/intl/js/intlTelInput.js')}}"></script>
          <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script> -->

        <!-- (Optional) Latest compiled and minified JavaScript translation files -->
         <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script> -->

          <script src="{{asset('admin/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <!-- SlimScroll -->
        <script src="{{asset('admin/plugins/slimScroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <!-- FastClick -->
        <script src="{{asset('admin/plugins/fastclick/fastclick.min.js')}}" type="text/javascript"></script>
        <!-- AdminLTE App -->
         <script src="{{asset('admin/js/app.min.js')}}" type="text/javascript"></script>
        <!-- icheck -->
         <script src="{{asset('admin/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js')}}"></script>
    <script>
        Chart.defaults.global.responsive      = true;
        Chart.defaults.global.scaleFontFamily = "'Source Sans Pro'";
        Chart.defaults.global.animationEasing = "easeOutQuart";
    </script>
    @yield('modals')
    @yield('scripts')
</body>
</html>
