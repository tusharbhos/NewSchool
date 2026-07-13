@extends('layouts.back-end.app')
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Principal Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.principal.edit', ['id' => $principal->id]) }}"><i class="fas fa-edit"></i> Edit Details</a>
                    <a class="btn btn-primary" href="{{ route('admin.principal.index') }}"><i class="fas fa-angle-double-left"></i> All Principal</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="{{ $principal->name }}" disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="text" class="form-control" value="{{ $principal->email }}" disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" value="{{ $principal->phone_number }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" style="height:auto !important;" disabled>{{ $principal->address }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-label">Status</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($principal->status == 1? "checked":"") }} disabled>
                                <span class="selectgroup-button">Active</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($principal->status == 0? "checked":"") }} disabled>
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