<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Session;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"],
    ];

    public function index()
    {
        if (!\Helper::userAccess('office-account-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "Accounts"];

        return view('admin.account.index', [
            'breadcrumbs' => $this->breadcrumbs]);

    }

    public function listAccount(Request $request)
    {
        if (!\Helper::userAccess('office-account-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Account::query();
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('account_name', 'like', "%" . $search . "%");
                    $q->orWhere('account_short_name', 'like', '%' . $search . '%');
                });
            }
        }
        if ($request->has('columns') && $request->has('order')) {
            $order_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $sort_by = $columnName_arr[$order_arr[0]['column']]['data'];
            $sort_type = $order_arr[0]['dir'];
            if ($sort_by != '' && $sort_type != '') {
                $query->orderBy($sort_by, $sort_type);
            }
        }
        $iTotalRecords = $query->count();
        $data = $query->skip($start)->take($rowperpage)->get();

        //Add action ACL
        $data->map(function ($item) {
            return $item['action'] = [
                'view' => \Helper::userAccess('office-account-view'),
                'edit' => \Helper::userAccess('office-account-edit'),
                'delete' => \Helper::userAccess('office-account-delete'),
            ];
        });

        return response()->json([
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "aaData" => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\Helper::userAccess('office-account-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $this->breadcrumbs[] = ['link' => "admin/account", 'name' => "Accounts"];
        $this->breadcrumbs[] = ['name' => "Create Account"];

        return view('admin.account.create', [
            'breadcrumbs' => $this->breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!\Helper::userAccess('office-account-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $validator_messages = [
            'account_name.string' => 'Please enter valid office name',
            'account_short_name.string' => 'Please enter valid office short name',
            'state_account.*.account_state.distinct' => 'State already selected',
            'state_account.*.account_state.required' => 'Please select a state',
            'state_account.*.account_state.distinct' => 'State already selected',
            'state_account.*.account_state.max' => 'Please select a valid state',
            'state_account.*.account_gst.required' => 'Please enter GST value',
            'state_account.*.account_address_1.required' => 'Please enter address 1',
            'state_account.*.account_address_2.required' => 'Please enter address 2',
            'state_account.*.account_gst.max' => 'Max length exceeded',
            'state_account.*.account_address_1.max' => 'Max length exceeded',
            'state_account.*.account_address_2.max' => 'Max length exceeded',
        ];
        $validator_conditions = [
            'account_name' => 'required|max:32|string|unique:accounts,account_name,NULL,id,deleted_at,NULL',
            'account_short_name' => 'required|string|max:32|unique:accounts,account_short_name,NULL,id,deleted_at,NULL',
            'state_account' => 'required|array|min:1',
            'state_account.*.account_state' => 'required|string|distinct|max:64',
            'state_account.*.account_gst' => 'required|string|max:128',
            'state_account.*.account_address_1' => 'required|string|max:150',
            'state_account.*.account_address_2' => 'nullable|string|max:150',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $account_data = ['account_name' => $request->account_name, 'account_short_name' => $request->account_short_name];
            $account = Account::create($account_data);
            foreach ($request->state_account as $sub_account) {
                $sub_account_data = ['account_slug' => $account->slug, 'account_state' => $sub_account['account_state'], 'account_gst' => $sub_account['account_gst'], 'account_address_1' => $sub_account['account_address_1'], 'account_address_2' => $sub_account['account_address_2']];
                SubAccount::create($sub_account_data);
            }
            return redirect()->route('admin.account')
                ->with('success', 'Account created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        if (!\Helper::userAccess('office-account-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/account", 'name' => "Accounts"], ['name' => "Account Details"],
        ];

        return view('admin.account.show', compact('account'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        if (!\Helper::userAccess('office-account-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/account", 'name' => "Accounts"], ['name' => "Edit Account"],
        ];
        return view('admin.account.edit', compact('account'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        if (!\Helper::userAccess('office-account-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $validator_messages = [
            'account_name.string' => 'Please enter valid office name',
            'account_short_name.string' => 'Please enter valid office short name',
            'state_account.*.account_state.distinct' => 'State already selected',
            'state_account.*.account_state.distinct' => 'State already selected',
            'state_account.*.account_state.required' => 'Please select a state',
            'state_account.*.account_state.distinct' => 'State already selected',
            'state_account.*.account_state.max' => 'Please select a valid state',
            'state_account.*.account_gst.required' => 'Please enter GST value',
            'state_account.*.account_address_1.required' => 'Please enter address 1',
            'state_account.*.account_address_2.required' => 'Please enter address 2',
            'state_account.*.account_gst.max' => 'Max length exceeded',
            'state_account.*.account_address_1.max' => 'Max length exceeded',
            'state_account.*.account_address_2.max' => 'Max length exceeded',
        ];
        $validator_conditions = [
            'account_name' => 'required|max:32|string|unique:accounts,account_name,' . $account->slug . ',slug,deleted_at,NULL',
            'account_short_name' => 'required|string|max:32|unique:accounts,account_short_name,' . $account->slug . ',slug,deleted_at,NULL',
            'state_account' => 'required|array|min:1',
            'state_account.*.account_state' => 'required|string|distinct|max:64',
            'state_account.*.account_gst' => 'required|string|max:128',
            'state_account.*.account_address_1' => 'required|string|max:150',
            'state_account.*.account_address_2' => 'nullable|string|max:150',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $account_data = ['account_name' => $request->account_name, 'account_short_name' => $request->account_short_name];
            $account->update($account_data);
            $update_sub_accounts = [];
            foreach ($request->state_account as $sub_account) {
                $update_sub_accounts[] = $sub_account['account_state'];
                $sub_account_data = ['account_slug' => $account->slug, 'account_state' => $sub_account['account_state'], 'account_gst' => $sub_account['account_gst'], 'account_address_1' => $sub_account['account_address_1'], 'account_address_2' => $sub_account['account_address_2']];
                // check if already exist
                $update_already_exist = SubAccount::where([
                    ['account_state', '=', $sub_account['account_state']],
                    ['account_slug', '=', $account->slug]])->first();
                if ($update_already_exist) {
                    $update_already_exist->update($sub_account_data);
                } else {
                    SubAccount::create($sub_account_data);
                }
            }
            $get_exist_subaccounts = SubAccount::where('account_slug', '=', $account->slug)->pluck('account_state')->toArray();
            $delete_subaccounts = array_diff($get_exist_subaccounts, $update_sub_accounts);
            if (sizeof($delete_subaccounts) > 0) {
                SubAccount::where('account_slug', '=', $account->slug)->whereIn('account_state', $delete_subaccounts)->delete();
            }
            return redirect()->route('admin.account')
                ->with('success', 'Account updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        if (!\Helper::userAccess('office-account-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.account');
        }

        $account->subAccounts()->delete();
        $account->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }
}