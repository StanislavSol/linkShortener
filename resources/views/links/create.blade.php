@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Short Link</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('links.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="original_url">Original URL</label>
                            <input type="url" class="form-control @error('original_url') is-invalid @enderror"
                                   id="original_url" name="original_url" required>
                            @error('original_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
