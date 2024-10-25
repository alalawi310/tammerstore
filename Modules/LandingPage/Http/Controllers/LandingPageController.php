<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;
use Illuminate\Support\Facades\Storage;
use App\Models\Utility;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user() && \Auth::user()->type == 'super admin')
        {
            return view('landingpage::landingpage.topbar');
        }
        else
        {
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $data = [
            "topbar_status" => $request->topbar_status ? $request->topbar_status : "off",
            "topbar_notification_msg" =>  $request->topbar_notification_msg,
        ];

        foreach($data as $key => $value){

            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> __('Topbar setting update successfully')]);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('landingpage::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function getInfoImages(Request $request , $slug=null , $section="")
    {

        return view('landingpage::layouts.infoimages',compact('slug','section'));
    }

    public function seoView()
    {
        if(\Auth::user() && \Auth::user()->type == 'super admin')
        {
            $settings = LandingPageSetting::settings();
            return view('landingpage::landingpage.seo',compact('settings'));
        }
        else
        {
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    public function seoStore(Request $request)
    {
        $dir        = Storage::url('uploads');
        if ($request->hasFile('metaimage')) {
            $fileName = rand(10,100).'_'.time() . "_" . $request->metaimage->getClientOriginalName();
            $path = Utility::upload_file($request,'metaimage',$fileName,$dir,[]);
        }
        if (!empty($request->metaimage) && isset($path['url'])) {
            $image = str_replace('/storage', '', $path['url']);
        }
        $data = [
            "metatitle" => $request->metatitle,
            "metakeyword" =>  $request->metakeyword,
            "metadesc" =>  $request->metadesc,
            "metaimage" =>  $path['url'] ?? null
        ];
        foreach($data as $key => $value){

            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> __('SEO setting update successfully')]);

    }
}
