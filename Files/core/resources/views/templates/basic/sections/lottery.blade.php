@php
    $lotteryContent = getContent('lottery.content', true);
    $phases = \App\Models\Phase::available()
        ->latest('draw_date')
        ->limit(10)
        ->with('lottery')
        ->get();
@endphp

<section class="pt-50 pb-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$lotteryContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row">
            <div class="col-lg-12">

                @include($activeTemplate . 'partials.lotteries')

            </div>
        </div>
        <div class="text-center">
            <a class="btn btn--base btn--capsule wow fadeInUp mt-3" data-wow-duration="0.5s" data-wow-delay="0.7s" href="{{ route('lottery') }}">@lang('View All')</a>
        </div>
    </div>
</section>
