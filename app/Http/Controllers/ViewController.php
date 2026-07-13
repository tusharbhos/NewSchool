<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstChapter;
use App\Models\User;
use App\Models\TrnTeacherClass;
use App\Models\TrnChapterTeacher;
use App\Mail\ChapterAllocation;

use App\Models\MstClass;
use Auth;
use Log;
use File;
use Mail;
use Carbon\Carbon;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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

    public function viewProtectedExcel($chapter,$file_name)
    {
        $chapter = MstChapter::where('slug',$chapter)->first();
        $folder  = $chapter->asset_path;
        $path    = "media/$folder/attachements/$file_name";
        return view('media.view_protected_excel',compact('path'));
    }

    public function viewProtectedWord($chapter,$file_name)
    {
        $chapter = MstChapter::where('slug',$chapter)->first();
        $folder  = $chapter->asset_path;
        $path    = "media/$folder/attachements/$file_name";
        return view('media.view_protected_word',compact('path'));
    }

    public function viewProtectedPdf($chapter,$file_name)
    {
        $chapter = MstChapter::where('slug',$chapter)->first();
        $folder  = $chapter->asset_path;
        $path    = "media/$folder/attachements/$file_name";
        return view('media.view_protected_pdf',compact('path'));
    }

    public function sendChapterAllocationEmail() {
        $dates = $this->getCurrentWeekDates();
        $start = $dates['start'];
        $end   = $dates['end'];
        $chapter_ids = MstChapter::where('status',1)->whereBetween('release_date', [$start, $end])->pluck('id');
        Log::debug(json_encode($chapter_ids,true));
        if ($chapter_ids->isNotEmpty()) {
            // Retrieve unique teacher IDs associated with the retrieved chapter IDs
            $teacher_ids = TrnChapterTeacher::whereIn('chapter_id', $chapter_ids)->distinct()->pluck('teacher_id');

            if ($teacher_ids->isNotEmpty()) {
                 Log::debug(json_encode($teacher_ids,true));
                // Iterate over each teacher ID
                foreach ($teacher_ids as $teacher_id) {
                    // Find the user by teacher ID
                    $user = User::find($teacher_id);

                    // Retrieve chapter IDs associated with the current teacher
                    $teacher_chapter_ids = TrnChapterTeacher::whereIn('chapter_id', $chapter_ids)
                        ->where('teacher_id', $teacher_id)
                        ->distinct()
                        ->pluck('chapter_id');

                    if ($teacher_chapter_ids->isNotEmpty()) {
                        // Retrieve chapters associated with the retrieved chapter IDs
                        $chapters = MstChapter::where('status', 1)
                            ->whereIn('id', $teacher_chapter_ids)
                            ->get();

                        if ($chapters->isNotEmpty()) {
                            // Send email to the teacher
                            Mail::to($user->email)->send(new ChapterAllocation($user->name, $chapters));
                        }
                    }
                }
            }
        }
        return array("status" => 'success');
    }

    function getCurrentWeekDates()
    {
        // Set the timezone
        $timezone = 'Asia/Kolkata'; // Replace with your timezone, for example, 'America/New_York'
        $currentDate = Carbon::now($timezone);

        // Set the start day of the week to Monday
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        Carbon::setWeekEndsAt(Carbon::SUNDAY);

        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();

        return [
            'start' => $startOfWeek->toDateString(),
            'end' => $endOfWeek->toDateString(),
        ];
    }
}
