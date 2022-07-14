@extends('layouts.app')

@section('content')
<div>
    <ul>
        <li>{{ $url->id }}</li>
        <li>{{ $url->name }}</li>
        <li>{{ $url->created_at }}</li>
    </ul>
</div>
@endsection
