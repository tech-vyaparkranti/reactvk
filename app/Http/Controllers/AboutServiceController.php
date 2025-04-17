<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutServiceRequest;
use App\Http\Requests\StoreAboutServiceRequest;
use App\Http\Requests\UpdateAboutServiceRequest;
use App\Models\AboutService;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AboutServiceController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewAboutServicesMaster()
    {
        
        return view("Dashboard.Pages.aboutServicesAdmin");
    }

    public function saveAboutServicesMaster(AboutServiceRequest $request)
    {
        Cache::forget("about_service");
        switch ($request->input("action")) {
            case "insert":
                $return = $this->insertData($request);
                break;
            case "update":
                $return = $this->updateData($request);
                break;
            case "enable":
                $return = $this->enableRow($request);
                break;
            case "disable":
                $return = $this->disableRow($request);
                break;
            default:
                $return = ["status" => false, "message" => "Unknown action.", "data" => null];
        }
        return response()->json($return);
    }

    public function ImageUpload(AboutServiceRequest $request)
    {
        $maxId = AboutService::max(AboutService::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, AboutService::SERVICE_IMAGE, "/website/uploads/service_images/", "service_image_$maxId");
    }
    public function insertData(AboutServiceRequest $request)
    {
        $checkDuplicate = AboutService::where(AboutService::SERVICE_NAME, $request->input(AboutService::SERVICE_NAME))->first();

        if ($checkDuplicate) {
            $return = $this->returnMessage("Service name is already taken");
        } else {
            $image_url = "";
            $imageUpload = $this->ImageUpload($request);
            if ($imageUpload["status"]) {
                $image_url = $imageUpload["data"];
            } else {
                return $imageUpload;
            }
            $createNewRow = new AboutService();
            $createNewRow->{AboutService::SERVICE_NAME} = $request->input(AboutService::SERVICE_NAME);
            $createNewRow->{AboutService::SERVICE_IMAGE} = $image_url;
            $createNewRow->{AboutService::SERVICE_DETAILS} = $request->input(AboutService::SERVICE_DETAILS);
            $createNewRow->{AboutService::POSITION} = $request->input(AboutService::POSITION);
            $createNewRow->{AboutService::STATUS} = 1;
            $createNewRow->{AboutService::CREATED_BY} = Auth::user()->id;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        }
        return $return;
    }

    public function  updateData(AboutServiceRequest $request)
    {
        $checkDuplicate = AboutService::where([
            [AboutService::SERVICE_NAME, $request->input(AboutService::SERVICE_NAME)],
            [AboutService::ID, "<>", $request->input(AboutService::ID)]
        ])->first();

        if ($checkDuplicate) {
            $return = $this->returnMessage("Service name is already taken");
        } else {
            $updateModel = AboutService::find($request->input(AboutService::ID));
            $image_url = $updateModel->{AboutService::SERVICE_IMAGE};
            if($request->file(AboutService::SERVICE_IMAGE)){
                $imageUpload = $this->ImageUpload($request);
                if ($imageUpload["status"]) {
                    $image_url = $imageUpload["data"];
                } else {
                    return $imageUpload;
                }
            }          
            
            
            $updateModel->{AboutService::SERVICE_NAME} = $request->input(AboutService::SERVICE_NAME);
            $updateModel->{AboutService::SERVICE_IMAGE} = $image_url;
            $updateModel->{AboutService::SERVICE_DETAILS} = $request->input(AboutService::SERVICE_DETAILS);
            $updateModel->{AboutService::POSITION} = $request->input(AboutService::POSITION);
            $updateModel->{AboutService::STATUS} = 1;
            $updateModel->{AboutService::UPDATED_BY} = Auth::user()->id;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        }
        return $return;
    }

    public function enableRow(AboutServiceRequest $request)
    {
        $check = AboutService::where(AboutService::ID, $request->input(AboutService::ID))->first();
        if ($check) {
            $check->{AboutService::STATUS} = 1;
            $check->{AboutService::UPDATED_BY} = Auth::user()->id;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(AboutServiceRequest $request)
    {
        $check = AboutService::where(AboutService::ID, $request->input(AboutService::ID))->first();
        if ($check) {
            $check->{AboutService::STATUS} = 0;
            $check->{AboutService::UPDATED_BY} = Auth::user()->id;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function aboutServicesData()
    {

        $query = AboutService::select(
            AboutService::SERVICE_NAME,
            AboutService::SERVICE_IMAGE,
            AboutService::SERVICE_DETAILS,
            AboutService::POSITION,
            AboutService::STATUS,
            AboutService::ID
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->{AboutService::ID} . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->{AboutService::ID} . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->{AboutService::STATUS} == 1) {
                    return $this->addDiv($btn_edit . $btn_disable);
                } else {
                    return $this->addDiv($btn_edit . $btn_enable);
                }
            })
            ->rawColumns(['action','service_details'])
            ->make(true);
    }

    public function getHomePageServices(){
        $homeServices = $this->getAboutServiceData();
        if(empty($homeServices)){
            Cache::forget("about_service");
        }
        return response()->json($homeServices);
    }

    public function getAboutServiceData(){
        return Cache::rememberForever('about_service', function () {
            return AboutService::where(AboutService::STATUS,1)
            ->select(DB::raw("concat('".url('')."',".AboutService::SERVICE_IMAGE.") as ".AboutService::SERVICE_IMAGE),AboutService::SERVICE_NAME,
            AboutService::SERVICE_DETAILS,AboutService::ID)->orderBy(AboutService::POSITION,"asc")
            ->get();
        });
    }
}
