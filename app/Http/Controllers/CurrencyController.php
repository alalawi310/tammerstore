<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objUser = \Auth::user();
        if (auth()->user()->isAbleTo('Manage currency') && $objUser->type == 'super admin')
        {
            $currency = DB::table('currency')->get();
            return view('currency.index', compact('currency'));
        }
        else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          return view('currency.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create currency')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:currency,name',
                    'code' => 'required|unique:currency,code',
                    'symbol' => 'required|unique:currency,symbol',
            ]);
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $name = $request->input('name');
            $code = $request->input('code');
            $symbol = $request->input('symbol');
            DB::table('currency')->insert([
                'name' => $name,
                'code' => $code,
                'symbol' => $symbol,
            ]);

            return redirect()->back()->with('success', 'Currency Inserted successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
            $currencies = DB::table('currency')->where('id', $id)->first();
            return view('currency.edit',compact('currencies'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        if (auth()->user()->isAbleTo('Edit currency')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:currency,name,' . $id,
                    'code' => 'required|unique:currency,code,' . $id,
                    'symbol' => 'required|unique:currency,symbol,' . $id,
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $name = $request->input('name');
            $code = $request->input('code');
            $symbol = $request->input('symbol');
            DB::table('currency')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'code' =>  $code,
                    'symbol' => $symbol,
                ]);

            return redirect()->back()->with('success', 'Currency updated successfully.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
     /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
                // DB::table('currency')->where('id', $id)->delete();
                // return redirect()->back()->with('error', 'Currency deleted successfully.');
    }
}
