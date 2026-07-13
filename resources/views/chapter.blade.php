@extends('layouts.front-end.app')

@section('css')
<style>
    :root { --lesson-dark:#24353b; --lesson-teal:#2f9da3; --lesson-border:#dfe6e8; }
    .lesson-shell { display:grid; grid-template-columns:330px minmax(0,1fr); min-height:calc(100vh - 116px); background:#f5f7f8; }
    .lesson-sidebar { position:sticky; top:0; height:calc(100vh - 10px); overflow-y:auto; border-right:1px solid var(--lesson-border); background:white; }
    .sidebar-head { padding:2.2rem; color:white; background:var(--lesson-dark); }
    .sidebar-head a { color:#bce2e3; font-size:1.3rem; }
    .sidebar-head h2 { margin:1rem 0 .5rem; color:white; font-size:2.1rem; }
    .sidebar-head span { color:#cbd5d8; font-size:1.3rem; }
    .chapter-nav { list-style:none; padding:0; margin:0; }
    .chapter-nav li { border-bottom:1px solid #edf0f1; }
    .chapter-nav a { display:grid; grid-template-columns:30px 1fr; gap:1rem; align-items:start; padding:1.6rem; color:#44565f; line-height:1.35; }
    .chapter-nav a:hover,.chapter-nav a.active { color:#173f4b; background:#dff4f4; }
    .nav-marker { width:22px; height:22px; display:grid; place-items:center; border:2px solid var(--lesson-teal); border-radius:50%; color:var(--lesson-teal); font-size:1rem; }
    .chapter-nav a.active .nav-marker { color:white; background:var(--lesson-teal); }
    .lesson-main { min-width:0; }
    .lesson-topbar { position:sticky; top:0; z-index:4; display:flex; justify-content:space-between; align-items:center; min-height:64px; padding:1.1rem 3rem; color:white; background:var(--lesson-dark); }
    .lesson-topbar h1 { margin:0; color:white; font-size:1.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .back-class { padding:.8rem 1.4rem; border:1px solid #6a7b81; border-radius:6px; color:white !important; white-space:nowrap; }
    .lesson-content { width:min(920px,calc(100% - 5rem)); margin:3rem auto 6rem; }
    .video-panel { overflow:hidden; margin-bottom:2.5rem; border-radius:10px; background:#101719; box-shadow:0 12px 32px rgba(19,43,52,.16); }
    .media-title { display:flex; align-items:center; gap:.8rem; padding:1.2rem 1.6rem; color:white; background:var(--lesson-dark); font-weight:600; }
    .lesson-video { display:block; width:100%; max-height:560px; background:#101719; }
    .audio-panel { padding:3rem; background:#eef6f6; }
    .audio-panel audio { width:100%; }
    .lesson-section { margin-top:2.5rem; padding:2.6rem; border:1px solid var(--lesson-border); border-radius:10px; background:white; }
    .lesson-section h2 { margin:0 0 1.6rem; color:#1c3943; font-size:2.2rem; }
    .notes { color:#4f626a; font-size:1.6rem; line-height:1.75; }
    .resource { display:flex; justify-content:space-between; gap:1.5rem; align-items:center; padding:1.5rem 0; border-bottom:1px solid #edf0f1; }
    .resource:last-child { border:0; padding-bottom:0; }
    .resource-info { display:flex; gap:1.2rem; align-items:center; min-width:0; }
    .resource-icon { width:42px; height:42px; flex:0 0 42px; display:grid; place-items:center; border-radius:8px; color:#d34b48; background:#fff0ef; font-size:2rem; }
    .resource-name { overflow-wrap:anywhere; color:#314750; font-weight:600; }
    .download-btn { flex:none; padding:.9rem 1.4rem; border-radius:7px; color:white !important; background:var(--lesson-teal); }
    .mobile-chapters { display:none; width:100%; margin:0; border:0; border-radius:0; }
    @media(max-width:900px){.lesson-shell{display:block}.lesson-sidebar{display:none}.mobile-chapters{display:block}.lesson-topbar{position:relative;padding:1.2rem 1.5rem}.lesson-content{width:calc(100% - 3rem);margin-top:2rem}}
    @media(max-width:575px){.lesson-topbar h1{font-size:1.45rem}.back-class{font-size:0;padding:.8rem}.back-class i{font-size:1.5rem}.lesson-section{padding:1.8rem}.resource{align-items:flex-start;flex-direction:column}.download-btn{margin-left:54px}}
</style>
@endsection

@section('content')
<main class="lesson-shell">
    <aside class="lesson-sidebar">
        <div class="sidebar-head"><a href="{{ route('classroom', $class) }}"><i class="icon-long-arrow-left"></i> Back to class</a><h2>{{ $class->class_title }}</h2><span>{{ $chapters->count() }} chapters</span></div>
        <ol class="chapter-nav">
            @foreach($chapters as $item)
                <li><a href="{{ route('chapter', $item->slug) }}" class="{{ $item->id === $chapter->id ? 'active' : '' }}"><span class="nav-marker">{{ $loop->iteration }}</span><span>{{ $item->title }}</span></a></li>
            @endforeach
        </ol>
    </aside>
    <section class="lesson-main">
        <select class="mobile-chapters form-control" aria-label="Select chapter" onchange="if(this.value) window.location.href=this.value">
            @foreach($chapters as $item)<option value="{{ route('chapter', $item->slug) }}" {{ $item->id === $chapter->id ? 'selected' : '' }}>{{ $loop->iteration }}. {{ $item->title }}</option>@endforeach
        </select>
        <header class="lesson-topbar"><h1>{{ $chapter->title }}</h1><a class="back-class" href="{{ route('classroom', $class) }}"><i class="icon-long-arrow-left"></i> All chapters</a></header>
        <div class="lesson-content">
            @forelse($videos as $video)
                <div class="video-panel">
                    <div class="media-title"><i class="{{ $video['type'] === 'video' ? 'icon-play' : 'icon-volume-2' }}"></i> {{ pathinfo($video['name'], PATHINFO_FILENAME) }}</div>
                    @if($video['type'] === 'video')
                        <video class="lesson-video" controls preload="metadata" poster="{{ route('video.thumbnail', ['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}"><source src="{{ route('stream', ['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}" type="{{ $video['mime_type'] }}">Your browser does not support video playback.</video>
                    @else
                        <div class="audio-panel"><audio controls preload="metadata"><source src="{{ route('stream', ['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}" type="{{ $video['mime_type'] }}"></audio></div>
                    @endif
                </div>
            @empty
                <div class="lesson-section"><h2>Lesson media</h2><p>No video or audio has been uploaded for this chapter yet.</p></div>
            @endforelse

            <section class="lesson-section"><h2>Lesson notes</h2><div class="notes">{!! filled(strip_tags($chapter->description ?? '')) ? $chapter->description : '<p>No notes have been added for this chapter yet.</p>' !!}</div></section>

            @if(count($attachments))
                <section class="lesson-section"><h2>Downloads &amp; resources</h2>
                    @foreach($attachments as $file)
                        <div class="resource"><div class="resource-info"><span class="resource-icon"><i class="{{ strtolower($file['extension']) === 'pdf' ? 'icon-file-text' : 'icon-paperclip' }}"></i></span><div><div class="resource-name">{{ $file['full_name'] }}</div><small>{{ strtoupper($file['extension']) }} resource</small></div></div><a class="download-btn" href="{{ route('download-attachments', ['slug'=>$chapter->slug,'file_name'=>$file['full_name']]) }}"><i class="icon-download"></i> Download</a></div>
                    @endforeach
                </section>
            @endif
        </div>
    </section>
</main>
@endsection
