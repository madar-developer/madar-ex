@extends('admin.layout.app')
@section('style')
<style>
    table {
        border-collapse: collapse;
        border: 1px solid black;
    }


    td,
    th {

        text-align: left;
        padding: 7px;
        border-bottom: 1px solid black;
    }

    @media print {
        .xc {
            text-align: left !important;
            border: 1px solid #000 !important;
            background-color: #000 !important;
            color: #fff !important;
            width: 2rem !important;
        }
    }

</style>
@endsection
@section('header')

        <div class="add-btn">
            <button type="button" id='btn' value='Print' onclick='printDiv();'
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-print"></i>
            </button>
        </div>
@endsection
@section('content')

<div class="card-box">

    <div class="row">


        <div class="title-page sub-header-title-custom">
            <h3 style="color: inherit;"><a href="{{route('companies.index')}}"><span> addresses</span><i class="fa fa-angle-left" style="padding: 0 5px; color:inherit;"></i></a><span>   </span></h3>


    </div>

        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> تفاصيل المتجر </h3>
              </div>
            <table class="table table-striped" style="  border: 1px solid gray;">
                <thead>

                <tbody>
                    <tr style="  border: 1px solid gray;">
                        <th scope="row" style="  border: 1px solid gray; color:#000;">اسم المتجر</th>
                        <td style="  border: 1px solid gray;">{{$company->first()->name}}</td>

                    </tr>

                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم تليفون المتجر</th>
                        <td style="  border: 1px solid gray;">{{$company->first()->phone}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;">البريد الالكترونى</th>
                        <td style="  border: 1px solid gray;">{{$company->first()->email}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> المدينه</th>
                        <td style="  border: 1px solid gray;">{{$company->first()->City()->first()->name}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> العنوان بالتفصيل</th>
                        <td style="  border: 1px solid gray;">{{$company->first()->adress_details}}</td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> تفاصيل الطلبات </h3>
              </div>
            <table class="table table-striped" style="  border: 1px solid gray;">
                <thead>

                <tbody>

                    @foreach (OrderStatus() as $key => $value)
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;">الطلبات {{$value}}</th>
                        <td style="  border: 1px solid gray;">{{$company->Order()->where('status','=',$key)->count()}}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
