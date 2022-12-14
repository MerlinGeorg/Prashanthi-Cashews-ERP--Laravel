<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StaffController;

//A D M I N
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockyardController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\PackageCenterController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ShipperDetailsController;
use App\Http\Controllers\Factory\BormaStockController;
use App\Http\Controllers\Stockyard\InwardRcnController;
use App\Http\Controllers\Factory\BoilingStockController;
use App\Http\Controllers\Factory\CuttingStockController;
use App\Http\Controllers\Stockyard\OutwardRcnController;
use App\Http\Controllers\Factory\SizeringStockController;
use App\Http\Controllers\Factory\FactoryRcnStockController;
use App\Http\Controllers\Factory\FactoryRcnInwardController;
use App\Http\Controllers\Stockyard\StockyardRcnStockController;

//P U B L I C
use App\Http\Controllers\Factory\EmployeeController as FactoryEmployeeController;
use App\Http\Controllers\Stockyard\EmployeeController as StockyardEmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/* Route Dashboards */
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('analytics', [DashboardController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
    Route::get('ecommerce', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
});
/* Route Dashboards */

/* Route Authentication Pages */
Route::group(['prefix' => 'auth'], function () {
    Route::get('login-basic', [AuthenticationController::class, 'login_basic'])->name('auth-login-basic');
    Route::get('login-cover', [AuthenticationController::class, 'login_cover'])->name('auth-login-cover');
    Route::get('register-basic', [AuthenticationController::class, 'register_basic'])->name('auth-register-basic');
    Route::get('register-cover', [AuthenticationController::class, 'register_cover'])->name('auth-register-cover');
    Route::get('forgot-password-basic', [AuthenticationController::class, 'forgot_password_basic'])->name('auth-forgot-password-basic');
    Route::get('forgot-password-cover', [AuthenticationController::class, 'forgot_password_cover'])->name('auth-forgot-password-cover');
    Route::get('reset-password-basic', [AuthenticationController::class, 'reset_password_basic'])->name('auth-reset-password-basic');
    Route::get('reset-password-cover', [AuthenticationController::class, 'reset_password_cover'])->name('auth-reset-password-cover');
    Route::get('verify-email-basic', [AuthenticationController::class, 'verify_email_basic'])->name('auth-verify-email-basic');
    Route::get('verify-email-cover', [AuthenticationController::class, 'verify_email_cover'])->name('auth-verify-email-cover');
    Route::get('two-steps-basic', [AuthenticationController::class, 'two_steps_basic'])->name('auth-two-steps-basic');
    Route::get('two-steps-cover', [AuthenticationController::class, 'two_steps_cover'])->name('auth-two-steps-cover');
    Route::get('register-multisteps', [AuthenticationController::class, 'register_multi_steps'])->name('auth-register-multisteps');
    Route::get('lock-screen', [AuthenticationController::class, 'lock_screen'])->name('auth-lock_screen');
});
/* Route Authentication Pages */

/* Public Site Route */
Route::get('/', function () {return redirect('/admin');})->name('dashborad');
Route::get('/staff/register', [StaffController::class, 'create'])->name('staff-register');
Route::post('/staff/upload', [StaffController::class, 'upload'])->name('staff-upload');
Route::post('/staff/upload-profile-image', [StaffController::class, 'uploadProfileImage'])->name('staff-upload-profile-image');
Route::post('/staff', [StaffController::class, 'store'])->name('staff-submit');
Route::get('/staff/work-locations/{type}', [StaffController::class, 'workLocations']);
Route::get('/staff/roles/{work_location_type}', [StaffController::class, 'roles']);

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::get('forgot', [LoginController::class, 'showForgotForm'])->name('admin.forgot');
    Route::post('forgot', [LoginController::class, 'forgot'])->name('admin.forgot.submit');
    Route::get('reset/{token}', [LoginController::class, 'showResetForm'])->name('admin.reset');
    Route::post('reset', [LoginController::class, 'reset'])->name('admin.reset.submit');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
});

/* Route Admin */
Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'checkstatus']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('staff/list', [UserController::class, 'listUsers'])->name('admin.staff.list');
    Route::get('staff/permission/{id}', [UserController::class, 'userPermission'])->name('admin.staff.permission');
    Route::post('staff/permission', [UserController::class, 'submitUserPermission'])->name('admin.staff.permission.submit');
    Route::get('staff/change-password/{slug}', [UserController::class, 'changePassword'])->name('admin.staff.password');
    Route::post('staff/change-password', [UserController::class, 'submitChangePassword'])->name('admin.staff.password.submit');
    Route::resource('staff', UserController::class, [
        'names' => [
            'create' => 'admin.staff.create',
            'edit' => 'admin.staff.edit',
            'show' => 'admin.staff.show',
            'index' => 'admin.staff',
        ],
    ])->parameters([
        'staff' => 'staff:slug',
    ]);

    Route::get('office/list', [OfficeController::class, 'listOffice']);
    Route::resource('office', OfficeController::class, [
        'names' => [
            'create' => 'admin.office.create',
            'store' => 'admin.office.store',
            'edit' => 'admin.office.edit',
            'show' => 'admin.office.show',
            'index' => 'admin.office',
        ],
    ])->parameters([
        'office' => 'office:slug',
    ]);

    Route::get('stockyard/list', [StockyardController::class, 'listStockyard']);
    Route::get('stockyard/warehouses/{stockyard_slug?}', [StockyardController::class, 'getWarehouses']);

    Route::resource('stockyard', StockyardController::class, [
        'names' => [
            'create' => 'admin.stockyard.create',
            'store' => 'admin.stockyard.store',
            'edit' => 'admin.stockyard.edit',
            'show' => 'admin.stockyard.show',
            'index' => 'admin.stockyard',
        ],
    ])->parameters([
        'stockyard' => 'stockyard:slug',
    ]);

    Route::get('factory/list', [FactoryController::class, 'listFactory']);
    Route::resource('factory', FactoryController::class, [
        'names' => [
            'create' => 'admin.factory.create',
            'store' => 'admin.factory.store',
            'edit' => 'admin.factory.edit',
            'show' => 'admin.factory.show',
            'index' => 'admin.factory',
        ],
    ])->parameters([
        'factory' => 'factory:slug',
    ]);

    Route::get('package-center/list', [PackageCenterController::class, 'listPackageCenter']);
    Route::resource('package-center', PackageCenterController::class, [
        'names' => [
            'create' => 'admin.package-center.create',
            'store' => 'admin.package-center.store',
            'edit' => 'admin.package-center.edit',
            'show' => 'admin.package-center.show',
            'index' => 'admin.package-center',
        ],
    ])->parameters([
        'package-center' => 'package-center:slug',
    ]);

    Route::get('shipper-details/list', [ShipperDetailsController::class, 'listShipperDetails']);
    Route::resource('shipper-details', ShipperDetailsController::class, [
        'names' => [
            'create' => 'admin.shipper-details.create',
            'store' => 'admin.shipper-details.store',
            'edit' => 'admin.shipper-details.edit',
            'show' => 'admin.shipper-details.show',
            'index' => 'admin.shipper-details',
        ],
    ])->parameters([
        'shipper-details' => 'shipper-details:slug',
    ]);

    Route::get('state_account/{state}', [StockyardController::class, 'stateAccount']);
    Route::get('account/list', [AccountController::class, 'listAccount']);
    Route::resource('account', AccountController::class, [
        'names' => [
            'create' => 'admin.account.create',
            'store' => 'admin.account.store',
            'edit' => 'admin.account.edit',
            'show' => 'admin.account.show',
            'index' => 'admin.account',
        ],
    ])->parameters([
        'account' => 'account:slug',
    ]);

    Route::get('employee/list', [EmployeeController::class, 'listUsers'])->name('admin.employee.list');
    Route::post('employee/upload', [EmployeeController::class, 'upload'])->name('staff-upload');
    Route::resource('employee', EmployeeController::class, [
        'names' => [
            'create' => 'admin.employee.create',
            'edit' => 'admin.employee.edit',
            'show' => 'admin.employee.show',
            'index' => 'admin.employee',
        ],
    ])->parameters([
        'employee' => 'employee:slug',
    ]);

    Route::get('role/list', [RoleController::class, 'listRoles'])->name('admin.role.list');
    Route::get('role/privileges', [RoleController::class, 'privileges'])->name('admin.role.privileges');
    Route::get('role/permissions/{slug}', [RoleController::class, 'editPrivileges'])->name('admin.role.privileges.edit');
    Route::post('role/privileges/{slug}', [RoleController::class, 'savePrivileges'])->name('admin.role.privileges.save');
    Route::resource('role', RoleController::class, [
        'names' => [
            'create' => 'admin.role.create',
            'store' => 'admin.role.store',
            'edit' => 'admin.role.edit',
            'show' => 'admin.role.show',
            'index' => 'admin.role',
        ],
    ])->parameters([
        'role' => 'role:slug',
    ]);

    Route::get('resource/list', [ResourceController::class, 'listResources'])->name('admin.resource.list');
    Route::resource('resource', ResourceController::class, [
        'names' => [
            'create' => 'admin.resource.create',
            'store' => 'admin.resource.store',
            'edit' => 'admin.resource.edit',
            'show' => 'admin.resource.show',
            'index' => 'admin.resource',
        ],
    ])->parameters([
        'resource' => 'resource:slug',
    ]);

    Route::get('permission/list', [PermissionController::class, 'listPermissions'])->name('admin.permission.list');
    Route::resource('permission', PermissionController::class, [
        'names' => [
            'create' => 'admin.permission.create',
            'store' => 'admin.permission.store',
            'edit' => 'admin.permission.edit',
            'show' => 'admin.permission.show',
            'index' => 'admin.permission',
        ],
    ])->parameters([
        'permission' => 'permission:slug',
    ]);

    Route::get('usergroup/list', [UserGroupController::class, 'listUserGroups'])->name('admin.usergroup.list');
    Route::resource('usergroup', UserGroupController::class, [
        'names' => [
            'create' => 'admin.usergroup.create',
            'store' => 'admin.usergroup.store',
            'edit' => 'admin.usergroup.edit',
            'index' => 'admin.usergroup',
            'show' => 'admin.usergroup.show',
        ],
    ])->parameters([
        'usergroup' => 'usergroup:slug',
    ]);

    Route::get('jobcategory/list', [JobCategoryController::class, 'listJobCategories'])->name('admin.jobcategory.list');
    Route::resource('jobcategory', JobCategoryController::class, [
        'names' => [
            'create' => 'admin.jobcategory.create',
            'store' => 'admin.jobcategory.store',
            'edit' => 'admin.jobcategory.edit',
            'index' => 'admin.jobcategory',
            'show' => 'admin.jobcategory.show',
        ],
    ])->parameters([
        'jobcategory' => 'jobcategory:slug',
    ]);

});

/* Route Admin */

/* Route Stockyard */
Route::group(['prefix' => 'stockyard', 'middleware' => 'admin'], function () {
    //RCN-Stock
    Route::resource('rcn-stock', StockyardRcnStockController::class, [
        'names' => [
          
            'create' => 'stockyard.rcn-stock.create',
            'store' => 'stockyard.rcn-stock.store',

          
            'edit' => 'stockyard.rcn-stock.edit',
            'update' => 'stockyard.rcn-stock.update',
            'index' => 'stockyard.rcn-stock',
            'show' => 'stockyard.rcn-stock.show',
           
        ],
    ])->parameters([
        'rcn-stock' => 'rcn-stock:slug',
    ]);

    Route::get('list-by-stockyardMixComp/{factoryslug}/{stockyard_rcn_stock_slug}', [StockyardRcnStockController::class, 'StockyardListMixCompine']);
    Route::get('stockyard_rcn_stock/list-by-rcnmark/{factoryslug}/{mark}', [StockyardRcnStockController::class, 'listStockByFactoryMark']);


    Route::get('rcn-stocks/{slug}/mix_edit', [StockyardRcnStockController::class, 'edit_mix'])->name('stockyard.rcn-stock.edit_mix');
    Route::put('rcn-stocks/updateMix/{id}', [StockyardRcnStockController::class, 'updateMix'])->name('stockyard.rcn-stocks.updateMix');
    Route::get('rcn-stockz-mix/{slug}', [StockyardRcnStockController::class, 'view_mix'])->name('stockyard.rcn-stockz.mix');

    //Route::get('inward-rcn/add/{stockyard}/{stockyardrcn}', [InwardRcnController::class, 'addInwardRcn'])->name('stockyard.inward-rcn.add');

   Route::get('rcn-stock-create-mix', [StockyardRcnStockController::class, 'create_mix'])->name('stockyard.rcn-stock.create_mix');
 
   Route::post('rcn-stock-store-mix', [StockyardRcnStockController::class, 'store_mix'])->name('stockyard.rcn-stock.store_mix');




   Route::get('rcn-stocks/{slug}/compine_edit', [StockyardRcnStockController::class, 'edit_compine'])->name('stockyard.rcn-stock.edit_compine');
   Route::put('rcn-stocks/updateCompine/{id}', [StockyardRcnStockController::class, 'updateCompine'])->name('stockyard.rcn-stocks.updateCompine');
   Route::get('rcn-stockz-compine/{slug}', [StockyardRcnStockController::class, 'view_compine'])->name('stockyard.rcn-stockz.compine');
  Route::get('rcn-stock-create-compine', [StockyardRcnStockController::class, 'create_compine'])->name('stockyard.rcn-stock.create_compine');
  Route::post('rcn-stock-store-compine', [StockyardRcnStockController::class, 'store_compine'])->name('stockyard.rcn-stock.store_compine');




     Route::get('rcn-stocks/{slug}/split_edit', [StockyardRcnStockController::class, 'edit_split'])->name('stockyard.rcn-stock.edit_split');
     Route::put('rcn-stocks/updateSplit/{id}', [StockyardRcnStockController::class, 'updateSplit'])->name('stockyard.rcn-stocks.updateSplit');
     Route::get('rcn-stockz/{slug}', [StockyardRcnStockController::class, 'view_split'])->name('stockyard.rcn-stockz');

     //Route::get('inward-rcn/add/{stockyard}/{stockyardrcn}', [InwardRcnController::class, 'addInwardRcn'])->name('stockyard.inward-rcn.add');

    Route::get('rcn-stock-create-split', [StockyardRcnStockController::class, 'create_splitz'])->name('stockyard.rcn-stock.create_splitz');
  
    Route::post('rcn-stock-store-split', [StockyardRcnStockController::class, 'store_splitz'])->name('stockyard.rcn-stock.store_splitz');

    Route::get('rcn-stock-list', [StockyardRcnStockController::class, 'listStockyardRcn'])->name('stockyard.rcn-stock.list');
    Route::post('rcn-stock/sub-account-list', [StockyardRcnStockController::class, 'listStockyardRcnSubAccounts']);

    //Inward-RCN
    Route::get('inward-rcn', [InwardRcnController::class, 'createInwardRcn'])->name('stockyard.inward-rcn.create');
    
    Route::post('inward-rcn/stockyard-rcn-stock-list/split', [InwardRcnController::class, 'listStockyardRcnStocksForSplit'])->name('stockyard.inward-rcn.stockyard.list.split');

    Route::post('inward-rcn/stockyard-rcn-stock-list', [InwardRcnController::class, 'listStockyardRcnStocks'])->name('stockyard.inward-rcn.stockyard.list');
    Route::get('inward-rcn/add/{stockyard}/{stockyardrcn}', [InwardRcnController::class, 'addInwardRcn'])->name('stockyard.inward-rcn.add');
    Route::post('inward-rcn/save-rcn', [InwardRcnController::class, 'saveRcn'])->name('stockyard.inward-rcn.save');
    Route::get('inward-rcn/list/{stockyard}/{stockyardrcn}', [InwardRcnController::class, 'listInwardRcn'])->name('stockyard.inward-rcn.list');
    Route::get('inward-rcn/{slug}/edit-rcn', [InwardRcnController::class, 'editRcn'])->name('stockyard.inward-rcn.edit');
    Route::post('inward-rcn/update-rcn/{id}', [InwardRcnController::class, 'updateRcn'])->name('stockyard.inward-rcn.update');
    Route::get('inward-rcn/{slug}/view-rcn', [InwardRcnController::class, 'viewRcn'])->name('stockyard.inward-rcn.show');
    Route::delete('inward-rcn/{id}/delete-rcn', [InwardRcnController::class, 'deleteRcn'])->name('stockyard.inward-rcn.delete');

    //Outward-RCN
    Route::resource('outward-rcn', OutwardRcnController::class, [
        'names' => [
            'create' => 'stockyard.outward-rcn.create',
            'store' => 'stockyard.outward-rcn.store',
            'edit' => 'stockyard.outward-rcn.edit',
            'show' => 'stockyard.outward-rcn.show',
            'index' => 'stockyard.outward-rcn',
        ],
    ])->parameters([
        'outward-rcn' => 'outward-rcn:slug',
    ]);

    Route::get('outward-rcn-list', [OutwardRcnController::class, 'listStockyardOutwardRcn']);

    Route::get('employee/list', [StockyardEmployeeController::class, 'listUsers'])->name('stockyard.employee.list');
    Route::post('employee/upload', [StockyardEmployeeController::class, 'upload'])->name('stockyard.staff-upload');
    Route::resource('employee', StockyardEmployeeController::class, [
        'names' => [
            'create' => 'stockyard.employee.create',
            'edit' => 'stockyard.employee.edit',
            'show' => 'stockyard.employee.show',
            'index' => 'stockyard.employee',
        ],
    ])->parameters([
        'employee' => 'employee:slug',
    ]);
});

Route::group(['prefix' => 'factory', 'middleware' => 'admin'], function () {
    Route::get('employee/list', [FactoryEmployeeController::class, 'listUsers'])->name('factory.employee.list');
    Route::post('employee/upload', [FactoryEmployeeController::class, 'upload'])->name('staff-upload');
    Route::resource('employee', FactoryEmployeeController::class, [
        'names' => [
            'create' => 'factory.employee.create',
            'edit' => 'factory.employee.edit',
            'show' => 'factory.employee.show',
            'index' => 'factory.employee',
        ],
    ])->parameters([
        'employee' => 'employee:slug',
    ]);

    Route::get('stock/sizering/list', [SizeringStockController::class, 'listSizering']);
    Route::get('stock/sizering/list-by-factory/{factoryslug}/{factory_stock_slug}', [SizeringStockController::class, 'listSizeringByFactory']);
    Route::get('stock/sizering/stock-by-factory/{factoryslug}', [SizeringStockController::class, 'listStockByFactory']);
    Route::get('stock/sizering/stock-by-factorys/{factoryslug}/{mark}', [SizeringStockController::class, 'listStockByFactoryMark']);




    Route::resource('stock/sizering', SizeringStockController::class, [
        'names' => [
            'create' => 'factory.sizering.create',
            'store' => 'factory.sizering.store',
            'edit' => 'factory.sizering.edit',
            'index' => 'factory.sizering',
            'show' => 'factory.sizering.show',
        ],
    ])->parameters([
        'sizering' => 'sizering:slug',
    ]);

    Route::get('stock/boiling/list', [BoilingStockController::class, 'listBoiling']);
    Route::get('stock/boiling/list-by-factory/{factoryslug}/{stockyard_rcn_stock_slug}', [BoilingStockController::class, 'listBoilingByFactory']);
    Route::get('stock/boiling/list-by-stockyard/{factoryslug}/{stockyard_rcn_stock_slug}', [BoilingStockController::class, 'listBoilingByStockyard']);
    Route::get('stock/boiling/list-by-stockyardMixComp/{factoryslug}/{stockyard_rcn_stock_slug}', [BoilingStockController::class, 'listBoilingByStockyardMixCompine']);


    
    Route::get('stock/boiling/stock-by-factory/{factoryslug}', [BoilingStockController::class, 'listStockByFactory']);
    Route::resource('stock/boiling', BoilingStockController::class, [
        'names' => [
            'create' => 'factory.boiling.create',
            'store' => 'factory.boiling.store',
            'edit' => 'factory.boiling.edit',
            'index' => 'factory.boiling',
            'show' => 'factory.boiling.show',
        ],
    ])->parameters([
        'boiling' => 'boiling:slug',
    ]);

    Route::get('stock/cutting/list', [CuttingStockController::class, 'listCutting']);
    Route::get('stock/cutting/stock-by-factory/{factoryslug}', [CuttingStockController::class, 'listStockByFactory']);
    Route::resource('stock/cutting', CuttingStockController::class, [
        'names' => [
            'create' => 'factory.cutting.create',
            'store' => 'factory.cutting.store',
            'edit' => 'factory.cutting.edit',
            'index' => 'factory.cutting',
            'update'=>'factory.cutting.update'
        ]
    ])->parameters([
        'cutting' => 'cutting:slug',
    ]);
    Route::any('stocks/cuttings/delete/{id}', [CuttingStockController::class, 'destroy'])->name('stock.cutting.delete');

    //Factory-Inward
    Route::resource('factory-rcn-inward', FactoryRcnInwardController::class, [
        'names' => [
            'create' => 'factory.factory-rcn-inward.create',
            'store' => 'factory.factory-rcn-inward.store',
            'edit' => 'factory.factory-rcn-inward.edit',
            'show' => 'factory.factory-rcn-inward.show',
            'index' => 'factory.factory-rcn-inward',
        ],
    ]);
    Route::get('factory-rcn-inward-list', [FactoryRcnInwardController::class, 'listFactoryRcnInwards'])->name('factory.factory-inward-list');
    Route::post('factory-rcn-inward/get-rcn-stock', [FactoryRcnInwardController::class, 'getRcnStock']);

    //Factory Stock
    Route::resource('rcn-stock', FactoryRcnStockController::class, [
        'names' => [
            'create' => 'factory.rcn-stock.create',
            'store' => 'factory.rcn-stock.store',
            'show' => 'factory.rcn-stock.show',
            'edit' => 'factory.rcn-stock.edit',
            'index' => 'factory.rcn-stock',
        ],
    ]);
    Route::get('factory-rcn-stock-list', [FactoryRcnStockController::class, 'listFactoryRcnStocks']);

    //Borma Stock
    Route::resource('stock/borma', BormaStockController::class, [
        'names' => [
            'create' => 'factory.borma.create',
            'store' => 'factory.borma.store',
            'show' => 'factory.borma.show',
            'edit' => 'factory.borma.edit',
            'index' => 'factory.borma',
        ],
    ])->parameters([
        'borma' => 'borma:slug',
    ]);
    Route::get('stock/borma-list', [BormaStockController::class, 'listBormaStocks']);
});

/** Office */
Route::resource('student', StudentController::class);

// Route::resource('office', OfficeController::class, [
//     'names' => [
//         'create' => 'admin.staff.create',
//         'edit' => 'admin.staff.edit',
//         'index' => 'admin.staff',
//         'store' => 'admin.staff.store'
//     ]
// ]);