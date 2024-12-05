<?php

namespace App\Http\Controllers;

use App\Models\CharOfAccount;
use Illuminate\Http\Request;

class COAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $accounts = CharOfAccount::get();
        return view("coa.index",compact("accounts"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $accounts = CharOfAccount::get();
        return view("coa.create", compact("accounts"));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
          // Validate the incoming request data
          $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'nature' => 'required|string',
            'opening_balance' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        // If validation fails, return back with the validation errors
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validation passed, create the CharOfAccount
        CharOfAccount::create([
            'title' => $request->title,
            'nature' => $request->nature,
            'parent_id' => ($request->is_header == true) ? 0 : $request->parent_id,
            'opening_balance' => $request->opening_balance ?? 0,
            'description' => $request->description,
        ]);

        // Redirect back or to a success page
        return redirect()->back()->with('success', 'CharOfAccount created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $coa = CharOfAccount::find($id);
        $accounts = CharOfAccount::all(); // Assuming this is for the parent account dropdown

        return view('coa.edit', compact('coa', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
