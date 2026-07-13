@extends('layouts.front-end.app')

@section('css')
<style>
:root {
    --lms-blue: #145b7d;
    --lms-teal: #2f9da3;
    --lms-ink: #183344;
}

.course-hero {
    padding: 4rem 0;
    color: white;
    background: linear-gradient(120deg, #123f58, #217c8c);
}

.course-hero a {
    color: #ccecef;
}

.course-hero h1 {
    color: white;
    margin: 1rem 0 .7rem;
    font-size: 3.8rem;
}

.course-wrap {
    padding: 4rem 0 6rem;
    background: #f6f8fa;
    min-height: 60vh;
}

.course-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.course-toolbar h2 {
    margin: 0;
    color: var(--lms-ink);
}

.lesson-list {
    overflow: hidden;
    border: 1px solid #e0e7ea;
    border-radius: 12px;
    background: white;
}

.lesson-row {
    display: grid;
    grid-template-columns: 46px 1fr auto;
    gap: 1.4rem;
    align-items: center;
    padding: 1.8rem 2rem;
    border-bottom: 1px solid #edf0f2;
}

.lesson-row:last-child {
    border: 0;
}

.lesson-state {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border: 2px solid var(--lms-teal);
    border-radius: 50%;
    color: var(--lms-teal);
}

.lesson-state.done {
    color: white;
    background: var(--lms-teal);
}

.lesson-title {
    color: var(--lms-ink);
    font-weight: 600;
    font-size: 1.65rem;
}

.lesson-meta {
    margin-top: .4rem;
    color: #7b8b94;
    font-size: 1.3rem;
}

.start-btn {
    padding: .9rem 1.6rem;
    border-radius: 7px;
    color: white !important;
    background: var(--lms-teal);
    font-weight: 600;
}

.empty-lessons {
    padding: 5rem 2rem;
    text-align: center;
    color: #74858f;
}

@media(max-width:575px) {
    .course-hero h1 {
        font-size: 3rem
    }

    .lesson-row {
        grid-template-columns: 38px 1fr;
        padding: 1.5rem
    }

    .start-btn {
        grid-column: 2;
        width: max-content
    }

    .course-toolbar {
        align-items: flex-start;
        flex-direction: column;
        gap: .8rem
    }
}
</style>
@endsection

@section('content')
<main>
    <section class="course-hero">
        <div class="container">
            <a href="{{ route('home') }}"><i class="icon-long-arrow-left"></i> Home</a>
            <h1>{{ $class->class_title }}</h1>
            <p>{{ $chapters->count() }} {{ Str::plural('released chapter', $chapters->count()) }} available</p>
        </div>
    </section>
    <section class="course-wrap">
        <div class="container">
            <div class="course-toolbar">
                <h2>Course chapters</h2><span>Learn at your own pace</span>
            </div>
            <div class="lesson-list">
                @forelse($chapters as $chapter)
                <div class="lesson-row">
                    <div class="lesson-state {{ in_array($chapter->id, $readIds) ? 'done' : '' }}"><i
                            class="{{ in_array($chapter->id, $readIds) ? 'icon-check' : 'icon-play' }}"></i></div>
                    <div><a class="lesson-title" href="{{ route('chapter', $chapter->slug) }}">{{ $chapter->title }}</a>
                        <div class="lesson-meta">Released
                            {{ \Carbon\Carbon::parse($chapter->release_date)->format('d M Y') }}</div>
                    </div>
                    <a class="start-btn"
                        href="{{ route('chapter', $chapter->slug) }}">{{ in_array($chapter->id, $readIds) ? 'Continue' : 'Start lesson' }}</a>
                </div>
                @empty
                <div class="empty-lessons">
                    <h3>No released chapters yet</h3>
                    <p>New lessons assigned to this class will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection