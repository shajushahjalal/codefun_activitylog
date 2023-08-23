@extends('Activity::activity.masterPage')
@section('mainPart')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        User Activity Details
                    </div>
                    <div class="card-body">
                        <div class="row mt-2 mb-2">
                            <div class="col-12 col-sm-4">
                                Action Description 
                            </div>
                            <div class="col-12 col-sm-8">
                                {{ $activity->description ?? "N/A" }}
                            </div>
                        </div>
                        <div class="row mt-2 mb-2">
                            <div class="col-12 col-sm-4">
                                Event Name 
                            </div>
                            <div class="col-12 col-sm-8">
                                {{ $activity->event_name ?? "N/A" }}
                            </div>
                        </div>
                        @if( !empty($activity->causerable_type) )
                            <div class="col-12 col-sm-4">
                                Action By 
                            </div>
                            <div class="col-12 col-sm-8">
                                {{ $activity->causer->name ?? ($activity->causer->first_name ?? "N/A") }}
                            </div>
                        @endif
                        
                        @if($is_change)
                            <div class="row mt-2 mb-2">
                                <div class="col-12">
                                    <h2 class="fw-bold">Data Inserted</h2>
                                </div>
                                <div class="col-12 col-sm-4">
                                    Changes Records
                                </div>
                                <div class="col-12 col-sm-8">
                                    @foreach($update as $attribute => $value)
                                        {{ ucwords(str_replace("_", " ", $attribute)) }} : {!! $value ?? "" !!} 
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="row mt-2 mb-2">
                                <h2 class="fw-bold">Data Inserted</h2>
                                @foreach($activity->updated_data as $key => $value)
                                    @if( $key == "password" || $key == "remember_token" || $key == "id")
                                        @continue
                                    @endif
                                    <div class="col-12 col-sm-4">
                                        {{ ucfirst($key) }}
                                    </div>
                                    <div class="col-12 col-sm-8">
                                        @if(!is_array($value))
                                            {{ $value }}
                                        @else
                                            {{ json_decode($value) }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection