<!DOCTYPE html>
  <?php 
    $set = new \App\Model\Common\Setting();
    $set = $set->findOrFail(1);
    ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>@yield('title') | {{$set->favicon_title}}</title>
        <link rel="shortcut icon" href='{{asset("common/images/$set->fav_icon")}}' type="image/x-icon" />
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
         <link rel="stylesheet" href="{{asset('admin/plugins/datepicker/bootstrap-datepicker.min.css')}}">

        <!-- Custom style -->
        <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}">
         <!-- <link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}"> -->

        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="{{asset('admin/css/_all-skins.min.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <link href="{!!asset('admin/css/bill.css')!!}" rel="stylesheet" type="text/css" />
         <link rel="stylesheet" href="{{asset('common/css/intlTelInput.css')}}">


         <!-- jQuery 2.1.4 -->
        <script src="{{asset("common/js/jquery-2.1.4.js")}}" type="text/javascript"></script>
        <script src="{{asset("common/js/jquery2.1.1.min.js")}}" type="text/javascript"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script> -->
<style>

.most-popular {
   
  
    border: 1px solid;
    padding: 10px;
    box-shadow: 5px 10px white;
 
}
#myBar  {
       background-color: red;
       width: 100%; /* Adjust with JavaScript */
       height: 40px;
       border-radius: 40px;
    }
 .select2-selection{
    border-radius:0px !important;
 }
 .select2-selection__choice{
    background-color:grey !important; 
 }

</style>
<script type="text/javascript">
    document.onreadystatechange = function () {
  var state = document.readyState
  if (state != 'complete') {
      move();
  }
}
function move() {
       
  $(".w3-green").removeClass("hide");
  var elem = document.getElementById("myBar");   
  var width = 1;
  var id = setInterval(frame, 0);
  function frame() {
    if (width >= 100) {
      clearInterval(id);
      $(".w3-green").addClass("hide");
    } else {
      width++; 
      elem.style.width = width + '%'; 
    }
  }
}
</script>
    </head>
    <?php 
    $set = new \App\Model\Common\Setting();
    $set = $set->findOrFail(1);
    ?>
        

    <!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
    <!-- the fixed layout is not compatible with sidebar-mini -->
    <body class="hold-transition skin-blue sidebar-mini">
       <!-- Site wrapper -->
        <div class="wrapper">

            <header class="main-header">
                <div id="myBar" class="w3-green " style="height:3px;width:0"></div>

                <!-- Logo -->
                <a href="{{url('/')}}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <!-- <span class="logo-mini">{{$set->title}}</span> -->
                    <!-- logo for regular state and mobile devices -->
                    @if ($set->title != '')
                    <span class="logo-lg"><b>{{$set->title}} </b></span>
                    @else
                    <span class="logo-lg">
                        <img src='{{ asset("admin/images/$set->admin_logo")}}' class="img-rounded" alt="Admin-Logo"  height="45">
                       

                    </span>
                    @endif
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    
                                    <img src="{{Auth::user()->profile_pic}}" class="user-image" alt="User Image" />
                                    
                                    <span class="hidden-xs">{{ucfirst(Auth::user()->first_name)}} {{ucfirst(Auth::user()->last_name)}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        
                                        <img src="{{Auth::user()->profile_pic}}" class="img-circle" alt="User Image" />
                                        
                                        <p>
                                            {{ucfirst(Auth::user()->first_name)}} {{ucfirst(Auth::user()->last_name)}}
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{url('profile')}}" class="btn btn-default btn-flat">{{Lang::get('message.profile')}}</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{url('auth/logout')}}" class="btn btn-default btn-flat">{{Lang::get('message.signout')}}</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>

            <!-- =============================================== -->

            <!-- Left side column. contains the sidebar -->
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
                            <a href="{{url('/')}}">
                                <i class="fa fa-dashboard"></i> <span>{{Lang::get('message.dashboard')}}</span>
                            </a>
                          
                        </li>
                 <!--         <li class="treeview">
                            <a href="{{url('/stats')}}">
                                <i class="fa fa-dashboard"></i> <span>{{Lang::get('message.stats')}}</span>
                            </a>
                          
                        </li> -->


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
                                   <!-- <li><a href="{{url('product-type')}}"><i class="fa fa-thumb-tack"></i>{{Lang::get('message.types')}}</a></li> -->
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



            

            <!-- =============================================== -->

            <!-- Content Wrapper. Contains page content -->
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
                <strong>Copyright &copy; {{date('Y')}} <a href="{{$set->website}}" target="_blank">{{$set->company}}</a>.</strong> All rights reserved. Powered by <a href="https://ladybirdweb.com" target="_blank"><img src="{{asset('common/images/Ladybird1.png')}}" alt="Ladybird"></a>
            </footer>


        </div><!-- ./wrapper -->
       
        <!-- Bootstrap 3.3.2 JS -->
        <script src="{{asset('common/js/theme.init.js')}}"></script>
        <script src="{{asset('common/js/intlTelInput.js')}}"></script>

        <script src="{{asset('admin/plugins/jquery-file-upload/vendor/jquery.ui.widget.js')}}"></script>   
      
        <script src="{{asset('admin/plugins/jquery-file-upload/jquery.fileupload.js')}}"></script>   
         <!-- <script src="{{asset('plugins/jquery-file-upload/main.js')}}"></script> -->
            <script src="{{asset('admin/plugins/jquery-file-upload/jquery.iframe-transport.js')}}"></script>
     <script src="{{asset('admin/plugins/jquery-file-upload/resumable.js')}}"></script>
     
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
        <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datepicker/bootstrap-datepicker.min.js')}}"></script>
        <!-- icheck -->
        <script src="{{asset('admin/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
        <script>

        </script>
        
        @yield('icheck')
        <!-- AdminLTE for demo purposes -->
        <script src="{{asset('admin/plugins/input-mask/jquery.inputmask.js')}}"></script>
        <script src="{{asset('admin/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
        <script src="{{asset('admin/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
        <script src="{{asset('admin/js/demo.js')}}" type="text/javascript"></script>
        <script src="{{asset("admin/plugins/moment-develop/moment.js")}}" type="text/javascript"></script>
        <script src="{{asset("admin/plugins/datepicker/bootstrap-datetimepicker4.7.14.min.js")}}" type="text/javascript"></script>
        @yield('datepicker')
    </body>
</html>
