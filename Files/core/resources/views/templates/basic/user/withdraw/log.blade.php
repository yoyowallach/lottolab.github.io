@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center mt-2">
                <div class="col-lg-12">

                    <div class="d-flex justify-content-between mb-3 flex-wrap gap-3">
                        <form class="flex-grow-1 form-in-width" action="">
                            <div class="d-flex justify-content-end">
                                <div class="input-group">
                                    <input class="form-control" name="search" type="text" value="{{ request()->search }}" placeholder="@lang('Search by transactions')">
                                    <button class="input-group-text bg-primary border-0 text-white">
                                        <i class="las la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <a class="btn btn--base flex-shrink-0" href="{{ route('user.withdraw') }}">
                            @lang('Withdraw')
                        </a>
                    </div>

                    <div class="table-responsive--md">
                        <table class="custom--table table">
                            <thead>
                                <tr>
                                    <th>@lang('Gateway | Transaction')</th>
                                    <th class="text-center">@lang('Initiated')</th>
                                    <th class="text-center">@lang('Amount')</th>
                                    <th class="text-center">@lang('Conversion')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($withdraws as $withdraw)
                                    <tr>
                                        <td>
                                            <span class="fw-bold"><span class="text-primary">
                                                    {{ __(@$withdraw->method->name) }}</span></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </td>
                                        <td class="text-center">
                                            {{ showDateTime($withdraw->created_at) }} <br>
                                            {{ diffForHumans($withdraw->created_at) }}
                                        </td>
                                        <td class="text-center">
                                            {{ __($general->cur_sym) }}{{ showAmount($withdraw->amount) }} - <span
                                                class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                            <br>
                                            <strong title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                                {{ __($general->cur_text) }}
                                            </strong>

                                        </td>
                                        <td class="text-center">
                                            1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }}
                                            {{ __($withdraw->currency) }}
                                            <br>
                                            <strong>{{ showAmount($withdraw->final_amount) }}
                                                {{ __($withdraw->currency) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @php echo $withdraw->statusBadge @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn--base detailBtn" data-user_data="{{ json_encode($withdraw->withdraw_information) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                                <i class="la la-desktop"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="rounded-bottom text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($withdraws->hasPages())
                        <div class="card-footer">
                            {{ $withdraws->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </section>

    {{-- APPROVE MODAL --}}
    <div class="modal fade" id="detailModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn--danger text-white" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
