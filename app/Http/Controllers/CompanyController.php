<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies = Company::get();
        return view('company.index', compact("companies"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required',
            'description' => 'required|string',
            'logo' => 'required|image',
        ]);
        if ($request->hasFile('logo')) {
            $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $filePath = $request->file('logo')->storeAs('logos', $fileName, 'public');

            $validatedData['logo'] = $filePath;
        }
        Company::create($validatedData);
        return redirect()->route('company.index')->with('success', 'Company created successfully.');
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
        $company = Company::find($id);
        if ($company) {
            return view('company.edit', compact('company'));
        } else {
            return redirect()->route('company.index')->with('error', 'Company not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required',
            'description' => 'required|string',
            'logo' => 'nullable|image',
        ]);

        $company = Company::findOrFail($companyId);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            // Define the file name and store location for the new logo
            $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();
            $filePath = $request->file('logo')->storeAs('logos', $fileName, 'public');

            $validatedData['logo'] = $filePath;
        }


        if ($company) {
            $company->update($validatedData);
            return redirect()->route('company.index')->with('success', 'Company updated successfully.');
        } else {
            return redirect()->route('company.index')->with('error', 'Company not found.');
        }
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
        $company = Company::find($id);

        if ($company) {
            $company->delete();
            return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
        } else {
            return redirect()->route('company.index')->with('error', 'Company not found.');
        }
    }
}
