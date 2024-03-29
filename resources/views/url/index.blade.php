@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Сайты</h1>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Последняя проверка</th>
                <th>Код ответа</th>
            </tr>

            @foreach($urls as $url)
                <tr>
                    <td>{{ $url->id }}</td>
                    <td><a href="{{ route('urls.show', $url->id)}}">{{ $url->name }}</a></td>
                    <td>{{ $urlChecks[$url->id]->last_created_at ?? '' }}</td>
                    <td>{{ $urlChecks[$url->id]->status_code ?? ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $urls->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
