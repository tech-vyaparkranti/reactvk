<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PackageRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Package;

class PackageController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewPackages()
    {
        
        return view("Dashboard.Pages.managePackages");
    }

    public function savePackages(PackageRequest $request)
    {
        Cache::forget("about_us");
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

    public function insertData(PackageRequest $request)
    {
            $createNewRow = new Package();
            $createNewRow->category = $request->category;
            $createNewRow->package_class = $request->package_class;
            $createNewRow->price = $request->price;
            $createNewRow->title = $request->title;
            $createNewRow->package_details = $request->package_details;
            $createNewRow->position = $request->position;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(PackageRequest $request)
    {
            $updateModel = Package::find($request->id);
        if($updateModel){
            
            $updateModel->category = $request->category;
            $updateModel->package_class = $request->package_class;
            $updateModel->price = $request->price;
            $updateModel->title = $request->title;
            $updateModel->package_details = $request->package_details;
            $updateModel->position = $request->position;
            $updateModel->status = $request->status;
            $updateModel->save();
            $return = $this->returnMessage("update successfully.", true);
        }else{
                $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
            }
        return $return;
    }

    public function enableRow(PackageRequest $request)
    {
        $check = Package::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(PackageRequest $request)
    {
        $check = Package::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function packageData()
    {
        $query = Package::select(
            'category','package_class','price' ,'package_details','status','position','id','title'
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->id . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->id . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->status == 1) {
                    return $this->addDiv($btn_edit . $btn_disable);
                } else {
                    return $this->addDiv($btn_edit . $btn_enable);
                }
            })
            ->rawColumns(['action','package_details'])
            ->make(true);
    }

    public function getPackage()
    {
        $packages = Package::where('status', 1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'packages' => $packages,
        ];

        return response()->json($data, 200);
    }
}
