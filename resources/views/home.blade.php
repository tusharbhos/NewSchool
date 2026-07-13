@extends('layouts.front-end.app')

@section('css')
<style>
    :root { --lms-blue:#145b7d; --lms-teal:#2f9da3; --lms-ink:#183344; --lms-soft:#f4f8fa; }
    .catalogue-hero { padding:5.5rem 0 4.5rem; background:linear-gradient(120deg,#edf8fb 0%,#fff 65%,#eef5ff 100%); }
    .catalogue-kicker { color:var(--lms-teal); text-transform:uppercase; letter-spacing:.18em; font-size:1.2rem; font-weight:700; }
    .catalogue-hero h1 { margin:.8rem 0 1.2rem; color:var(--lms-ink); font-size:4rem; font-weight:700; }
    .catalogue-hero p { max-width:630px; color:#637784; font-size:1.7rem; }
    .class-grid { padding:4.5rem 0 6rem; background:#f7f9fb; min-height:55vh; }
    .class-card { height:100%; overflow:hidden; border:1px solid #e3e9ed; border-radius:14px; background:white; box-shadow:0 10px 28px rgba(24,51,68,.07); transition:.2s ease; }
    .class-card:hover { transform:translateY(-4px); box-shadow:0 16px 34px rgba(24,51,68,.12); }
    .class-cover { position:relative; height:220px; overflow:hidden; background:linear-gradient(135deg,#145b7d,#39a8aa); }
    .class-cover img { width:100%; height:100%; object-fit:cover; }
    .class-cover:after { content:""; position:absolute; inset:0; background:linear-gradient(0deg,rgba(7,38,52,.6),transparent 65%); }
    .class-label { position:absolute; z-index:1; left:2.2rem; bottom:1.8rem; color:white; font-size:2.8rem; line-height:1.15; font-weight:700; }
    .class-body { padding:2.3rem; }
    .class-meta { display:flex; gap:1rem; align-items:center; color:#72838d; font-size:1.4rem; }
    .class-body p { min-height:48px; margin:1.4rem 0 2rem; color:#61727c; }
    .view-class { display:flex; justify-content:center; align-items:center; padding:1.2rem; border-radius:8px; color:white !important; background:var(--lms-teal); font-weight:600; }
    .empty-catalogue { padding:6rem 2rem; border:1px dashed #bfd0d8; border-radius:14px; background:white; text-align:center; }
    @media(max-width:767px){ .catalogue-hero{padding:3.5rem 0}.catalogue-hero h1{font-size:3rem}.class-cover{height:185px} }
</style>
@endsection

@section('content')
<main>
    <section class="catalogue-hero">
        <div class="container">
            <div class="catalogue-kicker">Learning dashboard</div>
            <h1>My Assigned Classes</h1>
            <p>Select a class to browse every released chapter, lesson video, note and downloadable learning material assigned to you.</p>
        </div>
    </section>
    <section class="class-grid">
        <div class="container">
            <div class="row">
                @forelse($classes as $class)
                    @php $cover = $class->cover_chapter; @endphp
                    <div class="col-sm-6 col-lg-4 mb-4">
                        <article class="class-card">
                            <a href="{{ route('classroom', $class) }}">
                                <div class="class-cover">
                                    @if($cover && $cover->chapter_image)
                                        <img src="{{ asset('media/'.$cover->asset_path.'/banner/banner_'.$cover->chapter_image) }}" alt="{{ $class->class_title }}" onerror="this.style.display='none'">
                                    @endif
                                    <div class="class-label">{{ $class->class_title }}</div>
                                </div>
                            </a>
                            <div class="class-body">
                                <div class="class-meta"><i class="icon-book-open"></i> {{ $class->chapter_count }} {{ Str::plural('chapter', $class->chapter_count) }}</div>
                                <p>Videos, notes and chapter resources for {{ $class->class_title }}.</p>
                                <a class="view-class" href="{{ route('classroom', $class) }}">View class content <i class="icon-long-arrow-right ml-2"></i></a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12"><div class="empty-catalogue"><h3>No class assigned yet</h3><p>Please contact the administrator to assign a class to your account.</p></div></div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection
