<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditTrialController;
use App\Models\WebsiteSetup;
use App\Models\Explore;
use App\Models\ExploreSRC;
use App\Models\ExploreSlide;
use App\Models\Sliders;
use App\Models\Services;







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


    public function CreateExplore(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_ExploreSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = new Explore();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }
        if($req->hasFile("DetailedPicture")){
            $s->DetailedPicture = $req->file("DetailedPicture")->store("", "public");
        }
        $s->ExploreID = $this->audit->IdGenerator();

        $fields = [
           "CoverType", "Title", "SubTitle", "YearModel", "Price",
           "GearType","FuelType"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Created Explore Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Explore created successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to create Explore"], 400);
        }


    }

    public function UpdateExplore(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Update_ExploreSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Explore::where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }
        if($req->hasFile("DetailedPicture")){
            $s->DetailedPicture = $req->file("DetailedPicture")->store("", "public");
        }

        $fields = [
           "CoverType", "Title", "SubTitle", "YearModel", "Price",
           "GearType","FuelType"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Updated Explore Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Explore Updated successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Update Explore"], 400);
        }


    }

    public function DeletedExplore(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_ExploreSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Explore::where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Explore Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Explore Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Explore"], 400);
        }


    }

    public function ViewSingleExplore(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = Explore::where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllExplore(Request $req){

        $this->audit->RateLimit($req->ip());
        $s = Explore::get();
       return $s;


    }


    public function CreateExploreSRC(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_ExploreSRC");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = Explore::where("ExploreID", $req->ExploreID)->first();
     if(!$exP){
         return response()->json(["message" => "Explore does not exist"], 400);

     }

        $s = new ExploreSRC();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $s->ExploreID = $exP->ExploreID;

        $fields = [
           "CoverType"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Added a ".$req->CoverType."to ".$exP->Title." in the explore section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => $req->CoverType." added successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to add ".$req->CoverType], 400);
        }


    }

    public function DeletedExploreSRC(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_ExploreSRC");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = ExploreSRC::where("id", $req->Id)->where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Explore Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Explore Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Explore"], 400);
        }


    }

    public function ViewSingleExploreSRC(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = ExploreSRC::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificExploreSRC(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = ExploreSRC::where("ExploreID", $req->ExploreID)->get();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllExploreSRC(Request $req){

        $this->audit->RateLimit($req->ip());
        $s = ExploreSRC::get();
       return $s;


    }

    public function CreateExploreSlide(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_ExploreSpecs");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = Explore::where("ExploreID", $req->ExploreID)->first();
     if(!$exP){
         return response()->json(["message" => "Explore does not exist"], 400);

     }

        $s = new ExploreSlide();

        $s->ExploreID = $exP->ExploreID;

        $fields = [
           "Title","Description","Section"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Added a specification to ".$exP->Title." in the explore section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification added successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to add ".$req->CoverType], 400);
        }


    }

    public function DeletedExploreSlide(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_ExploreSpecs");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = ExploreSlide::where("id", $req->Id)->where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Explore Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Explore"], 400);
        }


    }

    public function ViewSingleExploreSlide(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = ExploreSlide::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificExploreSlide(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = ExploreSlide::where("ExploreID", $req->ExploreID)->get();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllExploreSlide(Request $req){

        $this->audit->RateLimit($req->ip());
        $s = ExploreSlide::get();
       return $s;


    }



    public function CreateSlider(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_SlideSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = new Sliders();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $s->SliderID = $this->audit->IdGenerator();

        $fields = [
           "CoverType", "Title", "SubTitle"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Created Slider Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Slider created successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to create Explore"], 400);
        }


    }

    public function UpdateSlider(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Update_SliderSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Sliders::where("SliderID", $req->SliderID)->first();
        if(!$s){
            return response()->json(["message" => "Slider does not exist"], 400);

        }

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }


        $fields = [
           "CoverType", "Title", "SubTitle"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Updated Slider Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Slider Updated successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Update Slides"], 400);
        }


    }

    public function DeletedSlider(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_SliderSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Sliders::where("SliderID", $req->SliderID)->first();
        if(!$s){
            return response()->json(["message" => "Slider does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Slider Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Slider Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Slider"], 400);
        }


    }

    public function ViewSingleSlider(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = Sliders::where("SliderID", $req->SliderID)->first();
        if(!$s){
            return response()->json(["message" => "Slider does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllSlider(Request $req){

        $this->audit->RateLimit($req->ip());
        $s = Sliders::get();
       return $s;


    }



    public function CreateServices(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_ServicesSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = new Services();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $s->ServiceID = $this->audit->IdGenerator();

        $fields = [
           "CoverType", "Title", "SubTitle", "Description"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Created Services Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Services created successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to create Services"], 400);
        }


    }

    public function UpdateServices(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Update_ServiceSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Services::where("ServiceID", $req->ServiceID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $fields = [
           "CoverType", "Title", "SubTitle", "Description"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Updated Service Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Service Updated successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Update Service"], 400);
        }


    }

    public function DeletedService(Request $req){

        $this->audit->RateLimit($req->ip());
        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_ServiceSection");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Services::where("ServiceID", $req->ServiceID)->first();
        if(!$s){
            return response()->json(["message" => "Service does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Service Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Service Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Service"], 400);
        }


    }

    public function ViewSingleService(Request $req){

        $this->audit->RateLimit($req->ip());

        $s = Services::where("ServiceID", $req->ServiceID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllService(Request $req){

        $this->audit->RateLimit($req->ip());
        $s = Services::get();
       return $s;


    }











}
