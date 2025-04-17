<?php

namespace App\Http\Controllers;

use App\Http\Requests\HeroBannerRequest;
use App\Models\HeroBanner;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HeroBannerController extends Controller
{
    use CommonFunctions;

    public function viewBanner(){
        return view("Dashboard.Pages.manageHeroBanner");
    }

    public function getBanner(){
            $query = HeroBanner::select(
                HeroBanner::IMAGE,
                HeroBanner::ID,
                HeroBanner::TITLE,
                HeroBanner::SUB_TITLE,
                HeroBanner::STATUS
            );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{HeroBanner::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{HeroBanner::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{HeroBanner::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        
        
    }

    public function saveBanner(HeroBannerRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertBanner($request);
                    break;
                case "update":
                    $return = $this->updateBanner($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableBanner($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertBanner(HeroBannerRequest $request){ 
        $imageUpload = $this->heroImageUpload($request);
        
        if($imageUpload['status'])
        {
            $Banner = new HeroBanner();
            $Banner->{HeroBanner::IMAGE} = $imageUpload['data'];
            $Banner->{HeroBanner::STATUS} = $request->input(HeroBanner::STATUS);           
            $Banner->{HeroBanner::TITLE} = $request->input(HeroBanner::TITLE);
            $Banner->{HeroBanner::SUB_TITLE} = $request->input(HeroBanner::SUB_TITLE);
            $Banner->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function heroImageUpload(HeroBannerRequest $request){
        $maxId = HeroBanner::max(HeroBanner::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/Banner/","slide_$maxId");
    }

    
    public function updateBanner(HeroBannerRequest $request){
        $check = HeroBanner::where([HeroBanner::ID=>$request->input(HeroBanner::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->heroImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{HeroBanner::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{HeroBanner::TITLE} = $request->input(HeroBanner::TITLE);
            $check->{HeroBanner::SUB_TITLE} = $request->input(HeroBanner::SUB_TITLE);
            $check->{HeroBanner::STATUS} = $request->input(HeroBanner::STATUS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableBanner(HeroBannerRequest $request){
        $check = HeroBanner::find($request->input(HeroBanner::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{HeroBanner::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{HeroBanner::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function getHeroBanner()
    {
        $banners = HeroBanner::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'Banners' => $banners,
        ];

        return response()->json($data, 200);
    }

    public function bannerDetails($id)
    {
        $banner = HeroBanner::where('id',$id)->first();
        if($banner)
        {
            $data = [
                'status' => true,
                'success' => true,
                'banner' => $banner,
            ];
    
        }
        else{
            $data = [
                'status' => false,
                'success' => false,
                'message' => "details not found for given id",
            ];
        }
        
        return response()->json($data, 200);
    }
}
