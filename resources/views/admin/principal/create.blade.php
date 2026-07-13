@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Create Principal</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.principal.index') }}"><i class="fas fa-angle-double-left"></i> All Principal</a>
                </div>
            </div>
            <div class="card-body">
                <form id="principal-form" action="{{ route('admin.principal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Full Name<code>*</code></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" name="name" maxlength="100" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label>Email<code>*</code></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter a email" name="email" maxlength="100" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label>Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Enter a phone number" name="phone_number" maxlength="20" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" placeholder="Enter a address" class="form-control @error('address') is-invalid @enderror" maxlength="255" style="height:auto !important;" oninput="updateCharacterCount(this)">{{ old('address') }}</textarea>
                        <p class="text-right" style="padding:0px !important;"><small id="characterCount" >Characters remaining: 255</small></p>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_save_details">Save Principal</button>    
                    </div>  
                </form>
            </div>
        </div>    
    </div>
</div> 
@endsection

@section('javascript')
<script type="text/javascript">
    document.getElementById("btn_save_details").addEventListener("click", function() {
        var form = document.getElementById("principal-form");
        if (form.checkValidity()) {
            $('.loader').show();
            form.submit();
        } else {
            // Optionally, you can display an error message or style the form fields
            // Trigger form validation
            form.reportValidity();
            
            // Optionally, you can also focus the first invalid field
            var invalidField = form.querySelector(":invalid");
            if (invalidField) {
                invalidField.focus();
            }
        }
    });

</script>
@stop