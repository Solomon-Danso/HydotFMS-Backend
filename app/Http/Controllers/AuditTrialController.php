<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminUser;
use App\Models\AuditTrial;
use App\Models\Visitors;
use App\Models\CustomerTrail;
use App\Models\ProductAssessment;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Models\UserFunctions;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use App\Models\RateLimitCatcher;
use App\Models\Customer;
use DateTime;


class AuditTrialController extends Controller
{

function Auditor($UserId, $Action) {
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address

    try {
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ipAddress}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $ipDetails = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }



        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $location = $ipDetails->loc ?? ''; // Latitude and Longitude
        $latitude = $location ? explode(',', $location)[0] : '';
        $longitude = $location ? explode(',', $location)[1] : '';
    } catch (\Exception $e) {
        Log::error('Error in Auditor function: ' . $e->getMessage());
        $country = $city = $latitude = $longitude = 'Unknown';
    }

    // Get user agent information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Parse the user agent string to determine device and OS
    $device = $this->detectDevice($userAgent);
    $os = $this->detectOperatingSystem($userAgent);

    // URL path
    $urlPath = $_SERVER['REQUEST_URI'];

    $stu = AdminUser::where('UserId', $UserId)->first();
    if ($stu == null) {
        return response()->json(["message" => "Admin does not exist"], 400);
    }


    $googleMapsLink = $latitude && $longitude ? "https://maps.google.com/?q={$latitude},{$longitude}" : '';

    // Create a new AuditTrail instance and save the log to the database
    $auditTrail = new AuditTrial();
    $auditTrail->ipAddress = $ipAddress ?? " ";
    $auditTrail->country = $country ?? " ";
    $auditTrail->city = $city ?? " ";
    $auditTrail->device = $device ?? " ";
    $auditTrail->os = $os ?? " ";
    $auditTrail->urlPath = $urlPath ?? " ";
    $auditTrail->action = $Action ?? " ";
    $auditTrail->googlemap = $googleMapsLink ?? " ";
    $auditTrail->userId = $stu->UserId ?? " ";
    $auditTrail->userName = $stu->Username?? " ";
    $auditTrail->userPic = $stu->Picture ?? " ";

    $auditTrail->save();
}

function CustomerAuditor($UserId, $Action) {
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address

    try {
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ipAddress}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $ipDetails = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }



        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $location = $ipDetails->loc ?? ''; // Latitude and Longitude
        $latitude = $location ? explode(',', $location)[0] : '';
        $longitude = $location ? explode(',', $location)[1] : '';
    } catch (\Exception $e) {
        Log::error('Error in Auditor function: ' . $e->getMessage());
        $country = $city = $latitude = $longitude = 'Unknown';
    }

    // Get user agent information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Parse the user agent string to determine device and OS
    $device = $this->detectDevice($userAgent);
    $os = $this->detectOperatingSystem($userAgent);

    // URL path
    $urlPath = $_SERVER['REQUEST_URI'];

    $stu = Customer::where('UserId', $UserId)->first();

    $googleMapsLink = $latitude && $longitude ? "https://maps.google.com/?q={$latitude},{$longitude}" : '';

    // Create a new AuditTrail instance and save the log to the database
    $auditTrail = new CustomerTrail();
    $auditTrail->ipAddress = $ipAddress ?? " ";
    $auditTrail->country = $country ?? " ";
    $auditTrail->city = $city ?? " ";
    $auditTrail->device = $device ?? " ";
    $auditTrail->os = $os ?? " ";
    $auditTrail->urlPath = $urlPath ?? " ";
    $auditTrail->action = $Action ?? " ";
    $auditTrail->googlemap = $googleMapsLink ?? " ";
    $auditTrail->userId = $stu->UserId ?? " ";
    $auditTrail->userName = $stu->Username ?? " ";;

    $auditTrail->save();
}

function ProductAssessment($productId, $Action) {
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address

    try {
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ipAddress}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $ipDetails = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }



        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $location = $ipDetails->loc ?? ''; // Latitude and Longitude
        $latitude = $location ? explode(',', $location)[0] : '';
        $longitude = $location ? explode(',', $location)[1] : '';
    } catch (\Exception $e) {
               $country = $city = $latitude = $longitude = 'Unknown';
    }

    // Get user agent information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Parse the user agent string to determine device and OS
    $device = $this->detectDevice($userAgent);
    $os = $this->detectOperatingSystem($userAgent);

    // URL path
    $urlPath = $_SERVER['REQUEST_URI'];

    $stu = Product::where('ProductId', $productId)->first();

    $googleMapsLink = $latitude && $longitude ? "https://maps.google.com/?q={$latitude},{$longitude}" : '';

    // Create a new AuditTrail instance and save the log to the database
    $auditTrail = new ProductAssessment();
    $auditTrail->ipAddress = $ipAddress ?? " ";
    $auditTrail->country = $country ?? " ";
    $auditTrail->city = $city ?? " ";
    $auditTrail->device = $device ?? " ";
    $auditTrail->os = $os ?? " ";
    $auditTrail->urlPath = $urlPath ?? " ";
    $auditTrail->action = $Action ?? " ";
    $auditTrail->googlemap = $googleMapsLink ?? " ";
    $auditTrail->productId = $stu->ProductId ?? " ";
    $auditTrail->productName = $stu->Title ?? " ";
    $auditTrail->productPic = $stu->Picture ?? " ";

    $auditTrail->save();
}


function CreateUserRole(Request $req){

    $rp = $this->RoleAuthenticator($req->AdminId, "Can_Create_Role");

    if ($rp->getStatusCode() !== 200) {
        return $rp;  // Return the authorization failure response
    }

    $staff = AdminUser::where("UserId",$req->UserId)->first();
    if($staff==null){
        return response()->json(["message"=>"Staff does not exist"],400);
    }

    $checker = UserFunctions::where("UserId",$req->UserId)->where("Function",$req->Function)->first();
    if($checker){
        $message =  $checker->Function." function has already been assigned to ".$staff->Username;
    return response()->json(["message"=>$message],400);
    }

    if($req->Function=="SuperAdmin"){
        return response()->json(["message"=>"Be very careful, Last Warning"],400);
    }


    $s = new UserFunctions();

    if($req->filled("UserId")){
        $s->UserId = $req->UserId;
    }

    if($req->filled("Function")){
        $s->Function = $req->Function;
    }

   $saver = $s->save();
   if($saver){
    $message =  $s->Function." function has been assigned to ".$staff->Username;
    $this->Auditor($req->AdminId, $message);
    return response()->json(["message"=>$message],200);
   }
   else{
    return response()->json(["message"=>"Could not assign"],400);
   }

}

function ViewUserFunctions(Request $req){

    $this->Auditor($req->AdminId, "Viewed all user roles");
    $role = UserFunctions::where("UserId", $req->UserId)->get();
    return $role;
}

function DeleteUserFunctions(Request $req){
  $rp=  $this->RoleAuthenticator($req->AdminId, "Can_Delete_Role");
    if ($rp->getStatusCode() !== 200) {
        return $rp;  // Return the authorization failure response
    }
    $role = UserFunctions::where("UserId", $req->UserId)->where("Function", $req->Function)->first();
    $saver = $role->delete();
    if($saver){
        $this->Auditor($req->AdminId, $req->Function." deleted successfully");
        return response()->json(["message"=>"User function deleted successfully"],200);
    }else{
        return response()->json(["message"=>"User function deletion failed"],400);
    }

}





function RoleAuthenticator($SenderId, $RoleFunction){
    // Retrieve the list of roles assigned to the user
    $RoleFunctionList = UserFunctions::where("UserId", $SenderId)->pluck('Function');


    // Flag to track if SuperAdmin role is found
    $isSuperAdmin = false;

    foreach ($RoleFunctionList as $Role) {
        if ($Role === "SuperAdmin") {
            // If the user is SuperAdmin, set the flag to true and break the loop
            $isSuperAdmin = true;
            break;
        }
    }


    // If the user is not SuperAdmin and the specified role does not match any of the user's roles
    if (!$isSuperAdmin && !$RoleFunctionList->contains($RoleFunction)) {
        return response()->json(["message" => "User not authorised to perform this task"], 400);
    }

    // If the user has the required role or is a SuperAdmin, proceed with the action
    return response()->json(["message" => "User authorized"], 200);
}









 public function Visitors() {
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address

    try {
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ipAddress}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $ipDetails = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }



        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $location = $ipDetails->loc ?? ''; // Latitude and Longitude
        $latitude = $location ? explode(',', $location)[0] : '';
        $longitude = $location ? explode(',', $location)[1] : '';
    } catch (\Exception $e) {
        Log::error('Error in Visitors function: ' . $e->getMessage());
        $country = $city = $latitude = $longitude = 'Unknown';
    }

    // Get user agent information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Parse the user agent string to determine device and OS
    $device = $this->detectDevice($userAgent);
    $os = $this->detectOperatingSystem($userAgent);

    // URL path
    $urlPath = $_SERVER['REQUEST_URI'];

    $googleMapsLink = $latitude && $longitude ? "https://maps.google.com/?q={$latitude},{$longitude}" : '';

    // Create a new AuditTrail instance and save the log to the database
    $auditTrail = new Visitors();
    $auditTrail->IpAddress = $ipAddress ?? " ";
    $auditTrail->Country = $country ?? " ";
    $auditTrail->City = $city ?? " ";
    $auditTrail->Device = $device ?? " ";
    $auditTrail->Os = $os ?? " ";
    $auditTrail->googlemap = $googleMapsLink ?? " ";

    $auditTrail->save();

    return response()->json(['success' => 'true'], 200);
}






public function RateLimitTracker($Ip) {
    $ipAddress = $Ip; // Get user's IP address

    try {
        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ipAddress}/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Check if any error occurred
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $ipDetails = json_decode($response);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON decoding error: ' . json_last_error_msg());
        }



        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $location = $ipDetails->loc ?? ''; // Latitude and Longitude
        $latitude = $location ? explode(',', $location)[0] : '';
        $longitude = $location ? explode(',', $location)[1] : '';
    } catch (\Exception $e) {
        Log::error('Error in Visitors function: ' . $e->getMessage());
        $country = $city = $latitude = $longitude = 'Unknown';
    }

    // Get user agent information
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Parse the user agent string to determine device and OS
    $device = $this->detectDevice($userAgent);
    $os = $this->detectOperatingSystem($userAgent);

    // URL path
    $urlPath = $_SERVER['REQUEST_URI'];

    $googleMapsLink = $latitude && $longitude ? "https://maps.google.com/?q={$latitude},{$longitude}" : '';

    // Create a new AuditTrail instance and save the log to the database
    $auditTrail = new RateLimitCatcher();
    $auditTrail->IpAddress = $ipAddress ?? " ";
    $auditTrail->Country = $country ?? " ";
    $auditTrail->City = $city ?? " ";
    $auditTrail->Device = $device ?? " ";
    $auditTrail->Os = $os ?? " ";
    $auditTrail->googlemap = $googleMapsLink ?? " ";

    $auditTrail->save();

    return response()->json(['success' => 'true'], 200);
}




function RoleList(Request $req){
    $this->RateLimit($req->ip());
    $rp= $this->RoleAuthenticator($req->AdminId, "Can_Select_Role");
    if ($rp->getStatusCode() !== 200) {
        return $rp;  // Return the authorization failure response
    }


    $RoleList = [
    "Can_Create_Role",
    "Can_View_Role",
    "Can_Delete_Role",
    "Can_Create_Admin",
    "Can_Update_Admin",
    "Can_View_Single_Admin",
    "Can_Block_Admin",
    "Can_UnBlock_Admin",
    "Can_Suspend_Admin",
    "Can_Configure_PaymentMethods",
    "Can_UnSuspend_Admin",
    "Can_View_All_Admin",
    "Can_Delete_Admin",
    "Can_Create_Menu",
    "Can_Delete_Menu",
    "Can_Create_Category",
    "Can_Update_Category",
    "Can_View_A_Single_Category",
    "Can_Delete_Category",
    "Can_Create_Product",
    "Can_Update_Product",
    "Can_Delete_Product",
    "Can_Access_Bagging",
    "Can_View_Bagging",
    "Can_Check_Checking",
    "Can_View_Checking",
    "Can_Assign_Delivery",
    "Can_Do_Delivery",
    "Can_Track_Delivery",
    "Can_Access_Dashboard",
    "Can_Block_Customer",
    "Can_UnBlock_Customer",
    "Can_Suspend_Customer",
    "Can_UnSuspend_Customer",
    "Can_View_All_Customer",
    "Can_Delete_Customer",
    "Can_View_Payment",
    "Can_View_Audit_Trail",
    "Can_View_Customer_Trail",
    "Can_View_Product_Assessment",
    "Can_View_Rate_Limit_Catcher",
    "Can_View_Master_Repo",
    "Can_Select_Role",
    "Can_Configure_Website",
    "Can_Configure_Delivery",
    "Can_Run_Promotion",
    "Can_Configure_System",
    "Can_Handle_PaymentOnDelivery",
    "Can_Update_Personal_Profile",
    "Can_Do_Delivery_To_Customers",
    "Can_Handle_Credit_Sales",
    "Can_Configure_WebsiteSetup",
    






    ];

    sort($RoleList);

    return response()->json(["message"=>$RoleList],200);


}


function PaymentMethodsList(Request $req){


    $PaymentList = [

   "Mobile Money or Credit Card",
   "Payment On Delivery",
    "Credit Sales",
    "Shopping Card",
    ];

    sort($PaymentList);

    return $PaymentList;


}




function RateLimit($Ip)
{

    $key = $Ip;
    $maxAttempts = 60;

    $decayMinutes = 1;


    if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
        $this->RateLimitTracker($Ip);
        throw new ThrottleRequestsException('Too many requests. Please try again later.');
    }

     RateLimiter::hit($key, $decayMinutes * 60);
}

function ManualFreeze($Ip, $attempts, $minute)
{

    $key = $Ip;
    $maxAttempts = $attempts;

    $decayMinutes = $minute;


    if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
        $this->RateLimitTracker($Ip);
        throw new ThrottleRequestsException('Unauthorized attempt detected. This activity is being logged and monitored.');
    }

     RateLimiter::hit($key, $decayMinutes * 60);
}








    function TokenGenerator(): string {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$^&*()_+{}|<>-=[],.';
        $length = 30;
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }


    function detectDevice($userAgent) {
        $isMobile = false;
        $mobileKeywords = ['Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone'];

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isMobile = true;
                break;
            }
        }

        return $isMobile ? 'Mobile' : 'Desktop';
    }

    // Function to detect operating system from User-Agent string
    function detectOperatingSystem($userAgent) {
        $os = 'Unknown';

        $osKeywords = ['Windows', 'Linux', 'Macintosh', 'iOS', 'Android'];

        foreach ($osKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $os = $keyword;
                break;
            }
        }

        return $os;
    }


    function IdGenerator(): string {
        $randomID = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        return $randomID;
    }

    function ProformaIdGenerator(): string {
        $dateTime = new DateTime();
        $randomID = $dateTime->format('YmdHis');
        return $randomID;
    }



}
