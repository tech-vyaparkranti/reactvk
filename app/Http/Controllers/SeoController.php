<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\SeoRequest;
use App\Models\Seo;

class SeoController extends Controller
{
    public function storeSeo(SeoRequest $request)
    {
        try {

            Seo::create([
                'website_url' => $request->website_url,
                'phone' => $request->phone,
                'ip_address' => $request->ip(),
            ]);           
            $data = [
                'success' => true,
                'status' => 200,
                'message' => 'Thank you for your message. We will contact you shortly'
            ];
        } catch(Exception $exception){
            $data = [
                'success' => true,
                'status' => 422,
                'message' => "Something went wrong. ".$exception->getMessage(),
            ];
        }
        return response()->json($data);
    }

    public function seoDataTable(){
        
        $query = Seo::select(
            'website_url','phone' ,'ip_address','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function viewSeo()
    {
        return view("Dashboard.Pages.seoData");
    }
}
