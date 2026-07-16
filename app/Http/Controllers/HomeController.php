<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstChapter;
use App\Models\TrnChapterTeacher;
use App\Models\MstClass;
use App\Models\TrnTeacherClass;
use File;
use Auth;
use Session;
use Log;
use Illuminate\Support\Facades\Response;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Mail\LoginCredentials;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $classes = MstClass::query()
            ->where('mst_classes.status', 1)
            ->whereHas('teachers', function ($query) {
                $query->where('users.id', Auth::id())
                    ->where('trn_teacher_classes.status', 1);
            })
            ->orderBy('class_title')
            ->get();

        $assignedChapters = $this->assignedChapters();
        $classes->each(function ($class) use ($assignedChapters) {
            $chapters = $assignedChapters->filter(
                fn ($chapter) => $this->chapterBelongsToClass($chapter, $class->id)
            );
            $class->setAttribute('chapter_count', $chapters->count());
            $class->setAttribute('cover_chapter', $chapters->first());
            $class->setAttribute('search_terms', $chapters->pluck('title')->prepend($class->class_title)->implode(' '));
        });

        return view('home', compact('classes'));
    }

    public function classroom(MstClass $class)
    {
        abort_unless($this->teacherHasClass($class->id), 403);

        $chapters = $this->assignedChapters()
            ->filter(fn ($chapter) => $this->chapterBelongsToClass($chapter, $class->id))
            ->values();
        $readIds = TrnChapterTeacher::where('teacher_id', Auth::id())
            ->where('status', 1)
            ->where('seen_status', 1)
            ->pluck('chapter_id')
            ->all();

        return view('classroom', compact('class', 'chapters', 'readIds'));
    }

    public function chapters(Request $request)
    {
        $chapters = $this->assignedChapters();

        if ($request->boolean('older')) {
            $cutoff = now()->startOfMonth()->subMonths(5);
            $title = 'Older Release Chapters';
            $chapters = $chapters->filter(
                fn ($chapter) => Carbon::parse($chapter->release_date)->lt($cutoff)
            );
        } elseif ($request->filled('month') && $request->filled('year')) {
            $month = max(1, min(12, (int) $request->input('month')));
            $year = (int) $request->input('year');
            $monthDate = Carbon::create($year, $month, 1);

            if ($request->input('week') === 'all') {
                $start = $monthDate->copy()->startOfMonth();
                $end = $monthDate->copy()->endOfMonth();
                $title = $monthDate->format('F Y').' — All Weeks';
            } else {
                $week = max(0, min(4, (int) $request->input('week', 0)));
                $weekNames = ['First Week', 'Second Week', 'Third Week', 'Fourth Week', 'Fifth Week'];
                $dates = $this->getWeekDatesForMonth($year, $month);
                $range = $dates[min($week, count($dates) - 1)];
                $start = Carbon::parse($range['start'])->max($monthDate->copy()->startOfMonth());
                $end = Carbon::parse($range['end'])->min($monthDate->copy()->endOfMonth());
                $title = $monthDate->format('F Y').' — '.$weekNames[$week];
            }

            $chapters = $chapters->filter(function ($chapter) use ($start, $end) {
                return Carbon::parse($chapter->release_date)->betweenIncluded($start, $end);
            });
        } else {
            $dates = $this->getCurrentWeekDates();
            $start = Carbon::parse($dates['start']);
            $end = Carbon::parse($dates['end']);
            $title = 'Latest Release Chapters';
            $chapters = $chapters->filter(function ($chapter) use ($start, $end) {
                return Carbon::parse($chapter->release_date)->betweenIncluded($start, $end);
            });
        }

        $chapters = $chapters->values();
        $readIds = TrnChapterTeacher::where('teacher_id', Auth::id())
            ->where('status', 1)
            ->where('seen_status', 1)
            ->pluck('chapter_id')
            ->all();

        return view('chapters', compact('chapters', 'readIds', 'title'));
    }

    public function chapter($slug) {
        
        $chapter = MstChapter::where('slug',$slug)->where('status',1)->first();

        
        
        if ($chapter) {

            $class = $this->classForChapter($chapter);
            abort_unless($class, 403);

            $trn = TrnChapterTeacher::where('chapter_id',$chapter->id)->where('status',1)->where('teacher_id',Auth::id())->first();
            if ($trn) {
                $trn->seen_status = 1;
                $trn->save();
            }

            $attachments = [];
            $videos = [];

            $folder     = $chapter->asset_path;
            $attachementsFolder = public_path("media/$folder/attachements");

            if (File::isDirectory($attachementsFolder)) {
                $files = File::files($attachementsFolder);
                foreach ($files as $file) {
                    $fullName = $file->getFilename();
                    $fileName = pathinfo($file, PATHINFO_FILENAME);
                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

                    $attachments[] = [
                        'name' => $fileName,
                        'extension' => $fileExtension,
                        'path' => $file,
                        'full_name' => $fullName,
                    ];

                    // array_push($attachments, $fileName);
                }
            }

            $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];
            $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];


            $videosFolder = public_path("media/$folder/videos");
            if (File::isDirectory($videosFolder)) {
                $files = File::files($videosFolder);
                 Log::debug("File Count : " . sizeof($files));
                foreach ($files as $file) {
                    $fileName = $file->getFilename();
                     Log::debug("File NAme : $fileName");
                    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    // Determine file type based on extension
                    $fileType = in_array($fileExtension, $videoExtensions) ? 'video' : (in_array($fileExtension, $audioExtensions) ? 'audio' : 'unknown');
                    
                    // Determine MIME type (optional)
                    $mimeType = mime_content_type($file->getPathname());

                    if ($fileType != "unknown") {
                        $videos[] = [
                            'name' => $fileName,
                            'extension' => $fileExtension,
                            'type' => $fileType,
                            'mime_type' => $mimeType // Optional, remove if not needed
                        ];
                    }
                    
                    // array_push($videos, $fileName);
                }
            }
            // Log::debug(json_encode($attachments,true));
            // Log::debug(json_encode($videos,true));
            $chapters = $this->assignedChapters()
                ->filter(fn ($item) => $this->chapterBelongsToClass($item, $class->id))
                ->values();

            return view('chapter', compact('chapter','attachments', 'videos', 'class', 'chapters'));
        }else{
            return redirect('home');
        }
        
    }

    public function downloadAttachment($slug,$file_name){
        $chapter = MstChapter::where('slug',$slug)->first();
        if ($chapter && $this->classForChapter($chapter) && basename($file_name) === $file_name) {
            $folder = $chapter->asset_path;
            $path   = "media/$folder/attachements/$file_name";
            $file   = public_path($path);
            if (file_exists($file)) {
                return response()->download($file, $file_name, ['Content-Type' => mime_content_type($file)]);
            }
        }
        return response()->json(['error' => 'File not found.'], 404);
    }

    public function stream($chapter_id, $video_name)
    {
        $chapter = MstChapter::find($chapter_id);
        abort_unless($chapter && $this->classForChapter($chapter) && basename($video_name) === $video_name, 404);
        $folder = $chapter->asset_path;
        $path   = "media/$folder/videos/$video_name";
        $file_location = public_path($path);

        abort_unless(file_exists($file_location), 404);

        $headers = [
            'Content-Type' => mime_content_type($file_location),
            'Accept-Ranges' => 'bytes',
            'Access-Control-Allow-Origin' => 'http://localhost:8888', // Replace with your actual domain
        ];

        $size = filesize($file_location);
        $length = $size;
        $start = 0;
        $end = $size - 1;

        $status = 200;
        if (isset($_SERVER['HTTP_RANGE'])) {
            $status = 206;
            $c_start = $start;
            $c_end = $end;

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }

            if ($range == '-') {
                $c_start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $c_start = $range[0];
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }

            $c_end = ($c_end > $end) ? $end : $c_end;
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                exit;
            }

            $stream = fopen($file_location, 'r');
            $start = $c_start;
            $end = $c_end;
            $length = $end - $start + 1;
            fseek($stream, $start);
        }

        $headers['Content-Length'] = $length;
        if ($status === 206) {
            $headers['Content-Range'] = 'bytes ' . $start . '-' . $end . '/' . $size;
        }

        return Response::stream(
            function () use ($file_location, $start, $length) {
                $stream = fopen($file_location, 'r');
                fseek($stream, $start);
                // echo fread($stream, $length);

                $chunkSize = 1024 * 2048; // 2 MB chunk size (adjust as needed)
                $end = $start + $length - 1;
                while (!feof($stream) && ($pos = ftell($stream)) <= $end) {
                    echo fread($stream, min($chunkSize, $end - $pos + 1));
                    flush(); // Flush output to the browser to prevent memory buildup
                }


                fclose($stream);
            },
            $status,
            $headers
        );
    }

    public function generateThumbnail($chapter_id, $video_name)
    {

        $chapter = MstChapter::find($chapter_id);
        abort_unless($chapter && $this->classForChapter($chapter) && basename($video_name) === $video_name, 404);
        $folder = $chapter->asset_path;
        $path   = "media/$folder/videos/$video_name";
        $videoPath = public_path($path);

        if (!file_exists($videoPath)) {
            abort(404);
        }

        $parts = explode('.', $video_name);
        $video = $parts[0];

        $path   = "media/$folder/videos/$video.jpg";
        $thumbnailPath = public_path($path);

        // Check if the thumbnail already exists
        if (file_exists($thumbnailPath)) {
            return response()->file($thumbnailPath);
        }

        // Generate the thumbnail using PHP-FFMpeg
        $ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open($videoPath);

        // Set the time to capture the thumbnail (e.g., 5 seconds)
        $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))
              ->save($thumbnailPath);

        // Return the generated thumbnail
        return response()->file($thumbnailPath);
    }

    public function record_watch_duration(Request $request){

    }

    public function week_chapter($month, $year, $week){

    }


    function getWeekDatesForMonth($year, $month)
    {
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1)->startOfWeek();
        $lastDayOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $weekDates = [];

        while ($firstDayOfMonth->lte($lastDayOfMonth)) {
            $startOfWeek = $firstDayOfMonth->copy();
            $endOfWeek = $firstDayOfMonth->copy()->endOfWeek();

            $weekDates[] = [
                'start' => $startOfWeek->toDateString(),
                'end' => $endOfWeek->toDateString(),
            ];

            $firstDayOfMonth->addWeek();
        }

        return $weekDates;
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

    function show($folder,$file) {
        $chapter = MstChapter::where('asset_path', $folder)->first();
        abort_unless($chapter && $this->classForChapter($chapter) && basename($file) === $file, 404);
        $path = public_path("media/$folder/attachements/$file");
        abort_unless(file_exists($path), 404);

        return response()->file($path, ['Content-Type' => mime_content_type($path)]);

        // $pdfPath = Storage::disk('public')->get($path);
        // if (!file_exists($pdfPath)) {
        //     abort(404);
        // }

        // $headers = [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="' . basename($pdfPath) . '"',
        // ];

        // return response()->file($pdfPath, $headers);

    }

    public function isExcelFile($path)
    {
        $fileExtension = pathinfo($path, PATHINFO_EXTENSION);
        return in_array($fileExtension, ['xls', 'xlsx']);
    }

    public function changePassword(){
        return view('change_password');
    }

    public function updatePassword(Request $request) {
        $this->validate($request, [
            'password' => 'required|min:8|confirmed',
            'current_password' => 'required',
        ]);

        // Custom validation for current password
        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        $email    = $user->email;
        $name     = $user->name;
        $password = $request->input('password');
        Mail::to($email)->send(new LoginCredentials($name,$password,$email));

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    function test(){
        Log::debug("Hey there");
    }

    private function assignedChapters()
    {
        $ids = TrnChapterTeacher::where('teacher_id', Auth::id())
            ->where('status', 1)
            ->pluck('chapter_id');

        return MstChapter::where('status', 1)
            ->whereIn('id', $ids)
            ->whereDate('release_date', '<=', now())
            ->orderBy('release_date')
            ->orderBy('title')
            ->get();
    }

    private function teacherHasClass($classId): bool
    {
        return TrnTeacherClass::where('teacher_id', Auth::id())
            ->where('class_id', $classId)
            ->where('status', 1)
            ->exists();
    }

    private function chapterBelongsToClass(MstChapter $chapter, $classId): bool
    {
        if ((int) $chapter->visibility === 1) {
            return true;
        }

        $classData = $chapter->class_data;
        if (is_string($classData)) {
            $classData = json_decode($classData, true);
        }

        return in_array((string) $classId, array_map('strval', is_array($classData) ? $classData : []), true);
    }

    private function classForChapter(MstChapter $chapter): ?MstClass
    {
        $assigned = TrnChapterTeacher::where('teacher_id', Auth::id())
            ->where('chapter_id', $chapter->id)
            ->where('status', 1)
            ->exists();

        if (!$assigned) {
            return null;
        }

        return MstClass::where('status', 1)
            ->whereHas('teachers', function ($query) {
                $query->where('users.id', Auth::id())
                    ->where('trn_teacher_classes.status', 1);
            })
            ->get()
            ->first(fn ($class) => $this->chapterBelongsToClass($chapter, $class->id));
    }
}
