<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Validator;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Leads;
use App\Models\Order;
use App\Models\Product;
use Session;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $companies = Auth::user()->UserCompanies->where("company_id","!=","1")->pluck("company_id");
        $data['users'] = User::wherehas('UserCompanies', function($query) use($companies){
            $query->whereIn("company_id",$companies);
        })->count();
        $data['Invoicetotal'] = Invoice::whereIn('company_id', $companies)->sum("total_amount");
        $data['client'] = Client::whereIn('company_id', $companies)->count();
      
        return view('home',$data);
    }

    public function profile()
    {

        $user = Auth::user();
        return view('profile',compact("user"));
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
        ]);

        $input = $request->all();

        $user = User::find($id);
        $user->update($input);

        session::flash('success','Record Updated Successfully');
        return redirect()->back();

    }

    public function edit($id)
    {
        $data['user'] = User::find($id);
        $profile = $user->profile; // assuming there is a one-to-one relationship between User and Profile

        return view('profile', $data);
    }



    public function profileupdate(Request $request){

        // dd($request->all());

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone_number;
        $user->address = $request->address;
        $user->created_by = auth()->user()->id;

        // Check if an image is present in the request
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = $file->getClientOriginalName() . time() . "Hatch-social." . $file->getClientOriginalExtension();
            $file->move('uploads/user/', $fileName);
            $user->image = $fileName;
        }

        $user->save();

        session::flash('success','Record Updated Successfully');
        return redirect('profile')->with('success','Record Uploaded Successfully');
    }




}
