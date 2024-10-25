<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $countries = Country::orderBy('name','ASC')->get();
            $get_country = Country::orderBy('name','ASC')->get()->pluck('name', 'id');

            $get_country->prepend('Select Country', 0);
            $get_state = State::orderBy('name','ASC')->get()->pluck('name', 'id');

            $get_state->prepend('Select State', 0);
            $country_first = Country::orderBy('name','ASC')->pluck('id')->first();
            $country_id = !empty($request->country) ? $request->country : $country_first;
            $states = State::where('country_id', $country_id)->orderBy('name','ASC')->get();
            $state_first = State::pluck('id')->first();

            $state_id = !empty($request->state) ? $request->state :  $state_first;
            $cities = City::where('state_id', $state_id)->orderBy('name','ASC')->get();

            if (!empty($request->state_id) || !empty($request->country)) {
                $filter_data = 'filtered';
            } else {
                $filter_data = null;
            }
            
            $country_active_tab = session()->get('country_active_tab');
            
            if (empty($country_active_tab)) {
                $country_active_tab = 'pills-country-tab';
            }
            return view('Country.index',compact('countries','get_country','get_state','states','state_id','country_id','filter_data','cities','country_active_tab'));



        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            return view('Country.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $countries = new Country();
            $countries->name = $request->name;
            $countries->save();
            session()->put('country_active_tab', $request->country_active_tab);
            return redirect()->back()->with('success', __('Country successfully created.'));
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
    public function edit(Country $country)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            return view('Country.edit',compact('country'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $country->name = $request->name;
            $country->save();
            session()->put('country_active_tab', $request->country_active_tab);
            return redirect()->back()->with('success', __('Country successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $country->delete();
            return redirect()->back()->with('success', __('Country successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getCountry(Request $request)
    {
        $query = State::where('country_id' , '=' ,$request->country);
        if (!empty($request->country)) {
            $query->where('country_id', '=', $request->country);
        }
        $filter_country = $query->orderBy('name','ASC')->get();

        $filter = view('Country.filter', compact('filter_country'))->render();

        return response()->json($filter);
    }

}
