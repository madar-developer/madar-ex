@extends('admin.layout.app')
@section('style')
<style>
    .salla-dev-card {
        min-height: 280px;
    }
    .salla-dev-response {
        background: #0f172a;
        color: #e2e8f0;
        border-radius: 6px;
        padding: 12px;
        min-height: 180px;
        max-height: 420px;
        overflow: auto;
        font-size: 12px;
        white-space: pre-wrap;
        word-break: break-word;
        margin: 0;
    }
    .salla-dev-response.is-ok {
        border: 1px solid #166534;
    }
    .salla-dev-response.is-error {
        border: 1px solid #991b1b;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card-box salla-dev-card">
            <h4 class="header-title m-t-0 m-b-20">Change Salla status</h4>
            <form id="salla-status-form">
                <div class="form-group">
                    <label>Order ID</label>
                    <input type="text" name="order_id" class="form-control" placeholder="id / serial / refrence_no / shipment_ref_id" required>
                </div>
                <div class="form-group">
                    <label>Changed status</label>
                    <select name="status" class="form-control" required>
                        <option value="">Select status</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Fire</button>
            </form>
            <h5 class="m-t-20">Response</h5>
            <pre id="salla-status-response" class="salla-dev-response">Waiting...</pre>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-box salla-dev-card">
            <h4 class="header-title m-t-0 m-b-20">Get Salla order info</h4>
            <form id="salla-info-form">
                <div class="form-group">
                    <label>Order ID</label>
                    <input type="text" name="order_id" class="form-control" placeholder="id / serial / refrence_no" required>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Fire</button>
            </form>
            <h5 class="m-t-20">Response</h5>
            <pre id="salla-info-response" class="salla-dev-response">Waiting...</pre>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
(function () {
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function pretty(data) {
        return JSON.stringify(data, null, 2);
    }

    function show(el, data, ok) {
        el.textContent = pretty(data);
        el.classList.remove('is-ok', 'is-error');
        el.classList.add(ok ? 'is-ok' : 'is-error');
    }

    function fire(url, payload, responseEl, button) {
        var original = button.textContent;
        button.disabled = true;
        button.textContent = 'Firing...';
        responseEl.textContent = 'Loading...';
        responseEl.classList.remove('is-ok', 'is-error');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(function (res) {
            return res.json().then(function (data) {
                return { ok: res.ok, data: data };
            });
        })
        .then(function (result) {
            show(responseEl, result.data, result.ok && result.data.ok !== false);
        })
        .catch(function (err) {
            show(responseEl, { ok: false, message: err.message }, false);
        })
        .finally(function () {
            button.disabled = false;
            button.textContent = original;
        });
    }

    document.getElementById('salla-status-form').addEventListener('submit', function (e) {
        e.preventDefault();
        fire(
            '{{ url("/dashboard/dev/salla/status") }}',
            {
                order_id: this.order_id.value,
                status: this.status.value
            },
            document.getElementById('salla-status-response'),
            this.querySelector('button[type="submit"]')
        );
    });

    document.getElementById('salla-info-form').addEventListener('submit', function (e) {
        e.preventDefault();
        fire(
            '{{ url("/dashboard/dev/salla/info") }}',
            { order_id: this.order_id.value },
            document.getElementById('salla-info-response'),
            this.querySelector('button[type="submit"]')
        );
    });
})();
</script>
@endsection
