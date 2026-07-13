@extends('layouts.front-end.app')

@section('css')
<style>
:root {
    --lms-teal: #2f9da3;
    --lms-ink: #183344;
}

.chapter-hero {
    padding: 4rem 0;
    color: white;
    background: linear-gradient(120deg, #123f58, #217c8c);
}

.chapter-hero a {
    color: #ccecef;
}

.chapter-hero h1 {
    margin: 1rem 0 .7rem;
    color: white;
    font-size: 3.6rem;
}

.chapter-page {
    padding: 4rem 0 6rem;
    background: #f6f8fa;
    min-height: 60vh;
}

.chapter-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
}

.chapter-toolbar h2 {
    margin: 0;
    color: var(--lms-ink);
}

.chapter-search {
    width: 300px;
    max-width: 100%;
    height: 42px;
    padding: 0 1.4rem;
    border: 1px solid #d7e0e4;
    border-radius: 7px;
    background: white;
}

.chapter-list {
    overflow: hidden;
    border: 1px solid #e0e7ea;
    border-radius: 12px;
    background: white;
}

.chapter-row {
    display: grid;
    grid-template-columns: 46px 1fr auto;
    gap: 1.4rem;
    align-items: center;
    padding: 1.8rem 2rem;
    border-bottom: 1px solid #edf0f2;
}

.chapter-row:last-child {
    border: 0;
}

.chapter-state {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border: 2px solid var(--lms-teal);
    border-radius: 50%;
    color: var(--lms-teal);
}

.chapter-state.done {
    color: white;
    background: var(--lms-teal);
}

.chapter-title {
    color: var(--lms-ink);
    font-size: 1.65rem;
    font-weight: 600;
}

.chapter-meta {
    margin-top: .4rem;
    color: #7b8b94;
    font-size: 1.3rem;
}

.open-btn {
    padding: .9rem 1.6rem;
    border-radius: 7px;
    color: white !important;
    background: var(--lms-teal);
    font-weight: 600;
}

.empty-chapters {
    padding: 5rem 2rem;
    text-align: center;
    color: #74858f;
}

@media(max-width:650px) {
    .chapter-hero h1 {
        font-size: 2.8rem
    }

    .chapter-toolbar {
        align-items: flex-start;
        flex-direction: column
    }

    .chapter-search {
        width: 100%
    }

    .chapter-row {
        grid-template-columns: 38px 1fr;
        padding: 1.5rem
    }

    .open-btn {
        grid-column: 2;
        width: max-content
    }
}
</style>
@endsection

@section('content')
<main>
    <section class="chapter-hero">
        <div class="container">
            <a href="{{ route('home') }}"><i class="icon-long-arrow-left"></i> home</a>
            <h1>{{ $title }}</h1>
            <p>{{ $chapters->count() }} {{ Str::plural('chapter', $chapters->count()) }} found</p>
        </div>
    </section>
    <section class="chapter-page">
        <div class="container">
            <div class="chapter-toolbar">
                <h2>Assigned chapters</h2><input id="chapterSearch" class="chapter-search" type="search"
                    placeholder="Search chapters..." aria-label="Search chapters">
            </div>
            <div class="chapter-list" id="chapterList">
                @forelse($chapters as $chapter)
                <div class="chapter-row" data-title="{{ Str::lower($chapter->title) }}">
                    <div class="chapter-state {{ in_array($chapter->id, $readIds) ? 'done' : '' }}"><i
                            class="{{ in_array($chapter->id, $readIds) ? 'icon-check' : 'icon-play' }}"></i></div>
                    <div><a class="chapter-title"
                            href="{{ route('chapter', $chapter->slug) }}">{{ $chapter->title }}</a>
                        <div class="chapter-meta">Released
                            {{ \Carbon\Carbon::parse($chapter->release_date)->format('d M Y') }}</div>
                    </div>
                    <a class="open-btn"
                        href="{{ route('chapter', $chapter->slug) }}">{{ in_array($chapter->id, $readIds) ? 'Continue' : 'Start lesson' }}</a>
                </div>
                @empty
                <div class="empty-chapters">
                    <h3>No chapters found</h3>
                    <p>No assigned chapters were released during this period.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection

@section('javascript')
<script>
document.getElementById('chapterSearch').addEventListener('input', function() {
    const term = this.value.toLowerCase().trim();
    document.querySelectorAll('#chapterList .chapter-row').forEach(function(row) {
        row.style.display = row.dataset.title.includes(term) ? '' : 'none';
    });
});
</script>
@endsection