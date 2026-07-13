@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>List of Teachers</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('admin.teacher.create') }}"><i class="fas fa-plus"></i> Add Teacher</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" id="items-table">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Teacher Name</th>
                            <th class="text-center">Phone No.</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Associated Classes</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                         
                          @foreach ($users as $user)
                          @php
                          $classes = "";
                          if(sizeof($user->classes) > 0){
                            $classes = $user->classes->pluck('class_title')->toArray();
                            $classes = implode(', ',$classes);
                          }else{
                            $classes = "-";
                          }
                          @endphp
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $classes }}</td>
                            <td>@if($user->status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">Inactive</span>@endif </td>
                           
                            <td>
                                <a href="{{ route('admin.teacher.edit',['id' => $user->id]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Edit Details" ><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.teacher.show',['id' => $user->id]) }}" class="btn btn-info text-white btn-sm" data-toggle="tooltip" title="View Details" ><i class="fa fa-eye"></i></a>
                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" title="Remove Teacher" onclick="remove({{$user->id}})"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-primary btn-sm" data-toggle="tooltip" title="Reset Password" onclick="resetPassword({{$user->id}})"><i class="fas fa-lock"></i></button>
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
                emptyTable: "No teacher found.",
            },
            columnDefs: [{ targets: [0,1,2,3,4,5,6], className: "text-center"},{ targets: [1,2,3,4,5,6], sortable:false},{ targets: [6], width:250}]
        });
    })

    function remove(id) {
      swal({
          title: 'Remove Teacher',
          text: 'Are you sure you want to remove this teacher? Please note that this action cannot be undone.',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
              $('.loader').show();
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              $.ajax({
                  type: "GET",
                  url: "{{ route('admin.teacher.destroy',['id' => '0']) }}" + id,
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
                          swal('Remove Teacher',data['message'], 'error');
                      }
                  },
                  error: function (response) {
                      $('.loader').hide();
                  }
              });
          }
      });
    }

    function resetPassword(id) {
      swal({
          title: 'Reset Password',
          text: 'Are you sure you want to reset the password for the selected teacher? The new password will be sent to their email.',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
      }).then((willDelete) => {
          if (willDelete) {
              $('.loader').show();
              var csrfToken = $('meta[name="csrf-token"]').attr('content');
              $.ajax({
                  type: "GET",
                  url: "{{ route('admin.teacher.reset.password',['id' => '0']) }}" + id,
                  data: { id: id, _token: csrfToken },
                  success: function (data) {
                      $('.loader').hide();
                      if (data['status']) {
                          swal(data['message'], {
                            icon: 'success',
                          }).then((data) => {
                              
                          });
                      }else{
                          swal('Reset Password',data['message'], 'error');
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