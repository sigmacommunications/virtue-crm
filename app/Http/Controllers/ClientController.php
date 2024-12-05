<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {  
        $user = Auth::user();
        $companies = $user->UserCompanies->pluck("company_id");
        $data['clients'] = Client::whereIn("company_id",$companies)->get();
        return view('client.index',$data);
    }

    public function create()
    {
        $user = Auth::user();
        $companies = $user->companies;
        return view('client.create',compact("companies"));
    }

    public function store(Request $request)
    {
        try
        {
            $this->validate(request(), [
                'company_name' => 'required',
                'category' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'telephone' => 'required',
                'description' => 'required',
                'salutation' => 'required',
                'currency' => 'required',
            ]);
            // dd($request->all());
            Client::create([
                'company_name' => $request->company_name,
                'salutation' => $request->salutation,
                'currency' => $request->currency,
                'user_id' => Auth::id(),
                'company_id' => $request->company_id,
                'category' => $request->category,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'telephone' => $request->telephone,
                'description' => $request->description,
                'lead_id' => 0,
            ]);
            return redirect('/client')->with(['success'=>'Client Create Successfully']);
        }
        catch(\Exception $e)
        {
            // throw $e;
            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }
    }

    public function show(Client $client)
    {
        //
    }

    public function edit(Client $client)
    {
        $user = Auth::user();
        $companies = $user->UserCompanies;
        return view('client.edit',compact("client","companies"));
    }

    public function update(Request $request, Client $client)
    {
        try
        {
            $this->validate(request(), [
                'company_name' => 'required',
                'category' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'telephone' => 'required',
                'description' => 'required',
            ]);
            $client->update([
                'company_name' => $request->company_name,
                'category' => $request->category,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'company_id' => $request->company_id,
                'phone' => $request->phone,
                'telephone' => $request->telephone,
                'description' => $request->description,
            ]);
            return redirect('/client')->with(['success'=>'Client Update Successfull']);
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */

    public function assign_client(Client $client)
    {
        try
        {
            return response()->json(['success'=>'success','clients'=>$client->get()]);
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }
    }

    public function destroy(Client $client)
    {
        try
        {
            $client->delete();
            return redirect()->back()->with(['success'=>'Client Delete Successfully']);
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with(['error'=>$e->getMessage()]);
        }
    }
}
