{!!Form::open( ['url' => route('orders-invoice', $order->id) ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
<div class="form-group">
    <label class=""> تكلفه التوصيل * </label>
    <div class="">
        {!! Form::number("cost",null,['class'=>'form-control', 'step' => "any"])!!}
    </div>
</div>
<div class="text-center">
    <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
</div>
{!!Form::close() !!}