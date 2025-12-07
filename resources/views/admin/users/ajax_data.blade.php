<div class="box-tebal bg-white mb-30">
    <div class="title">
        <h4><i class="fa fa-area-chart" aria-hidden="true"></i> بيانات العميل</h4>
    </div>
    <div class="box-body" style="padding: 0 10px;">
        <div role="tabpanel" class="tab-pane clearfix  m-b-30" style="overflow: hidden;">
            <table class="datatable-buttons table tab-pane-custom table-striped table-bordered text-center" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">البيانات</th>
                        </tr>
                    </thead>
                <tbody>
                    <tr>
                        <th class="text-center">بيانات العميل</th>
                        <th class="text-center">{{$user->name}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">رقم الهوية</th>
                        <th class="text-center">{{$user->identity}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">الجوال</th>
                        <th class="text-center">{{$user->phone}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">البريد الالكتروني</th>
                        <th class="text-center">{{$user->email}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">رقم الحساب</th>
                        <th class="text-center">{{$user->bank_account}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">اسم الضامن</th>
                        <th class="text-center">{{$user->guarantor_name}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">رقم تليفون الضامن</th>
                        <th class="text-center">{{$user->guarantor_phone}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">المحافظة</th>
                        <th class="text-center">{{$user->City->name ?? ''}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">المدينة</th>
                        <th class="text-center">{{$user->Region->name ?? ''}}</th>
                    </tr>

                    <tr>
                        <th class="text-center">المنطقة</th>
                        <th class="text-center">{{$user->District->name ?? ''}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
    
</div>
