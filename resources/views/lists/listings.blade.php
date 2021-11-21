@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{$title}}
                        <a href="#" class="btn btn-primary btn-sm text-left ml-2" data-toggle="modal" data-target="#newListModal">
                            <i class="fa fa-plus mr-1"></i>
                            Create New Task
                        </a>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <div id="newListModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New list</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.list.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input name="title" type="text" class="form-control" placeholder="List name">
                            </div>
                            <div class="form-group">
                                <textarea name="description" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Search to add members...">
                                <div class="member-container">
                                    @include('templates.member-icon', ['image' => 'https://pyxis.nymag.com/v1/imgs/fb4/6c0/70a4c87afa1ed28bbe965d1b2f5271f340-13-humans-season2.rsquare.w700.jpg'])@include('templates.member-icon', ['image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MXw3NjA4Mjc3NHx8ZW58MHx8fHw%3D&w=1000&q=80'])
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <div class="list-container">
                    @forelse($listings as $list)@include('lists.components.list', compact('list'))@empty
                        @include('lists.components.empty')
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@stop

@section('body.js')
    <script type="text/javascript">
        $(function(){
            $('.btn-more').click(function(){
                let target = $($(this).data('target'));
                //$('.menu-container').hide();
                target.toggle();
            });
            $('.list-menu').click(function(){
                $(this).parent().hide();
            })
        })
    </script>
@endsection
