@extends('layouts.panel')

@section('breadcrumbs')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>
                        {{ucfirst($title)}}
                        @admin
                        <a href="#" class="btn btn-primary btn-sm text-left ml-2" data-toggle="modal" data-target="#newListModal">
                            <i class="fa fa-plus mr-1"></i>
                            Create New Project
                        </a>
                        @endadmin
                    </h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ucfirst($title)}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        @admin
        <div id="newListModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.project.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input name="title" type="text" class="form-control" placeholder="Project name">
                            </div>
                            <div class="form-group">
                                <textarea name="description" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <h6>Members</h6>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Quick find...">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    @foreach($users as $user)
                                        <div class="col-6">
                                            <label for="nlu{{ $user->id }}">
                                                <input id="nlu{{ $user->id }}" type="checkbox" name="members[]" value="{{ $user->id }}" class="mr-2">
                                                {{ ucwords($user->username) }}
                                            </label>
                                        </div>
                                    @endforeach
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
        @endadmin
    </section>
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <div class="list-container">
                    @forelse($projects as $project)@include('projects.components.list',[compact('project'),'list_members'=>members($project->id,'project')])@empty
                        @include('projects.components.empty')
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
