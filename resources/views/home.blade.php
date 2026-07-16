@extends('layouts.front-end.app')
@section('css')
<style>
    .readed-more{color:#007bff!important}.readed-more:hover,.readed-more:focus{padding-right:2.2rem;box-shadow:0 1px 0 0 #007bff!important;color:#007bff!important}.toolbox-sort{width:250px!important}.new-dashboard-btn{display:inline-flex;align-items:center;gap:.7rem;padding:1rem 1.6rem;border-radius:7px;color:#fff!important;background:#2f9da3;font-weight:600}@media(max-width:768px){.toolbox-sort{max-width:100%}}
</style>
@endsection
@section('content')
<main class="main">
    <div class="page-header text-center" style="background-image:url('{{ asset('front-end/images/page-header-bg.jpg') }}')"><div class="container"><h1 class="page-title">Dashboard<span>{{ $title }}</span></h1></div></div>
    <nav aria-label="breadcrumb" class="breadcrumb-nav"><div class="container"><div class="toolbox" style="margin-bottom:0!important">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active">My Chapters</li></ol>
        <div class="toolbox-right"><a class="new-dashboard-btn" href="{{ route('portal.home') }}"><i class="icon-grid"></i> New Dashboard</a></div>
        <div class="toolbox-right"><div class="toolbox-info">@if(count($chapters)==0) No chapters were found @else Showing <span>{{ count($chapters) }}</span> Chapters @endif</div></div>
        <div class="toolbox-right"><div class="toolbox-sort"><div class="custom"><div class="header-search-wrapper"><label for="searchInput" class="sr-only">Search</label><input type="search" class="form-control" id="searchInput" placeholder="Search chapters..."></div></div></div></div>
    </div></div></nav>
    <div class="container mb-5"><div class="row">
        @forelse($chapters as $chapter)
            <div class="col-sm-6 col-md-6 col-lg-4 chapter-card" data-chapter-name="{{ Str::lower($chapter->title) }}"><article class="entry entry-grid" style="background:#ededed;border-radius:6px;margin-bottom:1rem">
                <figure class="entry-media pt-1" style="background:transparent"><a href="{{ route('chapter',['slug'=>$chapter->slug]) }}">@if(blank($chapter->chapter_image))<img src="{{ asset('back-end/img/banner/lesson-bro.svg') }}" style="height:137px" alt="Chapter">@else<img src="{{ asset('media/'.$chapter->asset_path.'/banner/thumb_'.$chapter->chapter_image) }}" onerror="this.src='{{ asset('back-end/img/banner/lesson-bro.svg') }}'" style="height:137px" alt="Chapter">@endif</a></figure>
                <div class="entry-body text-center"><div class="entry-meta"><a href="{{ route('chapter',$chapter->slug) }}">Assigned On: {{ date('d M, Y',strtotime($chapter->release_date)) }}</a></div><h2 class="entry-title"><a href="{{ route('chapter',$chapter->slug) }}">{{ $chapter->title }}</a></h2><div class="entry-content pb-1"><a href="{{ route('chapter',$chapter->slug) }}" class="read-more {{ in_array($chapter->id,$read_ids)?'readed-more':'' }}">{{ in_array($chapter->id,$read_ids)?'Continue':'Start Here' }}</a></div></div>
            </article></div>
        @empty
            <div class="col-lg-12 text-center"><img src="{{ asset('front-end/images/no_course.svg') }}" style="height:300px;width:100%"><p>No chapters were found for this week.</p></div>
        @endforelse
    </div></div>
</main>
@endsection
@section('javascript')
<script>document.getElementById('searchInput').addEventListener('input',function(){const term=this.value.toLowerCase();document.querySelectorAll('.chapter-card').forEach(card=>card.style.display=card.dataset.chapterName.includes(term)?'':'none')});</script>
@endsection
