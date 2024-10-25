<?php

namespace App\Http\Controllers;

use App\Models\TaxMethod;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class TaxMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tax_option_id)
    {
        //
        $country_option = Country::orderBy('name','ASC')->pluck('name', 'id')->prepend('Select Country', 0);
        return view('taxes-method.create',compact('country_option','tax_option_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user() && auth()->user()->isAbleTo('Create Tax Method'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'tax_rate' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $taxmethod                        = new TaxMethod();
            $taxmethod->name                  = $request->name;
            $taxmethod->tax_rate              = $request->tax_rate;
            $taxmethod->tax_id                = $request->tax_id;
            $taxmethod->country_id            = $request->country_id ?? null;
            $taxmethod->state_id              = $request->state_id ?? null;
            $taxmethod->city_id               = $request->city_id ?? null;
            $taxmethod->priority              = $request->priority;
            $taxmethod->enable_shipping       = $request->enable_shipping;
            $taxmethod->theme_id              = APP_THEME();
            $taxmethod->store_id              = getCurrentStore();
            $taxmethod->save();

        // Redirect back with success message
            return redirect()->back()->with('success', __('Tax-method successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxMethod $taxMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxMethod $taxMethod)
    {
        //
        $tax_method = TaxMethod::find($taxMethod->id);
        $country_option = Country::orderBy('name','ASC')->pluck('name', 'id')->prepend('Select Country', 0);
        $state_option = State::orderBy('name','ASC')->pluck('name', 'id')->prepend('Select State', 0);
        $city_option = City::orderBy('name','ASC')->pluck('name', 'id')->prepend('Select City', 0);
        return view('taxes-method.edit', compact('tax_method','country_option','state_option','city_option'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxMethod $taxMethod)
    {
        
        if(auth()->user() && auth()->user()->isAbleTo('Edit Tax Method'))
        {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'tax_rate' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $taxmethod = TaxMethod::find($taxMethod->id);
            $taxmethod->name                 = $request->name;
            $taxmethod->tax_rate              = $request->tax_rate;
            $taxmethod->country_id            = $request->country_id ?? null;
            $taxmethod->state_id              = $request->state_id ?? null;
            $taxmethod->city_id               = $request->city_id ?? null;
            $taxmethod->priority              = $request->priority;
            $taxmethod->enable_shipping       = $request->enable_shipping;
            $taxmethod->theme_id              = APP_THEME();
            $taxmethod->store_id              = getCurrentStore();
            $taxmethod->save();

            return redirect()->back()->with('success', __('Tax-method successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxMethod $taxMethod)
    {
        
        if(auth()->user() && auth()->user()->isAbleTo('Delete Tax Method'))
        {
            $tax = TaxMethod::find($taxMethod->id);
            $tax->delete();
            return redirect()->back()->with('success', __('Tax-method delete successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function cities_list(Request $request)
    {
        $state_id = $request->state_id;
        $cities_list = City::where('state_id',$state_id)->orderBy('name','ASC')->pluck('name', 'id')->prepend('Select City', 0)->toArray();
        return response()->json($cities_list);

    }
}
