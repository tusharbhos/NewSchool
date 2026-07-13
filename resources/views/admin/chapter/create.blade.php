@extends('layouts.back-end.app')
@section('css')
<link rel="stylesheet" href="{{ asset('back-end/bundles/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>
    .image-preview{
        background-image:url({{ asset('back-end/img/banner/placeholder_banner.png') }});
        background-size: contain; 
        width: 100%; 
        height: 360px;
    }

    @media only screen and (max-width: 767px) {
        .image-preview{
            height: 200px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .image-preview{
            height: 137px !important;
        }
    }
</style>
@stop
@section('content')
<div class="row ">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Create Chapter</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.chapter.index') }}"><i class="fas fa-angle-double-left"></i> All Chapters</a>
                </div>
            </div>
            <div class="card-body">
                <form id="chapter-form" action="{{ route('admin.chapter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_form_id" value="{{ old('_form_id',$randomFolderName) }}" autocomplete="off">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Chapter Title<code>*</code></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter chapter title" name="title" maxlength="255" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label>Release Date<code>*</code></label>
                            <input type="text" class="form-control datepicker @error('release_date') is-invalid @enderror" id="release_date" name="release_date" min="{{ date('Y-m-d') }}" value="{{ old('release_date') }}" required>
                            @error('release_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Visibility Settings: All Classes vs. Class-Specific<code>*</code> <span class="text-info" data-toggle="tooltip" title="All Classes: Accessible to all teachers associated with any class. Class-Specific: Restricted to teachers associated with a particular class, as determined by your selection."><i class="fas fa-info-circle"></i></span></label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="visibility" value="0" class="selectgroup-input-radio" @if(old('visibility') == 0) checked @endif><span class="selectgroup-button">Class-Specific</span>
                                </label>
                                <label class="selectgroup-item">
                                  <input type="radio" name="visibility" value="1" class="selectgroup-input-radio" @if(old('visibility') == 1) checked @endif>
                                  <span class="selectgroup-button">All Classes</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Associated Classes</label>
                        <select class="form-control select2  @error('class_id') is-invalid @enderror" id="class_id" name="class_id[]" multiple>
                            <option disabled>Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" data-teachers='@json($class->teachers)'>{{ $class->class_title }}</option>
                            @endforeach
                        </select>
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
                       <textarea class="summernote @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Chapter Banner <small class="text-dark">(Size: Max 4048KB | Dimensions: 1200 x 400)</small></label>
                        <div id="image-preview" class="image-preview" style="" >
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

                <div class="form-group">
                    <label>Chapter Videos/Audio</label>
                    <form action="{{ route('video-file-upload') }}" class="dropzone" id="media">
                        @csrf
                        <input type="file" name="file" style="display: none;">
                        <input type="hidden" name="upload_type" value="videos">
                        <input type="hidden" name="folder_type"  value="{{ old('_form_id',$randomFolderName) }}">
                    </form>
                    <button id="browseButton" class="btn btn-primary" style="border-radius: 0px !important;">Browse Chapter Videos/Audio</button>
                </div>

                <div class="form-group">
                    <label>Additional Chapter Material</label>
                    <form action="{{ route('video-file-upload') }}" class="dropzone" id="attachments">
                        <input type="file" name="file"  style="display: none;">
                        @csrf
                        <input type="hidden" name="upload_type" value="attachements">
                        <input type="hidden" name="folder_type"  value="{{ old('_form_id',$randomFolderName) }}">
                    </form>
                    <button id="browseAttachementButton" class="btn btn-primary" style="border-radius: 0px !important;">Browse Additional Chapter Material</button>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary" id="btn_save_chapter">Save Chapter</button>    
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
                $('#teachers').append(new Option(teacher.name + " (" + classTitles + ")", teacher.id, true, true));
            });
        });
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
            form.reportValidity();            
            var invalidField = form.querySelector(":invalid");
            if (invalidField) {
                invalidField.focus();
            }
        }
    });

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
          folder_type : "{{ old('_form_id',$randomFolderName) }}"
        },
        success: function (data){
            if (data["status"] == 'success') {
                iziToast.success({
                  title: 'Remove Status',
                  message: "File remove successfully",
                  position: 'topRight'
                });
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
          folder_type : "{{ old('_form_id',$randomFolderName) }}"
        },
        success: function (data){
            if (data["status"] == 'success') {
                iziToast.success({
                  title: 'Remove Status',
                  message: "File remove successfully",
                  position: 'topRight'
                });
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

    var saveButton = document.getElementById('btn_save_chapter');

    document.getElementById('browseButton').addEventListener('click', function() {
        myDropzone.hiddenFileInput.click();
    });

    document.getElementById('browseAttachementButton').addEventListener('click', function() {
        myAttachmentsDropzone.hiddenFileInput.click();
    });

    myDropzone.on("sending", function(file) {
        saveButton.disabled = true;
        console.log("Upload started");
    });

    myDropzone.on("queuecomplete", function() {
        saveButton.disabled = false;
        console.log("All files have uploaded");
    });

    myAttachmentsDropzone.on("sending", function(file) {
        saveButton.disabled = true;
        console.log("Upload started");
    });

    myAttachmentsDropzone.on("queuecomplete", function() {
        saveButton.disabled = false;
        console.log("All files have uploaded");
    });

    // myDropzone.on("thumbnail", function(file) {
    //     if (file.type.startsWith('audio/')) {
    //         var customIconPath = "{{asset('back-end/img/banner/audiobook.svg') }}";
    //         var previewElement = file.previewElement;
    //         var image = previewElement.querySelector(".dz-image img");
    //         if (image) {
    //             image.src = customIconPath;
    //         }
    //     }
    // });

    // myDropzone.on("addedfile", function(file) {
    //     if (file.type.startsWith('audio/') || file.type.startsWith('video/')) {
    //         var customIconPath = "{{asset('back-end/img/banner/audiobook.svg') }}";
            
    //         if (file.type.startsWith('video/')) {
    //             customIconPath = "{{asset('back-end/img/banner/video-book.svg') }}";
    //         }

    //         var previewElement = file.previewElement;
    //         var image = previewElement.querySelector(".dz-image img");

    //         if (image) {
    //             image.src = customIconPath;
    //             image.style.width = "64px"; // Example: Set width
    //             image.style.height = "64px"; // Example: Set height
    //         }

    //         // Ensure file name is visible
    //         var filenameElement = previewElement.querySelector(".dz-filename span");
    //         if (filenameElement) {
    //             filenameElement.innerText = file.name;
    //         } else {
    //             const filenameSpan = document.createElement('span');
    //             filenameSpan.classList.add('dz-filename');
    //             filenameSpan.innerHTML = `<span>${file.name}</span>`;
    //             previewElement.appendChild(filenameSpan);
    //         }
    //     }
    // });
</script>
@stop