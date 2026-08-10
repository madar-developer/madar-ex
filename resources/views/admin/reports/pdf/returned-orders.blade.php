<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: dejavusans, sans-serif;
            direction: rtl;
            color: #111;
            font-size: 11px;
        }
        .page-block {
            page-break-inside: avoid;
        }
        .page-block + .page-block {
            page-break-before: always;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            background: #EEECE1;
            border: 1px solid #999;
            padding: 8px 6px;
            margin: 0 0 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }
        .meta th {
            background: #EEECE1;
            font-weight: bold;
            font-size: 12px;
        }
        .meta td {
            background: #fff;
            font-size: 12px;
        }
        .head th {
            background: #EEECE1;
            font-weight: bold;
            font-size: 11px;
        }
        .col-num {
            width: 5%;
            background: #DDD9C3 !important;
            font-weight: bold;
        }
        .col-customer { width: 18%; }
        .col-order { width: 14%; }
        .col-date { width: 11%; }
        .col-payment { width: 14%; }
        .col-reason { width: 24%; }
        .col-notes { width: 14%; }
        .footer-sign {
            margin-top: 28px;
            width: 100%;
            border-collapse: collapse;
        }
        .footer-sign td {
            border: none;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            padding: 8px 4px;
            vertical-align: bottom;
        }
        .footer-sign .line {
            border-bottom: 1px solid #333;
            height: 28px;
            min-width: 140px;
            display: inline-block;
        }
    </style>
</head>
<body>
@foreach($groups as $group)
    <div class="page-block">
        <div class="title">{{ $title }}</div>

        <table class="meta" style="margin-bottom: 6px;">
            <tr>
                <th style="width: 18%;">اسم الشركة</th>
                <td style="width: 42%;">{{ $group['company_name'] }}</td>
                <th style="width: 12%;">التاريخ</th>
                <td style="width: 28%;">{{ $group['date'] }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr class="head">
                    <th class="col-num">م</th>
                    <th class="col-customer">اسم العميل</th>
                    <th class="col-order">رقم الطلب</th>
                    <th class="col-order">رقم البوليصة</th>
                    <th class="col-payment">حالة الدفع</th>
                    <th class="col-reason">سبب الارجاع</th>
                    <!-- <th class="col-notes">الملاحظات</th> -->
                    <th class="col-date">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group['orders'] as $row)
                <tr>
                    <td class="col-num">{{ $row['index'] }}</td>
                    <td class="col-customer">{{ $row['customer'] }}</td>
                    <td class="col-order">{{ $row['order_no'] }}</td>
                    <td class="col-serial">{{ $row['serial'] }}</td>
                    <td class="col-payment">{{ $row['payment'] }}</td>
                    <td class="col-reason">{{ $row['reason'] }}</td>
                    <!-- <td class="col-notes">{{ @$row['notes'] }}</td> -->
                    <td class="col-date">{{ $row['date'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="footer-sign">
            <tr>
                <td style="width: 50%;">اسم المستلم: <span class="line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                <td style="width: 50%;">التوقيع: <span class="line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
            </tr>
        </table>
    </div>
@endforeach
</body>
</html>
