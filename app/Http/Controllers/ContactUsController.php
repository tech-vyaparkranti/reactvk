<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\WebSiteElements;

class ContactUsController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;

    public function saveContactUsDetails(ContactUsRequest $request){
        try{
            $check = ContactUs::where([
                [ContactUs::PHONE_NUMBER,$request->input(ContactUs::PHONE_NUMBER)],
            ])->whereRaw("date(created_at)=date(now())")->first();
            // if($check){
            //     $data = [
            //         'success' => true,
            //         'status' => 422,
            //         'message' => 'You alread sent a message for today'
            //     ];
            // }else{
                $newContactUs = new ContactUs();
                $newContactUs->{ContactUs::NAME} = $request->input(ContactUs::NAME);
                // $newContactUs->{ContactUs::LAST_NAME} = $request->input(ContactUs::LAST_NAME);
                $newContactUs->{ContactUs::EMAIL} = $request->input(ContactUs::EMAIL);
                $newContactUs->{ContactUs::SUBJECT} = $request->input(ContactUs::SUBJECT);
                $newContactUs->{ContactUs::PHONE_NUMBER} = $request->input(ContactUs::PHONE_NUMBER);
                $newContactUs->{ContactUs::MESSAGE} = $request->input(ContactUs::MESSAGE);
                $newContactUs->{ContactUs::IP_ADDRESS} = $this->getIp();
                $newContactUs->save();
                // $this->sendContactUsEmail($newContactUs);
                $data = [
                    'success' => true,
                    'status' => 200,
                    'message' => 'Thank you for your message. We will contact you shortly'
                ];
            // }
        }catch(Exception $exception){
            $data = [
                'success' => true,
                'status' => 422,
                'message' => "Something went wrong. ".$exception->getMessage(),

            ];
        }
        return response()->json($data);
    }

    public function contactUsAdminPage(){
        return view("Dashboard.Pages.contactUsAdmin");
    }

    public function contactUsDataTable(){
        
        $query = ContactUs::select(
            ContactUs::NAME,
            ContactUs::SUBJECT,
            ContactUs::EMAIL,
            ContactUs::MESSAGE,
            ContactUs::PHONE_NUMBER,
            ContactUs::IP_ADDRESS,
            ContactUs::ID,
            DB::raw('DATE_FORMAT(CONVERT_TZ('.ContactUs::CREATED_AT.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function contactWebelement()
    {

        $elements = WebSiteElements::where('status','1')->whereIn('element',['Address','mobile','Map_link','contact_timing','international_address','footer_content'])->get();
        $elementData = $elements->pluck('element_details', 'element')->toArray();
        $data = [
            'status' => true,
            'success' => true,
            'elements' => $elementData,
        ];
        return response()->json($data, 200);
    }
}
