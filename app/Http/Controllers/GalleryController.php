<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    use CommonFunctions;

    public function viewGallery(){
        return view("Dashboard.Pages.manageGallery");
    }

    public function getGallery(){
            $query = Gallery::select(
                Gallery::IMAGE,
                Gallery::ID,
                Gallery::CATEGORY,
                Gallery::POSITION,
                Gallery::STATUS
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Gallery::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Gallery::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Gallery::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveGallery(GalleryRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertGallery($request);
                    break;
                case "update":
                    $return = $this->updateGallery($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableGallery($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertGallery(GalleryRequest $request){ 
        $imageUpload = $this->heroImageUpload($request);
        
        if($imageUpload['status'])
        {
            $Gallery = new Gallery();
            $Gallery->{Gallery::IMAGE} = $imageUpload['data'];
            $Gallery->{Gallery::STATUS} = $request->input(Gallery::STATUS);           
            $Gallery->{Gallery::CATEGORY} = $request->input(Gallery::CATEGORY);
            $Gallery->{Gallery::POSITION} = $request->input(Gallery::POSITION);
            $Gallery->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function heroImageUpload(GalleryRequest $request){
        $maxId = Gallery::max(Gallery::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/Gallery/","slide_$maxId");
    }

    
    public function updateGallery(GalleryRequest $request){
        $check = Gallery::where([Gallery::ID=>$request->input(Gallery::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->heroImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{Gallery::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{Gallery::CATEGORY} = $request->input(Gallery::CATEGORY);
            $check->{Gallery::POSITION} = $request->input(Gallery::POSITION);
            $check->{Gallery::STATUS} = $request->input(Gallery::STATUS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableGallery(GalleryRequest $request){
        $check = Gallery::find($request->input(Gallery::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Gallery::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Gallery::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function GalleryData()
    {
        $Gallerys = Gallery::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'Gallerys' => $Gallerys,
        ];

        return response()->json($data, 200);
    }

    public function GalleryDetails($id)
    {
        $Gallery = Gallery::where('id',$id)->first();
        if($Gallery)
        {
            $data = [
                'status' => true,
                'success' => true,
                'Gallery' => $Gallery,
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
