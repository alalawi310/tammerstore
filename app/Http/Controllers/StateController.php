<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $country,$state;
    public function __construct(Country $country, State $state)
    {
        $this->country = $country;
        $this->state = $state;
    }
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
            return view('State.create',compact('countries'));
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
        $state = new State();
        $state->name = $request->name;
        $state->country_id = $request->country_id;
        $state->save();

        session()->put('country_active_tab', $request->country_active_tab);

        return redirect()->back()->with('success', __('State successfully created.'));
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
    public function edit(State $state)
    {
        //
        if (auth()->user() && auth()->user()->type == 'super admin') {

            $country = Country::find($state->country_id);
            $countries = Country::orderBy('name','ASC')->get()->pluck('name','id');


            return view('State.edit',compact('state','country','countries'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,State $state)
    {
        
        $state->name = $request->state;
        $state->country_id= $request->country;
        $state->save();
        session()->put('country_active_tab', $request->country_active_tab);
        return redirect()->back()->with('success', __('State successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        
        if (auth()->user() && auth()->user()->type == 'super admin') {
            $state->delete();
            return redirect()->back()->with('success', __('State successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getState(Request $request)
    {

        $query = City::where('state_id' , '=' ,$request->state);
        if (!empty($request->state)) {
            $query->where('state_id', '=', $request->state);
        }
        $filter_state = $query->orderBy('name','ASC')->get();

        $filter = view('State.filter', compact('filter_state'))->render();

        return response()->json($filter);
    }

    public function getAllState()
    {
        $state = State::orderBy('name','ASC')->get()->toArray();
        return $state ;
    }


 public function getCityState(Request $request)
 {
     if ($request->country_id == 0) {
         $departments = State::orderBy('name','ASC')->get()->pluck('name', 'id')->toArray();
     } else {
         $departments = State::where('country_id', $request->country_id)->orderBy('name','ASC')->get()->pluck('name', 'id')->toArray();
     }
     return response()->json($departments);
 }
}
