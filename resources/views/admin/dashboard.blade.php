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
                {{--<div class="col-lg-3 col-6">
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3></h3>
                            <p>Employees</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users nav-icon text-danger"></i>
                        </div>
                        <a href="#" class="small-box-footer bg-danger" style="color:white!important;">View Employees <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>--}}
            </div>
        </div>
    </section>
@stop

