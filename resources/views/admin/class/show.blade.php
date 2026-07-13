@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Class Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.class.edit', ['id' => $class->id]) }}"><i class="fas fa-edit"></i> Edit Details</a>
                    <a class="btn btn-primary" href="{{ route('admin.class.index') }}"><i class="fas fa-angle-double-left"></i> All Classes</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label>Class Name</label>
                        <input type="text" class="form-control" value="{{ $class->class_title }}" disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="form-label">Status</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($class->status == 1? "checked":"") }} disabled>
                                <span class="selectgroup-button">Active</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($class->status == 0? "checked":"") }} disabled>
                              <span class="selectgroup-button">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div> 
@endsection