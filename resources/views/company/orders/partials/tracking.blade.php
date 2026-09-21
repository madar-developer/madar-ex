@php
    $st = $order->status;
    $stepLabels = $stepLabels ?? [];
    $orderLogs = $orderLogs ?? collect();
    $lastStepIndex = max(0, count($stepLabels) - 1);
    if ($st === 'returned') {
        $stepLabels[$lastStepIndex] = $returnedStepLabel ?? 'تم الإرجاع';
    }
    $orderStatusIsFailed = ($st === 'deliver_failed');
    $currentStepIndex = match ($st) {
        'new' => 0,
        'not_received' => 1,
        'init' => 1,
        'at_madar' => 2,
        'at_office', 'reschedule' => 3,
        'deliver_failed' => $lastStepIndex,
        'delivered', 'returned' => 7,
        'cancelled' => 0,
        default => 0,
    };
    $allStepsComplete = ($st === 'delivered') || ($st === 'returned');
    $trackNo = $order->serial ?: ($order->refrence_no ?: '—');
    $lastLog = $lastLog ?? $orderLogs->sortByDesc('id')->first();
    $driversById = $driversById ?? collect();
@endphp

<div class="order-track-card order-log-panel {{ $orderStatusIsFailed ? 'order-track-card--failed' : '' }}">
    <div class="order-track-head">
        <div class="ot-tracking">
            <div class="ot-label"><i class="fa fa-truck m-l-5"></i> رقم التتبع للشحنة</div>
            <div class="ot-value">{{ $trackNo }}</div>
        </div>
        <div class="ot-last">
            <div class="ot-label">التحديث الأخير</div>
            @if($lastLog)
                <div class="ot-msg">{{ $lastLog->details }}</div>
                <div class="ot-time">{{ $lastLog->created_at->format('d/m/Y H:i:s') }}</div>
            @else
                <div class="ot-msg text-muted">لا توجد سجلات بعد</div>
            @endif
        </div>
    </div>

    @if($st === 'cancelled')
        <div class="alert alert-warning m-b-20" style="text-align:right;">تم إلغاء هذا الطلب.</div>
    @endif

    @if($orderStatusIsFailed)
        <div class="order-status-alert--failed" role="alert">
            <strong>{{ $order->status_txt ?: 'فشل التسليم' }}</strong>
            @if(trim((string) ($order->reason ?? '')) !== '')
                <div class="reason">{{ $order->reason }}</div>
            @endif
        </div>
    @endif

    <div class="ot-stepper-wrap">
        <div class="ot-stepper">
            @foreach($stepLabels as $i => $label)
                <div class="ot-step {{ $orderStatusIsFailed && $i === $lastStepIndex ? 'ot-step--failed' : '' }}">
                    <div class="ot-step-node">
                        <div class="ot-dot
                            @if($orderStatusIsFailed && $i === $lastStepIndex) is-failed
                            @elseif($allStepsComplete && $st === 'delivered' && $i === $lastStepIndex) is-delivered
                            @elseif($allStepsComplete && $st === 'returned' && $i === $lastStepIndex) is-returned
                            @elseif($allStepsComplete && $i < $lastStepIndex) is-done
                            @elseif(!$allStepsComplete && $i < $currentStepIndex) is-done
                            @elseif(!$allStepsComplete && $i === $currentStepIndex && ! $orderStatusIsFailed) is-current
                            @endif
                        ">
                            @php
                                if ($allStepsComplete) {
                                    $segDone1 = true;
                                } else {
                                    $segDone1 = ($i < $currentStepIndex);
                                }
                            @endphp
                            @if($orderStatusIsFailed && $i === $lastStepIndex)
                                <i class="fa fa-times"></i>
                            @elseif($segDone1)
                                <i class="fa fa-check"></i>
                            @endif
                        </div>
                    </div>
                    <div class="ot-step-label">{{ $label }}</div>
                </div>
                @if($i < $lastStepIndex)
                    @php
                        if ($allStepsComplete) {
                            $segDone = true;
                        } else {
                            $segDone = ($i < $currentStepIndex);
                        }
                    @endphp
                    <div class="ot-step-line {{ $segDone ? 'is-done' : '' }}"></div>
                @endif
            @endforeach
        </div>
    </div>
</div>

@if(!empty($trackingData))
<div class="order-live-track">
    <h4><i class="fa fa-map-marker m-l-5"></i> تتبع التوصيل المباشر</h4>
    <div id="order-live-track-map" class="order-live-track-map"></div>
    <div class="order-live-track-legend">
        <span><i class="lg-start"></i> نقطة البداية (أول حضور)</span>
        <span><i class="lg-driver"></i> موقع السائق الحالي</span>
        <span><i class="lg-dest"></i> عنوان التسليم</span>
    </div>
    <div id="order-live-track-meta" class="order-live-track-meta"></div>
    <div id="order-live-track-alert" class="alert alert-warning order-live-track-alert" style="display:none;"></div>
</div>
@endif

<div class="order-log-panel">
    <h4 class="m-b-15" style="text-align:right;">سجل النشاط</h4>
    <div class="order-log-table-wrap">
        <table class="order-log-table table">
            <thead>
                <tr>
                    <th class="ot-tl-cell"></th>
                    <th>التاريخ</th>
                    <th>الموقع</th>
                    <th>النشاط</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orderLogs->sortByDesc('id') as $log)
                    @php
                        $locAr = $order->City ? (string) $order->City->name : '';
                        $locLine = $locAr ? ($locAr.', Saudi Arabia') : 'Madar Express';
                        $logRowFailed = ($log->status ?? '') === 'deliver_failed';
                    @endphp
                    <tr class="{{ $logRowFailed ? 'order-log-row--failed' : '' }}">
                        <td class="ot-tl-cell">
                            <span class="ot-tl-rail"></span>
                            <span class="ot-tl-dot"><i class="fa {{ $logRowFailed ? 'fa-times' : 'fa-check' }}"></i></span>
                        </td>
                        <td>
                            <div>{{ $log->created_at->format('d/m/Y') }}</div>
                            <div style="color:#888;font-size:12px;">{{ $log->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div class="ot-loc-en">{{ $locLine }}</div>
                        </td>
                        <td>
                            <div>{{ $log->details }}</div>
                            <div style="font-size:12px;color:#888;margin-top:6px;">
                                @if($log->added_by_type === 'driver')
                                    @php $d2 = $driversById->get($log->added_by_id); @endphp
                                    {{ $d2 ? ('سائق: '.trim($d2->first_name.' '.$d2->last_name)) : 'سائق' }}
                                @else
                                    النظام
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding:24px;">لا توجد سجلات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
