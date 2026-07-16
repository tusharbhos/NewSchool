@extends('layouts.front-end.app')

@section('css')
<style>
    :root{--portal-teal:#2f9da3;--portal-ink:#183344}
    .portal-hero{padding:5.5rem 0 4.5rem;background:linear-gradient(120deg,#edf8fb 0%,#fff 65%,#eef5ff 100%)}
    .portal-kicker{color:var(--portal-teal);text-transform:uppercase;letter-spacing:.18em;font-size:1.2rem;font-weight:700}
    .portal-hero h1{margin:.8rem 0 1.2rem;color:var(--portal-ink);font-size:4rem;font-weight:700}
    .portal-hero p{max-width:650px;color:#637784;font-size:1.7rem}
    .portal-grid{padding:4.5rem 0 6rem;background:#f7f9fb;min-height:55vh}
    .class-tools{display:flex;align-items:center;gap:1.2rem;margin-bottom:2.6rem;padding:1.5rem;border:1px solid #e2e9ed;border-radius:12px;background:#fff;box-shadow:0 8px 24px rgba(24,51,68,.05)}
    .class-search-wrap{position:relative;flex:1}.class-search-wrap i{position:absolute;top:50%;left:1.5rem;transform:translateY(-50%);color:#748892}
    .class-search{width:100%;height:46px;padding:0 4.2rem;border:1px solid #d6e0e5;border-radius:8px;background:#fbfcfd}.class-search:focus{border-color:var(--portal-teal);outline:none;box-shadow:0 0 0 3px rgba(47,157,163,.12)}
    .class-filter{width:190px;height:46px;padding:0 1.2rem;border:1px solid #d6e0e5;border-radius:8px;background:#fff}.result-count{color:#71838c;white-space:nowrap}
    .portal-card{height:100%;overflow:hidden;border:1px solid #e3e9ed;border-radius:14px;background:#fff;box-shadow:0 10px 28px rgba(24,51,68,.07);transition:.2s}.portal-card:hover{transform:translateY(-4px);box-shadow:0 16px 34px rgba(24,51,68,.12)}
    .class-cover{position:relative;height:220px;overflow:hidden;background:linear-gradient(135deg,#145b7d,#39a8aa)}.class-cover img{width:100%;height:100%;object-fit:cover}.class-cover:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(7,38,52,.6),transparent 65%)}
    .class-label{position:absolute;z-index:1;left:2.2rem;bottom:1.8rem;color:#fff;font-size:2.8rem;line-height:1.15;font-weight:700}.class-body{padding:2.3rem}.class-meta{color:#72838d;font-size:1.4rem}.class-body p{min-height:48px;margin:1.4rem 0 2rem;color:#61727c}
    .view-class{display:flex;justify-content:center;padding:1.2rem;border-radius:8px;color:#fff!important;background:var(--portal-teal);font-weight:600}.empty-portal,.no-results{padding:5rem 2rem;border:1px dashed #bfd0d8;border-radius:14px;background:#fff;text-align:center}.no-results{display:none}
    @media(max-width:767px){.portal-hero{padding:3.5rem 0}.portal-hero h1{font-size:3rem}.class-cover{height:185px}.class-tools{align-items:stretch;flex-direction:column}.class-filter{width:100%}}
</style>
@endsection

@section('content')
<main>
    <section class="portal-hero"><div class="container"><div class="portal-kicker">New learning portal</div><h1>My Assigned Classes</h1><p>Search your classes and open every released video, note and downloadable chapter resource.</p></div></section>
    <section class="portal-grid"><div class="container">
        @if($classes->isNotEmpty())
            <div class="class-tools"><div class="class-search-wrap"><i class="icon-search"></i><input id="classSearch" class="class-search" type="search" placeholder="Search by class or chapter name..."></div><select id="classFilter" class="class-filter"><option value="all">All Classes</option><option value="with">With Chapters</option><option value="empty">No Chapters Yet</option></select><span id="classResultCount" class="result-count">{{ $classes->count() }} classes</span></div>
        @endif
        <div class="row">
            @forelse($classes as $class)
                @php $cover=$class->cover_chapter; @endphp
                <div class="col-sm-6 col-lg-4 mb-4 class-item" data-search="{{ Str::lower($class->search_terms) }}" data-has-chapters="{{ $class->chapter_count ? 'yes' : 'no' }}"><article class="portal-card">
                    <a href="{{ route('classroom',$class) }}"><div class="class-cover">@if($cover && $cover->chapter_image)<img src="{{ asset('media/'.$cover->asset_path.'/banner/banner_'.$cover->chapter_image) }}" alt="{{ $class->class_title }}" onerror="this.style.display='none'">@endif<div class="class-label">{{ $class->class_title }}</div></div></a>
                    <div class="class-body"><div class="class-meta"><i class="icon-book-open"></i> {{ $class->chapter_count }} {{ Str::plural('chapter',$class->chapter_count) }}</div><p>Videos, notes and chapter resources for {{ $class->class_title }}.</p><a class="view-class" href="{{ route('classroom',$class) }}">View class content</a></div>
                </article></div>
            @empty
                <div class="col-12"><div class="empty-portal"><h3>No class assigned yet</h3><p>Please contact the administrator.</p></div></div>
            @endforelse
        </div>
        <div id="noSearchResults" class="no-results"><h3>No matching classes found</h3><p>Try another class or chapter name.</p></div>
    </div></section>
</main>
@endsection

@section('javascript')
<script>
(function(){const search=document.getElementById('classSearch'),filter=document.getElementById('classFilter');if(!search||!filter)return;const cards=[...document.querySelectorAll('.class-item')],count=document.getElementById('classResultCount'),empty=document.getElementById('noSearchResults');function run(){const term=search.value.toLocaleLowerCase().trim(),selected=filter.value;let visible=0;cards.forEach(card=>{const has=card.dataset.hasChapters==='yes',show=card.dataset.search.includes(term)&&(selected==='all'||selected==='with'&&has||selected==='empty'&&!has);card.style.display=show?'':'none';if(show)visible++});count.textContent=visible+(visible===1?' class':' classes');empty.style.display=visible?'none':'block'}search.addEventListener('input',run);filter.addEventListener('change',run)})();
</script>
@endsection
