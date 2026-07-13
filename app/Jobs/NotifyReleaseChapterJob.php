<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MstChapter;
use App\Models\TrnChapterTeacher;
use App\Models\User;
use App\Mail\ChapterAllocation;
use Carbon\Carbon;
use Mail;

class NotifyReleaseChapterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dates = $this->getCurrentWeekDates();
        $start = $dates['start'];
        $end   = $dates['end'];
        $chapter_ids = MstChapter::where('status',1)->whereBetween('release_date', [$start, $end])->pluck('id');

        if ($chapter_ids->isNotEmpty()) {
            $teacher_ids = TrnChapterTeacher::whereIn('chapter_id', $chapter_ids)->distinct()->pluck('teacher_id');

            if ($teacher_ids->isNotEmpty()) {

                foreach ($teacher_ids as $teacher_id) {
                    $user = User::find($teacher_id);
                    $teacher_chapter_ids = TrnChapterTeacher::whereIn('chapter_id', $chapter_ids)
                        ->where('teacher_id', $teacher_id)
                        ->distinct()
                        ->pluck('chapter_id');

                    if ($teacher_chapter_ids->isNotEmpty()) {
                        $chapters = MstChapter::where('status', 1)
                            ->whereIn('id', $teacher_chapter_ids)
                            ->get();

                        if ($chapters->isNotEmpty()) {
                            Mail::to($user->email)->send(new ChapterAllocation($user->name, $chapters));
                        }
                    }
                }
            }
        }
    }

    public function getCurrentWeekDates()
    {
        // Set the timezone
        // $timezone = 'Asia/Kolkata'; // Replace with your timezone, for example, 'America/New_York'
        $currentDate = Carbon::now();

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
