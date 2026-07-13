@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Create Class</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.class.index') }}"><i class="fas fa-angle-double-left"></i> All Classes</a>
                </div>
            </div>
            <div class="card-body">
                <form id="class-form" action="{{ route('admin.class.store') }}" method="POST" class="needs-validation"enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Class Name<code>*</code></label>
                        <input type="text" class="form-control @error('class_title') is-invalid @enderror" placeholder="Enter class name" name="class_title" maxlength="100" value="{{ old('class_title') }}" required>
                        @error('class_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
               
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_save_details">Save Class</button>    
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
        var form = document.getElementById("class-form");
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