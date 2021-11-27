<div class="list">
    <div class="title">
        <h6>{{ ucwords($project->title) }}</h6>@admin<div class="actions">
            <button class="more btn-more" type="button" data-target="#m{{ md5($project->id) }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-h"></i>
            </button>
            <div class="menu-container" style="display:none;" id="m{{ md5($project->id) }}">
                <ul class="menu list-menu">
                    <li><a href="#" data-toggle="modal" data-target="#editListModal{{ $project->id }}">Edit</a></li>
                    <li><a href="#">Delete</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#editMembersModal{{ $project->id }}">Add Members</a></li>
                </ul>
            </div>
        </div>@endadmin
    </div>
   @admin
    <div class="task-container count-{{ count($project->tasks) }}">
        @forelse($project->tasks as $task)@include('tasks.components.task', [compact('task'),'members'=>members($task->id, 'task')])@empty
            @include('tasks.components.empty-task')
        @endforelse
    </div>
    @endadmin

    @employee
    <div class="task-container count-{{ count(tasks($project->id,auth()->id())) }}">
        @forelse( tasks($project->id,auth()->id()) as $task)@include('tasks.components.task', [compact('task'),'members'=>members($task->id, 'task')])@empty
            @include('tasks.components.empty-task')
        @endforelse
    </div>
    @endemployee

    @admin
    <div class="p-2">
        <a href="#" class="btn btn-link btn-sm btn-block text-left" data-toggle="modal" data-target="#newTaskModal{{ $project->id }}">
            <i class="fa fa-plus mr-1"></i>
            Create New Task
        </a>
        <div id="newTaskModal{{ $project->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="form-group">
                                <input name="title" type="text" class="form-control" placeholder="Task">
                            </div>
                            <div class="form-group">
                                <textarea name="description" class="form-control" placeholder="Description"></textarea>
                            </div>
                            <div class="form-group">
                                <input name="points" type="number" class="form-control" placeholder="Points" value="1">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label>Start Date:</label>
                                    <div class="form-group">
                                        <input name="start_date" type="date" class="form-control" placeholder="Start Date" >
                                    </div>
                                </div>
                                <div class="col">
                                    <label>End Date:</label>
                                    <div class="form-group">
                                        <input name="end_date" type="date" class="form-control" placeholder="End Date" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" value="Project: {{ $project->title }}" disabled>
                            </div>
                            <h6>Members</h6>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Quick find...">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    @foreach($users as $user)
                                        <div class="col-6">
                                            <label for="ntu{{ $user->id }}">
                                                <input id="ntu{{ $user->id }}" type="checkbox" name="members[]" value="{{ $user->id }}" class="mr-2">
                                                {{ ucfirst($user->username) }}
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
        <div id="editMembersModal{{ $project->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <input class="form-control" type="text" value="Project: {{ ucfirst($project->title) }}" disabled>
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
        <div id="editListModal{{ $project->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit {{ $project->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route(auth()->user()->user_role . '.project.update', $project->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Project title" value="{{ $project->title }}">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Description">{{ $project->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <input  class="form-control" type="text" placeholder="Quick find...">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    @foreach($users as $user)
                                        <div class="col-6">
                                            <label for="ntu{{ $user->id }}">
                                                <input id="ntu{{ $user->id }}" type="checkbox" name="members[]" value="{{ $user->id }}" class="mr-2"{!! in_array($user->id, $list_members)?' checked':'' !!}>
                                                {{ $user->username }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endadmin
</div><?php
