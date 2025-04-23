<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplyPosition;
use App\Http\Requests\ApplyPositionRequest;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class ApplyPositionController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;
    
    public function savePosition(ApplyPositionRequest $request)
    {
        if ($request->hasFile('resume')) {
            $maxId = ApplyPosition::max(ApplyPosition::ID);
            $maxId += 1;
            $timeNow = strtotime($this->timeNow());
            $maxId .= "_$timeNow";
            $uploads =  $this->uploadLocalFile($request, 'resume', "/files/apply_files/", "position_$maxId");
            $fileUrl = $uploads['data'];
        };

        // if ($request->hasFile('resume')) {
        //     $file = $request->file('resume');
        //     $maxId = ApplyPosition::max(ApplyPosition::ID) + 1;
        //     $timestamp = strtotime(now());
        //     $filename = "{$maxId}_{$timestamp}_" . $file->getClientOriginalName();
        //     $relativePath = 'files/apply_files/';
        //     $destinationPath = public_path($relativePath);
        
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0755, true);
        //     }
        //    $file->move($destinationPath, $filename);
        
        //     $fileUrl = config('app.url') . '/' . $relativePath . $filename;
        // }

        ApplyPosition::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'phone' =>$request->phone,
            'department' =>$request->department,
            'position_analytics' =>$request->position_analytics,
            'resume' =>$fileUrl,
            'cover_letter' =>$request->cover_letter,
        ]);

        $data = [
            'success' => true,
            'status' => 200,
            'message' => 'Thank you for apply. We will contact you shortly'
        ];
        return response()->json($data, 200);
    }

    public function appliedDataTable(){
        
        $query = ApplyPosition::select(
           'name','email','phone','department','position_analytics','resume','cover_letter','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function viewApplied()
    {
        return view("Dashboard.Pages.appliedData");
    }


}
