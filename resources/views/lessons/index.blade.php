@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Bijlessen</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead class="thead">
                            <tr>
                                <th>id</th>
                                <th>Datum</th>
                                <th>Tijd</th>
                                <th>Lengte(uren)</th>
                                <th>Gebruiker</th>
                                <th>Docent</th>
                            </tr>
                            </thead>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>
                                        <a href="{{ $lesson->path() }}">
                                            {{$lesson->id}}
                                        </a>
                                    </td>
                                    <td>{{$lesson->date}}</td>
                                    <td>{{$lesson->time}}</td>
                                    <td>{{$lesson->durationInHours}}</td>
                                    <td>{{$lesson->user->firstName}}</td>
                                    <td>{{$lesson->tutor->firstName}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
