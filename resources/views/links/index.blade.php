@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Links</div>

                <div class="card-body">
                    <a href="{{ route('links.create') }}" class="btn btn-primary mb-3">Create New Link</a>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Original URL</th>
                                <th>Short URL</th>
                                <th>Clicks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($links as $link)
                            <tr>
                                <td>{{ $link->original_url }}</td>
                                <td>
                                    <a href="{{ $link->short_url }}" target="_blank">
                                        {{ $link->short_url }}
                                    </a>
                                </td>
                                <td>{{ $link->visits_count }}</td>
                                <td>
                                    <a href="{{ route('links.show', $link) }}" class="btn btn-sm btn-info">Stats</a>
                                    <form action="{{ route('links.destroy', $link) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
