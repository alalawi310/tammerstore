<?php

namespace App\Http\Controllers;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
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
    public function create()
    {
        //
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $countries = Country::orderBy('name','ASC')->pluck('name','id');
            $state = State::orderBy('name','ASC')->pluck('name','id');

            return view('City.create',compact('countries','state'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $city = new City();
        $city->country_id = $request->country_id;
        $city->state_id = $request->state_id;
        $city->name = $request->name;
        $city->save();
        session()->put('country_active_tab', $request->country_active_tab);
        return redirect()->back()->with('success', __('City successfully created.'));
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


    public function edit(City $city)
    {
        if (auth()->user() && auth()->user()->type == 'super admin') {

            $countries = Country::orderBy('name','ASC')->get()->pluck('name', 'id');
            $country = Country::find($city->country_id);

            $states = State::where('country_id', $city->country_id)->orderBy('name','ASC')->pluck('name', 'id');
            $state = State::find($city->state_id);

            return view('City.edit', compact('city', 'countries', 'country', 'states', 'state'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        
        $city->name = $request->city;
        $city->state_id =$request->state;
        $city->country_id = $request->country;
        $city->save();
        session()->put('country_active_tab', $request->country_active_tab);
        return redirect()->back()->with('success', __('City  successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $city->delete();
            return redirect()->back()->with('success', __('City successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
