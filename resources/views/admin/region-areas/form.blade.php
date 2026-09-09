<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label>العنوان *</label>
                {!! Form::text('title', null, ['class' => 'form-control', 'required' => '', 'placeholder' => 'مثال: شمال الرياض']) !!}
            </div>
            <div class="form-group">
                <label>الكود *</label>
                {!! Form::text('code', null, ['class' => 'form-control', 'required' => '', 'placeholder' => 'مثال: RUH-N']) !!}
                <p class="help-block text-muted">يُحفظ هذا الكود في الطلب إذا كان موقعه داخل المنطقة</p>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary">
                    <input id="active_checkbox" name="active" value="1" type="checkbox"
                        {{ (!isset($area) || $area->active) ? 'checked' : '' }}>
                    <label for="active_checkbox">مفعّل</label>
                </div>
            </div>
            <p class="text-muted" id="draw-hint">انقر على الخريطة لإضافة نقاط المضلع (ثلاث نقاط على الأقل)، ثم انقر نقراً مزدوجاً أو اضغط «إنهاء الرسم». يمكنك سحب الرؤوس للتعديل بعد الإنهاء.</p>
        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <label>المنطقة على الخريطة *</label>
                {!! Form::text('address_display', null, ['class' => 'form-control', 'id' => 'autocomplete', 'placeholder' => 'ابحث عن موقع...']) !!}
                <div class="m-t-10 m-b-10">
                    <button type="button" class="btn btn-sm btn-primary" id="finish-polygon" disabled>إنهاء الرسم</button>
                    <button type="button" class="btn btn-sm btn-default" id="undo-vertex" disabled>تراجع عن نقطة</button>
                    <button type="button" class="btn btn-sm btn-danger" id="redraw-polygon">إعادة الرسم</button>
                </div>
                <div id="mapCanvas"></div>
                <input type="hidden" name="coordinates" id="coordinates" value="{{ isset($area) ? e(json_encode($area->coordinates)) : '' }}">
            </div>
        </div>
        <div class="col-lg-12 text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">حفظ</button>
        </div>
    </div>
</div>
