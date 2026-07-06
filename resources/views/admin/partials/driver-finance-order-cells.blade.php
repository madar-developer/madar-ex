@php
    $financeDriver = $financeDriver ?? ($item->Driver ?? null);
    $shipmentCost = \App\Support\DriverFinance::codShipmentCost($item);
    $driverCommission = \App\Support\DriverFinance::driverCommission($item, $financeDriver);
    $shipmentNet = \App\Support\DriverFinance::shipmentNet($item, $financeDriver);
    $codAmount = \App\Support\DriverFinance::codOrderAmount($item);
@endphp
<td>{{ $shipmentCost }}</td>
<td>{{ $driverCommission }}</td>
<td>{{ $shipmentNet }}</td>
<td>{{ $codAmount > 0 ? $codAmount : '-' }}</td>
