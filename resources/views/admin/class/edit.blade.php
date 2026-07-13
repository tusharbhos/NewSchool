@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Update Class Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.class.index') }}"><i class="fas fa-angle-double-left"></i> All Classes</a>
                </div>
            </div>
            <div class="card-body">
                <form id="class-form" action="{{ route('admin.class.update',['id' => $class->id ]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Class Name<code>*</code></label>
                            <input type="text" class="form-control @error('class_title') is-invalid @enderror" placeholder="Enter class name" name="class_title" maxlength="100" value="{{ old('class_title',$class->class_title) }}" required>
                            @error('class_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label">Status</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($class->status == 1? "checked":"") }}>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($class->status == 0? "checked":"") }}>
                                  <span class="selectgroup-button">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="btn_save_details">Update Details</button>    
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