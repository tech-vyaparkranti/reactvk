<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogReview;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReviewRequest;

class BlogReviewController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;
   public function saveReview(ReviewRequest $request)
   {
        BlogReview::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'comments' => $request->comments,
            'review' => $request->review,
            'blog_id' => $request->blog_id,
        ]);
        $data = [
            'success' => true,
            'status' => true,
            'message' => 'Thank you for your Review',
            'code' => 200,
        ];
        return response()->json($data, 200);
   }

   public function reviewDataTable(){
        $query = BlogReview::select(
            'first_name','last_name' ,'phone','comments','review','blog_id','status','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->id.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->id.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->status == 0){
                    return $btn_enable;
                }else{
                    return $btn_disable;
                }
            })
            ->rawColumns(['action',"comments"])
            ->make(true);
    }                          

    public function viewReview()
    {
        return view("Dashboard.Pages.reviewData");
    }

    public function changeReview(Request $request)
    {
        switch($request->input("action")){
            case "enable":
                case "disable":
                    $return = $this->enableDisableBlog($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
        }
        return response()->json($return);
    }

    public function enableDisableBlog(Request $request){
        $check = BlogReview::find($request->id);
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

}
