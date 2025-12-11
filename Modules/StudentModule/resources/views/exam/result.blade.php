@extends('layoutmodule::admin.main')

@section('content')
<h3>Exam Result: {{ $exam->title }}</h3>

<p><strong>Status:</strong> {{ $pivot->status }}</p>
<p><strong>Score:</strong> {{ $pivot->score }}%</p>
<p><strong>Started At:</strong> {{ $pivot->started_at }}</p>
<p><strong>Completed At:</strong> {{ $pivot->completed_at }}</p>

@endsection
