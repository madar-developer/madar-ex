<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>اسم الدائرة *</label>
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => '', 'placeholder' => 'مثال: مقر الشركة']) !!}
            </div>
            <div class="form-group">
                <label>نصف القطر (بالأمتار) *</label>
                {!! Form::number('radius_meters', isset($geofence) ? $geofence->radius_meters : 100, [
                    'class' => 'form-control',
                    'required' => '',
                    'min' => 10,
                    'max' => 10000,
                    'id' => 'radius_meters'
                ]) !!}
                <p class="help-block text-muted">حدد نصف قطر الدائرة على الخريطة (10 - 10000 متر)</p>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary">
                    <input id="active_checkbox" name="active" value="1" type="checkbox"
                        {{ (!isset($geofence) || $geofence->active) ? 'checked' : '' }}>
                    <label for="active_checkbox">مفعّل</label>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label>الموقع على الخريطة *</label>
                {!! Form::text('address_display', null, ['class' => 'form-control', 'id' => 'autocomplete', 'placeholder' => 'ابحث عن موقع...']) !!}
                <div id="mapCanvas"></div>
                {!! Form::hidden('latitude', null, ['id' => 'lat']) !!}
                {!! Form::hidden('longitude', null, ['id' => 'lng']) !!}
            </div>
        </div>
        <div class="col-lg-12 text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">حفظ</button>
        </div>
    </div>
</div>
