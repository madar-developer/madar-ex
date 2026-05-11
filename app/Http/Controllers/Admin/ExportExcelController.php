<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\CitiesReferenceExport;
use App\Exports\OrderFieldsTemplateExport;
use App\Exports\OrdersExport;
use App\Imports\OrderImport;
use App\Imports\CompanyOrderImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
class ExportExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:order_add', ['only' => ['ordersImportPage', 'downloadOrdersTemplate', 'downloadCitiesReference', 'Import']]);
    }

    public function ordersImportPage()
    {
        $title = 'استيراد الطلبات من Excel';
        return view('admin.orders.import', compact('title'));
    }

    public function downloadOrdersTemplate()
    {
        return Excel::download(new OrderFieldsTemplateExport(), 'orders-fields-template.xlsx');
    }

    public function downloadCitiesReference()
    {
        return Excel::download(new CitiesReferenceExport(), 'cities-reference.xlsx');
    }

    function index(){

        $company_data=DB::table('companies')->get();
        return view('reports.export_excel',compact('company_data'));    
    }
    

    // method to recoeve request to export customer data by lalravel package
    function excel(){
        return Excel::download(new OrdersExport, 'orders.xlsx');
        $company_data=DB::table('companies')->get()->toArray();
        $company_array[]=array('name', 'phone');
         //convert customer_data to php object
         foreach($company_data as $item)
         {
            $company_array[] = array(
                'name' => $item-> name ,
                'phone' => $item-> phone ,
            );
         }
         
         Excel::create('company Data ' ,function($excel) use ($company_array){
             $excel->setTitle('company Data');
             $excel->sheet('company Data' , function($sheet) use ($company_array){
                 $sheet->fromArray($company_array , null , 'A1' , false , false);
             });

         })->download('xlsx');
    }

    public function Import(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv',
            'company_id' => 'required|integer|exists:companies,id',
            'upload_date' => 'required|date',
        ]);

        if ($request->hasFile('excel')) {
            $file = uploadImage($request->file('excel'));
            $excelfile = public_path('/cdn/'.$file);
            Excel::import(new OrderImport((int) $request->get('company_id'), $request->get('upload_date')), $excelfile);
        }
            
            return redirect()->back()->with('success', 'All good!');
        
    }
    public function CompanyImport(Request $request)
    {
        
        if ($request->hasFile('excel')) {
            $file = uploadImage($request->file('excel'));
            $excelfile = public_path('/cdn/'.$file);
            Excel::import(new CompanyOrderImport, $excelfile);
        }
            
            return redirect()->back()->with('success', 'All good!');
        
    }
}
