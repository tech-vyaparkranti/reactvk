<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TechnologyRequest;
use App\Models\Technology;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TechnologyController extends Controller
{
    use CommonFunctions;

    public function viewTechnology(){
        return view("Dashboard.Pages.manageTechnology");
    }

    public function getTechnology(){
            $query = Technology::select(
                Technology::IMAGE,
                Technology::ID,
                Technology::CATEGORY,
                Technology::POSITION,
                Technology::STATUS,
                Technology::DESCRIPTION,
                Technology::TECH_NAME,
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Technology::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Technology::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Technology::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveTechnology(TechnologyRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertTechnology($request);
                    break;
                case "update":
                    $return = $this->updateTechnology($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableTechnology($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertTechnology(TechnologyRequest $request){ 
        $imageUpload = $this->heroImageUpload($request);
        
        if($imageUpload['status'])
        {
            $Technology = new Technology();
            $Technology->{Technology::IMAGE} = $imageUpload['data'];
            $Technology->{Technology::STATUS} = $request->input(Technology::STATUS);           
            $Technology->{Technology::CATEGORY} = $request->input(Technology::CATEGORY);
            $Technology->{Technology::POSITION} = $request->input(Technology::POSITION);
            $Technology->{Technology::DESCRIPTION} = $request->input(Technology::DESCRIPTION);
            $Technology->{Technology::TECH_NAME} = $request->input(Technology::TECH_NAME);

            $Technology->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function heroImageUpload(TechnologyRequest $request){
        $maxId = Technology::max(Technology::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/technology/","slide_$maxId");
    }

    
    public function updateTechnology(TechnologyRequest $request){
        $check = Technology::where([Technology::ID=>$request->input(Technology::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->heroImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{Technology::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{Technology::CATEGORY} = $request->input(Technology::CATEGORY);
            $check->{Technology::POSITION} = $request->input(Technology::POSITION);
            $check->{Technology::STATUS} = $request->input(Technology::STATUS);
            $check->{Technology::DESCRIPTION} = $request->input(Technology::DESCRIPTION);
            $check->{Technology::TECH_NAME} = $request->input(Technology::TECH_NAME);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableTechnology(TechnologyRequest $request){
        $check = Technology::find($request->input(Technology::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Technology::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Technology::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function technologyData()
    {
        $Technologys = Technology::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'Technologys' => $Technologys,
        ];

        return response()->json($data, 200);
    }

    public function technologyDetails($id)
    {
        $Technology = Technology::where('id',$id)->first();
        if($Technology)
        {
            $data = [
                'status' => true,
                'success' => true,
                'Technology' => $Technology,
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
