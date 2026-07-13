@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-warning text-white-all">
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-calendar-alt"></i>{{ $page_title }}</li>
          </ol>
        </nav>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Unread Chapters by Teachers</h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="{{ route('dashboard') }}"><i class="fas fa-angle-double-left"></i> Dashboard</a>
                    @php extract($data) @endphp
                    <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle text-white" aria-expanded="false">Filter</a>
                      <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(75px, 31px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <li class="dropdown-title">Select Period</li>
                        <li><a href="{{ route('chapters.report',['from' => $current_week_start_date, 'to' => $current_week_end_date, 'type' => $type]) }}" class="dropdown-item @if($week == 'current_week') active @endif">Current Week</a></li>
                        <li><a href="{{ route('chapters.report',['from' => $last_week_start_date, 'to' => $last_week_end_date, 'type' => $type]) }}" class="dropdown-item @if($week == 'last_week') active @endif">Last Week</a></li>
                        <li><a href="{{ route('chapters.report',['from' => $current_month_start_date, 'to' => $current_month_end_date, 'type' => $type]) }}" class="dropdown-item @if($week == 'current_month') active @endif">Current Month</a></li>
                        <li><a href="{{ route('chapters.report',['from' => $last_month_start_date, 'to' => $last_month_end_date, 'type' => $type]) }}" class="dropdown-item @if($week == 'last_month') active @endif">Last Month</a></li>
                    </ul>
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
                            <th class="text-center">Creation Date</th>
                            <th class="text-center">Release Date</th>
                            <th class="text-center">Teacher Name</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($chapters as $item)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center" style="padding: 4px;"><img alt="logo" style="box-shadow: none; width: 90px; height: 33px;object-fit: contain;" src="{{ asset( 'media/'. $item->chapter->asset_path .'/banner/thumb_'. $item->chapter->chapter_image) }}" onerror="this.src='{{ asset( "front-end/images/no_course.svg") }}'"></td>
                            <td>{{ $item->chapter->title }}</td>
                            <td>{{ date('d M, Y',strtotime($item->chapter->created_at)) }}</td>
                            <td>{{ date('d M, Y',strtotime($item->chapter->release_date)) }}</td>
                            <td>{{ $item->teacher->name }}</td>
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
        var title = "Unread Chapters by Teachers {{ $page_title }}";
        $("#items-table").dataTable({
            dom: 'Bfrtip',
            buttons: [
                { 
                    extend: 'csv',
                    title: title // Set the title for the CSV export
                },
                { 
                    extend: 'excel',
                    title: title // Set the title for the Excel export
                }
            ],
            language: {
                emptyTable: "No unread chapters found.",
            },
            columnDefs: [{ targets: [0,1,2,3,4,5], className: "text-center"},{ targets: [1,2,3,4,5], sortable:false}]
        });
    });
</script>
@stop