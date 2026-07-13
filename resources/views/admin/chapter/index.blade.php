@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>List of Chapters</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.chapter.create') }}"><i class="fas fa-plus"></i> Add Chapter</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" id="items-table">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"></th>
                            <th class="text-center">Chapter Name</th>
                            <th class="text-center">Class Name</th>
                            <th class="text-center">Creation Date</th>
                            <th class="text-center">Release Date</th>
                            <th class="text-center">Associated Teacher</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($chapters as $chapter)

                          @php
                          $title_chunks = str_split($chapter->title, 60);
                          $teachers = "";
                          if($chapter->visibility == 0 && sizeof($chapter->teachers) > 0){
                            $teachers = $chapter->teachers->pluck('name')->toArray();
                            $teachers = implode(', ',$teachers);

                            $teachers_chunks = str_split($teachers, 60);
                          }else{
                            if($chapter->visibility == 1) {
                                $teachers_chunks = ["All"];
                            }else{
                                $teachers_chunks = ["-"];
                            }
                          }
                          @endphp
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center" style="padding: 4px;"><img alt="logo" style="box-shadow: none; width: 90px; height: 33px;object-fit: contain;" src="{{ asset( 'media/'. $chapter->asset_path .'/banner/thumb_'. $chapter->chapter_image) }}" onerror="this.src='{{ asset( "front-end/images/no_course.svg") }}'"></td>
                            <td>
                                @foreach ($title_chunks as $chunk) 
                                   {{ $chunk }}<br>
                                @endforeach
                            </td>
                            <td>{{ $chapter->classes }}</td>
                            <td>{{ date('d M, Y',strtotime($chapter->created_at)) }}</td>
                            <td>{{ date('d M, Y',strtotime($chapter->release_date)) }}</td>
                            <td>
                                @foreach ($teachers_chunks as $chunk) 
                                   {{ $chunk }}<br>
                                @endforeach
                            </td>
                            <td>@if($chapter->status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">Inactive</span>@endif </td>
                           
                            <td>
                                <a href="{{ route('admin.chapter.edit',['id' => $chapter->id]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit Details"  ><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.chapter.show',['id' => $chapter->id]) }}" class="btn btn-info text-white btn-sm" data-toggle="tooltip" title="View Details" ><i class="fa fa-eye"></i></a>
                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Remove Class" onclick="removeClass({{$chapter->id}})"><i class="fas fa-trash-alt"></i></button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {!! $chapters->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $("#items-table").dataTable({
            language: {
                emptyTable: "No chhapters found.",
            },
            columnDefs: [{ targets: [0,1,2,3,4,5,6,7,8], className: "text-center"},{ targets: [1,2,3,4,5,6,7,8], sortable:false},{ targets: [2,6], width:120},{ targets: [8], width:120}]
        });
    });

    function removeClass(id) {
        swal({
            title: 'Remove Chapter',
            text: 'Are you sure you want to remove this chapter? Please note that this action cannot be undone.',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $('.loader').show();
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.chapter.destroy',['id' => '0']) }}" + id,
                    success: function (data) {
                        $('.loader').hide();
                        if (data['status']) {
                            swal(data['message'], {
                                icon: 'success',
                            }).then((data) => {
                                location.reload();
                            });
                        }else{
                            swal('Remove Chapter',data['message'], 'error');
                        }
                    },
                    error: function (response) {
                        $('.loader').hide();
                    }
                });
            }
        });
    }
</script>
@stop