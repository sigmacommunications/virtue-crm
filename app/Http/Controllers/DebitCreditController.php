<?php

namespace App\Http\Controllers;

use App\Models\CharOfAccount;
use App\Models\DebitCreditDetails;
use App\Models\DebitCreditMaster;
use Illuminate\Http\Request;
use App\Rules\BalancedTransactions;

class DebitCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $debitcredit = DebitCreditMaster::all();
        return view("debitcredit.index",compact("debitcredit"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $accounts = CharOfAccount::all();
        return view("debitcredit.create",compact("accounts"));
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
            'account.*' => 'required|exists:char_of_accounts,id',
            'type.*' => 'required|in:credit,debit',
            'description.*' => 'required|string|max:255',
            'amount.*' => 'required|numeric|min:0',
            // 'transactions' => ['required', new BalancedTransactions],
        ]);
        $master = array(
            "txn_no" => $this->generateTransactionNumber(),
            "Status" => "U",
            "user_id" => \Auth::id()
        );

        $parent = DebitCreditMaster::create($master);
        for ($i = 0; $i < count($request->account); $i++) {
            $debitCredit = new DebitCreditDetails();
            $debitCredit->parent_id = $parent->id;
            $debitCredit->account_id = $request->account[$i];
            $debitCredit->type = $request->type[$i];
            $debitCredit->description = $request->description[$i];
            $debitCredit->amount = $request->amount[$i];
            $debitCredit->save();
        }
        return redirect()->back()->with('success', 'Transaction created successfully');

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
            // Find the master record
        $master = DebitCreditMaster::findOrFail($id);

        // Delete the master record along with its details
        $master->details()->delete();
        $master->delete();
        return redirect()->back()->with('success', 'Transaction Deleted successfully');

    }

   private function generateTransactionNumber()
    {
        $lastTransaction = DebitCreditMaster::orderBy('id', 'desc')->first();
        $lastId = $lastTransaction ? $lastTransaction->id : 0;
        $inlineNumber = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT); // Pad with zeros to make it 4 digits
        $currentYear = date('Y');
        return 'TXN-' . $inlineNumber ."-". $currentYear;
    }
}
