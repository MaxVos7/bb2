@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Bijles {{ $lesson->id }}</div>

                    <div class="card-body">
                        <h5 class="card-title">Datum: {{ $lesson->date }}</h5>
                        <h5 class="card-title">Tijd: {{ $lesson->time }}</h5>
                        <h5 class="card-title">Lengte: {{ $lesson->durationInHours }} uur</h5>
                        <h5 class="card-title">Gebruiker: {{ $lesson->user->firstName }}</h5>
                        <h5 class="card-title">Tutor: {{ $lesson->tutor->firstName }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
