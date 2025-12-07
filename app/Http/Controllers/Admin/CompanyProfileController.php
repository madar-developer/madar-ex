<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Company;
use Auth;
use Carbon\Carbon;


class CompanyProfileController extends Controller
{
    public function profile()
    {
        $company = auth()->user();
        $title = $company->name;
        return view('company.profile.show', compact('company', 'title'));
    }
}
    