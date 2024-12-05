<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Auth;
class ReportsController extends Controller
{
    //



    
    public function CustomerReport(Request $request)
    {
        $clients = Client::whereIn("company_id",Auth::user()->UserCompanies->pluck("company_id"))->get();
        if ($request->ajax())
        {
            $data = Invoice::query()->select('invoices.*','c.company_name')->leftjoin("clients as c","invoices.client_id","=","c.id");
            $data->where('invoices.client_id', $request->input('client_id') ?? 0);

            if ($request->has('date') && $request->filled('date')) {
                $date = Carbon::parse($request->input('date'))->toDateString();
                $data->whereDate('invoices.created_at', $date);
            }

            return DataTables::of($data)->make(true);
        }
        return view('reports.customer_report',compact("clients"));

    }

    public function CompaniesReport(Request $request)
    {
        $companies = Company::get();
        if ($request->ajax())
        {
            $data = Invoice::query()->select('invoices.*','c.company_name')->leftjoin("clients as c","invoices.client_id","=","c.id");
            $data->where('invoices.company_id', $request->input('company_id') ?? 0);

            if ($request->has('date') && $request->filled('date')) {
                $date = Carbon::parse($request->input('date'))->toDateString();
                $data->whereDate('invoices.created_at', $date);
            }
            return DataTables::of($data)->make(true);
        }
        return view('reports.companies_report',compact("companies"));
    }
    public function UserReport(Request $request)
    {

        $user = Auth::user();
        $users = User::whereHas('UserCompanies', function ($query) use ($user) {
            $query->whereIn('company_id', $user->UserCompanies->pluck("company_id"));
        })->orderBy('id', 'DESC')->get();
        if ($request->ajax())
        {
            $data = Invoice::query()->select('invoices.*','c.company_name')->leftjoin("clients as c","invoices.client_id","=","c.id");
            $data->where('invoices.user_id', $request->input('user_id') ?? 0);

            if ($request->has('date') && $request->filled('date')) {
                $date = Carbon::parse($request->input('date'))->toDateString();
                $data->whereDate('invoices.created_at', $date);
            }
            return DataTables::of($data)->make(true);
        }
        return view('reports.users_report',compact("users"));
    }
}
