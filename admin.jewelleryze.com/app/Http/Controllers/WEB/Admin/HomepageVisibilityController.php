<?php

namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePageOneVisibility;
class HomepageVisibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }

    public function index(){
        $sections = HomePageOneVisibility::all();
        return view("admin.home_page_one_visibility", compact("sections"));
    }

    public function update(Request $request){

        if($request->id == null || $request->status == null || $request->qty == null) {
            return response()->json(['message' => 'All field should be required'], 403);
        }else{
            $section = HomePageOneVisibility::find($request->id);
            $section->section_name = $request->section_name;
            $section->qty = $request->qty;
            $section->status = ($request->status == 'on')?1:0;
            $section->save();
        }
        

        /*foreach($request->ids as $index => $id){
            if($request->ids[$index] == null || $request->section_names[$index] == null || $request->quantities[$index] == null) {
                return response()->json(['message' => 'All field should be required'], 403);
            }else{
                $section = HomePageOneVisibility::find($request->ids[$index]);
                $section->section_name = $request->section_names[$index];
                $section->qty = $request->quantities[$index];
                $section->save();
            }
        }*/

        // $notification= trans('admin_validation.Update Successfully');
        // return response()->json(['notification' => $notification], 200);
        
        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.homepage-visibility')->with($notification);

    }
}
