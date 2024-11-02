<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditTrialController;
use App\Models\WebsiteSetup;

class WebsiteConfigurationController extends Controller
{

    protected $audit;


    public function __construct(AuditTrialController $auditTrialController)
    {
        $this->audit = $auditTrialController;

    }

    public function WebsiteSetup(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Configure_WebsiteSetup");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = WebsiteSetup::firstOrNew();

        if($req->hasFile("CompanyLogo")){
            $s->CompanyLogo = $req->file("CompanyLogo")->store("", "public");
        }

        $fields = [
           "CompanyName", "ShopURL", "Location", "PhoneNumber", "Email",
           "Whatsapp","Instagram","Facebook","LinkedIn","Latitude","Longitude"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Configured Website Setup";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Website Setup Configured Successfully"], 200);

        }else{
            return response()->json(["message" => "Could not update Configure Website"], 400);
        }


    }


    function ViewMainWebsite(Request $req){
        $this->audit->RateLimit($req->ip());
        $s = WebsiteSetup::get();
        return $s;
    }







}
