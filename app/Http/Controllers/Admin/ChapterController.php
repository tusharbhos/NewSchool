<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstChapter;
use App\Models\User;
use App\Models\TrnTeacherClass;
use App\Models\TrnChapterTeacher;
use App\Models\MstClass;
use Auth;
use Log;
use File;


use Intervention\Image\Facades\Image;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Storage;
use pbmedia\LaravelFFMpeg\FFMpegServiceProvider;
use FFMpeg\Format\Video\X264;
use Carbon\Carbon;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chapters = MstChapter::with(['teachers'])->where('status','!=',2)->orderBy('id','desc')->paginate(300);

        for ($i=0; $i < sizeof($chapters); $i++) { 
            if ($chapters[$i]['visibility'] == 1) {
                $chapters[$i]['classes'] = "All";
            }else{
                $ids = json_decode($chapters[$i]['class_data'],true) ?: [];
                if (sizeof($ids) > 0) {
                   $classes = MstClass::whereIn('id',$ids)->pluck('class_title')->implode(', ');
                   $chapters[$i]['classes'] = $classes;
                }else{
                   $chapters[$i]['classes'] = "-";
                }
            }
        }
        return view('admin.chapter.index',compact('chapters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = MstClass::with('teachers.classes')->where('status','!=',2)->get();
        $randomFolderName = Str::random(8);
        return view('admin.chapter.create',compact('classes','randomFolderName'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title'        => 'required|max:255',
            'release_date' => 'required|date'
        ];

         // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

         if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            ]);
        }

        $title   = $request->input('title');
        $folder  = $request->input('_form_id');
        $slug    = Str::slug($title) . '-' . time();

        $params = [ 
            'title'         => $title, 
            'release_date'  => $request->input('release_date'), 
            'created_by'    => Auth::id(), 
            'status'        => 1, 
            'slug'          => $slug, 
            'asset_path'    => $folder,
            'visibility'    => $request->input('visibility'),
            'class_data'    => json_encode([],true)
        ];

        if($request->has('description')) {
            $params['description'] =$request->input('description');
        }

        if ($request->has('class_id') && $request->has('visibility') && $request->input('visibility') == 0) {
            $params['class_data'] = json_encode($request->input('class_id'),true);
        }

        $chapter = MstChapter::create($params);

        if ($request->has('visibility') && $request->input('visibility') == 1) {
            $teachers = User::where('status',1)->where('role',3)->get();
            for($i = 0; $i < sizeof($teachers); $i++){
                $id = $teachers[$i]['id'];
                if ($id != "") {
                    $ct_params = [];
                    $ct_params['chapter_id'] = $chapter->id;
                    $ct_params['teacher_id'] = $id;
                    TrnChapterTeacher::create($ct_params);
                }
            }
        }else{
           
            if ($chapter && $request->has('teachers')) {
                $teachers = $request->input('teachers');
                for($i = 0; $i < sizeof($teachers); $i++){
                    $id = $teachers[$i];
                    if ($id != "") {
                        $ct_params = [];
                        $ct_params['chapter_id'] = $chapter->id;
                        $ct_params['teacher_id'] = $id;
                        TrnChapterTeacher::create($ct_params);
                    }
                }
            }
        }
        

        if ($request->hasFile('image')) {
            $chapter_id = $chapter->id;
            $image      = $request->file('image');
            $imagename  = time() . '.' . $image->extension();

            $path = public_path("media/$folder/banner/");
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $image->move($path, $imagename);
            $base_path = $path . $imagename;
           
            $img = Image::make($base_path);
            $img->resize(1200, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->resizeCanvas(1200,400, 'center');
            $background = Image::canvas(1200, 400, '#f6f6f6');
            $background->insert($img, 'center');
            $background->save($path . "banner_$imagename");

            $img = Image::make($base_path);
            $img->resize(370, 123, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->resizeCanvas(370,123, 'center');
            $img->save($path . "thumb_$imagename");

            $chapter->chapter_image  =  $imagename;
            $chapter->save();
        }

        return redirect()->back()->with('message', [
            'type' => 'success',
            'title' => '',
            'message' => 'New Chapter Added Successfully.',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $chapter = MstChapter::find($id);

        $classes = "";
        $teachers = "";

        $ids = json_decode($chapter->class_data,true) ?: [];
        if (sizeof($ids) > 0) {

            $classes = MstClass::whereIn('id',$ids)->pluck('class_title')->join(', ');

            $class_teachers_ids = TrnTeacherClass::whereIn('class_id',$ids)->pluck('teacher_id');
            
            if (sizeof($class_teachers_ids) > 0) {

                $teachers = User::whereIn('id',$class_teachers_ids)->with(['classes' => function ($query) {
                    $query->select('class_title');
                }])
                ->get()
                ->unique('id'); 

                $teach  = [];

                for ($i=0; $i < sizeof($teachers); $i++) { 
                    $teacher = $teachers[$i];
                    $title = $teacher->name . ' (' . $teacher->classes->pluck('class_title')->join(', ') . ' )';
                    array_push($teach,$title);
                }
                $teachers = implode(', ', $teach);
            }
        }
    
        $attachments = [];
        $videos = [];

        $folder     = $chapter->asset_path;
        $attachementsFolder = public_path("media/$chapter->asset_path/attachements");

        if (File::isDirectory($attachementsFolder)) {
            $files = File::files($attachementsFolder);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                array_push($attachments, $fileName);
            }
        }

        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];

        $videosFolder = public_path("media/$chapter->asset_path/videos");
        if (File::isDirectory($videosFolder)) {
            $files = File::files($videosFolder);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                $fileType = in_array($fileExtension, $videoExtensions) ? 'video' : (in_array($fileExtension, $audioExtensions) ? 'audio' : 'unknown');

                if ($fileType != "unknown") {
                    array_push($videos, $fileName);
                }
            }
        }

        return view('admin.chapter.show',compact('chapter','teachers','videos','attachments', 'classes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $chapter        = MstChapter::find($id);
        $ids            = TrnChapterTeacher::where('chapter_id',$id)->pluck('teacher_id')->toArray();
        $classes        = MstClass::with('teachers.classes')->where('status','!=',2)->get();
        
        $attachments = [];
        $videos      = [];

        $folder             = $chapter->asset_path;
        $attachementsFolder = public_path("media/$chapter->asset_path/attachements");

        if ($chapter) {
            if ($chapter->asset_path == null || $chapter->asset_path == "") {
                $chapter->asset_path = Str::random(8);
                $chapter->save();
            }
        }

        if (File::isDirectory($attachementsFolder)) {
            $files = File::files($attachementsFolder);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                array_push($attachments, $fileName);
            }
        }

        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'wmv'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];

        $videosFolder = public_path("media/$chapter->asset_path/videos");
        if (File::isDirectory($videosFolder)) {
            $files = File::files($videosFolder);
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                $fileType = in_array($fileExtension, $videoExtensions) ? 'video' : (in_array($fileExtension, $audioExtensions) ? 'audio' : 'unknown');

                if ($fileType != "unknown") {
                    array_push($videos, $fileName);
                }
            }
        }

        if ($chapter->class_data == null) {
            $chapter->class_data = json_encode([],true);
           
        }
      
        $dateToCheck            = Carbon::createFromFormat('Y-m-d', $chapter->release_date);
        $currentDate            = Carbon::now();
        $isDateLessThanCurrent  = $dateToCheck->lessThan($currentDate);

        return view('admin.chapter.edit',compact('chapter','classes','ids','videos','attachments', 'isDateLessThanCurrent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'title'        => 'required|max:255',
            'release_date' => 'required|date'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            ]);
        }

        $chapter                = MstChapter::find($id);
        $chapter->title         = $request->input('title');
        $chapter->release_date  = $request->input('release_date');

        if ($request->has('description')) {
            $chapter->description = $request->input('description');
        }

        if ($request->has('status')) {
            $chapter->status = $request->input('status');
        }

        if ($request->has('visibility')) {
            $chapter->visibility = $request->input('visibility');
        }

        if ($request->has('class_id') && $request->has('visibility') && $request->input('visibility') == 0) {
            $chapter->class_data = json_encode($request->input('class_id'),true);
        }else{
            $chapter->class_data = json_encode([],true);
        }
        $chapter->save();

        TrnChapterTeacher::where('chapter_id',$chapter->id)->where('seen_status',0)->delete();
        TrnChapterTeacher::where('chapter_id',$chapter->id)->update(['status' => 2]);

        if ($request->has('visibility') && $request->input('visibility') == 1) {
            $teachers = User::where('status',1)->where('role',3)->get();
            for($i = 0; $i < sizeof($teachers); $i++){
                $id = $teachers[$i]['id'];

                $recordExist = TrnChapterTeacher::where('chapter_id',$chapter->id)->where('teacher_id',$id)->first();
                if ($recordExist) {
                   $recordExist->status = 1;
                   $recordExist->save();
                }else{
                    $ct_params = [];
                    $ct_params['chapter_id'] = $chapter->id;
                    $ct_params['teacher_id'] = $id;
                    TrnChapterTeacher::create($ct_params);
                }
            }
        }else{
            if ($chapter && $request->has('teachers')) {
                $teachers = $request->input('teachers');
                for($i = 0; $i < sizeof($teachers); $i++){
                    $id = $teachers[$i];
                    if ($id != "") {
                        $ct_params = [];
                        $ct_params['chapter_id'] = $chapter->id;
                        $ct_params['teacher_id'] = $id;
                        $tech = TrnChapterTeacher::where('chapter_id',$chapter->id)->where('teacher_id',$id)->first();
                        if (!$tech) {
                            TrnChapterTeacher::create($ct_params);
                        }else{
                            $tech->status = 1;
                            $tech->save();
                        }
                    }
                }
            }
        }


        $folder = $chapter->asset_path;

        if ($request->hasFile('image')) {
            $old_path = $chapter->chapter_image;

            $chapter_id = $chapter->id;
            $image      = $request->file('image');
            $imagename  = time() . '.' . $image->extension();

            $path = public_path("media/$folder/banner/");
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $image->move($path, $imagename);
            $base_path = $path . $imagename;
           
            $img = Image::make($base_path);
            $img->resize(1200, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->resizeCanvas(1200,400, 'center');
            $background = Image::canvas(1200, 400, '#f6f6f6');
            $background->insert($img, 'center');
            $background->save($path . "banner_$imagename");

            $img = Image::make($base_path);
            $img->resize(370, 123, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->resizeCanvas(370,123, 'center');
            $img->save($path . "thumb_$imagename");

            $chapter->chapter_image  =  $imagename;
            $chapter->save();

            if ($old_path != null && $old_path != "") {

                $original_image_path = public_path("media/$folder/banner/$old_path");
                if (file_exists($original_image_path)) {
                    unlink($original_image_path);
                }

                $banner_path = public_path("media/$folder/banner/banner_$old_path");
                if (file_exists($banner_path)) {
                    unlink($banner_path);
                }

                $thumb_path = public_path("media/$folder/banner/thumb_$old_path");
                if (file_exists($thumb_path)) {
                    unlink($thumb_path);
                }
            }
            
        }


        return redirect()->back()->with('message', [
            'type' => 'success',
            'title' => '',
            'message' => 'Chapter updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chapter = MstChapter::where('id',$id)->where('status','!=',2)->first();
        if ($chapter) {
            $chapter->status = 2;
            $chapter->save();

            TrnChapterTeacher::where('chapter_id', $id)->where('seen_status',0)->delete();
            TrnChapterTeacher::where('chapter_id', $id)->update(['status' => 2]);

            return [
                'status' => true,
                'title' => 'Remove Chapter',
                'message' => 'Chapter removed successfully',
            ];
        }else{
            return [
                'status' => false,
                'title' => 'Remove Chapter',
                'message' => 'Invalid operation'
            ];
        }
    }

    /**
       * Handles the file upload
       *
       * @param Request $request
       *
       * @return JsonResponse
       *
       * @throws UploadMissingFileException
       * @throws UploadFailedException
       */
    public function upload(Request $request) {  //from web route
        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
          throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
          // save the file and return any response you need, current example uses `move` function. If you are
          // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
          return $this->saveFile($save->getFile(), $request);
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
          "done"    => $handler->getPercentageDone(),
          'status' => true
        ]);
    }

    /**
       * Saves the file
       *
       * @param UploadedFile $file
       *
       * @return JsonResponse
       */

    protected function saveFile(UploadedFile $file, Request $request) {
        
        $extension = $file->getClientOriginalExtension();
        $file_name = str_replace(".".$extension, "", $file->getClientOriginalName());

        $uploadType = $request->input('upload_type');
        $folderType = $request->input('folder_type');

        $fileName   = $this->createFilename($file,$request);

        $mime_original  = $file->getMimeType();
        $mime           = str_replace('/', '-', $mime_original);

        $filePath  = "media/$folderType/$uploadType/";
        $finalPath = public_path($filePath);

        Log::debug("File : $filePath");
        Log::debug("Folder : $folderType");
        Log::debug("Type: $uploadType");
        if(!File::isDirectory($finalPath)) {
            File::makeDirectory($finalPath, 0777, true, true);
        }

        $fileSize = $file->getSize();
        $file->move($finalPath, $fileName);

        if (file_exists($finalPath . $fileName) && strpos($mime_original, 'video/') === 0) {
            // $videoPath = $finalPath . $fileName;
            // $parts  = explode('.', $fileName);
            // $video  = $parts[0];

            // $path   = "media/$folderType/videos/$video.jpg";
            // $thumbnailPath = public_path($path);

            // // Generate the thumbnail using PHP-FFMpeg
            // $ffmpeg = FFMpeg\FFMpeg::create();
            // $video = $ffmpeg->open($videoPath);

            // // Set the time to capture the thumbnail (e.g., 5 seconds)
            // $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))->save($thumbnailPath);
        }
        
        return response()->json([
            'path'        => $finalPath,
            'name'        => $fileName,
            'mime_type'   => $mime
        ]);
    }

   /**
       * Create unique filename for uploaded file
       * @param UploadedFile $file
       * @return string
       */

    protected function createFilename(UploadedFile $file, Request $request) {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName());
        return $filename.".".$extension;
    }

   /**
       * Delete uploaded file WEB ROUTE
       * @param Request request
       * @return JsonResponse
       */

    public function delete(Request $request) {
        $file_name  = $request->input('filename');
        $uploadType = $request->input('upload_type');
        $folderType = $request->input('folder_type');
        $filePath  = "media/$folderType/$uploadType/$file_name";
        $path = public_path($filePath);
        if (file_exists($path)) {
            unlink($path);
        }
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function classWiseTeachers($class) {
        $classIds = explode(",", $class);

        $teachers = User::whereHas('classes', function ($query) use ($classIds) {
            $query->whereIn('class_id', $classIds);
        })
        ->with(['classes' => function ($query) {
            $query->select('class_title');
        }])
        ->get()
        ->unique('id'); 

        $teachers = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'name' => $teacher->name, 
                'classes' => $teacher->classes->pluck('class_title')->join(', ')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $teachers
        ], 200);
    }

    public function attachements($assets,$type){
        $attachments = [];
        $directory_path   = public_path("media/$assets/$type");
        $videoAudioExtensions = ['mp4', 'mov', 'avi', 'mp3', 'wav', 'm4a'];

        if (File::isDirectory($directory_path)) {
            $files = File::files($directory_path);
            foreach ($files as $file) {

                if ($type == 'videos') {
                    $fileName = $file->getFilename();
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $fileMimeType = mime_content_type($file->getPathname());

                    if (in_array($fileExtension, $videoAudioExtensions) || strpos($fileMimeType, 'audio/') === 0 || strpos($fileMimeType, 'video/') === 0) {
                        array_push($attachments, $fileName);
                    }
                }else{
                    $fileName = $file->getFilename();
                    array_push($attachments, $fileName);
                }                
            }
        }
        return $attachments;
    }
}
