@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Teacher Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.teacher.edit', ['id' => $teacher->id]) }}"><i class="fas fa-edit"></i> Edit Details</a>
                    <a class="btn btn-primary" href="{{ route('admin.teacher.index') }}"><i class="fas fa-angle-double-left"></i> All Teacher</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="{{ $teacher->name }}" disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="text" class="form-control" value="{{ $teacher->email }}" disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" value="{{ $teacher->phone_number }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" style="height:auto !important;" disabled>{{ $teacher->address }}</textarea>
                </div>

                <div class="form-group">

                    @php
                    $class_ids = [];
                    if(sizeof($teacher->classes) > 0){
                        $class_ids = $teacher->classes->pluck('id')->toArray();
                    }
                    @endphp

                    <label>Classes</label>
                    <select class="form-control select2" multiple disabled>
                        @foreach($classes as $class)
                        <option value="{{ $class->id}}" @if(in_array($class->id.'',$class_ids))selected @endif > {{ $class->class_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-label">Status</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($teacher->status == 1? "checked":"") }} disabled>
                                <span class="selectgroup-button">Active</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($teacher->status == 0? "checked":"") }} disabled>
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