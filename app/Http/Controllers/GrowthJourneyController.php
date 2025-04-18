<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GrowthJourneyRequest;
use App\Models\GrowthJourney;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GrowthJourneyController extends Controller
{
    use CommonFunctions;

    public function viewGrowthJourney(){
        return view("Dashboard.Pages.manageGrowthJourney");
    }

    public function getGrowthJourney(){
            $query = GrowthJourney::select(
                GrowthJourney::ID,
                GrowthJourney::TITLE,
                GrowthJourney::ICON,
                GrowthJourney::STATUS,
                GrowthJourney::EX_LEVEL,
                GrowthJourney::SHORT_DESCRIPTION,
                GrowthJourney::POSITION,
                GrowthJourney::SKILL,
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{GrowthJourney::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{GrowthJourney::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{GrowthJourney::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveGrowthJourney(GrowthJourneyRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertGrowthJourney($request);
                    break;
                case "update":
                    $return = $this->updateGrowthJourney($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableGrowthJourney($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertGrowthJourney(GrowthJourneyRequest $request)
    { 
            $image_url = "";
            $bannerImage = $this->ImageUpload($request);

            if ($bannerImage["status"]) {
                $image_url = $bannerImage["data"];
            } else {
                return $bannerImage;
            }  
            $GrowthJourney = new GrowthJourney();
            $GrowthJourney->{GrowthJourney::ICON} = $image_url;           
            $GrowthJourney->{GrowthJourney::STATUS} = $request->input(GrowthJourney::STATUS);           
            $GrowthJourney->{GrowthJourney::TITLE} = $request->input(GrowthJourney::TITLE);
            $GrowthJourney->{GrowthJourney::EX_LEVEL} = $request->input(GrowthJourney::EX_LEVEL);
            $GrowthJourney->{GrowthJourney::SHORT_DESCRIPTION} = $request->input(GrowthJourney::SHORT_DESCRIPTION);
            $GrowthJourney->{GrowthJourney::POSITION} = $request->input(GrowthJourney::POSITION);
            $GrowthJourney->{GrowthJourney::SKILL} = $request->input(GrowthJourney::SKILL);
            $GrowthJourney->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        return $return;
    }

    public function ImageUpload(GrowthJourneyRequest $request)
    {
        $maxId = GrowthJourney::max(GrowthJourney::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, 'icon', "/images/growth/", "growth_$maxId");
    }

    
    public function updateGrowthJourney(GrowthJourneyRequest $request){
        $check = GrowthJourney::where([GrowthJourney::ID=>$request->input(GrowthJourney::ID)])->first();

        if($check){
            if($request->hasFile('icon'))
            {
                $image_url = "";
                $bannerImage = $this->ImageUpload($request);
                if ($bannerImage["status"]) {
                    $image_url = $bannerImage["data"];
                }
                else{
                    $image_url = $bannerImage;
                }
                $check->{GrowthJourney::ICON} = $image_url;
            }
            $check->{GrowthJourney::STATUS} = $request->input(GrowthJourney::STATUS);
            $check->{GrowthJourney::TITLE} = $request->input(GrowthJourney::TITLE);
            $check->{GrowthJourney::EX_LEVEL} = $request->input(GrowthJourney::EX_LEVEL);
            $check->{GrowthJourney::SHORT_DESCRIPTION} = $request->input(GrowthJourney::SHORT_DESCRIPTION);
            $check->{GrowthJourney::POSITION} = $request->input(GrowthJourney::POSITION);
            $check->{GrowthJourney::SKILL} = $request->input(GrowthJourney::SKILL);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableGrowthJourney(GrowthJourneyRequest $request){
        $check = GrowthJourney::find($request->input(GrowthJourney::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{GrowthJourney::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{GrowthJourney::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function GrowthJourneyData()
    {
        $GrowthJourneys = GrowthJourney::where('status',1)->paginate(10);
        $data = [
            'status' => true,
            'success' => true,
            'GrowthJourneys' => $GrowthJourneys,
        ];

        return response()->json($data, 200);
    }

    public function GrowthJourneyDetails($id)
    {
        $GrowthJourney = GrowthJourney::where('id',$id)->first();
        if($GrowthJourney)
        {
            $data = [
                'status' => true,
                'success' => true,
                'GrowthJourney' => $GrowthJourney,
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
