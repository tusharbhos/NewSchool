@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>List of Classes</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.class.create') }}"><i class="fas fa-plus"></i> Add Class</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="items-table">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Class Name</th>
                            <th class="text-center">Associated Teachers</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                         
                          @foreach ($classes as $class)
                          @php
                          $teachers = "";
                          if(sizeof($class->teachers) > 0){
                            $teachers = $class->teachers->pluck('name')->toArray();
                            $teachers = implode(', ',$teachers);

                            $teachers_chunks = str_split($teachers, 60);
                          }else{
                            $teachers_chunks = ["-"];
                          }
                          @endphp
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $class->class_title }}</td>
                             <td>
                                @foreach ($teachers_chunks as $chunk) 
                                   {{ $chunk }}<br>
                                @endforeach
                            </td>
                            <td>@if($class->status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">Inactive</span>@endif </td>
                           
                            <td>
                                <a href="{{ route('admin.class.edit',['id' => $class->id]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit Details" ><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.class.show',['id' => $class->id]) }}" class="btn btn-info text-white btn-sm" data-toggle="tooltip" title="View Details" ><i class="fa fa-eye"></i></a>
                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Remove Class" onclick="removeClass({{$class->id}})"><i class="fas fa-trash-alt"></i></button>
                            </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
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
                emptyTable: "No class found.",
            },
            columnDefs: [{ targets: [0,1,2,3,4], className: "text-center"},{ targets: [1,2,3,4], sortable:false},{ targets: [3,4], width:120},{ targets: [0], width:50}]
        });
    })

    function removeClass(id) {
      swal({
          title: 'Remove Class',
          text: 'Are you sure you want to remove this class? Please note that this action cannot be undone.',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
              $('.loader').show();
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              $.ajax({
                  type: "GET",
                  url: "{{ route('admin.class.destroy',['id' => '0']) }}" + id,
                  data: { id: id, _token: csrfToken },
                  success: function (data) {
                      $('.loader').hide();
                      if (data['status']) {
                          swal(data['message'], {
                            icon: 'success',
                          }).then((data) => {
                              location.reload();
                          });
                      }else{
                          swal('Remove Class',data['message'], 'error');
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