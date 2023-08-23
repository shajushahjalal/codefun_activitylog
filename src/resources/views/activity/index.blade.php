@extends('Activity::activity.masterPage')
@section('mainPart')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3 mb-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        User Activity Log List
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-primary">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Event</th>
                                    <th>Description</th>
                                    <th>Performed By</th>
                                    <th>Action Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td>
                                        {{ $activity->event_name ?? "N/A" }}
                                    </td>
                                    <td>
                                        {{ $activity->description ?? "N/A" }}
                                    </td>
                                    <td>
                                        {{ $activity->causer->name ?? ($activity->causer->first_name ?? "N/A") }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($activity->created_at)->format("d-M, Y h:i A") }}
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('activity_log.view', [$activity->uuid]) }}">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3 mb-3">
                
            </div>
            <div class="col-12">
                {!! $activities->links() !!}
            </div>
        </div>
    </div>
@endsection
