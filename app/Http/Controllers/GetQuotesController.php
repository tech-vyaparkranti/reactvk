<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GetQuotes;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class GetQuotesController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;

    public function saveQuotes(Request $request)
    {
        GetQuotes::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'location' => $request->location,
            'message' => $request->message,
        ]);

        $data = [
            'success' => true,
            'status' => true,
            'message' => 'Thank you for your Quotes',
            'code' => 200,
        ];
        return response()->json($data, 200);
    }

    public function quotesDataTable(){
        
        $query = GetQuotes::select(
            'name','location' ,'phone' ,'message','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function viewQuotes()
    {
        return view("Dashboard.Pages.quotesData");
    }
}
