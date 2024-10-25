<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;
use Modules\LandingPage\Entities\OwnerMenu;
use Modules\LandingPage\Entities\OwnerMenuItem;
use Modules\LandingPage\Entities\OwnerMenuSetting;
use Carbon\Carbon;

class OwnerMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->type == 'super admin'){
            $settings = LandingPageSetting::settings();
            $menus = OwnerMenu::where('created_by',\Auth::user()->id)->get();
            $pages = json_decode($settings['menubar_page'], true);
            return view('landingpage::landingpage.ownermenu.index', compact('pages','menus','settings'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::landingpage.ownermenu.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $settings = LandingPageSetting::settings();
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $menus                        = new OwnerMenu();
        $menus->name                  = $request->name;
        $menus->created_by            = \Auth::user()->id;
        $menus->save();
        return redirect()->back()->with('success', __('Menus successfully created.'));

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
        // if(auth()->user() && auth()->user()->isAbleTo('Edit Menu'))
        // {
            $menuitems = '';
            $desiredMenu = '';
            if (isset($id)) {
                $desiredMenu = OwnerMenu::where('id', $id)->first();
                if ($desiredMenu->content != '') {
                    $menuitems = json_decode($desiredMenu->content);
                    $menuitems = $menuitems[0] ?? [];
                    // dd($menuitems);
                    foreach ($menuitems as $menu) {
                        $menu->title    = OwnerMenuItem::where('id', $menu->id)->value('title');
                        $menu->slug     = OwnerMenuItem::where('id', $menu->id)->value('slug');
                        $menu->target   = OwnerMenuItem::where('id', $menu->id)->value('target');
                        $menu->type     = OwnerMenuItem::where('id', $menu->id)->value('type');
                        if (!empty($menu->children[0])) {
                            foreach ($menu->children[0] as $child) {
                                $child->title   = OwnerMenuItem::where('id', $child->id)->value('title');
                                $child->slug    = OwnerMenuItem::where('id', $child->id)->value('slug');
                                $child->target  = OwnerMenuItem::where('id', $child->id)->value('target');
                                $child->type    = OwnerMenuItem::where('id', $child->id)->value('type');
                            }
                        }
                    }
                } else {
                    $menuitems = OwnerMenuItem::where('menu_id', $desiredMenu->id)->get();

                }
                $settings = LandingPageSetting::settings();
                $pages = json_decode($settings['menubar_page'], true);
                return view('landingpage::landingpage.ownermenu.edit', compact('desiredMenu', 'menuitems','pages'));
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $key)
    {
        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page'], true);
        $page_slug = str_replace(' ', '_', strtolower($request->menubar_page_name));
        $datas['menubar_page_name'] = $request->menubar_page_name;
        $datas['menubar_page_contant'] = $request->menubar_page_contant;

        $datas['page_slug'] = $page_slug;
        $datas['template_name'] = $request->template_name;

        if (isset($request->template_name) && $request->template_name == 'page_url') {
            $datas['page_url'] = $request->page_url;
            $datas['menubar_page_contant'] = '';
        } else {
            $datas['page_url'] = '';
            $datas['menubar_page_contant'] = $request->menubar_page_contant;
        }

        if ($request->login) {
            $datas['login'] = 'on';
        } else {
            $datas['login'] = 'off';
        }


        if($request->header){
            $datas['header'] = 'on';
        }else{
            $datas['header'] = 'off';
        }

        if($request->footer){
            $datas['footer'] = 'on';
        }else{
            $datas['footer'] = 'off';
        }

        $data[$key] = $datas;
        $data = json_encode($data);


        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'],['value' => $data]);
        return redirect()->back()->with(['success'=> __('Page Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        // if(auth()->user() && auth()->user()->isAbleTo('Delete Menu'))
        // {
            OwnerMenuItem::where('menu_id', $id)->delete();
            OwnerMenu::findOrFail($id)->delete();
            return redirect()->route('ownermenus.index')->with('success', __('Menu deleted successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function customStore(Request $request)
    {

        if( $request->site_logo){
            $site_logo = "site_logo." . $request->site_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image/';
            $path = LandingPageSetting::upload_file($request,'site_logo',$site_logo,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data['site_logo'] = $site_logo;
        }

        $data['site_description'] = $request->site_description;

        foreach($data as $key => $value){

            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> __('Settings save successfully.')]);
    }

    public function customPage($slug)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);

        foreach ($pages as $key => $page) {
            if($page['page_slug'] == $slug){
                return view('landingpage::layouts.custompage', compact('page', 'settings'));
            }
        }

    }

    public function addPageToMenu(Request $request)
    {
        $menuid     = $request->menuid;
        $ids        = $request->ids;
        $menu       = OwnerMenu::findOrFail($menuid);
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);
        if ($menu->content == '' || empty($menu->content) || $menu->content == 'null') {
            $array = [];
            foreach ($ids as $key => $id) {
                $matchingPage = null;
                foreach ($pages as $page) {
                    if ($page['menubar_page_name'] === $id) {
                        $matchingPage = $page;
                        break;
                    }
                }
                if ($matchingPage) {
                    $title = $matchingPage['menubar_page_name'];
                    $slug = $matchingPage['page_slug'];

                    $item = OwnerMenuItem::create([
                        'title'         => $title,
                        'slug'          => $slug,
                        'type'          => 'page',
                        'menu_id'       => $menuid,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ]);

                    $array[$key]['id'] = $item->id;
                }
            }
            // if (count($array) > 0) {
            //     $oldata = json_encode([$array]);
            //     $menu->update(['content' => $oldata]);
            // }
        } else {
            $olddata = json_decode($menu->content, true);
            foreach ($ids as $id) {
                $matchingPage = null;
                foreach ($pages as $page) {
                    if ($page['menubar_page_name'] === $id) {
                        $matchingPage = $page;
                        break;
                    }
                }

                if ($matchingPage) {
                    $title = $matchingPage['menubar_page_name'];
                    $slug = $matchingPage['page_slug'];
                    $pageData[] = [
                        'title'         => $title,
                        'slug'          => $slug,
                        'type'          => 'page',
                        'menu_id'       => $menuid,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ];
                }
            }
            OwnerMenuItem::insert($pageData);
            foreach ($ids as $id) {
                $array['title']     = $title;
                $array['slug']      = $slug;
                $array['type']      = 'page';
                $array['target']    = NULL;
                $array['id']        = OwnerMenuItem::where('slug', $array['slug'])->where('title', $array['title'])->where('type', $array['type'])->orderby('id', 'DESC')->value('id');
                $array['children']  = [[]];
                array_push($olddata[0], $array);

                $oldata = json_encode($olddata);
                $menu->update(['content' => $oldata]);
            }
        }

        return response()->json(['status' => true , 'message' => __('Menu updated successfully.')]);
    }

    public function updateMenuItem(Request $request)
    {

        // if(auth()->user() && auth()->user()->isAbleTo('Edit Menu'))
        // {
            $data           = $request->all();
            $item           = OwnerMenuItem::findOrFail($request->id);
            $data['target'] = (isset($request->target)) ? '_blank' : '';
            $item->update($data);
            return redirect()->back();
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function deleteMenuItem($id, $key, $in = '')
    {
        // if(auth()->user() && auth()->user()->isAbleTo('Delete Menu'))
        // {
            $menuitem   = OwnerMenuItem::findOrFail($id);
            $menu       = OwnerMenu::where('id', $menuitem->menu_id)->first();

            if ($menu->content != '') {
                $data       = json_decode($menu->content, true);
                $maindata   = $data[0];
                dd($data);
                if ($in == '') {
                    unset($data[0][$key]);
                    $newdata = json_encode($data);
                    $menu->update(['content' => $newdata]);
                } else {
                    unset($data[0][$key]['children'][0][$in]);
                    $newdata = json_encode($data);
                    $menu->update(['content' => $newdata]);
                }
            }
            $menuitem->delete();
            return redirect()->back();
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function updateMenu(Request $request)
    {
        // if(auth()->user() && auth()->user()->isAbleTo('Edit Menu'))
        // {
            $newdata                = $request->all();
            $menu                   = OwnerMenu::findOrFail($request->menuid);
            $content                = $request->data;
            $newdata['location']    = $request->location;
            $newdata['content']     = json_encode($content);
            $menu->update($newdata);
            return response()->json(['status' => true , 'message' => __('Menu updated successfully.')]);
        // }
        // else
        // {
        //     return response()->json(['status' => false , 'message' => __('Permission denied.')]);
        // }
    }

    public function addLinkToMenu(Request $request)
    {
        $data       = $request->all();
        $menuid     = $request->menuid;
        $menu       = OwnerMenu::findOrFail($menuid);
        if ($menu->content == '') {
            $data['title']      = $request->link;
            $data['slug']       = $request->url;
            $data['type']       = 'custom';
            $data['menu_id']    = $menuid;
            OwnerMenuItem::create($data);
        } else {
            $olddata            = json_decode($menu->content, true);
            $data['title']      = $request->link;
            $data['slug']       = $request->url;
            $data['type']       = 'custom';
            $data['menu_id']    = $menuid;
            OwnerMenuItem::create($data);
            $array              = [];
            $array['title']     = $request->link;
            $array['slug']      = $request->url;
            $array['type']      = 'custom';
            $array['target']    = NULL;
            $array['id']        = OwnerMenuItem::where('slug', $array['slug'])->where('title', $array['title'])->where('type', $array['type'])->orderby('id', 'DESC')->value('id');
            $array['children']  = [[]];
            array_push($olddata[0], $array);
            $oldata = json_encode($olddata);
            $menu->update(['content' => $oldata]);
        }

        return response()->json(['status' => true , 'message' => __('Menu updated successfully.')]);
    }

    public function manageOwnermenu(Request $request)
    {
        $enable_login = $request->enable_login ? 'on' : 'off';
        $enable_header = $request->enable_header ? 'on' : 'off';
        $enable_footer = $request->enable_footer ? 'on' : 'off';
        $enable_dropdown = $request->enable_dropdown ? 'on' : 'off';
        $menus_id = !empty($request->menus_id) ? implode(',', $request->menus_id) : null;

        $userId = auth()->user()->id;
        $ownermenu = OwnerMenuSetting::where('created_by', $userId)->first();
        if ($ownermenu && isset($ownermenu->menus_id)) {
            $get_menu = explode(',', $ownermenu->menus_id);
        } else {
            $get_menu = [];
        }
        if ($ownermenu) {
            $ownermenu->menus_id = $menus_id;
            $ownermenu->enable_header = $enable_header;
            $ownermenu->enable_login = $enable_login;
            $ownermenu->enable_footer = $enable_footer;
            $ownermenu->enable_dropdown = $enable_dropdown;
            $ownermenu->save();
        } else {
            $ownermenu = new OwnerMenuSetting();
            $ownermenu->menus_id = $menus_id;
            $ownermenu->enable_header = $enable_header;
            $ownermenu->enable_login = $enable_login;
            $ownermenu->enable_footer = $enable_footer;
            $ownermenu->enable_dropdown = $enable_dropdown;
            $ownermenu->created_by = $userId;
            $ownermenu->save();
        }

        return redirect()->back()->with('success', __('Menu Setting updated successfully.'));
    }

}
