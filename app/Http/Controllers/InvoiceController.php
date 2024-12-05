<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Tax;
use App\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Leads;
use App\Models\Payment;
use App\Models\UserLead;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Stripe\Charge;
use Stripe\Stripe;

class InvoiceController extends Controller
{


    public function __construct()
    {
        Stripe::setApiKey(config("cashier.secret"));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $invoices = Invoice::whereIn("company",Auth::user()->UserCompanies->pluck("company_id") )->get();
        return view('invoices.index', compact("invoices"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = 0)
    {
        $user = Auth::user();
        $users = User::whereHas('UserCompanies', function ($query) use ($user) {
            $query->whereIn('company_id', $user->UserCompanies->pluck("company_id"));
        })->orderBy('id', 'DESC')->get();
        $products = Product::get();
        $clients = Client::whereIn("company_id",$user->UserCompanies->pluck("company_id"))->get();
        $invoice_count = Invoice::count();
        $invoice_id = "INV-" . str_pad($invoice_count + 1, 5, 0, STR_PAD_LEFT);
        $taxes = Tax::all();
        $pms = PaymentMethod::whereHas("MethodCompanies", function ($query) use ($user) {
            $query->whereIn('company_id', $user->UserCompanies->pluck("company_id"));
        })->get();
        $companies = $this->getCompaniesForUser($user);
        // dd($companies);

        return view('invoices.create', compact('users', 'invoice_id', 'user', 'products', 'clients', 'taxes', 'pms', 'companies'));
    }
    private function getCompaniesForUser(User $user)
    {
        if (Auth::user()->hasRole('admin')) {
            return Company::select(['id', 'title'])->get();
        }

        return $user->companies()->get(['id', 'title']);
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
        $validatedData = $request->validate([
            'invoice_id' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'company' => 'required|integer|exists:companies,id',
            'amount_paid' => 'nullable|numeric|min:0',
            'product' => 'required|array',
            'product.*' => 'required|string|max:255',
            'description' => 'required|array',
            'description.*' => 'nullable|string|max:1000',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
        ]);
        $client = Client::findorfail($request->client_id);
        $user = User::findorfail($request->user_id);
        $total_amount = array_sum($request->input('amount'));
        $total_qty = array_sum($request->input('qty'));
        $description = json_encode($request->input('description'));
        $product = json_encode($request->input('product'));
        $invoice = Invoice::create([
            'user_id' => $user->id,
            'invoice_id' => $request->invoice_id,
            'company_id' => $user->company_id ?? 0,
            'company' => $request->company ?? 0,
            'client_id' => $request->client_id,
            'comment' => $request->comment ?? "",
            'additional_text' => $request->additional_text,
            'status' => 'inprocess', // Adjust as needed
            "payment_method" => $request->payment_method,
            'total_amount' => $total_amount * $total_qty,
            'amount_paid' => $request->amount_paid ?? 0,
        ]);

        // Insert details into the invoice_details table
        foreach ($request->input('product') as $key => $product) {
            InvoiceDetail::create([
                'invoice_id' =>  $invoice->id,
                'product' => $product,
                'description' => $request->input('description')[$key],
                'amount' => $request->input('amount')[$key],
                'qty' => $request->input('qty')[$key],
                'discount' => $request->input('discount')[$key] ?? 0.00,
                'tax' => $request->input('tax')[$key] ?? 0,
                'total_amount' => $request->input('amount')[$key] * $request->input('qty')[$key],
            ]);
        }
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
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
        $invoice = Invoice::findorfail($id);
        return view('invoices.view', compact("invoice"));
    }
    public function invoice($id)
    {
        //

        $invoice = Invoice::findorfail($id);
        return view('invoices.invoiceshow', compact("invoice"));
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
        $user = Auth::user();
        $invoice = Invoice::findOrFail($id); // Retrieve the invoice by its ID

        $user = Auth::user();
        $users = User::whereHas('UserCompanies', function ($query) use ($user) {
            $query->whereIn('company_id', $user->UserCompanies->pluck("company_id"));
        })->orderBy('id', 'DESC')->get();
        $products = Product::get();
        $clients = Client::whereHas("ClientCompanies", function ($query) use ($user) {
            $query->whereIn('company_id', $user->UserCompanies->pluck("company_id"));
        })->get();
        $invoice_count = Invoice::count();
        $invoice_id = "INV-" . str_pad($invoice_count + 1, 5, 0, STR_PAD_LEFT);
        $taxes = Tax::all();
        $pms = PaymentMethod::all();
        $companies = $this->getCompaniesForUser($user);
        return view('invoices.edit', compact('users', 'companies', 'invoice_id', 'invoice', 'user', 'products', 'clients', 'taxes', 'pms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     //
    //     $total_amount = array_sum($request->input('amount'));
    //     $total_qty = array_sum($request->input('qty'));
    //     $description = json_encode($request->input('description'));
    //     $product = json_encode($request->input('product'));

    //     $invoice = Invoice::findOrFail($id);

    //     $invoice->user_id = Auth::id();
    //     $invoice->company_id = Auth::user()->company_id;
    //     $invoice->client_id = $request->client_id;
    //     $invoice->company = $request->company;
    //     $invoice->comment = $request->comment;
    //     $invoice->status = 'inprocess'; // Adjust as needed
    //     $invoice->total_amount = $total_amount * $total_qty;
    //     $invoice->amount_paid = $request->amount_paid;
    //     $invoice->save();

    //     // Update details in the invoice_details table
    //     $invoice->details()->delete(); // Delete existing details
    //     foreach ($request->input('product') as $key => $product) {
    //         $invoice->details()->create([
    //             'product' => $product,
    //             'description' => $request->input('description')[$key],
    //             'amount' => $request->input('amount')[$key],
    //             'qty' => $request->input('qty')[$key],
    //             'total_amount' => $request->input('amount')[$key] * $request->input('qty')[$key],
    //         ]);
    //     }

    //     return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    // }
    public function update(Request $request, $id)
    {
        
        // Validate the request data
        $validatedData = $request->validate([
            'invoice_id' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'company' => 'required|integer|exists:companies,id',
            'amount_paid' => 'nullable|numeric|min:0',
            'product' => 'required|array',
            'product.*' => 'required|string|max:255',
            'description' => 'required|array',
            'description.*' => 'nullable|string|max:1000',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
        ]);
        // Find the existing invoice
        $invoice = Invoice::findOrFail($id);
        $user = User::findorfail($request->user_id);    
        // Calculate totals
        $total_amount = array_sum($request->input('amount'));
        $total_qty = array_sum($request->input('qty'));

        // Update the invoice details
        $invoice->update([
            'user_id' => $user->id,
            'invoice_id' => $request->invoice_id,
            'company_id' => $request->company ?? 0,
            'client_id' => $request->client_id,
            'comment' => $request->comment ?? "",
            'additional_text' => $request->additional_text,
            'status' => 'inprocess', // Adjust as needed
            "payment_method" => $request->payment_method,
            'total_amount' => $total_amount * $total_qty,
            'amount_paid' => $request->amount_paid ?? 0,
        ]);

        // Delete the existing invoice details (optional, or update existing records)
        $invoice->details()->delete();

        // Insert updated details into the invoice_details table
        foreach ($request->input('product') as $key => $product) {
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'product' => $product,
                'description' => $request->input('description')[$key],
                'amount' => $request->input('amount')[$key],
                'qty' => $request->input('qty')[$key],
                'discount' => $request->input('discount')[$key] ?? 0.00,
                'tax' => $request->input('tax')[$key] ?? 0,
                'total_amount' => $request->input('amount')[$key] * $request->input('qty')[$key],
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
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
        $Invoice = Invoice::find($id);
        if ($Invoice) {
            $Invoice->delete();
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } else {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }
    }


    public function addCustomer($lead_id)
    {

        $exist = Client::where([
            ['user_id', "=", Auth::id()],
            ['company_id', "=", Auth::user()->company_id],
            ['lead_id', "=", $lead_id],
        ])->exists();
        if (!$exist) {
            $lead = Leads::findorfail($lead_id);
            Client::create([
                'company_name' => $lead->company_name,
                'user_id' => Auth::id(),
                'company_id' => Auth::user()->company_id,
                'lead_id' => $lead_id,
                'first_name' => $lead->name,
                'last_name' => "",
                'email' => $lead->email,
                'phone' => $lead->phone,
                'telephone' => $lead->phone,
                'password' => Hash::make("12345678"),
                'description' => $lead->description,
            ]);
            return redirect()->route('invoices.index')->with('success', 'Customer Created Successfull.');
        } else {
            return redirect()->route('invoices.index')->with('error', 'Already Client Exist .');
        }
    }




    public function dopayment(request $request)
    {
        $invoice = Invoice::findorfail($request->invoice_id);
        try {
            // Make the charge to Stripe
            $charge = Charge::create([
                'amount' => $invoice->total_amount * 100, // Amount in cents
                'currency' => 'usd',
                'source' => $request->token,
                'description' => 'Inovice Paid Ref No .#' . $invoice->invoice_id,
            ]);
            Payment::create([
                "txn_id" => $charge["id"],
                "invoice_id" => $invoice->id,
                "amount" => $invoice->total_amount,
                "status" => $charge["status"],
                "pm" => "stripe"
            ]);
            $invoice->status = "paid";
            $invoice->save();
            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            // Payment failed, handle error
            return redirect()->route('invoices.index')->with('error', $e->getMessage());
        }
    }
}
