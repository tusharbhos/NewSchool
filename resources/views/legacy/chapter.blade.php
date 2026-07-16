@extends('layouts.front-end.app')

@section('css')
<style>
    .legacy-chapter-banner{max-width:400px;width:auto;object-fit:contain}.legacy-hero{background-size:cover;background-position:center}.legacy-hero-row{display:flex;align-items:center}.legacy-page-header{padding:3rem 1.6rem;background:transparent!important}.legacy-page-header h1 small{display:block;margin-bottom:.8rem;color:#333;font-size:1.6rem}.legacy-tabs{display:flex;align-items:stretch}.legacy-tabs .nav-tabs{width:245px;flex:0 0 245px}.legacy-tabs .nav-link{padding:1.5rem;border-bottom:1px solid #e2e2e2}.legacy-tabs .tab-content{min-width:0;flex:1;border:1px solid #d7d7d7;border-left:0}.legacy-media{padding:1.5rem}.legacy-video{display:block;width:100%;height:440px;object-fit:contain;background:#111}.legacy-audio{width:100%;margin:4rem 0}.legacy-description{padding:2.5rem 0;font-size:1.6rem;line-height:1.7}.legacy-materials{display:flex;flex-wrap:wrap;gap:1rem;padding:1rem 0 3rem}.legacy-mobile-select{display:none}.legacy-empty{padding:3rem;border:1px solid #e2e2e2;text-align:center}.breadcrumb-mobile{display:none}
    @media(max-width:767px){.legacy-hero-row{display:block}.legacy-chapter-banner{width:100%;max-width:100%}.legacy-tabs{display:block}.legacy-tabs .nav-tabs{display:none}.legacy-tabs .tab-content{border:1px solid #d7d7d7}.legacy-mobile-select{display:block;margin-bottom:1rem}.legacy-video{height:240px}.breadcrumb-desktop{display:none}.breadcrumb-mobile{display:block}}
</style>
@endsection

@section('content')
<main class="main">
    <div class="legacy-hero" style="background-image:url('{{ asset('front-end/images/page-header-bg.jpg') }}')"><div class="container"><div class="legacy-hero-row">
        <img src="{{ asset('media/'.$chapter->asset_path.'/banner/thumb_'.$chapter->chapter_image) }}" onerror="this.src='{{ asset('back-end/img/banner/default_chapter.png') }}'" class="legacy-chapter-banner" alt="{{ $chapter->title }}">
        <div class="page-header legacy-page-header"><h1 class="page-title"><small>Chapter</small>{{ $chapter->title }}</h1></div>
    </div></div></div>

    <nav aria-label="breadcrumb" class="breadcrumb-nav"><div class="container"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active breadcrumb-desktop">{{ Str::limit($chapter->title,125,'...') }}</li><li class="breadcrumb-item active breadcrumb-mobile">{{ Str::limit($chapter->title,25,'...') }}</li></ol></div></nav>

    <div class="container mb-5"><div class="page-content">
        @if(count($videos))
            <select id="legacyMediaSelect" class="form-control legacy-mobile-select" aria-label="Select multimedia">
                @foreach($videos as $video)<option value="legacy-tab-{{ $loop->iteration }}">{{ $loop->iteration }}: Multimedia ({{ ucfirst($video['type']) }})</option>@endforeach
            </select>
            <div class="legacy-tabs">
                <ul class="nav nav-tabs nav-tabs-bg flex-column" role="tablist">
                    @foreach($videos as $video)<li class="nav-item"><a class="nav-link {{ $loop->first?'active':'' }}" id="legacy-tab-{{ $loop->iteration }}-link" data-toggle="tab" href="#legacy-tab-{{ $loop->iteration }}" role="tab">{{ $loop->iteration }}: Multimedia ({{ ucfirst($video['type']) }})</a></li>@endforeach
                </ul>
                <div class="tab-content">
                    @foreach($videos as $video)
                        <div class="tab-pane fade {{ $loop->first?'show active':'' }}" id="legacy-tab-{{ $loop->iteration }}" role="tabpanel"><div class="legacy-media">
                            @if($video['type']==='video')
                                <video class="legacy-video" controls preload="metadata" poster="{{ route('video.thumbnail',['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}"><source src="{{ route('stream',['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}" type="{{ $video['mime_type'] }}">Your browser does not support video playback.</video>
                            @else
                                <audio class="legacy-audio" controls preload="metadata"><source src="{{ route('stream',['chapter_id'=>$chapter->id,'video_name'=>$video['name']]) }}" type="{{ $video['mime_type'] }}"></audio>
                            @endif
                        </div></div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="legacy-empty">No multimedia has been uploaded for this chapter.</div>
        @endif

        <div class="legacy-description editor-content">{!! $chapter->description !!}</div>

        @if(count($attachments))
            <hr><h6>Additional Chapter Material</h6><div class="legacy-materials">
                @foreach($attachments as $file)
                    @if(Str::lower($file['extension'])==='pdf')
                        <a href="{{ route('pdf.show',['filename'=>$file['full_name'],'folder'=>$chapter->asset_path]) }}" class="btn btn-primary btn-round btn-shadow" target="_blank">{{ $file['full_name'] }} <i class="icon-long-arrow-right"></i></a>
                    @else
                        <a href="{{ route('download-attachments',['slug'=>$chapter->slug,'file_name'=>$file['full_name']]) }}" class="btn btn-primary btn-round btn-shadow">{{ $file['full_name'] }} <i class="icon-long-arrow-right"></i></a>
                    @endif
                @endforeach
            </div>
        @endif
    </div></div>
</main>
@endsection

@section('javascript')
<script>
document.getElementById('legacyMediaSelect')?.addEventListener('change',function(){document.getElementById(this.value+'-link')?.click()});
</script>
@endsection
