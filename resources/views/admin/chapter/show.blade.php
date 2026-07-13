@extends('layouts.back-end.app')
@section('css')
<link rel="stylesheet" href="{{ asset('back-end/bundles/summernote/summernote-bs4.css') }}">
<style type="text/css">
    .banner_image{
        width: 100%; 
        height:400px!important; 
        object-fit: contain;
        padding: 4px 8px !important;
        background-color: #fdfdff;
        border-color: #e4e6fc;
        line-height: 1.5;
        color: #495057;
        background-clip: padding-box;
        border: 1px solid #ced4da;
    }


    @media only screen and (max-width: 767px) {
        .banner_image{
            height:200px !important; 
            object-fit: contain;
        }
    }

    @media only screen and (max-width: 480px) {
        .banner_image{
            height:137px !important; 
            object-fit: contain;
        }
    }
</style>
@stop
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Chapter Details</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.chapter.edit', ['id' => $chapter->id]) }}"><i class="fas fa-edit"></i> Edit Details</a>
                    <a class="btn btn-primary" href="{{ route('admin.chapter.index') }}"><i class="fas fa-angle-double-left"></i> All Chapters</a>
                </div>
            </div>
            <div class="card-body">
                    <div class="form-group">
                        <label>Chapter Title</label>
                        <input type="text" class="form-control" value="{{ $chapter->title }}" disabled>
                    </div>
                    <div class="form-row">
                       

                        <div class="form-group col-md-4">
                            <label>Release Date</label>
                            <input type="text" class="form-control" value="{{ date('d M, Y', strtotime($chapter->release_date)) }}" disabled>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label">Status</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($chapter->status == 1? "checked":"") }} disabled>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($chapter->status == 0? "checked":"") }} disabled>
                                  <span class="selectgroup-button">Inactive</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Visibility Settings</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="visibility" value="0" class="selectgroup-input-radio" @if($chapter->visibility == 0) checked @endif disabled><span class="selectgroup-button">Class-Specific</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="visibility" value="1" class="selectgroup-input-radio" @if($chapter->visibility == 1) checked @endif disabled>
                                  <span class="selectgroup-button">All Classes</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($chapter->visibility == 0)
                    <div class="form-group">
                        <label>Associated Classes</label>
                        <textarea class="form-control" style="height: auto !important;" disabled>{{ $classes }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Associated Teachers</label>
                        <textarea class="form-control" style="height: auto !important;" disabled>{{ $teachers }}</textarea>
                    </div>
                    @endif
                    <div class="form-group">
                       <label>Chapter Description</label>
                       <textarea class="summernote" id="description">{{ $chapter->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Chapter Banner <small class="text-dark">(Size: Max 4048KB | Dimensions: 1200 x 400)</small></label>
                        <img class="banner_image" src="{{ asset( 'media/'. $chapter->asset_path .'/banner/banner_'. $chapter->chapter_image) }}" onerror="this.src='{{ asset("back-end/img/banner/placeholder_banner.png") }}'" />
                    </div>
                </form>
            </div>
        </div> 
    </div>
    <div class="form-row col-lg-12">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Chapter Videos/Audio</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="videos-table">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">File Name</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($videos as $file)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $file }} </td>
                                <td> <a href="{{ asset('media/'. $chapter->asset_path . '/videos/' . $file) }}" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="View Audio/Video File" target="_blank"><i class="fa fa-eye"></i></a></td>
                              </tr>
                              @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Additional Chapter Material</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="attachments-table">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">File Name</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($attachments as $file)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $file }} </td>
                                <td> <a href="{{ asset('media/'. $chapter->asset_path . '/attachements/' . $file) }}" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="View Additional Chapter Material" target="_blank"><i class="fa fa-eye"></i></a></td>
                              </tr>
                              @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection
@section('javascript')
<script src="{{ asset('back-end/bundles/summernote/summernote-bs4.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#description').summernote('disable');
    });

    $("#videos-table").dataTable({
        searching: false, paging: false, info: false,
        language: {
            emptyTable: "No video/audio found.",
        },
        columnDefs: [{ targets: [0,1,2], className: "text-center"},{ targets: [1,2], sortable:false}]
    });

    $("#attachments-table").dataTable({
        searching: false, paging: false, info: false,
        language: {
            emptyTable: "No additional chapter material found.",
        },
        columnDefs: [{ targets: [0,1,2], className: "text-center"},{ targets: [1,2], sortable:false}]
    });
</script>
@stop