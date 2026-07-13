@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Chapters</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" id="items-table">
                        <thead>
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"></th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Class Name</th>
                            <th class="text-center">Creation Date</th>
                            <th class="text-center">Release Date</th>
                            <th class="text-center">Associated Teacher</th>
                            <th class="text-center">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($chapters as $chapter)

                          @php
                          $title_chunks = str_split($chapter->title, 60);
                          $teachers = "";
                          if(sizeof($chapter->teachers) > 0){
                            $teachers = $chapter->teachers->pluck('name')->toArray();
                            $teachers = implode(', ',$teachers);

                            $teachers_chunks = str_split($teachers, 60);
                          }else{
                            $teachers_chunks = ["-"];
                          }
                          @endphp
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center" style="padding: 4px;"><img alt="logo" style="box-shadow: none; width: 90px; height: 33px;object-fit: contain;" src="{{ asset( 'media/'. $chapter->asset_path .'/banner/thumb_'. $chapter->image_banner) }}" onerror="this.src='{{ asset( "front-end/images/no_course.svg") }}'"></td>
                            <td>
                                @foreach ($title_chunks as $chunk) 
                                   {{ $chunk }}<br>
                                @endforeach
                            </td>
                            <td>{{ $chapter->class->class_title }}</td>
                            <td>{{ date('d M, Y',strtotime($chapter->created_at)) }}</td>
                            <td>{{ date('d M, Y',strtotime($chapter->release_date)) }}</td>
                            <td>
                                @foreach ($teachers_chunks as $chunk) 
                                   {{ $chunk }}<br>
                                @endforeach
                            </td>
                            <td>@if($chapter->status == 1) <span class="badge badge-success">Active</span> @else <span class="badge badge-danger">Inactive</span>@endif </td>
                          
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
                emptyTable: "No chhapters found.",
            },
            columnDefs: [{ targets: [0,1,2,3,4,5,6,7], className: "text-center"},{ targets: [1,2,3,4,5,6,7], sortable:false},{ targets: [2,6], width:120},{ targets: [7], width:120}]
        });
    });
</script>
@stop