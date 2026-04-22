@extends('admin.layout.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="m-t-0 m-b-20">{{ $title ?? 'استيراد الطلبات من Excel' }}</h4>
            <p class="text-muted m-b-20">
                قم بتحميل قالب Excel، عبّئ البيانات حسب أعمدة الطلب، ثم ارفع الملف مرة أخرى للاستيراد.
            </p>

            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>1) تحميل قالب الطلبات</strong>
                        </div>
                        <div class="panel-body">
                            <!-- <p class="m-b-15">القالب يحتوي أعمدة حقول الطلب (Order fields) كصف أول.</p> -->
                            <a href="{{ route('orders.import-template') }}?v={{ time() }}" class="btn btn-primary">
                                <i class="fa fa-download"></i> تحميل القالب
                            </a>
                            <a href="{{ route('orders.import-cities-reference') }}" class="btn btn-default m-r-10">
                                <i class="fa fa-map-marker"></i> تحميل المدن 
                            </a>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>2) رفع ملف Excel بعد التعبئة</strong>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('orders.import-excel') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="company_id">المتجر/الشركة</label>
                                    {!! Form::select('company_id', StoreOrCompany(), null, ['id' => 'company_id', 'class' => 'form-control', 'required' => true]) !!}
                                </div>
                                <div class="form-group">
                                    <label for="excel">ملف Excel</label>
                                    <input type="file" name="excel" id="excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-upload"></i> رفع واستيراد
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info m-b-0">
                <!-- <strong>ملاحظة:</strong> يرجى عدم تغيير أسماء الأعمدة في الصف الأول داخل القالب. الحقول النظامية مثل <code>status</code> و <code>serial</code> يتم توليدها تلقائيا أثناء الاستيراد. -->
            </div>
        </div>
    </div>
</div>
@endsection
