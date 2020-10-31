@extends('layouts.dashboard.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <h1>@lang('site.update')</h1>
        <ol class="breadcrumb">
            <li ><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
            <li ><a href="{{route('dashboard.users.index')}}">@lang('site.users')</a></li>
            <li class="active">@lang('site.update')</li>
        </ol>
    </section>
    <section class="content">
       <div class="box box-primary">

            <div class="box-header">
                <h3 class="box-title">@lang('site.update')</h3>
            </div>{{-- end of box header --}}

            <div class="box-body">
            @include('partials._errors')

            <form action="{{route('dashboard.users.update',$user->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>@lang('site.first_name')</label>
                    <input type="text" name="first_name" class="form-control" value="{{$user->first_name}}">
                </div>

                <div class="form-group">
                    <label>@lang('site.last_name')</label>
                    <input type="text" name="last_name" class="form-control" value="{{$user->last_name}}">
                </div>

                <div class="form-group">
                    <label>@lang('site.email')</label>
                    <input type="email" name="email" class="form-control" value="{{$user->email}}">
                </div>

                <div class="form-group">
                    <label>@lang('site.image')</label>
                    <input type="file" name="image" class="form-control image">
                </div>
                <div class="form-group">
                   <img src="{{asset('uploads/image_user/'.$user->image)}}" alt="" style="width: 100px" class="img-thumbnail image_preview">
                </div>

                <div class="form-group">

                    <h2>@lang('site.permissions')</h2>

                    <div class="nav-tabs-custom">

                        @php
                            $models = ['users','categories','products'];
                            $maps = ['create','read','update','delete'];
                        @endphp

                        <ul class="nav nav-tabs">

                            @foreach ($models as $index=>$model)
                                  <li class="{{$index == 0 ? 'active' : ''}}"><a href="#{{$model}}" data-toggle="tab">@lang('site.'.$model)</a></li>
                            @endforeach

                        </ul>{{-- end of nav tabs --}}

                        <div class="tab-content">
                            @foreach ($models as $index=>$model)
                                <div class="tab-pane {{$index == 0 ? 'active' : ''}}" id="{{$model}}">
                                    @foreach ($maps as $map)
                                <label for="checkbox" style="margin-left: 5px"><input type="checkbox" name=permissions[] {{$user->hasPermission($model.'_'.$map) ? 'checked' : ''}} value="{{$model .'_'.$map}}" >@lang('site.'.$map) </label>
                                    @endforeach
                                </div> {{-- end of tab pane --}}
                            @endforeach

                        </div>{{-- end of tab content --}}

                    </div>{{-- end of nav tabs custom --}}

                </div>{{-- end form group --}}


                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                </div>

            </form>
        </div>{{-- end of box body  --}}

    </div>{{-- end of box --}}
    </section>
</div>

@endsection
