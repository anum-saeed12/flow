<a href="#" class="task{!! $task->completed==1 ? ' task-completed' :'' !!}" data-toggle="modal" data-target="#taskEditModal{{ $task->id }}">
    <h6>{{ $task->title }}</h6>
    <p>{{ crop($task->description) }}</p>
</a>
<div id="taskEditModal{{ $task->id }}" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $task->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route(auth()->user()->user_role . '.task.update', $task->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="form-group">
                        <input name="title" type="text" class="form-control" placeholder="Task" value="{{ $task->title }}"@employee disabled @endemployee>
                    </div>
                    <div class="form-group">
                        <textarea name="description" class="form-control" placeholder="Description" @employee disabled @endemployee>{{ $task->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <input name="points" type="number" class="form-control" placeholder="Points" value="{{ $task->points }}" @employee disabled @endemployee>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label>Start Date:</label>
                            <div class="form-group">
                                <input name="start_date" type="date" class="form-control" placeholder="Start Date" value="{{ $task->start_date }}"@employee disabled @endemployee>
                            </div>
                        </div>
                        <div class="col">
                            <label>End Date:</label>
                            <div class="form-group">
                                <input name="end_date" type="date" class="form-control" placeholder="End Date" value="{{ $task->end_date }}"@employee disabled @endemployee>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" value="Project: {{ $project->title }}" disabled>
                    </div>
                    @admin
                    <div class="form-group">
                        <input  class="form-control" type="text" placeholder="Quick find...">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            @foreach($users as $user)
                                <div class="col-6">
                                    <label for="ntu{{ $user->id }}">
                                        <input id="ntu{{ $user->id }}" type="checkbox" name="members[]" value="{{ $user->id }}" class="mr-2"{!! in_array($user->id, $members)?' checked':'' !!} @employee disabled @endemployee>
                                        {{ $user->username }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endadmin

                    @employee
                    <div class="form-group">
                        <ul>
                        @foreach(members($task->id,'task',false) as $member)
                            <li>{!! auth()->id() == $member->id ? "You ({$member->username})":$member->username !!}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endemployee
                    <div class="text-right">
                       {{-- <label class="completed-container mr-3">
                            <input type="checkbox" @if($task->completed=='1')checked="checked"@endif>
                            <span class="completed" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Complete</span>
                        </label>--}}
                        @if($task->completed!='1')
                            <a href="{{ route('employee.task.completed',$task->id)}}" class="btn btn-outline-success mr-2">Complete</a>
                        @else
                            <a href="#" class="btn btn-success mr-2">Completed</a>
                        @endif
                        <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Close</button>
                        {{--<button type="submit" class="btn btn-outeline-primary"><i class="fa fa-plus mr-2"></i>Create</button>--}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
