@extends('layouts.front-end.app')
@section('content')
<main class="main">
   
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->
    
    <!-- Elements list -->
    <div class="container mb-5">
     
        <div class="col-lg-6 col-md-6 col-sm-12 offset-lg-3 offset-md-3 pt-4 pb-4">
            @if(session('success'))
                <div id="successMessage" class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <script>
                // Timeout function to remove the success message after 5 seconds
                setTimeout(function() {
                    var successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        successMessage.style.display = 'none';
                    }
                }, 5000); // 5000 milliseconds = 5 seconds
            </script>
            <form method="POST" action="{{ route('update.password') }}">
                @csrf
                <div ng-bind-html="message" class="form-group"></div>
                <div class="form-group">
                    <label for="singin-password-2">Current Password *</label>
                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="old-password">
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div><!-- End .form-group -->

               <div class="form-group">
                    <label for="password">New Password *</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div><!-- End .form-group -->

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary btn-rounded"><span>Change Password</span><i class="icon-long-arrow-right"></i></button></div>
            </form>
        </div>
    </div><!-- End Elements list -->
</main><!-- End .main -->
@endsection
