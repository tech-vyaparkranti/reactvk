<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChooseNowCardRequest;
use App\Models\ChooseNowCard;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ChooseNowCardController extends Controller
{
    use CommonFunctions;

    public function viewChooseNowCard(){
        return view("Dashboard.Pages.manageChooseNowCard");
    }

    public function getChooseNowCard(){
            $query = ChooseNowCard::select(
               "icon",'title','description' ,'status','id'
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
            ->rawColumns(['action','description'])
            ->make(true);
        
    }

    public function saveChooseNowCard(ChooseNowCardRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertChooseNowCard($request);
                    break;
                case "update":
                    $return = $this->updateChooseNowCard($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableChooseNowCard($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertChooseNowCard(ChooseNowCardRequest $request){ 

            $video = new ChooseNowCard();

            $imageUpload = $this->ImageUpload($request);
            if($imageUpload['status'])
            {
                $imageUrl = $imageUpload['data'];
            }
            $video->icon = $imageUrl; 
            $video->title = $request->title;           
            $video->description = $request->short_description;           
            $video->status = $request->status;           
            $video->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        return $return;
    }
    
    public function updateChooseNowCard(ChooseNowCardRequest $request){
        $check = ChooseNowCard::where(["id"=>$request->id])->first();

        if($check){

            if($request->hasFile("icon"))
            {
                $imageUpload = $this->ImageUpload($request);
                if($imageUpload['status'])
                {
                    $imageUrl = $imageUpload['data'];
                    $check->icon = $imageUrl; 
                }
            }
            $check->description = $request->short_description; 
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

    public function ImageUpload(ChooseNowCardRequest $request){
        $maxId = ChooseNowCard::max(ChooseNowCard::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"icon","/images/chooseCard/","slide_$maxId");
    }

    public function enableDisableChooseNowCard(ChooseNowCardRequest $request){
        $check = ChooseNowCard::find($request->id);
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

    public function chooseNowCardData()
    {
        $chooseNowCard = ChooseNowCard::where('status',1)->orderBy("updated_at",'desc')->limit(4)->get();
        $data = [
            'status' => true,
            'success' => true,
            'chooseNowCard' => $chooseNowCard,
        ];
        return response()->json($data, 200);
    }
}
