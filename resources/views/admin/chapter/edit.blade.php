@extends('layouts.back-end.app')
@section('css')
<link rel="stylesheet" href="{{ asset('back-end/bundles/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style type="text/css">
    .image-preview{
        background-size: cover; 
        background-repeat: no-repeat;
        width: 100%; 
        height: 360px;
    }


    @media only screen and (max-width: 767px) {
        .image-preview{
            height: 200px !important;
        }
    }

    .badge-info{
            display-inline:block !important;
        }

    @media only screen and (max-width: 480px) {
        .image-preview{
            height: 137px !important;
        }

        .badge-info{
            display-inline:none !important;
        }
    }
</style>
@stop
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Chapter</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.chapter.index') }}"><i class="fas fa-angle-double-left"></i> All Chapters</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <code><b>Please remember to click 'Save Chapter' after entering information in the form below.</b></code>
                </div>

                <form id="chapter-form" action="{{ route('admin.chapter.update',['id' => $chapter->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateFileSize()">
                    @csrf
                    <input type="hidden" name="_form_id" value="{{ old('_form_id',$chapter->asset_path) }}" autocomplete="off">

                    <div class="form-group">
                        <label>Chapter Title<code>*</code></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter chapter title" name="title" maxlength="255" value="{{ old('title',$chapter->title) }}" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-row">
                       

                        <div class="form-group col-md-4">
                            <label>Release Date<code>*</code></label>
                            @if($isDateLessThanCurrent) 
                            <input type="hidden" class="form-control datepicker" id="release_date" name="release_date" value="{{ old('release_date',$chapter->release_date) }}">
                            <input type="text" class="form-control" value="{{ $chapter->release_date }}" disabled>
                            @else
                             <input type="text" class="form-control datepicker @error('release_date') is-invalid @enderror" id="release_date" name="release_date" min="{{ date('Y-m-d') }}" value="{{ old('release_date',$chapter->release_date) }}"  required>
                            @endif
                           
                            @error('release_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @php
                            $class = "";
                            
                            for($i=0; sizeof($classes) > $i; $i++){
                                if($classes[$i]['id'] == $chapter->class_id){
                                    $class = $classes[$i]['class_title'];
                                }
                            }
                        @endphp

                        <div class="form-group col-md-4">
                            <label class="form-label">Status</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status" value="1" class="selectgroup-input-radio"  {{ ($chapter->status == 1? "checked":"") }}>
                                    <span class="selectgroup-button">Active</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="status" value="0" class="selectgroup-input-radio" {{ ($chapter->status == 0? "checked":"") }}>
                                  <span class="selectgroup-button">Inactive</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label">Visibility Settings: All Classes vs. Class-Specific<code>*</code> <span class="text-info" data-toggle="tooltip" title="All Classes: Accessible to all teachers associated with any class. Class-Specific: Restricted to teachers associated with a particular class, as determined by your selection."><i class="fas fa-info-circle"></i></span></label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="visibility" value="0" class="selectgroup-input-radio" @if(old('visibility',$chapter->visibility) == 0) checked @endif><span class="selectgroup-button">Class-Specific</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="visibility" value="1" class="selectgroup-input-radio" @if(old('visibility',$chapter->visibility) == 1) checked @endif>
                                  <span class="selectgroup-button">All Classes</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Associated Classes</label>
                        <select class="form-control select2  @error('class_id') is-invalid @enderror" id="class_id" name="class_id[]" multiple>
                            <option value="" disabled>Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"  data-teachers='@json($class->teachers)' @if(in_array($class->id,json_decode($chapter->class_data,true))) selected @endif> {{ $class->class_title }}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Select Associated Teacher</label>
                        <select class="form-control select2  @error('teachers') is-invalid @enderror" id="teachers" name="teachers[]" multiple>
                            <option value="" disabled>Select Teacher</option>
                        </select>
                        @error('teachers')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                       <label>Chapter Description</label>
                       <textarea class="summernote @error('description') is-invalid @enderror" name="description">{{ old('description',$chapter->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Chapter Banner <small class="text-dark">(Size: Max 4048KB | Dimensions: 1200 x 400)</small></label>
                        <div id="image-preview" class="image-preview" style="background-image:url({{ asset( 'media/'. $chapter->asset_path .'/banner/banner_'. $chapter->chapter_image) }});">
                          <label for="image-upload" id="image-label">Choose File</label>
                          <input type="file" name="image" id="image-upload" accept="image/png, image/jpg, image/jpeg" />
                        </div>
                        <input type="hidden" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="card-footer">
                
                <button type="button" class="btn btn-primary" id="btn_save_chapter">Save Chapter</button>
                    
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
                    <div class="form-group">
                        <code><b>Dropbox automatically saves uploaded files, so there's no need to manually save.</b></code>
                        <form action="{{ route('video-file-upload') }}" class="dropzone" id="media">
                            @csrf
                            <input type="file" name="file"  style="display: none;">
                            <input type="hidden" name="upload_type" value="videos">
                            <input type="hidden" name="folder_type"  value="{{ old('_form_id',$chapter->asset_path) }}">
                        </form>
                        <button id="browseButton" class="btn btn-primary" style="border-radius: 0px !important;">Browse Chapter Videos/Audio</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="videos-table">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">File Name</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
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

                    <div class="form-group">
                        <code><b>Dropbox automatically saves uploaded files, so there's no need to manually save.</b></code>
                        <form action="{{ route('video-file-upload') }}" class="dropzone" id="attachments">
                            <input type="file" name="file"  style="display: none;">
                            @csrf
                            <input type="hidden" name="upload_type" value="attachements">
                            <input type="hidden" name="folder_type"  value="{{ old('_form_id',$chapter->asset_path) }}">
                        </form>
                        <button id="browseAttachementButton" class="btn btn-primary" style="border-radius: 0px !important;">Browse Additional Chapter Material</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="attachments-table">
                            <thead>
                              <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">File Name</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
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
<script src="{{ asset('back-end/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('back-end/js/page/create-post.js') }}"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


<script type="text/javascript">

    $(document).ready(function() {
        $('#class_id').select2({
            placeholder: "Select classes"
        });

        $('#teachers').select2({
            placeholder: "Select teachers"
        });

        $(".datepicker").daterangepicker({
            locale: { format: "YYYY-MM-DD" },
            singleDatePicker: true,
            minDate:moment()
        });

        $('#class_id').change(function() {
            let uniqueTeachers = {};
            $(this).find('option:selected').each(function() {
                var teachers = $(this).data('teachers');
                $.each(teachers, function(index, teacher) {
                    if (!uniqueTeachers[teacher.id]) {
                        uniqueTeachers[teacher.id] = {
                            name: teacher.name,
                            classes: teacher.classes,
                            id: teacher.id
                        };
                    } else {
                        teacher.classes.forEach(c => {
                            if (uniqueTeachers[teacher.id].classes.findIndex(x => x.class_title === c.class_title) === -1) {
                                uniqueTeachers[teacher.id].classes.push(c);
                            }
                        });
                    }
                });
            });

            $('#teachers').empty();
            $.each(uniqueTeachers, function(id, teacher) {
                let classTitles = teacher.classes.map(c => c.class_title).join(", ");
                $('#teachers').append(new Option(teacher.name + " (" + classTitles + ")", teacher.id, false, true));
            });

            
        });

        $(".datepicker").daterangepicker({
            locale: { format: "YYYY-MM-DD" },
            singleDatePicker: true,
            minDate:moment()
        });

        $('#class_id').trigger('change');
        
        getMediaFile();
        getAttachementsFile();   
        

        $('#teachers').val(@json($ids)).trigger('change');
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

    document.getElementById("btn_save_chapter").addEventListener("click", function() {
        var fileInput = document.getElementById('image-upload');
        if (fileInput.files.length > 0) {
            var fileSize = fileInput.files[0].size; // in bytes
            var maxSize = 4194304; // 4 MB in bytes

            if (fileSize > maxSize) {
                iziToast.warning({
                    title: 'Banner Image',
                    message: 'Chapter banner upload max size is 4048KB.',
                    position: 'topRight'
                });
                return false; // prevent form submission
            }
        }


        var form = document.getElementById("chapter-form");
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

    function validateFileSize() {
        var fileInput = document.getElementById('image-upload');
        if (fileInput.files.length > 0) {
            var fileSize = fileInput.files[0].size; // in bytes
            var maxSize = 4194304; // 4 MB in bytes

            if (fileSize > maxSize) {
                iziToast.warning({
                    title: 'Logo Upload',
                    message: 'Course logo upload max size is 4048KB.',
                    position: 'topRight'
                });
                return false; // prevent form submission
            }
        }
        return true; // allow form submission
    }

    var myDropzone = new Dropzone("#media", {
      dictDefaultMessage: '<i class="fa fa-file-video text-danger"></i> &#x2022; <i class="fa fa-file-audio text-success"></i><br>Browse or drag and drop audio/video(.mp4,.mov,.ogg,.wav,.mp3) files here to upload.',
      parallelUploads: 1,  
      maxFilesize: 1024,
      chunking: true, 
      forceChunking: true,
      parallelChunkUploads: true,
      chunkSize: 2000000,
      retryChunks: true,
      retryChunksLimit: 3,
      renameFile: function(file) {
        return file.name;
      },
      removedfile: function(file) {
        var name = file.upload.filename;
        $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("video-file-delete") }}',
        data: {
          filename: name,
          upload_type : "videos",
          folder_type : "{{ old('_form_id',$chapter->asset_path) }}"
        },
        success: function (data){
            if (data["status"] == 'success') {
                iziToast.success({
                  title: 'Remove Status',
                  message: "File remove successfully",
                  position: 'topRight'
                });
                getMediaFile();
            }
        },
        error: function(e) {
          console.log(e);
        }});
        var fileRef;
        return (fileRef = file.previewElement) != null ?
          fileRef.parentNode.removeChild(file.previewElement) : void 0;
      },
      acceptedFiles: '.mp4,.mov,.ogg,.wav,.mp3',
      addRemoveLinks: true,
      timeout: 50000,
      success: function(file, response)
      {
        iziToast.success({
          title: 'Upload Status',
          message: "File upload successfully",
          position: 'topRight'
        });
        getMediaFile();
      },
      error: function(file, response)
      {
        iziToast.error({
          title: 'Invalid File Format!',
          message: response,
          position: 'topRight'
        });
        this.removeFile(file);
      }
    });

    var myAttachmentsDropzone = new Dropzone("#attachments", {
      dictDefaultMessage: '<i class="fa fa-file-pdf text-danger"></i> &#8226; <i class="fa fa-file-excel text-success"></i> &#x2022; <i class="fa fa-file-word text-info"></i><br>Browse or drag and drop PDF/Word/Excel files here to upload.',
      parallelUploads: 1,
      maxFilesize: 1024,
      chunking: true, 
      forceChunking: true,
      parallelChunkUploads: true,
      chunkSize: 2000000,
      retryChunks: true, 
      retryChunksLimit: 3,
      renameFile: function(file) {
        return file.name;
      },
      removedfile: function(file) {
        var name = file.upload.filename;
        $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("video-file-delete") }}',
        data: {
          filename: name,
          upload_type : "attachements",
          folder_type : "{{ old('_form_id',$chapter->asset_path) }}"
        },
        success: function (data){
            if (data["status"] == 'success') {
                iziToast.success({
                  title: 'Remove Status',
                  message: "File remove successfully",
                  position: 'topRight'
                });
                getAttachementsFile();
            }
        },
        error: function(e) {
          console.log(e);
        }});
        var fileRef;
        return (fileRef = file.previewElement) != null ?
          fileRef.parentNode.removeChild(file.previewElement) : void 0;
      },
      acceptedFiles: '.pdf,.doc,.docx,.xls,.xlsx',
      addRemoveLinks: true,
      timeout: 50000,
      success: function(file, response)
      {
        iziToast.success({
          title: 'Upload Status',
          message: "File upload successfully",
          position: 'topRight'
        });
        getAttachementsFile();
      },
      error: function(file, response)
      {
        iziToast.error({
          title: 'Invalid File Format!',
          message: response,
          position: 'topRight'
        });
        this.removeFile(file);
      }
    });

    function getMediaFile(){
        $.ajax({
            type: "GET",
            url: "{{ route('chapter.attachements',['assets' => $chapter->asset_path, 'type' => 'videos']) }}",
            cache: false,
            async: true,
            success: function(response) {
                if (response.length > 0) {
                    $("#videos-table").dataTable().fnDestroy();
                    var v1 = $("#videos-table").dataTable({
                        searching: false, paging: false, info: false,
                        language: {
                            emptyTable: "No video/audio found.",
                        },
                        columnDefs: [{ targets: [0,1,2], className: "text-center"},{ targets: [1,2], sortable:false}]
                    }).api();
                    v1.rows().remove();

                    for (var i = 0; i < response.length; i++) {
                        var file = response[i];
                        var path = "media/{{ $chapter->asset_path }}/videos/"+file;
                        var actions = '<a href="{{ asset("") }}'+path+'" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="View Audio/Video File" target="_blank"><i class="fa fa-eye"></i></a> <button class="btn btn-outline-danger btn-sm ml-1" data-toggle="tooltip" title="Remove Audio/Video File" onclick="delete_file(`videos`,`'+file+'`);"><i class="fas fa-trash-alt"></i> </button>';
                        v1.row.add([
                            i + 1,
                            file,
                            actions
                        ]).draw();
                    }

                    $('[data-toggle="tooltip"]').tooltip('update');
                }else{
                    var v1 = $("#videos-table").dataTable().api();
                    v1.rows().remove();
                    v1.rows().draw();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });     
    }

    function getAttachementsFile(){
        $.ajax({
            type: "GET",
            url: "{{ route('chapter.attachements',['assets' => $chapter->asset_path, 'type' => 'attachements']) }}",
            cache: false,
            async: true,
            success: function(response) {
                if (response.length > 0) {
                    $("#attachments-table").dataTable().fnDestroy();
                    var v1 = $("#attachments-table").dataTable({
                        searching: false, paging: false, info: false,
                        language: {
                            emptyTable: "No additional chapter material found.",
                        },
                        columnDefs: [{ targets: [0,1,2], className: "text-center"},{ targets: [1,2], sortable:false}]
                    }).api();
                    v1.rows().remove();

                    for (var i = 0; i < response.length; i++) {
                        var file = response[i];
                        var path = "media/{{ $chapter->asset_path }}/attachements/"+file;
                        var actions = '<a href="{{ asset("") }}'+path+'" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="View Additional Chapter Material" target="_blank"><i class="fa fa-eye"></i></a> <button class="btn btn-outline-danger btn-sm ml-1" data-toggle="tooltip" title="Remove Additional Chapter Material" onclick="delete_file(`attachements`,`'+file+'`);"><i class="fas fa-trash-alt"></i> </button>';
                        v1.row.add([
                            i + 1,
                            file,
                            actions
                        ]).draw();
                    }

                    $('[data-toggle="tooltip"]').tooltip('update');
                }else{
                    var v1 = $("#attachments-table").dataTable().api();
                    v1.rows().remove();
                    v1.rows().draw();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });    
    }

    function delete_file(folder,file) {
        swal({
              title: 'Remove File',
              text: 'Are you sure you want to remove this file? Please note that this action cannot be undone.',
              icon: 'warning',
              buttons: true,
              dangerMode: true,
        }).then((willDelete) => {
              if (willDelete) {
                $('.loader').show();
                $.ajax({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("video-file-delete") }}',
                data: {
                  filename: file,
                  upload_type : folder,
                  folder_type : "{{ $chapter->asset_path }}"
                },
                success: function (response){
                    $('.loader').hide();
                    if (response["status"] == 'success') {
                        swal("","File remove successfully.", 'success');

                        if (folder == 'videos') {
                            getMediaFile();
                        }else{
                            getAttachementsFile();
                        }
                    }
                },
                error: function(e) {
                    $('.loader').hide();
                  console.log(e);
                }});
            }
        });
    }


    document.getElementById('browseButton').addEventListener('click', function() {
        myDropzone.hiddenFileInput.click();
    });

    document.getElementById('browseAttachementButton').addEventListener('click', function() {
        myAttachmentsDropzone.hiddenFileInput.click();
    });
</script>
@stop