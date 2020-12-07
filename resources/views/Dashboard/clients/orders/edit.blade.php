@extends('layouts.dashboard.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.add')</h1>
        <ol class="breadcrumb">
            <li ><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
            <li ><a href="{{route('dashboard.clients.index')}}">@lang('site.clients')</a></li>
            <li class="active">@lang('site.add')</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">

                <div class="box box-primary">

                    <div class="box-header">

                        <h3 class="box-title" style="margin-bottom: 10px">@lang('site.categories')</h3>

                    </div><!-- end of box header -->

                    <div class="box-body">
                        @foreach ($categories as $category)

                            <div class="panel-group">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#{{$category->id}}">{{$category->name}}</a>
                                        </h4>
                                    </div>

                                    <div id="{{$category->id}}" class="panel-collapse collapse">


                                        <div class="panel-body">
                                            @if ($category->product->count() > 0 )

                                                <table class="table table-hover">
                                                    <tr>
                                                        <th>@lang('site.name')</th>
                                                        <th>@lang('site.stok')</th>
                                                        <th>@lang('site.price')</th>
                                                        <th>@lang('site.add')</th>
                                                    </tr>
                                                    @foreach ($category->product as $item)
                                                        <tr>
                                                            <td>{{$item->name}}</td>
                                                            <td>{{$item->stok}}</td>
                                                            <td>{{$item->sale_price}}</td>
                                                            <td>
                                                                <a href=""
                                                                   id="product-{{ $item->id }}"
                                                                   data-name="{{ $item->name }}"
                                                                   data-id="{{ $item->id }}"
                                                                   data-price="{{ $item->sale_price }}"
                                                                   class="btn {{in_array($item->id,$order->products->pluck('id')->toArray()) ? 'btn-default disabled' : 'btn-success'}} btn-sm add-product-btn">
                                                                    <i class="fa fa-plus"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </table><!-- end of table -->
                                            @else
                                                <h3>@lang('site.no_data_found')</h3>
                                            @endif

                                        </div><!-- end of panel body -->

                                    </div><!-- end of panel collapse -->

                                </div><!-- end of panel primary -->

                            </div><!-- end of panel group -->
                        @endforeach

                    </div><!-- end of box body -->

                </div><!-- end of box -->

            </div><!-- end of col -->

            <div class="col-md-6">

                <div class="box box-primary">

                    <div class="box-header">

                        <h3 class="box-title">@lang('site.orders')</h3>

                    </div><!-- end of box header -->

                    <div class="box-body">

                        <form action="{{route('dashboard.clients.orders.update',['order' => $order->id, 'client' => $client->id])}}" method="post">

                            {{ csrf_field() }}
                            {{ method_field('put') }}

                            @include('partials._errors')

                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('site.product')</th>
                                    <th>@lang('site.quantity')</th>
                                    <th>@lang('site.price')</th>
                                </tr>
                                </thead>

                                <tbody class="order-list">

                                    @foreach ($order->products as $product)
                                        <tr>
                                            <td>{{ $product->name}}</td>
                                        <td><input type="number" name="products[{{$product->id}}][quantity]" data-price="{{number_format($product->sale_price * $product->pivot->quantity , 2)}}" class="form-control input-sm product-quantity" min="1" value="{{$product->pivot->quantity}}"></td>
                                            <td class="product-price">{{number_format($product->sale_price * $product->pivot->quantity , 2)}}</td>
                                            <td><button class="btn btn-danger btn-sm remove-product-btn" data-id="{{$product->id}}"><span class="fa fa-trash"></span></button></td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table><!-- end of table -->

                            <h4>@lang('site.total') : <span class="total-price">{{ number_format($order->total_price, 2) }}</span></h4>

                                <button class="btn btn-primary btn-block" id="form-btn"><i class="fa fa-edit"></i> @lang('site.edit_order')</button>

                        </form>

                    </div><!-- end of box body -->

                </div><!-- end of box -->

            </div><!-- end of col -->

    </section>

</div>

@endsection
