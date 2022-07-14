@extends('layouts.app')

@section('content')
    <div>
        <ul>
            @foreach($urls as $url)
                <li>{{ $url->name }}</li>
            @endforeach
        </ul>
    </div>
@endsection
