@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Stats for: {{ $link->short_url }}</div>

                <div class="card-body">
                    <p><strong>Original URL:</strong> {{ $link->original_url }}</p>
                    <p><strong>Total Clicks:</strong> {{ $link->visits->count() }}</p>

                    <h5 class="mt-4">Recent Clicks</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>IP Address</th>
                                <th>Visited At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                            <tr>
                                <td>{{ $visit->ip_address }}</td>
                                <td>{{ $visit->visited_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
