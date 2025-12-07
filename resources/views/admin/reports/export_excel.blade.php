{{--  <!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>  --}}

    @extends('admin.layout.app')
@section('style')
@section('header')
@endsection
@section('content')


<!-- Page-Title -->

<div class="row">

    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 part-top">
                        <div class="row">

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="name"
                                                value="{{(array_key_exists('name', $search))? $search['name'] : ''}}"
                                                class="form-control" placeholder="اسم الشركه">
                                        </div>
                                    </div>
                    
                                </div>
                            </div>


                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="phone"
                                                value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}"
                                                class="form-control" placeholder="الفون ">
                                        </div>
                                    </div>
                    
                                </div>
                            </div>
                        </div>
                    </div>

    <div class='container'>
        <h3 style='align:center;'> اصدر تقرير المتاجر والشركات  </h3>
        <a href="{{route('export_excel.excel')}}" class="btn btn-sucess">  اصدار تقرير المتاجر والشركات  </a>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>  اسم المتجر </th>
                    <th>   رقم تليفون المتجر </th>
                </tr>
                @foreach($company_data as $item)
                <tr>
                    <td>{{$item->name}} </td>
                    <td>{{$item->phone}} </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>








    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
        < /body>
