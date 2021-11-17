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
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop


@section('content')
<section class="content">
    <div class="container" style="width: 50%; !important;">
        <div class="row">
            <div class="col-md-12">
                @if(session()->has('success'))
                    <div class="callout callout-success" style="color:green">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="callout callout-danger" style="color:red">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="card card-info" >
                    <form class="form-horizontal" action="{{ route('user.store.admin') }}" method="POST">
                        @csrf
                        <div class="card-body pb-0 pt-2 mt-2" >
                            <div class="row">
                                <div class="col">
                                    <label for="username">Username</label><br/>
                                    <input type="text" name="username" class="form-control" id="username"
                                           value="{{ old('username') }}">
                                    <div class="text-danger">@error('username'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="email">Email</label><br/>
                                    <input type="email" name="email" class="form-control" id="email"
                                           value="{{ old('email') }}">
                                    <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                                </div>
                           </div>
                            <div class="row">
                                <div class="col">
                                    <label for="password">Password</label><br/>
                                    <input type="password" name="password" class="form-control" id="password"
                                           value="{{ old('password') }}">
                                    <div class="text-danger">@error('password'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="user_role">User Role</label><br/>
                                    <select name="user_role" class="form-control">
                                        <option selected="selected" value>Select</option>
                                        <option value="admin">Admin</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                    <div class="text-danger">@error('user_role'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col mb-3 text-center">
                                    <button type="submit" class="btn btn-default">Cancel</button>
                                    <span class="mr-3"></span>
                                    <button type="submit" class="btn btn-info">{{$title}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

