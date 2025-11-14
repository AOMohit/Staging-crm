<?php

use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\ManualTaxController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\VendorCategoryController;
use App\Http\Controllers\VendorServiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\LoyalityPtsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TripBookingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ExtraServiceController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\StationaryController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CarbonDonationController;


use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckLogin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/pdfcertificate', function () {
    return view('pdf/carbon-certificate');
});
Route::get('/storage/app/{path}', function ($path) {
    $path = storage_path('app/' . $path);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('path', '.*');

Route::get('/test-trip-reminder', function () {
    \Artisan::call('send:trip-reminders');
    return 'Reminder command chal gaya!';
});

// without any middleare
Route::get('/', function () {
    return redirect()->route('login');
})->name('/');

//================= cron jobs =================
Route::get('tier', [CronController::class, 'customerTier'])->name('tier');
Route::get('ongoing-trip-report', [CronController::class, 'ongoingTripReport'])->name('ongoing-trip-report');
Route::get('send-birthday-email', [CronController::class, 'sendBirthdayEmail'])->name('send-birthday-email');
Route::get('reminder', [CronController::class, 'sendReminderToUnsubmittedForms'])->name('reminder');
Route::get('expenses-created-today', [CronController::class, 'sendTodayExpenseReport']);
Route::get('part-payment-daily-report', [CronController::class, 'dailypartPaymentReport']);

//================= cron jobs =================

// without any middleare
Route::get('stationary_export', [TripController::class, 'stationaryExport'])->name('stationary_export');
Route::get('merchandise_export', [TripController::class, 'merchandiseExport'])->name('merchandise_export');
Route::get('ongoing_trip', [ReportController::class, 'ongoingTrip'])->name('ongoing_trip');
Route::get('customer_registration_data', [TripController::class, 'customerRegistrationData'])->name('customer_registration_data');
Route::get('ongoing_trip_pdf', [ReportController::class, 'ongoingTripPdf'])->name('ongoing_trip_pdf');
// without any middleare


Route::middleware('CheckLogin')->group(function () {
    Route::get('/offset', fn() => view('admin.carbon.realtime'));
    Route::post('/offset', [CarbonDonationController::class, 'submit']);

    Route::get('/enquiries/unread-count', [ProfileController::class, 'unreadCount']);
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/tripstat', [ProfileController::class, 'tripstat'])->name('tripstat');
    Route::post('/getCustomerDetailsById', [ProfileController::class, 'getCustomerDetailsById'])->name('getCustomerDetailsById');
    Route::post('/getStateByCountry', [ProfileController::class, 'getStateByCountry'])->name('getStateByCountry');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('/password-update', [ProfileController::class, 'updatePassword'])->name('password-update');

    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('my-profile');
    Route::post('/profile-update', [ProfileController::class, 'updateprofile'])->name('profile-update');

    Route::post('/sendOtpToCustomer', [ProfileController::class, 'sendOtpToCustomer'])->name('sendOtpToCustomer');
    Route::post('/verifyOTP', [ProfileController::class, 'verifyOTP'])->name('verifyOTP');


    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        Route::get('site', [SettingController::class, 'site'])->name('site');
        Route::post('site-update', [SettingController::class, 'siteUpdate'])->name('site-update');

        Route::get('loyalty-point-terms-condition', [SettingController::class, 'loyaltyPointTermsCondition'])->name('loyalty-point-terms-condition');
        Route::post('loyalty-point-terms_conditions-update', [SettingController::class, 'loyaltyPointTermsConditionUpdate'])->name('loyalty-point-terms_conditions-update');

        Route::get('third-party', [SettingController::class, 'thirdParty'])->name('third-party');
        Route::post('third-party-update', [SettingController::class, 'thirdPartyUpdate'])->name('third-party-update');

        Route::get('terms', [SettingController::class, 'terms'])->name('terms');
        Route::post('terms-update', [SettingController::class, 'termsUpdate'])->name('terms-update');

        Route::get('privacy', [SettingController::class, 'privacy'])->name('privacy');
        Route::post('privacy-update', [SettingController::class, 'privacyUpdate'])->name('privacy-update');

        Route::get('about', [SettingController::class, 'about'])->name('about');
        Route::post('about-update', [SettingController::class, 'aboutUpdate'])->name('about-update');

        Route::get('contact', [SettingController::class, 'contact'])->name('contact');
        Route::post('contact-update', [SettingController::class, 'contactUpdate'])->name('contact-update');

        Route::get('important', [SettingController::class, 'important'])->name('important');
        Route::post('important-update', [SettingController::class, 'importantUpdate'])->name('important-update');

        Route::get('tier', [SettingController::class, 'tier'])->name('tier');
        Route::post('tier-update', [SettingController::class, 'tierUpdate'])->name('tier-update');

        Route::get('earn', [SettingController::class, 'earn'])->name('earn');
        Route::post('earn-update', [SettingController::class, 'earnUpdate'])->name('earn-update');

        Route::get('redeem', [SettingController::class, 'redeem'])->name('redeem');
        Route::post('redeem-update', [SettingController::class, 'redeemUpdate'])->name('redeem-update');

        Route::get('transfer', [SettingController::class, 'transfer'])->name('transfer');
        Route::post('transfer-update', [SettingController::class, 'transferUpdate'])->name('transfer-update');
        
        Route::get('loyalty-point-faq', [SettingController::class, 'loyaltyPointFaq'])->name('loyalty-point-faq');
        Route::post('loyalty_point_faq_store',[SettingController::class,'loyaltypointfaqstore'])->name('loyalty_point_faq_store');
        Route::get('loyalty_point_faq_delete',[SettingController::class,'loyaltypointfaqdelete'])->name('loyalty_point_faq_delete');
        
        Route::group(['prefix' => 'extra_service', 'as' => 'extra_service.'], function () {
            Route::get('/', [ExtraServiceController::class, 'index'])->name('index');
            Route::get('get', [ExtraServiceController::class, 'get'])->name('get');
            Route::get('add', [ExtraServiceController::class, 'create'])->name('add');
            Route::post('store', [ExtraServiceController::class, 'store'])->name('store');
            Route::get('edit/{id}', [ExtraServiceController::class, 'edit'])->name('edit');
            Route::post('update', [ExtraServiceController::class, 'update'])->name('update');
            Route::get('delete/{id}', [ExtraServiceController::class, 'destroy'])->name('delete');
        });

        Route::group(['prefix' => 'vendor_category', 'as' => 'vendor_category.'], function () {
            Route::get('/', [VendorCategoryController::class, 'index'])->name('index');
            Route::get('get', [VendorCategoryController::class, 'get'])->name('get');
            Route::get('add', [VendorCategoryController::class, 'create'])->name('add');
            Route::post('store', [VendorCategoryController::class, 'store'])->name('store');
            Route::get('edit/{id}', [VendorCategoryController::class, 'edit'])->name('edit');
            Route::post('update', [VendorCategoryController::class, 'update'])->name('update');
            Route::get('delete/{id}', [VendorCategoryController::class, 'destroy'])->name('delete');
        });

        Route::group(['prefix' => 'vendor_service', 'as' => 'vendor_service.'], function () {
            Route::get('/', [VendorServiceController::class, 'index'])->name('index');
            Route::get('get', [VendorServiceController::class, 'get'])->name('get');
            Route::get('add', [VendorServiceController::class, 'create'])->name('add');
            Route::post('store', [VendorServiceController::class, 'store'])->name('store');
            Route::get('edit/{id}', [VendorServiceController::class, 'edit'])->name('edit');
            Route::post('update', [VendorServiceController::class, 'update'])->name('update');
            Route::get('delete/{id}', [VendorServiceController::class, 'destroy'])->name('delete');
        });

        Route::group(['prefix' => 'merchandise', 'as' => 'merchandise.'], function () {
            Route::get('/', [MerchandiseController::class, 'index'])->name('index');
            Route::get('get', [MerchandiseController::class, 'get'])->name('get');
            Route::get('add', [MerchandiseController::class, 'create'])->name('add');
            Route::post('store', [MerchandiseController::class, 'store'])->name('store');
            Route::get('edit/{id}', [MerchandiseController::class, 'edit'])->name('edit');
            Route::post('update', [MerchandiseController::class, 'update'])->name('update');
            Route::get('delete/{id}', [MerchandiseController::class, 'destroy'])->name('delete');
        });

        Route::group(['prefix' => 'stationary', 'as' => 'stationary.'], function () {
            Route::get('/', [StationaryController::class, 'index'])->name('index');
            Route::get('get', [StationaryController::class, 'get'])->name('get');
            Route::get('add', [StationaryController::class, 'create'])->name('add');
            Route::post('store', [StationaryController::class, 'store'])->name('store');
            Route::get('edit/{id}', [StationaryController::class, 'edit'])->name('edit');
            Route::post('update', [StationaryController::class, 'update'])->name('update');
            Route::get('delete/{id}', [StationaryController::class, 'destroy'])->name('delete');
        });

        Route::group(['prefix' => 'relationship', 'as' => 'relationship.'], function () {
            Route::get('/', [RelationshipController::class, 'index'])->name('index');
            Route::get('get', [RelationshipController::class, 'get'])->name('get');
            Route::get('add', [RelationshipController::class, 'create'])->name('add');
            Route::post('store', [RelationshipController::class, 'store'])->name('store');
            Route::get('edit/{id}', [RelationshipController::class, 'edit'])->name('edit');
            Route::post('update', [RelationshipController::class, 'update'])->name('update');
            Route::get('delete/{id}', [RelationshipController::class, 'destroy'])->name('delete');
        });
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('get-customers', [CustomerController::class, 'getCustomers'])->name('get-customers');
        Route::get('add', [CustomerController::class, 'create'])->name('add');
        Route::post('store', [CustomerController::class, 'store'])->name('store');
        Route::post('minor/store', [CustomerController::class, 'minorStore'])->name('minor.store');
        Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::post('update', [CustomerController::class, 'update'])->name('update');
        Route::get('delete/{id}', [CustomerController::class, 'destroy'])->name('delete');
        Route::get('view/{id}', [CustomerController::class, 'view'])->name('view');
        Route::get('activity/{id}', [CustomerController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [CustomerController::class, 'activity'])->name('activity-get');

        Route::post('import', [CustomerController::class, 'import'])->name('import');
        Route::get('export-sample', [CustomerController::class, 'exportSample'])->name('export-sample');
        Route::get('export', [CustomerController::class, 'export'])->name('export');
        Route::post('referal-store', [CustomerController::class, 'referalStore'])->name('referal-store');

        Route::group(['prefix' => 'details', 'as' => 'details.'], function () {
            Route::get('trips', [CustomerController::class, 'trips'])->name('trips');
            Route::get('points', [CustomerController::class, 'points'])->name('points');
            Route::get('transfer', [CustomerController::class, 'transfer'])->name('transfer');
            Route::get('referal', [CustomerController::class, 'referal'])->name('referal');
            Route::get('minor', [CustomerController::class, 'minor'])->name('minor');
            Route::get('email-suggestions', [CustomerController::class, 'emailSuggestions'])->name('email-suggestions');

        });

    });

    Route::group(['prefix' => 'birthdays', 'as' => 'birthday.'], function () {
        Route::get('/', [BirthdayController::class, 'index'])->name('index');
        Route::get('get-birthdays', [BirthdayController::class, 'getBirthdays'])->name('get-birthdays');
        Route::get('export', [BirthdayController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'vendors', 'as' => 'vendors.'], function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('get-vendors', [VendorController::class, 'getvendors'])->name('get-vendors');
        Route::get('add', [VendorController::class, 'create'])->name('add');
        Route::post('store', [VendorController::class, 'store'])->name('store');
        Route::get('edit/{id}', [VendorController::class, 'edit'])->name('edit');
        Route::get('view/{id}', [VendorController::class, 'view'])->name('view');
        Route::post('update', [VendorController::class, 'update'])->name('update');
        Route::get('delete/{id}', [VendorController::class, 'destroy'])->name('delete');
        Route::get('activity/{id}', [VendorController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [VendorController::class, 'activity'])->name('activity-get');

        Route::post('import', [VendorController::class, 'import'])->name('import');
        Route::get('export-sample', [VendorController::class, 'exportSample'])->name('export-sample');
        Route::get('export', [VendorController::class, 'export'])->name('export');

        Route::group(['prefix' => 'details', 'as' => 'details.'], function () {
            Route::get('services', [VendorController::class, 'services'])->name('services');
        });
    });

    Route::group(['prefix' => 'agent', 'as' => 'agent.'], function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::get('get-agents', [AgentController::class, 'getagents'])->name('get-agents');
        Route::get('add', [AgentController::class, 'create'])->name('add');
        Route::post('store', [AgentController::class, 'store'])->name('store');
        Route::get('edit/{id}', [AgentController::class, 'edit'])->name('edit');
        Route::get('view/{id}', [AgentController::class, 'view'])->name('view');
        Route::post('update', [AgentController::class, 'update'])->name('update');
        Route::get('delete/{id}', [AgentController::class, 'destroy'])->name('delete');
        Route::get('activity/{id}', [AgentController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [AgentController::class, 'activity'])->name('activity-get');

        Route::post('import', [AgentController::class, 'import'])->name('import');
        Route::get('export-sample', [AgentController::class, 'exportSample'])->name('export-sample');
        Route::get('export', [AgentController::class, 'export'])->name('export');

        Route::group(['prefix' => 'details', 'as' => 'details.'], function () {
            Route::get('referrals', [AgentController::class, 'referrals'])->name('referrals');
        });

    });

    Route::group(['prefix' => 'inventory_category', 'as' => 'inventory_category.'], function () {
        Route::get('/', [InventoryCategoryController::class, 'index'])->name('index');
        Route::get('get', [InventoryCategoryController::class, 'get'])->name('get');
        Route::get('add', [InventoryCategoryController::class, 'create'])->name('add');
        Route::post('store', [InventoryCategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [InventoryCategoryController::class, 'edit'])->name('edit');
        Route::post('update', [InventoryCategoryController::class, 'update'])->name('update');
        Route::get('delete/{id}', [InventoryCategoryController::class, 'destroy'])->name('delete');

        Route::get('export', [InventoryCategoryController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('get', [InventoryController::class, 'get'])->name('get');
        Route::get('add', [InventoryController::class, 'create'])->name('add');
        Route::post('store', [InventoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [InventoryController::class, 'edit'])->name('edit');
        Route::get('view/{id}', [InventoryController::class, 'view'])->name('view');
        Route::get('view-product', [InventoryController::class, 'viewProduct'])->name('view-product');
        Route::get('view-details/{id}', [InventoryController::class, 'viewDetails'])->name('view-details');
        Route::post('update', [InventoryController::class, 'update'])->name('update');
        Route::get('delete/{id}', [InventoryController::class, 'destroy'])->name('delete');
        Route::get('activity/{id}', [InventoryController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [InventoryController::class, 'activity'])->name('activity-get');

        Route::get('edit-stock', [InventoryController::class, 'editStock'])->name('edit-stock');
        Route::get('delete-stock', [InventoryController::class, 'deleteStock'])->name('delete-stock');
        Route::get('activity-stock', [InventoryController::class, 'activityStock'])->name('activity-stock');
        Route::get('get-activity-stock', [InventoryController::class, 'activityStockHistory'])->name('get-activity-stock');

        Route::get('export', [InventoryController::class, 'export'])->name('export');

        Route::group(['prefix' => 'details', 'as' => 'details.'], function () {
            Route::get('get', [InventoryController::class, 'history'])->name('get');
            Route::post('stock', [InventoryController::class, 'stock'])->name('stock');
            Route::post('stockUpdate', [InventoryController::class, 'stockUpdate'])->name('stockUpdate');
            Route::post('stockHistoryUpdate', [InventoryController::class, 'stockHistoryUpdate'])->name('stockHistoryUpdate');
        });
    });

    Route::group(['prefix' => 'trip', 'as' => 'trip.'], function () {
        Route::get('/', [TripController::class, 'index'])->name('index');
        Route::get('get', [TripController::class, 'get'])->name('get');
        Route::get('add', [TripController::class, 'create'])->name('add');
        Route::post('store', [TripController::class, 'store'])->name('store');
        Route::get('edit/{id}', [TripController::class, 'edit'])->name('edit');
        Route::post('update', [TripController::class, 'update'])->name('update');
        Route::get('delete/{id}', [TripController::class, 'destroy'])->name('delete');
        Route::get('view/{id}', [TripController::class, 'view'])->name('view');
        Route::get('activity/{id}', [TripController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [TripController::class, 'activity'])->name('activity-get');
        Route::post('change-status', [TripController::class, 'changeStatus'])->name('change-status');
        Route::post('cancel', [TripController::class, 'cancelTrip'])->name('cancel');

        Route::post('import', [TripController::class, 'import'])->name('import');
        Route::get('export-sample', [TripController::class, 'exportSample'])->name('export-sample');
        Route::get('export', [TripController::class, 'export'])->name('export');

        Route::post('getExpenseById', [TripController::class, 'getExpenseById'])->name('getExpenseById');

        Route::group(['prefix' => 'details', 'as' => 'details.'], function () {
            Route::get('travelers', [TripController::class, 'travelers'])->name('travelers');
            Route::get('rooms', [TripController::class, 'rooms'])->name('rooms');
            Route::get('vehicle', [TripController::class, 'vehicle'])->name('vehicle');
            Route::get('extra', [TripController::class, 'extra'])->name('extra');
            Route::get('vendor', [TripController::class, 'vendor'])->name('vendor');
            Route::get('merchandise', [TripController::class, 'merchandise'])->name('merchandise');
            Route::get('stationary', [TripController::class, 'stationary'])->name('stationary');
            Route::get('agents', [TripController::class, 'agents'])->name('agents');
            Route::post('expense', [TripController::class, 'expense'])->name('expense');
            Route::get('expense-report-downlaod', [TripController::class, 'expenseReportDownload'])->name('expense-report-downlaod');
            Route::post('vendorByExp', [TripController::class, 'vendorByExp'])->name('vendorByExp');
            Route::post('getServiceByVendor', [TripController::class, 'getServiceByVendor'])->name('getServiceByVendor');
            Route::get('view', [TripController::class, 'viewExpense'])->name('view');
            Route::get('get', [TripController::class, 'getExpense'])->name('get');
            Route::post('makeExpPayment', [TripController::class, 'makeExpPayment'])->name('makeExpPayment');
            Route::get('delete/{id}', [TripController::class, 'deleteExpense'])->name('delete');
            Route::post('getCustomerByBookingId', [TripController::class, 'getCustomerByBookingId'])->name('getCustomerByBookingId');
            Route::post('allotRoom', [TripController::class, 'allotRoom'])->name('allotRoom');
            Route::get('room-view', [TripController::class, 'roomView'])->name('room-view');
            Route::get('room-get', [TripController::class, 'roomGet'])->name('room-get');
            Route::get('room-delete', [TripController::class, 'roomDelete'])->name('room-delete');
            Route::post('getCustomerByBookingIdForVehicle', [TripController::class, 'getCustomerByBookingIdForVehicle'])->name('getCustomerByBookingIdForVehicle');
            Route::post('allotVehicle', [TripController::class, 'allotVehicle'])->name('allotVehicle');
            Route::get('vehicle-view', [TripController::class, 'vehicleView'])->name('vehicle-view');
            Route::get('vehicle-get', [TripController::class, 'vehicleGet'])->name('vehicle-get');
            Route::get('vehicle-delete', [TripController::class, 'vehicleDelete'])->name('vehicle-delete');
            Route::get('export-room', [TripController::class, 'exportRoom'])->name('export-room');
            Route::get('export-vehicle', [TripController::class, 'exportVehicle'])->name('export-vehicle');
            Route::get('export-expense', [TripController::class, 'exportExpense'])->name('export-expense');
            Route::get('export-master', [TripController::class, 'exportMaster'])->name('export-master');
            Route::get('send-merchandise-email', [TripController::class, 'sendMerEmail'])->name('send-merchandise-email');
            Route::get('send-stationary-email', [TripController::class, 'sendStatEmail'])->name('send-stationary-email');
            Route::get('receivable', [TripController::class, 'receivable'])->name('receivable');
            Route::get('sendEmail', [TripController::class, 'sendEmail'])->name('sendEmail');
            Route::get('delete-expense/{id}', [TripController::class, 'deleteExpenseDetail'])->name('delete-expense');
            Route::post('edit-expense-history', [TripController::class, 'editExpenseDetail'])->name('edit-expense-history');
            Route::get('carbonSampleSheet', [TripController::class, 'carbonsample'])->name('carbonSampleSheet');
            Route::post('carbonInfoImport', [TripController::class, 'carbonInfoImport'])->name('carbonInfoImport');
            Route::get('carboninfoData', [TripController::class, 'getcarboninfoData'])->name('carboninfoData');
            Route::post('update-carbon-neutral-data', [TripController::class, 'UpdatecarbonNeutralData'])->name('update-carbon-neutral-data');
            Route::post('get-new-carbon-customers', [TripController::class, 'getNewCarbonCustomers'])->name('get-new-carbon-customers');
        });
    });

    Route::group(['prefix' => 'enquiry', 'as' => 'enquiry.'], function () {
        Route::get('/', [EnquiryController::class, 'index'])->name('index');
        Route::get('get', [EnquiryController::class, 'get'])->name('get');
        Route::get('view', [EnquiryController::class, 'view'])->name('view');

        Route::get('export', [EnquiryController::class, 'export'])->name('export');

    });

    Route::group(['prefix' => 'loyalty', 'as' => 'loyalty.'], function () {
        Route::get('/', [LoyalityPtsController::class, 'index'])->name('index');
        Route::get('get', [LoyalityPtsController::class, 'get'])->name('get');
        Route::get('gift', [LoyalityPtsController::class, 'create'])->name('gift');
        Route::get('cashback', [LoyalityPtsController::class, 'cashback'])->name('cashback');
        Route::post('store', [LoyalityPtsController::class, 'store'])->name('store');
        Route::get('activity/{id}', [LoyalityPtsController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [LoyalityPtsController::class, 'activity'])->name('activity-get');
        Route::post('confirmation-email', [LoyalityPtsController::class, 'confirmationEmail'])->name('confirmation-email');

        Route::get('export', [LoyalityPtsController::class, 'export'])->name('export');

    });

    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        Route::group(['prefix' => 'export', 'as' => 'export.'], function () {
            Route::get('booking-by-trip', [ReportController::class, 'bookingByTrip'])->name('booking-by-trip');
            Route::get('booking-by-traveler', [ReportController::class, 'bookingByTraveler'])->name('booking-by-traveler');
            Route::get('booking-by-location', [ReportController::class, 'bookingByLocation'])->name('booking-by-location');
            Route::get('booking-by-agent', [ReportController::class, 'bookingByAgent'])->name('booking-by-agent');
            Route::get('booking-by-agent', [ReportController::class, 'bookingByAgent'])->name('booking-by-agent');
            Route::get('total-trips', [TripController::class, 'export'])->name('total-trips');
            Route::get('total-bookings', [TripBookingController::class, 'export'])->name('total-bookings');
            Route::get('total-customers', [CustomerController::class, 'export'])->name('total-customers');
            Route::get('total-agents', [AgentController::class, 'export'])->name('total-agents');
            Route::get('total-vendors', [VendorController::class, 'export'])->name('total-vendors');
            Route::get('total-inventory', [InventoryController::class, 'export'])->name('total-inventory');
            Route::get('total-loyalty', [LoyalityPtsController::class, 'export'])->name('total-loyalty');
            Route::get('customer-by-location', [ReportController::class, 'customerByLocation'])->name('customer-by-location');
            Route::get('payment-by-type', [ReportController::class, 'paymentByType'])->name('payment-by-type');
            Route::get('expense-by-trip', [ReportController::class, 'expenseByTrip'])->name('expense-by-trip');
            Route::get('expense-by-vendor', [ReportController::class, 'expenseByVendor'])->name('expense-by-vendor');
            Route::get('net-receivables', [ReportController::class, 'receivables'])->name('net-receivables');
            Route::get('loyalty-points-redeemed', [ReportController::class, 'loyaltyPtsRdm'])->name('loyalty-points-redeemed');
            Route::get('loyalty-points-available', [ReportController::class, 'loyaltyPtsAvail'])->name('loyalty-points-available');
            Route::get('booking-by-lead', [ReportController::class, 'bookingByLead'])->name('booking-by-lead');
            Route::get('booking-by-gender', [ReportController::class, 'bookingByGender'])->name('booking-by-gender');
            Route::get('booking-by-shirt', [ReportController::class, 'bookingByshirt'])->name('booking-by-shirt');
            Route::get('sustainability', [ReportController::class, 'sustainability'])->name('sustainability');
            Route::get('ongoing-trip', [ReportController::class, 'ongoingTrip'])->name('ongoing-trip');
            Route::get('sent-invoice', [ReportController::class, 'sentInvoice'])->name('sent-invoice');
            Route::get('pending-invoice', [ReportController::class, 'pendingInvoice'])->name('pending-invoice');


        });
    });

    Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {
        Route::get('/', [TripBookingController::class, 'index'])->name('index');
        Route::get('get', [TripBookingController::class, 'get'])->name('get');
        Route::get('activity/{id}', [TripBookingController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [TripBookingController::class, 'activity'])->name('activity-get');
        Route::get('view', [TripBookingController::class, 'view'])->name('view');
        Route::get('export', [TripBookingController::class, 'export'])->name('export');
        Route::get('delete/{id}', [TripBookingController::class, 'destroy'])->name('delete');
        Route::post('import', [TripBookingController::class, 'import'])->name('import');
        Route::get('new-trip', [TripBookingController::class, 'create'])->name('new-trip');
        Route::post('billing-customer',[TripBookingController::class, 'billingCustomer'])->name('billing-customer');
        Route::post('/get-customer-manual-tax', [ManualTaxController::class, 'getCustomerManualTax'])->name('getCustomerManualTax');
        Route::post('/save-customer-manual-tax', [ManualTaxController::class, 'saveCustomerManualTax'])->name('saveCustomerManualTax');


        // new trip
        Route::group(['prefix' => 'trip', 'as' => 'trip.'], function () {
            Route::post('booking-for', [TripBookingController::class, 'bookingFor'])->name('booking-for');
            Route::post('lead', [TripBookingController::class, 'leadSource'])->name('lead');
            Route::post('sub-lead', [TripBookingController::class, 'subLeadSource'])->name('sub-lead');
            Route::post('expedition', [TripBookingController::class, 'expedition'])->name('expedition');
            Route::post('trips', [TripBookingController::class, 'trips'])->name('trips');
            Route::post('vehicles', [TripBookingController::class, 'vehicles'])->name('vehicles');
            Route::post('seats', [TripBookingController::class, 'seats'])->name('seats');
            Route::post('seatsAmt', [TripBookingController::class, 'seatsAmt'])->name('seatsAmt');
            Route::post('vehicleSecAmt', [TripBookingController::class, 'vehicleSecAmt'])->name('vehicleSecAmt');
            Route::post('vehicleCmt', [TripBookingController::class, 'vehicleCmt'])->name('vehicleCmt');
            Route::post('vehicleSecAmtCmt', [TripBookingController::class, 'vehicleSecAmtCmt'])->name('vehicleSecAmtCmt');
            Route::post('roomNumber', [TripBookingController::class, 'roomNumber'])->name('roomNumber');
            Route::post('saveRoomInfo', [TripBookingController::class, 'saveRoomInfo'])->name('saveRoomInfo');
            Route::post('paymentFrom', [TripBookingController::class, 'paymentFrom'])->name('paymentFrom');
            Route::post('paymentFromCmpny', [TripBookingController::class, 'paymentFromCmpny'])->name('paymentFromCmpny');
            Route::post('paymentFromTax', [TripBookingController::class, 'paymentFromTax'])->name('paymentFromTax');
            Route::post('paymentAllDone', [TripBookingController::class, 'paymentAllDone'])->name('paymentAllDone');
            Route::post('paymentByCustomer', [TripBookingController::class, 'paymentByCustomer'])->name('paymentByCustomer');
            Route::post('paymentAllDoneCheck', [TripBookingController::class, 'paymentAllDoneCheck'])->name('paymentAllDoneCheck');
            Route::post('paymentType', [TripBookingController::class, 'paymentType'])->name('paymentType');
            Route::post('paymentAmt', [TripBookingController::class, 'paymentAmt'])->name('paymentAmt');
            Route::post('paymentDate', [TripBookingController::class, 'paymentDate'])->name('paymentDate');
            Route::post('costs', [TripBookingController::class, 'costs'])->name('costs');
            Route::post('credit-note-amt-add', [TripBookingController::class, 'creditNoteAmt'])->name('credit-note-amt-add');
            Route::post('extraServices', [TripBookingController::class, 'extraServices'])->name('extraServices');
            Route::post('taxRequired', [TripBookingController::class, 'taxRequired'])->name('taxRequired');
            Route::post('formSubmited', [TripBookingController::class, 'formSubmited'])->name('formSubmited');
            Route::post('summary', [TripBookingController::class, 'summary'])->name('summary');
            Route::post('add-payment', [TripBookingController::class, 'addPayment'])->name('add-payment');
            Route::post('edit-payment', [TripBookingController::class, 'editPayment'])->name('edit-payment');
            Route::post('update-part-payment', [TripBookingController::class, 'updatePartPayment'])->name('update-part-payment');
            Route::post('cancel-booking', [TripBookingController::class, 'cancelBooking'])->name('cancel-booking');
            Route::post('upload-invoice', [TripBookingController::class, 'uploadInvoice'])->name('upload-invoice');
            Route::post('upload-invoice-action', [TripBookingController::class, 'uploadInvoiceAction'])->name('upload-invoice-action');
            Route::post('upload-multi-invoice-action', [TripBookingController::class, 'uploadMultipleInvoiceAction'])->name('upload-multi-invoice-action');
            Route::post('view-customer-details', [TripBookingController::class, 'customerDetails'])->name('view-customer-details');
            Route::post('upload-media', [TripBookingController::class, 'uploadMedia'])->name('upload-media');
            Route::post('delete-media', [TripBookingController::class, 'deleteMedia'])->name('delete-media');
            Route::post('correction-booking', [TripBookingController::class, 'correctionBooking'])->name('correction-booking');
            Route::post('schPayment', [TripBookingController::class, 'schPayment'])->name('schPayment');
             Route::post('ischeckedStore', [TripBookingController::class, 'ischeckedStore'])->name('ischeckedStore');
            Route::post('multipe-payment', [TripBookingController::class, 'multiplePayment'])->name('multipe-payment');
        });
    });

    Route::group(['prefix' => 'roles_permission', 'as' => 'roles_permission.'], function () {

        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('get', [RoleController::class, 'get'])->name('get');
        Route::get('add', [RoleController::class, 'create'])->name('add');
        Route::post('store', [RoleController::class, 'store'])->name('store');
        Route::get('edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::post('update', [RoleController::class, 'update'])->name('update');
        Route::get('delete/{id}', [RoleController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('get', [StaffController::class, 'get'])->name('get');
        Route::get('add', [StaffController::class, 'create'])->name('add');
        Route::post('store', [StaffController::class, 'store'])->name('store');
        Route::get('edit/{id}', [StaffController::class, 'edit'])->name('edit');
        Route::post('update', [StaffController::class, 'update'])->name('update');
        Route::get('delete/{id}', [StaffController::class, 'destroy'])->name('delete');
        Route::get('view/{id}', [StaffController::class, 'view'])->name('view');
        Route::get('activity/{id}', [StaffController::class, 'activityPage'])->name('activity');
        Route::get('activity-get', [StaffController::class, 'activity'])->name('activity-get');

        Route::post('import', [StaffController::class, 'import'])->name('import');
        Route::get('export-sample', [StaffController::class, 'exportSample'])->name('export-sample');
        Route::get('export', [StaffController::class, 'export'])->name('export');
    });

    Route::group(['prefix' => 'sustainability', 'as' => 'sustainability.'], function () {
        Route::get('/', [SustainabilityController::class, 'index'])->name('index');
        Route::get('get', [SustainabilityController::class, 'get'])->name('get');
        Route::get('view/{id}', [SustainabilityController::class, 'view'])->name('view');
        Route::get('sustainabilityList', [SustainabilityController::class, 'sustainabilityList'])->name('sustainabilityList');
        Route::get('export', [SustainabilityController::class, 'export'])->name('export');
    });


    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::get('check-expense', [AccountsController::class, 'checkExpense'])->name('check-expense');
        Route::post('get-check-expense', [AccountsController::class, 'getCheckExpense'])->name('get-check-expense');
        Route::get('export-check-expense', [AccountsController::class, 'exportCheckExpense'])->name('export-check-expense');

        Route::get('payment-received', [AccountsController::class, 'paymentReceived'])->name('payment-received');
        Route::post('get-payment-received', [AccountsController::class, 'getPaymentReceived'])->name('get-payment-received');
        Route::get('export-payment-received', [AccountsController::class, 'exportPaymentReceived'])->name('export-payment-received');
    });

});



Route::get('clear', function() {
    \Artisan::call('optimize:clear');
});


require __DIR__.'/auth.php';
