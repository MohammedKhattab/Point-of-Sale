@extends('layouts.dashboard.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.dashboard')</h1>
            <ol class="breadcrumb">
                <li ><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
            </ol>
        </section>
        <section class="content">
            <h1>
                this is dashboard
            </h1>
        </section>
    </div>

@endsection
