<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\PrincipalController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\ViewController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('send-chapter-allocatin-emails', [ ViewController::class,'sendChapterAllocationEmail']);

Route::group(['middleware' => ['auth','role:3']], function() {
    Route::controller(HomeController::class)->group(function() {
        Route::get('/home', 'index')->name('home');
        Route::get('/my-chapters', 'chapters')->name('chapters.index');
        Route::get('/classroom/{class}', 'classroom')->name('classroom');
        Route::get('/chapter/{slug}', 'chapter')->name('chapter');
        Route::get('/download-attachments/{slug}/{file_name}', 'downloadAttachment')->name('download-attachments');
        Route::get('/stream/{chapter_id}/{video_name}', 'stream')->name('stream');
        Route::get('/thumbnail/{chapter_id}/{video_name}', 'generateThumbnail')->name('video.thumbnail');
        Route::post('record-watch-duration', 'record_watch_duration')->name('record.watch.duration'); 
        Route::get('pdf/{folder}/{filename}', 'show')->name('pdf.show');
        Route::get('change-password', 'changePassword')->name('change.password');
        Route::post('update-password', 'updatePassword')->name('update.password');
    });
});

Route::group(['middleware' => ['auth','role:1,2']], function() {
    Route::controller(DashboardController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/{type}-chapters/{from}To{to}', 'show')->name('chapters.report');

    });
});

Route::group(['prefix' => 'chapter', 'middleware' => ['auth','role:1,2']], function() {
    Route::controller(ChapterController::class)->group(function() {
        Route::get('/', 'index')->name('admin.chapter.index');      
        Route::get('/add/new', 'create')->name('admin.chapter.create');
        Route::get('/show/{id}', 'show')->name('admin.chapter.show');
        Route::get('/edit/{id}', 'edit')->name('admin.chapter.edit');
        Route::get('/class-wise-teachers/{class}', 'classWiseTeachers')->name('class.wise.teachers');
        Route::get('/chapter/attachements/{assets}/{type}', 'attachements')->name('chapter.attachements');
        Route::get('/destroy/{id}', 'destroy')->name('admin.chapter.destroy');
        Route::post('/update/{id}', 'update')->name('admin.chapter.update');
        Route::post('/store', 'store')->name('admin.chapter.store');
        Route::post('video-upload','upload')->name('video-file-upload');
        Route::post('/video-delete','delete')->name('video-file-delete');
        Route::get('/backup/{folder}', 'downloadAndDeleteZip')->name('chapter.backup');
    });
});

Route::group(['prefix' => 'principal', 'middleware' => ['auth','role:1,2']], function() {
    Route::controller(PrincipalController::class)->group(function() {
        Route::get('/', 'index')->name('admin.principal.index');
        Route::get('/create', 'create')->name('admin.principal.create');
        Route::get('/show/{id}', 'show')->name('admin.principal.show');
        Route::get('/edit/{id}', 'edit')->name('admin.principal.edit');
        Route::get('/destroy/{id}', 'destroy')->name('admin.principal.destroy');
        Route::get('/reset-password/{id}', 'reset')->name('admin.principal.reset.password');
        Route::post('/update/{id}', 'update')->name('admin.principal.update');
        Route::post('/store', 'store')->name('admin.principal.store');
    });
});

Route::group(['prefix' => 'teacher', 'middleware' => ['auth','role:1,2']], function() {
    Route::controller(TeacherController::class)->group(function() {
        Route::get('/', 'index')->name('admin.teacher.index');
        Route::get('/create', 'create')->name('admin.teacher.create');
        Route::get('/show/{id}', 'show')->name('admin.teacher.show');
        Route::get('/edit/{id}', 'edit')->name('admin.teacher.edit');
        Route::get('/destroy/{id}', 'destroy')->name('admin.teacher.destroy');
        Route::get('/reset-password/{id}', 'reset')->name('admin.teacher.reset.password');
        Route::post('/update/{id}', 'update')->name('admin.teacher.update');
        Route::post('/store', 'store')->name('admin.teacher.store');
        Route::get('/admin-change-password/{id}', 'changePassword')->name('admin.change.password');
    });
});

Route::group(['prefix' => 'class', 'middleware' => ['auth','role:1,2']], function() {
    Route::controller(ClassController::class)->group(function() {
        Route::get('/', 'index')->name('admin.class.index');
        Route::get('/create', 'create')->name('admin.class.create');
        Route::get('/show/{id}', 'show')->name('admin.class.show');
        Route::get('/edit/{id}', 'edit')->name('admin.class.edit');
        Route::get('/destroy/{id}', 'destroy')->name('admin.class.destroy');
        Route::post('/update/{id}', 'update')->name('admin.class.update');
        Route::post('/store', 'store')->name('admin.class.store');
    });
});
