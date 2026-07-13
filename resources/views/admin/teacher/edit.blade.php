@extends('layouts.back-end.app')
@section('css')
<style type="text/css">
    .selectgroup-button {
        line-height: 42px !important;
        height: 42px !important;
    }
</style>
@stop
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Update Teacher Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.teacher.index') }}"><i class="fas fa-angle-double-left"></i> All Teachers</a>
                </div>
            </div>
            <div class="card-body">
                <form id="teacher-form" action="{{ route('admin.teacher.update',['id' => $teacher->id ]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Full Name<code>*</code></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" name="name" maxlength="100" value="{{ old('name',$teacher->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Email<code>*</code></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter a email" name="email" maxlength="100" value="{{ old('email',$teacher->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Enter a phone number" name="phone_number" maxlength="20" value="{{ old('phone_number',$teacher->phone_number) }}">
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-9">
                            @php
                            $class_ids = [];
                            if(sizeof($teacher->classes) > 0){
                                $class_ids = $teacher->classes->pluck('id')->toArray();
                            }
                            @endphp

                            <label>Classes</label>
                            <select class="form-control select2" name="classes[]"  multiple>
                                @foreach($classes as $class)
                                <option value="{{ $class->id}}" @if(in_array($class->id.'',$class_ids))selected @endif > {{ $class->class_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">Status</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($teacher->status == 1? "checked":"") }}>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($teacher->status == 0? "checked":"") }}>
                                  <span class="selectgroup-button">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" placeholder="Enter a address" class="form-control @error('address') is-invalid @enderror" maxlength="255" style="height:auto !important;" oninput="updateCharacterCount(this)">{{ old('address',$teacher->address) }}</textarea>
                        <p class="text-right" style="padding:0px !important;"><small id="characterCount" >Characters remaining: {{ 255 - strlen($teacher->address)}}</small></p>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_updated_details">Update Details</button>    
                    </div>  
                </form>
            </div>
        </div> 

        <div class="card">
            <div class="card-header">
                <h4>Change Password</h4>
            </div>
            <div class="card-body">
                <form id="change-password-form" action="{{ route('admin.change.password',['id' => $teacher->id ]) }}" method="GET" class="needs-validation" novalidate="">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <label>Password<code>*</code></label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" id="password" maxlength="20" minlength="6" value="{{ old('password') }}" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>                    
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_change_password">Change Password</button>    
                        <button type="button" class="btn btn-primary" onclick="generateRandomPassword()">Auto Generate</button>    
                    </div>  
                </form>
            </div>
        </div>    
    </div>
</div> 
@endsection
@section('javascript')
<script type="text/javascript">
    document.getElementById("btn_updated_details").addEventListener("click", function() {
        var form = document.getElementById("teacher-form");
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

    document.getElementById("btn_change_password").addEventListener("click", function() {
        var form = document.getElementById("change-password-form");
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


    function generateRandomPassword() {
        // Define the character set for the password
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        // Initialize the password string
        var password = "";

        // Generate the random password
        for (var i = 0; i < 8; i++) {
            var randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }


        $("#password").val(password);
    }
</script>
@stop