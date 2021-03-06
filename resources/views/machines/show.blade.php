@extends('layouts.page')

@section('title', $machine->name)

@section('content_header')
    <h1>{{ $machine->name }} <small>Machine details</small></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="fa fa-cube"></i>
                    <h3 class="box-title">{{ $machine->name }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <dl>
                        <dt>Brand</dt>
                        <dd>{{ $machine->brand }}</dd>
                        <dt>Model</dt>
                        <dd>{{ $machine->model }}</dd>
                        <dt>Integration token</dt>
                        <dd>{{ $machine->token }}</dd>
                    </dl>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            @if (!$machine->jobs->isEmpty())
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-play-circle-o"></i>
                        <h3 class="box-title">Latest job</h3>
                        <div class="box-tools">
                            {{ link_to_route('machine_job.show', 'Details', [$machine->jobs->first()->id], ['class' => 'btn btn-primary btn-sm']) }}
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @include('components.machine_jobs.status', ['job' => $machine->jobs->first()])
                        @include('components.machine_jobs.timer', ['job' => $machine->jobs->first()])
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            @endif
        </div>
        <div class="col-md-8">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="fa fa-play-circle-o"></i>
                    <h3 class="box-title">Jobs</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if($machine->jobs->isEmpty())
                        <blockquote><p>There are no jobs for this machine.</p></blockquote>
                    @else
                        <table class="table table-striped">
                            <tr>
                                <th>Started</th>
                                <th>Duration</th>
                                <th>Remaining</th>
                                <th>State</th>
                            </tr>
                            @foreach ($machine->jobs as $job)
                                <tr>
                                    <td>{{ link_to_route('machine_job.show', $job->created_at->timezone(Auth::user()->timezone)->format('d-m-Y G:i:s'), [$job->id]) }}</td>
                                    <td>@duration($job->duration)</td>
                                    <td>@duration($job->states->first()->seconds_remaining)</td>
                                    <td>
                                        @include('components.machine_jobs.icon', ['job' => $job, 'showText' => true])
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="fa fa-bolt"></i>
                    <h3 class="box-title">States</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if($machine->states->isEmpty())
                        <blockquote>
                            <p>This machine has not yet received any state updates.</p>
                            <small>Configure the <cite title="machine integration">machine integration</cite> to get started.</small>
                        </blockquote>
                    @else
                        <table class="table table-striped">
                            <tr>
                                <th>Remaining seconds</th>
                                <th>Remaining time</th>
                                <th>Created</th>
                                <th>Job</th>
                            </tr>
                            @foreach ($machine->states as $state)
                                <tr>
                                    <td>{{ $state->seconds_remaining }}</td>
                                    <td>@duration($state->seconds_remaining)</td>
                                    <td>
                                        @php
                                            // Determine state's creation time in user's timezone.
                                            $stateStartTime = $state->created_at->timezone(Auth::user()->timezone);
                                        @endphp
                                        {{ $stateStartTime->format('H:i:s') }}
                                        <small>
                                            {{ $stateStartTime->format('d-m-Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($state->job)
                                            {{ link_to_route('machine_job.show', $state->job->id, [$state->job->id]) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop
