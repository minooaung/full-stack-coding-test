@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>
                        Welcome => {{ $name }}
                        @if ($is_admin)
                            (Admin User)
                        @else
                            (Employee)
                        @endif
                    </h4>
                    <p>{{ __('You are logged in!') }}</p>
                    
                    @if ($is_admin)                        
                        <br><br>
                        <button onclick="location.href='/users'" type="button" style="width:250px;">Manage Users</button>
                    @else                        
                        <p>You are not allowed to manage users</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
