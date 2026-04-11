@extends('admin.layout.app')

@section('style')
<style type="text/css">
    #orders-region-map {
        width: 100%;
        height: 520px;
        min-height: 400px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }
    .region-map-stats .panel-heading {
        text-align: right;
    }
    .region-map-table-wrap {
        max-height: 520px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title }}</h4>
        </div>
    </div>
</div>

<!-- <div class="row region-map-stats">
    <div class="col-md-12 m-b-15">
        <div class="panel panel-default">
            <div class="panel-heading">ملخص</div>
            <div class="panel-body" style="text-align: right;">
                <p class="m-b-5"><strong>إجمالي الطلبات (بمدينة محددة):</strong> {{ number_format($totalInRegions) }}</p>
                @if($nullCityCount > 0)
                    <p class="m-b-0 text-warning"><strong>طلبات بدون مدينة:</strong> {{ number_format($nullCityCount) }}</p>
                @endif
                <p class="text-muted m-t-10 m-b-0" style="font-size: 12px;">العلامات على الخريطة تستخدم متوسط إحداثيات الطلبات في كل مدينة عند توفرها.</p>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-md-8 m-b-20">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: right;">الخريطة</div>
            <div class="panel-body">
                <div id="orders-region-map"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 m-b-20">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align: right;">الطلبات حسب المدينة</div>
            <div class="panel-body region-map-table-wrap" style="padding: 0;">
                <table class="table table-striped table-bordered m-0" style="text-align: right;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المدينة</th>
                            <th>عدد الطلبات</th>
                            <th>على الخريطة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regions as $idx => $r)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $r['name'] }}</td>
                                <td>{{ number_format($r['count']) }}</td>
                                <td>{{ ($r['lat'] !== null && $r['lng'] !== null) ? 'نعم' : 'لا' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">لا توجد طلبات مرتبطة بمدن.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function initOrdersRegionMap() {
        function escHtml(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
        var regions = @json($regions);
        var defaultCenter = { lat: 24.7255553, lng: 47.1027146 };
        var map = new google.maps.Map(document.getElementById('orders-region-map'), {
            zoom: 6,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var hasPoint = false;

        regions.forEach(function (r) {
            if (r.lat == null || r.lng == null || isNaN(r.lat) || isNaN(r.lng)) {
                return;
            }
            hasPoint = true;
            var pos = { lat: r.lat, lng: r.lng };
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: r.name + ': ' + r.count,
                label: {
                    text: String(r.count),
                    color: '#ffffff',
                    fontSize: '11px',
                    fontWeight: 'bold'
                }
            });
            bounds.extend(pos);
            marker.addListener('click', function () {
                infowindow.setContent(
                    '<div dir="rtl" style="text-align:right;padding:4px 8px;min-width:140px;"><strong>' +
                    escHtml(r.name) +
                    '</strong><br>عدد الطلبات: ' +
                    escHtml(r.count) +
                    '</div>'
                );
                infowindow.open(map, marker);
            });
        });

        if (hasPoint) {
            map.fitBounds(bounds);
            google.maps.event.addListenerOnce(map, 'idle', function () {
                if (map.getZoom() > 11) {
                    map.setZoom(11);
                }
            });
        }
    }
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY&language=ar&callback=initOrdersRegionMap">
</script>
@endsection
