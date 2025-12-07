<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-6">
            {{--  //////////////////////////////////////////  --}}
           
            <div class="form-group">
                <label class="">  الوقت من * </span></label>
                <div class="">
                    {!! Form::time("time_from",null,['class'=>'form-control'])!!}
                </div>
            </div>
            </div>
            <div class="col-lg-6">
            <div class="form-group">
                <label class="">  الوقت الى * </span></label>
                <div class="">
                    {!! Form::time("time_to",null,['class'=>'form-control'])!!}
                </div>
            </div>
        </div>
     
            <div class="col-lg-12 text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ </button>
            </div>
    </div>
</div>