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
use App\Models\ServicesDetails;
use App\Models\RentACar;
use App\Models\RentACarSRC;
use App\Models\RentACarSpec;
use App\Models\Blog;
use App\Models\BlogDetails;


class WebsiteConfigurationController extends Controller
{

    protected $audit;


    public function __construct(AuditTrialController $auditTrialController)
    {
        $this->audit = $auditTrialController;

    }

    public function WebsiteSetup(Request $req){


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
           "Whatsapp","Instagram","Facebook","LinkedIn",
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

        $s = WebsiteSetup::get();
        return $s;
    }


    public function CreateExplore(Request $req){


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



        $s = Explore::where("ExploreID", $req->ExploreID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllExplore(Request $req){


        $s = Explore::get();
       return $s;


    }


    public function CreateExploreSRC(Request $req){


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



        $s = ExploreSRC::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificExploreSRC(Request $req){



        $s = ExploreSRC::where("ExploreID", $req->ExploreID)->get();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }

public function ViewAllExploreSRC(Request $req){


        $s = ExploreSRC::get();
       return $s;


    }

    public function CreateExploreSlide(Request $req){


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



        $s = ExploreSlide::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificExploreSlide(Request $req){



        $s = ExploreSlide::where("ExploreID", $req->ExploreID)->get();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllExploreSlide(Request $req){


        $s = ExploreSlide::get();
       return $s;


    }



    public function CreateSlider(Request $req){


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



        $s = Sliders::where("SliderID", $req->SliderID)->first();
        if(!$s){
            return response()->json(["message" => "Slider does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllSlider(Request $req){


        $s = Sliders::get();
       return $s;


    }



    public function CreateServices(Request $req){


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



        $s = Services::where("ServiceID", $req->ServiceID)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllService(Request $req){


        $s = Services::get();
       return $s;


    }


    public function CreateServicesDetails(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_ServicesDetails");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = Services::where("ServiceID", $req->ServiceID)->first();
     if(!$exP){
         return response()->json(["message" => "Service does not exist"], 400);

     }

        $s = new ServicesDetails();

        $s->ServiceID = $exP->ServiceID;

        $fields = [
           "Title","Description"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Added a specification to ".$exP->Title." in the Service section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification added successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to add ".$req->CoverType], 400);
        }


    }

    public function DeletedServicesDetails(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_ServicesDetails");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = ServicesDetails::where("id", $req->Id)->where("ServiceID", $req->ServiceID)->first();
        if(!$s){
            return response()->json(["message" => "Service does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Service Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Explore"], 400);
        }


    }

    public function ViewSingleServicesDetails(Request $req){



        $s = ServicesDetails::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Services does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificServicesDetails(Request $req){



        $s = ServicesDetails::where("ServiceID", $req->ServiceID)->get();
        if(!$s){
            return response()->json(["message" => "Service does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllServicesDetails(Request $req){


        $s =ServicesDetails::get();
       return $s;


    }



    public function CreateRentACar(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_RentACar");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = new RentACar();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }
        if($req->hasFile("DetailedPicture")){
            $s->DetailedPicture = $req->file("DetailedPicture")->store("", "public");
        }
        $s->RentACarID = $this->audit->IdGenerator();

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

            $message = "Created RentACar Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "RentACar created successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to create RentACar"], 400);
        }


    }

    public function UpdateRentACar(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Update_RentACar");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = RentACar::where("RentACarID", $req->RentACarID)->first();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

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
            return response()->json(["message" => "RentACar Updated successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Update RentACar"], 400);
        }


    }

    public function DeletedRentACar(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_RentACar");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = RentACar::where("RentACarID", $req->RentACarID)->first();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted RentACar Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "RentACar Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete RentACar"], 400);
        }


    }

    public function ViewSingleRentACar(Request $req){



        $s = RentACar::where("RentACarID", $req->RentACarID)->first();
        if(!$s){
            return response()->json(["message" => "Fleet does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllRentACar(Request $req){


        $s = RentACar::get();
       return $s;


    }


    public function CreateRentACarSRC(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_RentACarSRC");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = RentACar::where("RentACarID", $req->RentACarID)->first();
     if(!$exP){
         return response()->json(["message" => "RentACar does not exist"], 400);

     }

        $s = new RentACarSRC();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $s->RentACarID = $exP->RentACarID;

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

    public function DeletedRentACarSRC(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_RentACarSRC");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = RentACarSRC::where("id", $req->Id)->where("RentACarID", $req->RentACarID)->first();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted RentACar Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "RentACar Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete RentACarSRC"], 400);
        }


    }

    public function ViewSingleRentACarSRC(Request $req){



        $s = RentACarSRC::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "RentACarSRC does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificRentACarSRC(Request $req){



        $s = RentACarSRC::where("RentACarID", $req->RentACarID)->get();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllRentACarSRC(Request $req){


        $s = RentACarSRC::get();
       return $s;


    }


    public function CreateRentACarSpec(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_RentACarSpecs");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = RentACar::where("RentACarID", $req->RentACarID)->first();
     if(!$exP){
         return response()->json(["message" => "RentACar does not exist"], 400);

     }

        $s = new RentACarSpec();

        $s->RentACarID = $exP->RentACarID;

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

            $message = "Added a specification to ".$exP->Title." in the RentACar section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification added successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to add ".$req->CoverType], 400);
        }


    }

    public function DeletedRentACarSpec(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_RentACarSpecs");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = RentACarSpec::where("id", $req->Id)->where("RentACarID", $req->RentACarID)->first();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted RentACar Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete RentACar"], 400);
        }


    }

    public function ViewSingleRentACarSpec(Request $req){



        $s = RentACarSpec::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Explore does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificRentACarSpec(Request $req){



        $s = RentACarSpec::where("RentACarID", $req->RentACarID)->get();
        if(!$s){
            return response()->json(["message" => "RentACar does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllRentACarSpec(Request $req){


        $s = RentACarSpec::get();
       return $s;


    }


    public function CreateBlog(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_Blog");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = new Blog();

        if($req->hasFile("Src")){
            $s->Src = $req->file("Src")->store("", "public");
        }

        $s->BlogID = $this->audit->IdGenerator();

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

            $message = "Created Blog Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Blog created successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to create Blog"], 400);
        }


    }

    public function UpdateBlog(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Update_Blog");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Blog::where("BlogID", $req->BlogID)->first();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

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

            $message = "Updated Blog Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Blog Updated successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Update Blog"], 400);
        }


    }

    public function DeletedBlog(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_Blog");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = Blog::where("BlogID", $req->BlogID)->first();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Blog Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Blog Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Blog"], 400);
        }


    }

    public function ViewSingleBlog(Request $req){



        $s = Blog::where("BlogID", $req->BlogID)->first();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

        }


       return $s;


    }

    public function ViewAllBlog(Request $req){


        $s = Blog::get();
       return $s;


    }



    public function CreateBlogDetails(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Create_BlogDetails");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }

     $exP = Blog::where("BlogID", $req->BlogID)->first();
     if(!$exP){
         return response()->json(["message" => "Blog does not exist"], 400);

     }

        $s = new BlogDetails();

        $s->BlogID = $exP->BlogID;

        $fields = [
           "Title","Description"
        ];

        foreach($fields as $field){

            if($req->filled($field)){
                $s->$field = $req->$field;
            }

        }

        $saver = $s->save();
        if($saver){

            $message = "Added a specification to ".$exP->Title." in the Blog section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification added successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to add ".$req->CoverType], 400);
        }


    }

    public function DeletedBlogDetails(Request $req){


        $rp =  $this->audit->RoleAuthenticator($req->AdminId, "Can_Delete_BlogDetails");
        if ($rp->getStatusCode() !== 200) {
         return $rp;  // Return the authorization failure response
     }


        $s = BlogDetails::where("id", $req->Id)->where("BlogID", $req->BlogID)->first();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

        }


        $saver = $s->delete();
        if($saver){

            $message = "Deleted Blog Section";
            $this->audit->Auditor($req->AdminId, $message);
            return response()->json(["message" => "Specification Deleted successfully"], 200);

        }else{
            return response()->json(["message" => "Failed to Delete Explore"], 400);
        }


    }

    public function ViewSingleBlogDetails(Request $req){



        $s = BlogDetails::where("id", $req->Id)->first();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

        }


       return $s;


    }


    public function ViewSpecificBlogDetails(Request $req){



        $s = BlogDetails::where("BlogID", $req->BlogID)->get();
        if(!$s){
            return response()->json(["message" => "Blog does not exist"], 400);

        }


       return $s;


    }


    public function ViewAllBlogDetails(Request $req){


        $s =BlogDetails::get();
       return $s;


    }









}
