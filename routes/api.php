<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthenticator;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuditTrialController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerAuthenticationController;
use App\Http\Controllers\MenuCategoryProduct;
use App\Http\Middleware\CustomerAuthenticator;
use App\Http\Middleware\PrepaidMeterMiddleware;
use App\Http\Controllers\CartOrderPayment;
use App\Http\Controllers\BaggingCheckerDelivery;
use App\Http\Controllers\Master;
use App\Http\Controllers\DashBoard;
use App\Http\Controllers\APPS;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\MasterControllerV1;
use App\Http\Controllers\WebsiteConfigurationController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    /**********************************************
     *                                            *
     *   🌐 External ROUTES                        *
     *                                            *
     **********************************************/


     Route::post('audit/AppSetup', [APPS::class, 'AppSetup']);
     Route::post('audit/TopUp', [APPS::class, 'TopUp']);
     Route::post('SubscriptionPayment', [APPS::class, 'SubscriptionPayment']);
     Route::post('SubscriptionDetails', [APPS::class, 'SubscriptionDetails']);
     Route::post('SetUpCreateAdmin', [AdminUserController::class, 'SetUpCreateAdmin']);



Route::middleware([PrepaidMeterMiddleware::class])->group(function () {




    /**********************************************
     *                                            *
     *   🌐 GLOBAL ROUTES                        *
     *                                            *
     **********************************************/
     Route::post('LogIn', [AuthenticationController::class, 'LogIn']);
     Route::post('VerifyToken', [AuthenticationController::class, 'VerifyToken']);
     Route::post('ForgetPasswordStep1', [AuthenticationController::class, 'ForgetPasswordStep1']);
     Route::post('ForgetPasswordStep2', [AuthenticationController::class, 'ForgetPasswordStep2']);
     Route::post('Visitors', [AuditTrialController::class, 'Visitors']);
     Route::post('UnLocker', [AdminUserController::class, 'UnLocker']);


     Route::post('CustomerLogIn', [CustomerAuthenticationController::class, 'CustomerLogIn']);
     Route::post('CustomerVerifyToken', [CustomerAuthenticationController::class, 'CustomerVerifyToken']);
     Route::post('CustomerForgetPasswordStep1', [CustomerAuthenticationController::class, 'CustomerForgetPasswordStep1']);
     Route::post('CustomerForgetPasswordStep2', [CustomerAuthenticationController::class, 'CustomerForgetPasswordStep2']);
     Route::post('RoleList', [AuditTrialController::class, 'RoleList']);
     Route::post('ViewMenu', [MenuCategoryProduct::class, 'ViewMenu']);
     Route::post('ViewCategory', [MenuCategoryProduct::class, 'ViewCategory']);


     Route::post('ViewProduct', [MenuCategoryProduct::class, 'ViewProduct']);
     Route::post('ViewMenuProduct', [MenuCategoryProduct::class, 'ViewMenuProduct']);
     Route::post('ViewCategoryProduct', [MenuCategoryProduct::class, 'ViewCategoryProduct']);


     Route::post('ViewSingleProduct', [MenuCategoryProduct::class, 'ViewSingleProduct']);
     Route::post('TestRateLimit', [MenuCategoryProduct::class, 'TestRateLimit']);
     Route::post('CreateCustomer', [CustomerController::class, 'CreateCustomer']);
     Route::post('ViewCategory', [MenuCategoryProduct::class, 'ViewCategory']);
     Route::post('ViewCategoryFrontend', [MenuCategoryProduct::class, 'ViewCategoryFrontend']);
     Route::post('ViewMenu', [MenuCategoryProduct::class, 'ViewMenu']);

     Route::get('payment/{UserId}/{OrderId}', [CartOrderPayment::class, 'Payment']);
     Route::get('ConfirmPayment/{RefId}', [CartOrderPayment::class, 'ConfirmPayment']);


     Route::post('ViewTotalSales', [DashBoard::class, 'ViewTotalSales']);
     Route::post('ViewTotalExpenses', [DashBoard::class, 'ViewTotalExpenses']);
     Route::post('ViewTotalYearlySales', [DashBoard::class, 'ViewTotalYearlySales']);
     Route::post('ViewMonthlySalesAndExpenses', [DashBoard::class, 'ViewMonthlySalesAndExpenses']);
     Route::post('ViewTotalSalesForCurrentMonth', [DashBoard::class, 'ViewTotalSalesForCurrentMonth']);
     Route::post('ThisYearSales', [DashBoard::class, 'ThisYearSales']);
     Route::post('TotalCustomers', [DashBoard::class, 'TotalCustomers']);
     Route::post('EarningData', [DashBoard::class, 'EarningData']);
     Route::post('RecentTransaction', [DashBoard::class, 'RecentTransaction']);
     Route::post('YearlyContinent', [DashBoard::class, 'YearlyContinent']);
     Route::post('WeeklyStats', [DashBoard::class, 'WeeklyStats']);
     Route::post('TopCustomers', [DashBoard::class, 'TopCustomers']);
     Route::post('TopTrendingPortfolio', [DashBoard::class, 'TopTrendingPortfolio']);
     Route::post('Auditing', [DashBoard::class, 'Auditing']);
     Route::post('GetVisitors', [DashBoard::class, 'GetVisitors']);
     Route::post('CountVisitors', [DashBoard::class, 'CountVisitors']);
     Route::post('CountCountryVisitors', [DashBoard::class, 'CountCountryVisitors']);



     Route::post('SendChat', [APPS::class, 'SendChat']);
     Route::post('CreateSchedular', [APPS::class, 'CreateSchedular']);
     Route::post('UpdateSchedular', [APPS::class, 'UpdateSchedular']);
     Route::post('DeleteSchedule', [APPS::class, 'DeleteSchedule']);
     Route::post('GetSchedule', [APPS::class, 'GetSchedule']);
     Route::post('ReplyTheChat', [APPS::class, 'ReplyTheChat']);
     Route::post('GetChat', [APPS::class, 'GetChat']);
     Route::post('GetOneEmail', [APPS::class, 'GetOneEmail']);
     Route::post('GetOneReply', [APPS::class, 'GetOneReply']);

     Route::get('GetWebsite', [WebsiteController::class, 'GetWebsite']);
     Route::post('ViewProductImage', [MenuCategoryProduct::class, 'ViewProductImage']);

     Route::post('SearchProducts', [MenuCategoryProduct::class, 'SearchProducts']);
     Route::post('AdminViewSingleCustomer', [CustomerController::class, 'AdminViewSingleCustomer']);

     Route::get('MakePayment/{TransactionId}', [GlobalPaymentController::class, 'MakePayment']);

     Route::post('ViewPaymentMethods', [MasterControllerV1::class, 'ViewPaymentMethods']);
     Route::get('MakeCreditPayment/{TransactionId}', [MasterControllerV1::class, 'MakeCreditPayment']);
     Route::get('MakePaymentForShoppingCard/{TransactionId}', [MasterControllerV1::class, 'MakePaymentForShoppingCard']);
     Route::get('ConfirmCreditPayment/{TransactionId}', [MasterControllerV1::class, 'ConfirmCreditPayment']);
     Route::get('ConfirmShoppingCardPayment/{TransactionId}', [MasterControllerV1::class, 'ConfirmShoppingCardPayment']);
     Route::post('SchedulePayment', [MasterControllerV1::class, 'SchedulePayment']);
     Route::post('CardInformation', [MasterControllerV1::class, 'CardInformation']);
     Route::post('CardTopupHistory', [MasterControllerV1::class, 'CardTopupHistory']);
     Route::post('ViewCollectionAccount', [MasterControllerV1::class, 'ViewCollectionAccount']);
     Route::post('ViewCollectionAccountHistory', [MasterControllerV1::class, 'ViewCollectionAccountHistory']);

/*Main Website Configuration */

Route::post('ViewMainWebsite', [WebsiteConfigurationController::class, 'ViewMainWebsite']);
Route::post('ViewSingleExplore', [WebsiteConfigurationController::class, 'ViewSingleExplore']);
Route::post('ViewAllExplore', [WebsiteConfigurationController::class, 'ViewAllExplore']);
Route::post('ViewSingleExploreSRC', [WebsiteConfigurationController::class, 'ViewSingleExploreSRC']);
Route::post('ViewSpecificExploreSRC', [WebsiteConfigurationController::class, 'ViewSpecificExploreSRC']);
Route::post('ViewAllExploreSRC', [WebsiteConfigurationController::class, 'ViewAllExploreSRC']);
Route::post('ViewSingleExploreSlide', [WebsiteConfigurationController::class, 'ViewSingleExploreSlide']);
Route::post('ViewSpecificExploreSlide', [WebsiteConfigurationController::class, 'ViewSpecificExploreSlide']);
Route::post('ViewAllExploreSlide', [WebsiteConfigurationController::class, 'ViewAllExploreSlide']);

Route::post('ViewSingleSlider', [WebsiteConfigurationController::class, 'ViewSingleSlider']);
Route::post('ViewAllSlider', [WebsiteConfigurationController::class, 'ViewAllSlider']);

Route::post('ViewSingleService', [WebsiteConfigurationController::class, 'ViewSingleService']);
Route::post('ViewAllService', [WebsiteConfigurationController::class, 'ViewAllService']);





     Route::middleware([CustomerAuthenticator::class])->group(function () {

         /**********************************************
          *                                            *
          *   💳 PAYMENT ROUTES                        *
          *                                            *
          **********************************************/

         /**********************************************
          *                                            *
          *   🧍 CUSTOMERS ROUTES                      *
          *                                            *
          **********************************************/
         Route::post('UpdateCustomer', [CustomerController::class, 'UpdateCustomer']);
         Route::post('ViewSingleCustomer', [CustomerController::class, 'ViewSingleCustomer']);

         Route::post('AddToCart', [CartOrderPayment::class, 'AddToCart']);
         Route::post('UpdateCart', [CartOrderPayment::class, 'UpdateCart']);
         Route::post('ViewAllCart', [CartOrderPayment::class, 'ViewAllCart']);
         Route::post('DeleteCart', [CartOrderPayment::class, 'DeleteCart']);

         Route::post('AddToOrder', [CartOrderPayment::class, 'AddToOrder']);
         Route::post('ViewAllOrder', [CartOrderPayment::class, 'ViewAllOrder']);
         Route::post('DetailedOrder', [CartOrderPayment::class, 'DetailedOrder']);
         Route::post('EditProductInDetailedOrder', [CartOrderPayment::class, 'EditProductInDetailedOrder']);
         Route::post('DeleteProductInDetailedOrder', [CartOrderPayment::class, 'DeleteProductInDetailedOrder']);

         Route::post('AddDeliveryDetails', [CartOrderPayment::class, 'AddDeliveryDetails']);
         Route::post('GetTotalPaymentAmount', [CartOrderPayment::class, 'GetTotalPaymentAmount']);
         Route::post('GetCustNotification', [CartOrderPayment::class, 'GetCustNotification']);





     });



     // Routes that require authentication
     Route::middleware([ApiAuthenticator::class])->group(function () {

         /**********************************************
          *                                            *
          *   ⚙️ Website CONFIGURATIONS ROUTES          *
          *                                            *
          **********************************************/

        Route::post('WebsiteSetup', [WebsiteConfigurationController::class, 'WebsiteSetup']);
        Route::post('CreateExplore', [WebsiteConfigurationController::class, 'CreateExplore']);
        Route::post('UpdateExplore', [WebsiteConfigurationController::class, 'UpdateExplore']);
        Route::post('DeletedExplore', [WebsiteConfigurationController::class, 'DeletedExplore']);
        Route::post('CreateExploreSRC', [WebsiteConfigurationController::class, 'CreateExploreSRC']);
        Route::post('DeletedExploreSRC', [WebsiteConfigurationController::class, 'DeletedExploreSRC']);
        Route::post('CreateExploreSlide', [WebsiteConfigurationController::class, 'CreateExploreSlide']);
        Route::post('DeletedExploreSlide', [WebsiteConfigurationController::class, 'DeletedExploreSlide']);

        Route::post('CreateSlider', [WebsiteConfigurationController::class, 'CreateSlider']);
        Route::post('UpdateSlider', [WebsiteConfigurationController::class, 'UpdateSlider']);
        Route::post('DeletedSlider', [WebsiteConfigurationController::class, 'DeletedSlider']);

        Route::post('CreateServices', [WebsiteConfigurationController::class, 'CreateServices']);
        Route::post('UpdateServices', [WebsiteConfigurationController::class, 'UpdateServices']);
        Route::post('DeletedService', [WebsiteConfigurationController::class, 'DeletedService']);
















         /**********************************************
          *                                            *
          *   ⚙️ CONFIGURATIONS ROUTES                *
          *                                            *
          **********************************************/
         Route::post('RoleList', [AuditTrialController::class, 'RoleList']);
         Route::post('PaymentMethodsList', [AuditTrialController::class, 'PaymentMethodsList']);

         Route::post('CreateUserRole', [AuditTrialController::class, 'CreateUserRole']);
         Route::post('ViewUserFunctions', [AuditTrialController::class, 'ViewUserFunctions']);
         Route::post('DeleteUserFunctions', [AuditTrialController::class, 'DeleteUserFunctions']);
         Route::post('ViewAllPayment', [CartOrderPayment::class, 'ViewAllPayment']);
         Route::post('ViewAuditTrail', [Master::class, 'ViewAuditTrail']);
         Route::post('ViewCustomerTrail', [Master::class, 'ViewCustomerTrail']);
         Route::post('ViewProductAssessment', [Master::class, 'ViewProductAssessment']);
         Route::post('ViewRateLimitCatcher', [Master::class, 'ViewRateLimitCatcher']);
         Route::post('ViewMasterRepo', [Master::class, 'ViewMasterRepo']);

         Route::post('ViewMUsers', [Master::class, 'ViewMUsers']);
         Route::post('ViewMOrder', [Master::class, 'ViewMOrder']);
         Route::post('ViewMBagging', [Master::class, 'ViewMBagging']);
         Route::post('ViewMChecker', [Master::class, 'ViewMChecker']);
         Route::post('ViewMDelivery', [Master::class, 'ViewMDelivery']);
         Route::post('ViewMPayment', [Master::class, 'ViewMPayment']);
         Route::post('CreateWebsite', [WebsiteController::class, 'CreateWebsite']);



         /**********************************************
          *                                            *
          *   🧑‍💼 STAFF MEMBERS ROUTES               *
          *                                            *
          **********************************************/
         Route::post('SuspendAdmin', [AdminUserController::class, 'SuspendAdmin']);
         Route::post('UnSuspendAdmin', [AdminUserController::class, 'UnSuspendAdmin']);
         Route::post('BlockAdmin', [AdminUserController::class, 'BlockAdmin']);
         Route::post('UnBlockAdmin', [AdminUserController::class, 'UnBlockAdmin']);
         Route::post('CreateAdmin', [AdminUserController::class, 'CreateAdmin']);
         Route::post('UpdateAdmin', [AdminUserController::class, 'UpdateAdmin']);
         Route::post('MyProfileUpdateAdmin', [AdminUserController::class, 'MyProfileUpdateAdmin']);

         Route::post('ViewSingleAdmin', [AdminUserController::class, 'ViewSingleAdmin']);
         Route::post('DeleteAdmin', [AdminUserController::class, 'DeleteAdmin']);
         Route::post('ViewAllAdmin', [AdminUserController::class, 'ViewAllAdmin']);


         /**********************************************
          *                                            *
          *   🧍 CUSTOMERS ROUTES                      *
          *                                            *
          **********************************************/
         Route::post('DeleteCustomer', [CustomerController::class, 'DeleteCustomer']);
         Route::post('ViewAllCustomer', [CustomerController::class, 'ViewAllCustomer']);
         Route::post('BlockCustomer', [CustomerController::class, 'BlockCustomer']);
         Route::post('UnBlockCustomer', [CustomerController::class, 'UnBlockCustomer']);
         Route::post('SuspendCustomer', [CustomerController::class, 'SuspendCustomer']);
         Route::post('UnSuspendCustomer', [CustomerController::class, 'UnSuspendCustomer']);


             /**********************************************
          *                                            *
          *   📂 MENU CATEGORY PRODUCT ROUTES          *
          *                                            *
          **********************************************/

         Route::post('CreateMenu', [MenuCategoryProduct::class, 'CreateMenu']);
         Route::post('DeleteMenu', [MenuCategoryProduct::class, 'DeleteMenu']);

         Route::post('CreateCategory', [MenuCategoryProduct::class, 'CreateCategory']);
         Route::post('UpdateCategory', [MenuCategoryProduct::class, 'UpdateCategory']);
         Route::post('ViewSingleCategory', [MenuCategoryProduct::class, 'ViewSingleCategory']);
         Route::post('DeleteCategory', [MenuCategoryProduct::class, 'DeleteCategory']);

         Route::post('CreateProduct', [MenuCategoryProduct::class, 'CreateProduct']);
         Route::post('UpdateProduct', [MenuCategoryProduct::class, 'UpdateProduct']);
         Route::post('DeleteProduct', [MenuCategoryProduct::class, 'DeleteProduct']);

         Route::post('ProductImage', [MenuCategoryProduct::class, 'ProductImage']);
         Route::post('DeleteProductImage', [MenuCategoryProduct::class, 'DeleteProductImage']);

         Route::post('ViewProductAdmin', [MenuCategoryProduct::class, 'ViewProductAdmin']);


         /**********************************************
          *                                            *
          *   🛍️ BAGGING, ✅ CHECKER, 🚚 DELIVERY      *
          *                                            *
          **********************************************/

         Route::post('CheckBagging', [BaggingCheckerDelivery::class, 'CheckBagging']);
         Route::post('ViewBaggingList', [BaggingCheckerDelivery::class, 'ViewBaggingList']);
         Route::post('ViewConfirmedBaggingList', [BaggingCheckerDelivery::class, 'ViewConfirmedBaggingList']);
         Route::post('CheckChecker', [BaggingCheckerDelivery::class, 'CheckChecker']);
         Route::post('ViewCheckerList', [BaggingCheckerDelivery::class, 'ViewCheckerList']);
         Route::post('ViewConfirmedCheckerList', [BaggingCheckerDelivery::class, 'ViewConfirmedCheckerList']);
         Route::post('AssignForDelivery', [BaggingCheckerDelivery::class, 'AssignForDelivery']);
         Route::post('ViewUnAssignedDelivery', [BaggingCheckerDelivery::class, 'ViewUnAssignedDelivery']);
         Route::post('ViewAssignedDelivery', [BaggingCheckerDelivery::class, 'ViewAssignedDelivery']);
         Route::post('ViewSingleOrdersToDeliver', [BaggingCheckerDelivery::class, 'ViewSingleOrdersToDeliver']);
         Route::post('DeliverNow', [BaggingCheckerDelivery::class, 'DeliverNow']);
         Route::post('ViewSingleDeliveredOrders', [BaggingCheckerDelivery::class, 'ViewSingleDeliveredOrders']);
         Route::post('ViewDeliveredOrders', [BaggingCheckerDelivery::class, 'ViewDeliveredOrders']);

         Route::post('DetailedAllOrder', [CartOrderPayment::class, 'DetailedAllOrder']);
         Route::post('UseGoogleMap', [CartOrderPayment::class, 'UseGoogleMap']);
         Route::post('DetailedPaymentFromOrder', [CartOrderPayment::class, 'DetailedPaymentFromOrder']);

         Route::post('ViewGlobalDelivery', [BaggingCheckerDelivery::class, 'ViewGlobalDelivery']);


         Route::post('PaymentMethods', [MasterControllerV1::class, 'PaymentMethods']);
         Route::post('DeletePaymentMethods', [MasterControllerV1::class, 'DeletePaymentMethods']);
         Route::post('ViewAwaitingCreditSales', [MasterControllerV1::class, 'ViewAwaitingCreditSales']);
         Route::post('ViewSingleAwaitingCreditSales', [MasterControllerV1::class, 'ViewSingleAwaitingCreditSales']);

         Route::post('AcceptCreditSales', [MasterControllerV1::class, 'AcceptCreditSales']);
         Route::post('RejectCreditSales', [MasterControllerV1::class, 'RejectCreditSales']);
         Route::post('ScheduleSinglePayment', [MasterControllerV1::class, 'ScheduleSinglePayment']);
         Route::post('ScheduleShoppingCard', [MasterControllerV1::class, 'ScheduleShoppingCard']);
         Route::post('DeliveryConfig', [MasterControllerV1::class, 'DeliveryConfig']);
         Route::post('ViewDeliveryConfig', [MasterControllerV1::class, 'ViewDeliveryConfig']);
         Route::post('RunPromotion', [MasterControllerV1::class, 'RunPromotion']);
         Route::post('RevertPromotion', [MasterControllerV1::class, 'RevertPromotion']);
         Route::post('ConfirmPaymentOnDelivery', [MasterControllerV1::class, 'ConfirmPaymentOnDelivery']);
         Route::post('ViewPaymentOnDelivery', [MasterControllerV1::class, 'ViewPaymentOnDelivery']);











     });











});











