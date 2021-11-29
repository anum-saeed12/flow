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
                        <li class="breadcrumb-item">Alerts</li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@stop


@section('content')
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col">
                    @if($alert->seen == 0)
                    <div class="callout callout-success" style="color:green">
                        {!! $alert->message !!}
                    </div>
                    @else
                        <div class="callout callout-secondary">
                            {!! $alert->message !!}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-body pt-2 mt-2">
                            @if($alert->type == 'task')
                                <div class="row">
                                    <div class="col">
                                        <h2>
                                            <b>Task:</b>
                                            {{ $alert->subject_title }}
                                        </h2>
                                        <div style="margin-top:-.9rem;margin-bottom:.9rem;">({{ $alert->project_title }})</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><b>Description:</b><br/>
                                        <p>{!! $alert->subject_description !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b>Start Date:</b>
                                        {{ \Carbon\Carbon::parse($alert->subject_start_date)->format('d-M-Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b>End Date:</b>
                                        {{ \Carbon\Carbon::parse($alert->subject_end_date)->format('d-M-Y') }}
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col">
                                        <h2>
                                            <b>Project:</b>
                                            {{ $alert->project_title }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><b>Description:</b><br/>
                                        <p>{!! $alert->project_description !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <b>Creation Date:</b>
                                        {{ \Carbon\Carbon::parse($alert->project_creation_date)->format('d-M-Y') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

