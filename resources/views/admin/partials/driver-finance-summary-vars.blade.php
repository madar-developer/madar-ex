@php
    $financeShipmentCost = \App\Support\DriverFinance::batchShipmentCost($financeOrders);
    $financeNetProfit = $financeShipmentCost - $financeRow->driver_amount;
@endphp
