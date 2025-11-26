@extends('layout.index')
@section('title')
Info | {{$info->title}}
@endsection
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Info</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="/">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="/info/create">Info</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{$info->title}}</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="card card-profile">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">
                                    <div class="fw-bold">{{$info->title}}</div>
                                </h4>
                            </div>

                        </div>
                    </div>
                    <img class="img-fluid m-3" src="{{asset('img/info/'.$info->image)}}">
                    </img>
                    <div class="card-body">
                        <div class="user-profile text-center">
                            <div class="justify-content-start">{{$info->content}}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection