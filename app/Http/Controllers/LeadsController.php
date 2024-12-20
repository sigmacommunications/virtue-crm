<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\LeadStatus;
use App\Models\LeadSources;
use App\Models\User;
use App\Models\UserLead;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Auth;
use Monarobase\CountryList\CountryListFacade as Countries;

class LeadsController extends Controller
{
    public function index()
    {
        $leads = Leads::with('LeadsUser')->get();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $leadStatus = LeadStatus::get();
        $LeadSources = LeadSources::get();
        $countries = Countries::getList();

        return view('leads.create',compact('leadStatus','LeadSources','countries'));
    }

    public function store(Request $request)
    {

        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'source' => 'required|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'position' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'website' => 'nullable|string|url|max:255',
                'country' => 'nullable|string|max:255',
                'tags' => 'nullable|string|max:255',
                'description' => 'nullable|string',
        ]);

        // Create a new lead
        $lead = new Leads();
        $lead->name = $request->input('name');
        $lead->email = $request->input('email');
        $lead->source = $request->input('source');
        $lead->company_name = $request->input('company_name');
        $lead->position = $request->input('position');
        $lead->phone = $request->input('phone');
        $lead->website = $request->input('website');
        $lead->country = $request->input('country');
        $lead->tags = $request->input('tags');
        $lead->default_language = $request->input('default_language');
        $lead->description = $request->input('description');
        $lead->public = $request->has('public');
        $lead->contacted_today = $request->has('contacted_today');
        $lead->save();

        // return redirect()->route('leads.index')->with('success', 'Leads created successfully');
    }

    public function show($id)
    {
        $Leads = Leads::with('Source','Status')->where('id',$id)->first();
        return view('leads.show', compact('Leads'));
    }

    public function edit($id)
    {
        $lead = Leads::findOrFail($id);
        $leadStatus = LeadStatus::get();
        $LeadSources = LeadSources::get();
        $MemberUser = User::withRole('Member')->get();
        return view('leads.edit', compact('lead','leadStatus','LeadSources','MemberUser'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'user_member' => 'nullable|exists:user_members,id',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|url|max:255',
            'country' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'default_language' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lead = Leads::findOrFail($id);
        $lead->name = $request->input('name');
        $lead->source = $request->input('source');
        $lead->company_name = $request->input('company_name');
        $lead->position = $request->input('position');
        $lead->status = $request->input('status');
        $lead->user_member_id = $request->input('user_member');
        $lead->phone = $request->input('phone');
        $lead->website = $request->input('website');
        $lead->country = $request->input('country');
        $lead->tags = $request->input('tags');
        $lead->default_language = $request->input('default_language');
        $lead->description = $request->input('description');
        $lead->public = $request->has('public');
        $lead->contacted_today = $request->has('contacted_today');
        $lead->save();

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully');
    }

    public function destroy($id)
    {
        $leads = Leads::findOrFail($id);
        $leads->delete();

        return redirect()->route('leads.index')->with('success', 'Leads deleted successfully');
    }

    public function LeadAccepted($id)
    {
        // Check if the user has already picked the lead
        $existingUserLead = UserLead::where('lead_id', $id)
            ->where('user_id', Auth::id())
            ->first();
        if ($existingUserLead) {
            return redirect()->route('leads.index')->with('error', 'You have already picked this lead.');
        }

        // If the user has not picked the lead before, proceed to save the new UserLead record
        $userLead = new UserLead();
        $userLead->lead_id = $id;
        $userLead->user_id = Auth::id();
        $userLead->status = 'pick';
        $userLead->save();

        return redirect()->route('leads.index')->with('success', 'Leads picked successfully');
    }

    public function LeadsPick(){
        $user_id = Auth::id();
        $lead_pick = UserLead::with('users','leads')->where('user_id',$user_id)->whereIn('status',['pending','pick','rejected','accepted'])->get();
        return view('leads.leads_pick',compact('lead_pick'));

    }

    public function LeadsMarkConvert(Request $request)
    {

    }

    public function LeadsUserInvoice()
    {
        $user_id = Auth::id();
        if($user_id == 1){
            $lead_accepted = UserLead::with('users','leads')->whereIn('status',['accepted'])->get();
        }else{
            $lead_accepted = UserLead::with('users','leads')->where('user_id',$user_id)->whereIn('status',['accepted'])->get();
        }
        return view('leads.invoice',compact('lead_accepted'));
    }

    public function LeadsInvoiceShow($id)
    {

    }
}
