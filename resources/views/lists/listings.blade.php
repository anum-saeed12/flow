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
