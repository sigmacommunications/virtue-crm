<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;


class SetupPMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentmethods = PaymentMethod::all();
        return view('setup_pm.index', compact("paymentmethods"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::where("is_default", "0")->get();
        return view('setup_pm.create', compact('companies'));
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
        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'paymentMethod' => 'required|in:paypal,stripe',
            'paypalClientId' => 'required_if:paymentMethod,paypal|max:255',
            'paypalSecret' => 'required_if:paymentMethod,paypal|max:255',
            'stripePublicKey' => 'required_if:paymentMethod,stripe|max:255',
            'stripeSecretKey' => 'required_if:paymentMethod,stripe|max:255',
            'company_id' => 'required|array',
            'company_id.*' => 'required|exists:companies,id'
        ]);

        $paymentMethod = new PaymentMethod();

        $paymentMethod->title = $validatedData['title'];
        if ($validatedData['paymentMethod'] === 'paypal') {
            $paymentMethod->mechant_company = 'PayPal';
            $paymentMethod->client_id = $validatedData['paypalClientId'];
            $paymentMethod->secret_key = $validatedData['paypalSecret'];
        } elseif ($validatedData['paymentMethod'] === 'stripe') {
            $paymentMethod->mechant_company = 'Stripe';
            $paymentMethod->public_key = $validatedData['stripePublicKey'];
            $paymentMethod->secret_key = $validatedData['stripeSecretKey'];
        }

        $paymentMethod->save();

        foreach ($validatedData['company_id'] as $companyId) {
            $paymentMethod->MethodCompanies()->create(['company_id' => $companyId]);
        }

        return redirect()->route('setup-pm.index')->with('success', 'Payment method added successfully.');
    }

    /**
     * Update the specified payment method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|max:100',
            'paymentMethod' => 'required|in:paypal,stripe',
            'paypalClientId' => 'required_if:paymentMethod,paypal|max:255',
            'paypalSecret' => 'required_if:paymentMethod,paypal|max:255',
            'stripePublicKey' => 'required_if:paymentMethod,stripe|max:255',
            'stripeSecretKey' => 'required_if:paymentMethod,stripe|max:255',
            'company_id' => 'required|array',
            'company_id.*' => 'required|exists:companies,id'
        ]);

        $paymentMethod->title = $validatedData['title'];
        if ($validatedData['paymentMethod'] === 'paypal') {
            $paymentMethod->mechant_company = 'PayPal';
            $paymentMethod->client_id = $validatedData['paypalClientId'];
            $paymentMethod->secret_key = $validatedData['paypalSecret'];
            $paymentMethod->public_key = null;
        } elseif ($validatedData['paymentMethod'] === 'stripe') {
            $paymentMethod->mechant_company = 'Stripe';
            $paymentMethod->public_key = $validatedData['stripePublicKey'];
            $paymentMethod->secret_key = $validatedData['stripeSecretKey'];
            $paymentMethod->client_id = null;
        }

        $paymentMethod->save();

        foreach ($validatedData['company_id'] as $companyId) {
            $paymentMethod->MethodCompanies()->create(['company_id' => $companyId]);
        }

        return redirect()->route('setup-pm.index')->with('success', 'Payment method updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $companies = Company::where("is_default", "0")->get();

        return view('setup_pm.edit', compact("paymentMethod", 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->deleteOrFail();
        return redirect()->route('setup-pm.index')->with('success', 'Payment method deleted successfully.');
    }
}
