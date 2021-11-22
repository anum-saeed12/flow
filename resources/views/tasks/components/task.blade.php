<a href="#" class="task" data-toggle="modal" data-target="#taskEditModal{{ $task->id }}">
    <h6>{{ $task->title }}</h6>
    <p>{{ $task->description }}</p>
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
                    <input type="hidden" name="list_id" value="{{ $list->id }}">
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
                        <input  class="form-control" type="text" placeholder="Search to add members...">
                        <div class="member-container">
                            @include('templates.member-icon', ['image' => 'https://pyxis.nymag.com/v1/imgs/fb4/6c0/70a4c87afa1ed28bbe965d1b2f5271f340-13-humans-season2.rsquare.w700.jpg'])@include('templates.member-icon', ['image' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MXw3NjA4Mjc3NHx8ZW58MHx8fHw%3D&w=1000&q=80'])
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
