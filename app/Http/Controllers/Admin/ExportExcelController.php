<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\OrdersExport;
use App\Imports\OrderImport;
use App\Imports\CompanyOrderImport;
use DB;
use Excel;
use App\Models\Order;
class ExportExcelController extends Controller
{
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
        
        if ($request->hasFile('excel')) {
            $file = uploadImage($request->file('excel'));
            $excelfile = public_path('/cdn/'.$file);
            Excel::import(new OrderImport, $excelfile);
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
