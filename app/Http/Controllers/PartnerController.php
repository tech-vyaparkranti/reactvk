<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PartnerController extends Controller
{
    use CommonFunctions;

    public function viewPartner(){
        return view("Dashboard.Pages.managePartner");
    }

    public function getPartner(){
            $query = Partner::select(
                Partner::IMAGE,
                Partner::ID,
                Partner::SORTING,
                Partner::STATUS
            );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Partner::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Partner::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Partner::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        
        
    }

    public function savePartner(PartnerRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertPartner($request);
                    break;
                case "update":
                    $return = $this->updatePartner($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisablePartner($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertPartner(PartnerRequest $request){ 
        $imageUpload = $this->PartnerImageUpload($request);
        
        if($imageUpload['status'])
        {
            $Partner = new Partner();
            $Partner->{Partner::IMAGE} = $imageUpload['data'];
            $Partner->{Partner::STATUS} = $request->input(Partner::STATUS);           
            $Partner->{Partner::SORTING} = $request->input(Partner::SORTING);
            $Partner->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function PartnerImageUpload(PartnerRequest $request){
        $maxId = Partner::max(Partner::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/Partner/","slide_$maxId");
    }

    
    public function updatePartner(PartnerRequest $request){
        $check = Partner::where([Partner::ID=>$request->input(Partner::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->PartnerImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{Partner::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{Partner::SORTING} = $request->input(Partner::SORTING);
            $check->{Partner::STATUS} = $request->input(Partner::STATUS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisablePartner(PartnerRequest $request){
        $check = Partner::find($request->input(Partner::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Partner::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Partner::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function getPartners()
    {
        $Partners = Partner::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'Partners' => $Partners,
        ];

        return response()->json($data, 200);
    }

    public function PartnerDetails($id)
    {
        $Partner = Partner::where('id',$id)->first();
        if($Partner)
        {
            $data = [
                'status' => true,
                'success' => true,
                'Partner' => $Partner,
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
