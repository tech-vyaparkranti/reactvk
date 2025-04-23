<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VideoGalleryRequest;
use App\Models\VideoGallery;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VideoGalleryController extends Controller
{
    use CommonFunctions;

    public function viewVideoGallery(){
        return view("Dashboard.Pages.manageVideoGallery");
    }

    public function getVideoGallery(){
            $query = VideoGallery::select(
               'video_link','id','title','status'
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->id.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->id.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->status== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action','video_link'])
            ->make(true);
        
        
    }

    public function saveVideoGallery(VideoGalleryRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertVideoGallery($request);
                    break;
                case "update":
                    $return = $this->updateVideoGallery($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableVideoGallery($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertVideoGallery(VideoGalleryRequest $request){ 
        

            $video = new VideoGallery();
            $video->video_link = $request->video_link;
            $video->title = $request->title;           
            $video->status = $request->status;           
            $video->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        return $return;
    }
    
    public function updateVideoGallery(VideoGalleryRequest $request){
        $check = VideoGallery::where([VideoGallery::ID=>$request->input(VideoGallery::ID)])->first();

        if($check){
            $check->video_link = $request->video_link; 
            $check->title = $request->title;           
            $check->status = $request->status; 
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableVideoGallery(VideoGalleryRequest $request){
        $check = VideoGallery::find($request->input(VideoGallery::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->status = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->status = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function videoGalleryData()
    {
        $videoGallery = VideoGallery::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'videoGallery' => $videoGallery,
        ];
        return response()->json($data, 200);
    }
}
