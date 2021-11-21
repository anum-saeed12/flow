<div class="list">
    <div class="title">
        <h6>{{ $list->title }}</h6><div class="actions">
            <button class="more btn-more" type="button" data-target="#m{{ md5($list->id) }}">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="menu-container" style="display:none;" id="m{{ md5($list->id) }}">
                <ul class="menu list-menu">
                    <li><a href="#" data-toggle="modal" data-target="#list{{ $list->id }}">Edit</a></li>
                    <li><a href="#">Archive</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#mMem{{ $list->id }}">Add Members</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="task-container count-{{ count($list->tasks) }}">
        @forelse($list->tasks as $task)@include('tasks.components.task', compact('task'))@empty
            @include('tasks.components.empty-task')
        @endforelse
    </div>
    <div class="p-2">
        <a href="#" class="btn btn-link btn-sm btn-block text-left" data-toggle="modal" data-target="#mD{{ $list->id }}">
            <i class="fa fa-plus mr-1"></i>
            Create New Task
        </a>
        <div id="mD{{ $list->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.task.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="list_id" value="{{ $list->id }}">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Task">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" value="List: {{ $list->title }}" disabled>
                            </div>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Search to add members...">
                                <div class="member-container">
                                    @include('templates.member-icon', ['image' => 'https://pyxis.nymag.com/v1/imgs/fb4/6c0/70a4c87afa1ed28bbe965d1b2f5271f340-13-humans-season2.rsquare.w700.jpg'])@include('templates.member-icon', ['image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MXw3NjA4Mjc3NHx8ZW58MHx8fHw%3D&w=1000&q=80'])
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="mMem{{ $list->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Manage Members</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.assignment.store') }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Search to add members...">
                                <div class="member-container">
                                    @include('templates.member-icon', ['image' => 'https://pyxis.nymag.com/v1/imgs/fb4/6c0/70a4c87afa1ed28bbe965d1b2f5271f340-13-humans-season2.rsquare.w700.jpg'])@include('templates.member-icon', ['image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MXw3NjA4Mjc3NHx8ZW58MHx8fHw%3D&w=1000&q=80'])
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" value="List: {{ $list->title }}" disabled>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="list{{ $list->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New list</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.list.update', $list->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="List title" value="{{ $list->title }}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Description">{{ $list->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Search to add members...">
                                <div class="member-container">
                                    @include('templates.member-icon', ['image' => 'https://pyxis.nymag.com/v1/imgs/fb4/6c0/70a4c87afa1ed28bbe965d1b2f5271f340-13-humans-season2.rsquare.w700.jpg'])@include('templates.member-icon', ['image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MXw3NjA4Mjc3NHx8ZW58MHx8fHw%3D&w=1000&q=80'])
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php
