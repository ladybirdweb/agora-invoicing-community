@extends('themes.default1.layouts.master')
@section('content')

@foreach($licences as $licence)
<div class="col-md-3">
    <!-- /.col-lg-4 col-md-4 col-sm-8 col-xs-8 -->
    <div class="row">
        <!-- /.row -->
        <div class="col-md-12">
            <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-green-active text-center">
                    <h3 class="widget-user-username">{{ucfirst($licence->name)}}</h3>
                    <h5 class="widget-user-desc">
                        @if($licence->number_of_sla==0)
                            Unlimited
                        @else
                        {{$licence->number_of_sla}}
                        @endif
                        SLAs</h5>
                    <h3 class="widget-user-desc">$ {{$licence->price}}</h5>
                </div>


                <!-- /.row -->
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked text-center">
                        <li><h3>{{$licence->description}}</h3></li>
                        <li><div class="col-md-offset-3 col-xs-offset-4 text-center"> <div class="box-footer clearfix">
                                    <a href="{{url('pricing/'.$licence->id)}}" class="btn btn-sm btn-success  pull-left"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;Place Order</a>

                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box box-widget widget-user -->
    </div>
    <!-- /.row-->
</div>
<!-- /.col -->
@endforeach

@stop