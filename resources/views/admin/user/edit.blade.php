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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal" action="{{ route('user.update.admin', $users->id) }}" method="POST">
                            @csrf
                            <div class="card-body pb-0 pt-2 mt-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label><br/>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   value="{{ ucfirst($users->name) }}" required>
                                            <div class="text-danger">@error('name'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="username">Username</label><br/>
                                            <input type="text" name="username" class="form-control" id="username"
                                                   value="{{ ucfirst($users->username) }}" required>
                                            <div class="text-danger">@error('username'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email</label><br/>
                                            <input type="email" name="email" class="form-control" id="email"
                                                   value="{{ ucfirst($users->email) }}" required>
                                            <div class="text-danger">@error('email'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="password">Password</label><br/>
                                            <input type="password" name="password" class="form-control" id="password">
                                            <div class="text-danger">@error('password'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_role">User Role</label><br/>
                                            <select name="user_role" class="form-control optional-trigger" data-trigger-value="team" data-target="#user_category" data-target-required="#category_id" id="user_role" required>
                                                <option selected="selected" value>Select</option>
                                                <option value="admin" {{ $users->user_role == 'admin' ? 'selected="selected"' : '' }}>Admin
                                                </option>
                                                <option value="sale" {{ $users->user_role == 'sale' ? 'selected="selected"' : '' }}>Sales Person
                                                </option>
                                                <option value="manager" {{ $users->user_role == 'manager' ? 'selected="selected"' : '' }}>Manager
                                                </option>
                                                <option value="team" {{ $users->user_role == 'team' ? 'selected="selected"' : '' }}>Sourcing Team
                                                </option>
                                            </select>
                                            <div class="text-danger">@error('user_role'){{ $message }}@enderror</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="user_category"{!! $users->user_role!='teams'?' style="display:none;"':'' !!}>
                                            <div class="form-group">
                                                <label for="category_id">Category</label><br/>
                                                <select name="category_id" class="form-control" id="category_id">
                                                    <option selected="selected" value>Select</option>
                                                    @foreach($category as $names)
                                                        <option value="{{ $names->id }}" {{ $users->category_name == $names->category_name ? 'selected="selected"' : '' }}>{{ $names->category_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="text-danger">@error('user_role'){{ $message }}@enderror</div>
                                            </div>
                                        </div>
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

@section('extras')
    <script>
        $(function(){
            $('.optional-trigger').change(function(){
                let target = $($(this).data('target')),
                    targetRequired = $($(this).data('target-required')),
                    trigger = $(this).data('trigger-value'),
                    value = $(this).val();
                targetRequired.removeAttr('required');
                if (value === trigger) {
                    targetRequired.attr('required','required');
                    target.show();
                    return false
                }
                targetRequired.removeAttr('required');
                targetRequired.val('');
                return target.hide();
            });
        });
    </script>
@endsection
