<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscribe;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubscribeController extends Controller
{
    
    public function saveSubscribe(Request $request) 
    {
        Subscribe::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
        ]);
        $data = [
            'success' => true,
            'status' => 200,
            'message' => "Thank you for subscribe Vyapar Kranti "
        ];
        return response()->json($data, 200);
    }
    public function SubscribeDataTable(){
        
        $query = Subscribe::select(
            'email','ip_address','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function viewSubscribe()
    {
        return view("Dashboard.Pages.subscribeData");
    }
}
