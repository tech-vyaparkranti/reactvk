<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VacancyRequest;
use App\Models\Vacancy;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VacancyController extends Controller
{
    use CommonFunctions;

    public function viewVacancy(){
        return view("Dashboard.Pages.manageVacancy");
    }

    public function getVacancy(){
            $query = Vacancy::select(
                Vacancy::ID,
                Vacancy::TITLE,
                Vacancy::DEPARTMENT,
                Vacancy::STATUS,
                Vacancy::LOCATION,
                Vacancy::JOB_TYPE,
                Vacancy::DESCRIPTION,
                Vacancy::REQUIREMENT,
                Vacancy::BENEFITS,
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Vacancy::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Vacancy::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Vacancy::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveVacancy(VacancyRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertVacancy($request);
                    break;
                case "update":
                    $return = $this->updateVacancy($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableVacancy($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertVacancy(VacancyRequest $request){ 
       
            $vacancy = new Vacancy();
            $vacancy->{Vacancy::STATUS} = $request->input(Vacancy::STATUS);           
            $vacancy->{Vacancy::TITLE} = $request->input(Vacancy::TITLE);
            $vacancy->{Vacancy::DEPARTMENT} = $request->input(Vacancy::DEPARTMENT);
            $vacancy->{Vacancy::LOCATION} = $request->input(Vacancy::LOCATION);
            $vacancy->{Vacancy::JOB_TYPE} = $request->input(Vacancy::JOB_TYPE);
            $vacancy->{Vacancy::DESCRIPTION} = $request->input(Vacancy::DESCRIPTION);
            $vacancy->{Vacancy::REQUIREMENT} = $request->input(Vacancy::REQUIREMENT);
            $vacancy->{Vacancy::BENEFITS} = $request->input(Vacancy::BENEFITS);
            $vacancy->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        return $return;
    }
    
    public function updateVacancy(VacancyRequest $request){
        $check = Vacancy::where([Vacancy::ID=>$request->input(Vacancy::ID)])->first();

        if($check){

            $check->{Vacancy::STATUS} = $request->input(Vacancy::STATUS);
            $check->{Vacancy::TITLE} = $request->input(Vacancy::TITLE);
            $check->{Vacancy::DEPARTMENT} = $request->input(Vacancy::DEPARTMENT);
            $check->{Vacancy::LOCATION} = $request->input(Vacancy::LOCATION);
            $check->{Vacancy::JOB_TYPE} = $request->input(Vacancy::JOB_TYPE);
            $check->{Vacancy::DESCRIPTION} = $request->input(Vacancy::DESCRIPTION);
            $check->{Vacancy::REQUIREMENT} = $request->input(Vacancy::REQUIREMENT);
            $check->{Vacancy::BENEFITS} = $request->input(Vacancy::BENEFITS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableVacancy(VacancyRequest $request){
        $check = Vacancy::find($request->input(Vacancy::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Vacancy::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Vacancy::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function vacancyData()
    {
        $Vacancys = Vacancy::where('status',1)->paginate(10);
        $data = [
            'status' => true,
            'success' => true,
            'Vacancys' => $Vacancys,
        ];

        return response()->json($data, 200);
    }

    public function vacancyDetails($id)
    {
        $Vacancy = Vacancy::where('id',$id)->first();
        if($Vacancy)
        {
            $data = [
                'status' => true,
                'success' => true,
                'Vacancy' => $Vacancy,
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
