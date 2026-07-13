<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstChapter;
use App\Models\TrnChapterTeacher;
use App\Models\MstClass;

use Carbon\Carbon;
use Log;
use Session;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $start = Carbon::now();
        $end   = Carbon::now();
        $page_title   = "";
        $currentDate  = Carbon::now();
        $currentMonth = Carbon::now()->startOfMonth();
        $daysInMonth  = $currentMonth->daysInMonth;
        $weeks = ['First Week', 'Second Week', 'Third Week', 'Fourth Week', 'Fifth Week'];
        $index = 1;
        for ($i = 0; $i < $daysInMonth; $i++) {
            $currentDay = $currentMonth->copy()->addDays($i);
            if ($currentDay->isMonday() || $i === 0) {
                $weekStartDate = $currentDay->copy()->startOfWeek();
                $weekEndDate = $currentDay->copy()->endOfWeek();
                if ($currentDate->between($weekStartDate, $weekEndDate)) {
                    $start      = $weekStartDate->format('Y-m-d');;
                    $end        = $weekEndDate->format('Y-m-d');;
                    $start_date = $weekStartDate->format('d M, Y');
                    $end_date   = $weekEndDate->format('d M, Y');
                    // $week_name  = $weeks[$index];
                    $page_title = "Week Number($index) - From $start_date To $end_date ";
                }
                $index++;
            }
        }

        $ids = MstChapter::where('status',1)->whereBetween('release_date', [$start, $end])->pluck('id');
        $release_count = sizeof($ids);

        $unread_count = $chapters = TrnChapterTeacher::with('teacher', 'chapter.class')->where('seen_status',0)->whereIn('chapter_id',$ids)->count();

        $read_count = $chapters = TrnChapterTeacher::with('teacher', 'chapter.class')->where('seen_status',1)->whereIn('chapter_id',$ids)->count();
       

        Session::put('page_title', $page_title);
        return view('admin.dashboard', compact('page_title','start','end', 'release_count', 'unread_count', 'read_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($type,$from,$to)
    {

        $currentDate = Carbon::now();
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = $currentMonth->copy()->subMonth();

        // Current week
        $currentWeekStartDate = $currentDate->copy()->startOfWeek();
        $currentWeekEndDate = $currentDate->copy()->endOfWeek();

        // Last week
        $lastWeekStartDate = $currentDate->copy()->subWeek()->startOfWeek();
        $lastWeekEndDate = $currentDate->copy()->subWeek()->endOfWeek();

        // Current month
        $currentMonthStartDate = $currentMonth->copy()->startOfMonth();
        $currentMonthEndDate = $currentMonth->copy()->endOfMonth();

        // Last month
        $lastMonthStartDate = $lastMonth->copy()->startOfMonth();
        $lastMonthEndDate = $lastMonth->copy()->endOfMonth();

        // Format the dates
        $format = "Y-m-d";
        $data['current_week_start_date']    = $currentWeekStartDate->format($format);
        $data['current_week_end_date']      = $currentWeekEndDate->format($format);
        $data['last_week_start_date']       = $lastWeekStartDate->format($format);
        $data['last_week_end_date']         = $lastWeekEndDate->format($format);
        $data['current_month_start_date']   = $currentMonthStartDate->format($format);
        $data['current_month_end_date']     = $currentMonthEndDate->format($format);
        $data['last_month_start_date']      = $lastMonthStartDate->format($format);
        $data['last_month_end_date']        = $lastMonthEndDate->format($format);

        if ($data['current_week_start_date'] == $from) {
            $start_date = date('d M, Y', strtotime($data['current_week_start_date']));
            $end_date   = date('d M, Y', strtotime($data['current_week_end_date']));
            $page_title = "Current Week - From $start_date To $end_date ";
            $week = 'current_week';
        }else if ($data['last_week_start_date'] == $from) {
            $start_date = date('d M, Y', strtotime($data['last_week_start_date']));
            $end_date   = date('d M, Y', strtotime($data['last_week_end_date']));
            $page_title = "Last Week - From $start_date To $end_date ";
            $week = 'last_week';
        }else if ($data['current_month_start_date'] == $from) {
            $start_date = date('d M, Y', strtotime($data['current_month_start_date']));
            $end_date   = date('d M, Y', strtotime($data['current_month_end_date']));
            $page_title = "Current Month - From $start_date To $end_date ";
            $week = 'current_month';
        }else if ($data['last_month_start_date'] == $from) {
            $start_date = date('d M, Y', strtotime($data['last_month_start_date']));
            $end_date   = date('d M, Y', strtotime($data['last_month_end_date']));
            $page_title = "Last Month - From $start_date To $end_date ";
            $week = 'last_month';
        }else{
            $start_date = date('d M, Y', strtotime($from));
            $end_date   = date('d M, Y', strtotime($to));
            $page_title = "Week - From $start_date To $end_date ";
            $week = '';
        }

        if ($type == 'release') {
            $chapters = MstChapter::whereBetween('release_date', [$from, $to])->where('status',1)->get();
            for ($i=0; $i < sizeof($chapters); $i++) { 
                if ($chapters[$i]['visibility'] == 1) {
                    $chapters[$i]['classes'] = "All Classes";
                }else{
                    if ($chapters[$i]['class_data'] == null || sizeof(json_decode($chapters[$i]['class_data'])) == 0) {
                        $chapters[$i]['classes'] = "-";
                    }else{
                        $chapters[$i]['classes'] = MstClass::whereIn('id',json_decode($chapters[$i]['class_data'],true))->pluck('class_title')->join(', ') ;
                    }
                }
            }
        }else{
            $ids = MstChapter::where('status',1)->whereBetween('release_date', [$from, $to])->pluck('id');
            if (sizeof($ids)) {
                $status = $type == 'read' ? 1 : 0;
                $chapters = TrnChapterTeacher::with('teacher', 'chapter')->where('seen_status',$status)->whereIn('chapter_id',$ids)->get();
            }else{
                $chapters = [];
            }
        }

        
        return view('admin.chapter.'.$type,compact('chapters','data','type', 'page_title', 'week'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
