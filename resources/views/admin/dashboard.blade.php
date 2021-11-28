@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$title}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($users as $user)
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-white">
                            <div class="inner">
                                <h3>{{ $user->points }}</h3>
                                <p>
                                    <span style="font-size:.8rem;margin-top:-.8rem;position:relative;display:block;color:#999;">Points</span>
                                    {{ $user->username }}
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users nav-icon text-danger"></i>
                            </div>
                            <a href="{{ route('user.edit.admin', $user->id) }}" class="small-box-footer bg-danger" style="color:white!important;">Edit {{ $user->username }}<i class="fas fa-arrow-circle-right ml-2"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@stop

