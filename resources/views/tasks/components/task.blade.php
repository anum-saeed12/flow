<a href="#" class="task" data-toggle="modal" data-target="#taskEditModal{{ $task->id }}">
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
                    <input type="hidden" name="project_id" value="{{ $list->id }}">
                    <div class="form-group">
                        <input name="title" type="text" class="form-control" placeholder="Task" value="{{ $task->title }}">
                    </div>
                    <div class="form-group">
                        <textarea name="description" class="form-control" placeholder="Description">{{ $task->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <input name="points" type="number" class="form-control" placeholder="Points" value="{{ $task->points }}">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" value="List: {{ $list->title }}" disabled>
                    </div>
                    <div class="form-group">
                        <input  class="form-control" type="text" placeholder="Quick find...">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            @foreach($users as $user)
                                <div class="col-6">
                                    <label for="ntu{{ $user->id }}">
                                        <input id="ntu{{ $user->id }}" type="checkbox" name="members[]" value="{{ $user->id }}" class="mr-2"{!! in_array($user->id, $members)?' checked':'' !!}>
                                        {{ $user->username }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-right">
                        <label class="completed-container mr-3">
                            <input type="checkbox" @if($task->completed=='1')checked="checked"@endif>
                            <span class="completed" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Complete</span>
                        </label>
                        <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Close</button>
                        {{--<button type="submit" class="btn btn-outeline-primary"><i class="fa fa-plus mr-2"></i>Create</button>--}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
