<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-12">
            {{--  //////////////////////////////////////////  --}}
            <div class="form-group">
                <label class="">  الصوره * </span></label>
                <div class="">
                    {!! Form::file("image",['class'=>'form-control'])!!}
                    @if(isset($partner) && $partner->image != null)
                        <img src="{{getImage($partner->image)}}" style="height: 200px;">
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="">  العنوان * </span></label>
                <div class="">
                    {!! Form::text("title",null,['class'=>'form-control'])!!}
                </div>
            </div>
        </div>
     
            <div class="col-lg-12 text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ </button>
            </div>
    </div>
</div>