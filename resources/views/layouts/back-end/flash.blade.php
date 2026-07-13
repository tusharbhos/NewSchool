@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    
@endif
@if (session('failure'))
    <div class="alert alert-danger" role="alert">
        {{ session('failure') }}
    </div>
@endif

{{ Session::forget('success') }}
{{ Session::forget('failure') }} 