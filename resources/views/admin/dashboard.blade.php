@extends('layouts.back-end.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-primary text-white-all">
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-calendar-alt"></i>{{ session('page_title') }}</li>
          </ol>
        </nav>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="all_packages_count_div">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Released Chapters</h5>
                                <h2 class="mb-3 font-18">{{ $release_count }}</h2>
                                <p class="mb-0"><a class="badge badge-primary text-white mt-5" href="{{ route('chapters.report',['from' => $start, 'to' => $end, 'type' => 'release']) }}">View</a></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{ asset('back-end/img/banner/Icons-02.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="all_company_count_div">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Read Chapters</h5>
                                <h2 class="mb-3 font-18">{{ $read_count }}</h2>
                                <p class="mb-0"><a class="badge badge-success text-white mt-5" href="{{ route('chapters.report',['from' => $start, 'to' => $end, 'type' => 'read']) }}">View</a></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{ asset('back-end/img/banner/Icons-03.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12" id="all_visitor_count_div">
        <div class="card">
            <div class="card-statistic-4">
                <div class="align-items-center justify-content-between">
                    <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="font-15">Unread Chapters</h5>
                                <h2 class="mb-3 font-18">{{ $unread_count }}</h2>
                                <p class="mb-0"><a class="badge badge-warning text-white mt-5" href="{{ route('chapters.report',['from' => $start, 'to' => $end, 'type' => 'unread']) }}">View</a></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                            <div class="banner-img">
                                <img src="{{ asset('back-end/img/banner/Icons-04.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection