@php
    $financeDriver = $financeDriver ?? ($item->Driver ?? null);
    $shipmentCost = \App\Support\DriverFinance::codShipmentCost($item);
    $driverCommission = \App\Support\DriverFinance::driverCommission($item, $financeDriver);
    $shipmentNet = \App\Support\DriverFinance::shipmentNet($item, $financeDriver);
    $codAmount = \App\Support\DriverFinance::codOrderAmount($item);
@endphp
<td>{{ number_format($shipmentCost, 2) }}</td>
<td>{{ number_format($driverCommission, 2) }}</td>
<td>{{ number_format($shipmentNet, 2) }}</td>
<td>{{ $codAmount > 0 ? number_format($codAmount, 2) : '-' }}</td>
