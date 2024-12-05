<?php

use App\Http\Controllers\DebitCreditController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SetupPMController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\COAController;
use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Auth;

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

Route::get("customer-invoice/{id}", [InvoiceController::class, "invoice"])->name("invoices.invoice");

  // Start Payment

  Route::get("customer-payment/{invoice_id?}", [PaymentController::class, "createPayment"])->name("invoices.payment");
  Route::get('create-paypal-payment/{invoice_id?}', [PaymentController::class, 'CreatepPaypalPayment'])->name('create.paypal.payment');
  Route::get('payment-success', [PaymentController::class, 'capturePaypalPayment'])->name('payment.paypal.success');
  Route::get('payment-cancel', [PaymentController::class, 'cancelPaypalPayment'])->name('payment.paypal.cancel');


  Route::get('create-stripe-payment/{invoice_id?}', [PaymentController::class, 'CreatepStripePayment'])->name('create.stripe.payment');
  Route::post('create-paypal-payment', [PaymentController::class, 'stripepaymentprocess'])->name('payment.stripe');

Route::get('/', function () {
  return view('auth.login');
});

// Change file
Route::get('/google-calendar/connect', [GoogleCalendarController::class, 'connect']);
Route::get('/signup', [RegisterController::class, 'register_form'])->name('signup');
Route::get('logout', [LoginController::class, 'logout']);
Route::get('account/verify/{token}', [LoginController::class, 'verifyAccount'])->name('user.verify');
// Route::get('/', [HomeController::class,'index']);
Route::get('/detail/{id}', [HomeController::class, 'product_detail'])->name('product.detail');

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::get('/change_password', [DashboardController::class, 'change_password'])->name('change_password');
  Route::post('/store_change_password', [DashboardController::class, 'store_change_password'])->name('store_change_password');
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
  Route::resource('roles', RoleController::class);
  Route::resource('shift', ShiftController::class);
  Route::resource('setup-pm', SetupPMController::class);

  Route::resource("tax", TaxController::class);






  // Satrt Leads
  Route::resource('leads', LeadsController::class);
  Route::get('leads/accepted/{id}', [LeadsController::class, 'LeadAccepted'])->name('leads.accepted');
  Route::get('pick', [LeadsController::class, 'LeadsPick'])->name('leads.pick');


  Route::get('leads/user/invoice', [LeadsController::class, 'LeadsUserInvoice'])->name('leads.invoice');
  // Route::post('leads/mark/convert', [LeadsController::class, 'LeadsMarkConvert'])->name('leads.mark.convert');
  // Route::get('leads/invoice/view/{id}', [LeadsController::class, 'LeadsInvoiceShow'])->name('leads.invoice.show');

  // End Leads

  // Route::get(' ', [LeadsController::class, 'LeadsUserInvoice'])->name('invoice.index');


  // Start Invoice
  Route::resource('invoices', InvoiceController::class);


  


  Route::get("customer-add/{lead_id?}", [InvoiceController::class, "addCustomer"])->name("invoices.client.add");
  // End Invoice






  // Route::post("customer-Payment",[PaymentController::class,"dopayment"])->name("invoice.dopayment");
  // End Payment

  // Start Company
  Route::resource('company', CompanyController::class);
  // End Invoice


  Route::resource('department', DepartmentController::class);
  Route::resource('permission', PermissionController::class);
  Route::resource('task', TaskController::class);
  Route::resource('client', ClientController::class);


  Route::resource('project', ProjectController::class);
  Route::get('project-assign/{id}', [ProjectController::class, "assign_project"])->name("project-assign");
  Route::POST('project-assign-update', [ProjectController::class, "assign_project_update"])->name("project-assign-update");
  Route::get('project-Board/{id}', [ProjectController::class, "kanban"])->name("project-kanban");
  Route::POST('project-Kanban-update', [ProjectController::class, "StatusUpdate"])->name("project-kanban-update");
  Route::get('project-conversation/{id}/{department_id}', [ProjectController::class, "projectConversation"])->name("project-conversation");


  Route::resource("conversation", ConversationController::class);
  Route::post("/livewire/message/comment", \App\Http\Livewire\Comments::class);
  // Route::post("/livewire/message/comment",\App\Http\Livewire\CommentForm::class);




  Route::resource('mail', MailController::class);
  Route::resource('leadStatus', LeadStatusController::class);
  Route::resource('leadSources', LeadSourcesController::class);
  Route::resource('package', PackagesController::class);

  Route::get('clients/fetch', [ClientController::class, 'assign_client'])->name('clients.fetch');
  Route::get('projects/fetch', [ProjectController::class, 'assign_project'])->name('projects.fetch');

  Route::resource('users', UserController::class);









  Route::get('user/fetch', [UserController::class, 'assign_user'])->name('user.fetch');
  Route::get('user/permission/{id}', [UserController::class, 'user_permission'])->name('users.permission');
  Route::post('user/permission/update/{id}', [UserController::class, 'user_permission_update'])->name('user.permission.update');

  Route::resource('packages', PackagesController::class);
  Route::resource('category', CategoryController::class);
  Route::resource('subcategory', SubCategoryController::class);
  Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.index');
  Route::post('/profile', [DashboardController::class, 'profileupdate'])->name('user.update');
  Route::resource('product', ProductController::class);
  Route::get('subcatories/{category}', [SubCategoryController::class, 'subcatories']);
  Route::get('product/{id}/images', [ProductController::class, 'images']);
  Route::post('product/{id}/images', [ProductController::class, 'postImages']);
  Route::get('product/image/{id}/delete', [ProductController::class, 'imgDelete']);
  Route::get('get/product', [ProductController::class, 'FetchProduct']);
  Route::resource('pages', PageController::class);
  Route::resource('sections', SectionController::class);
  Route::resource('general_setting', GeneralSettingController::class);
  Route::resource('orders', OrderController::class);




  Route::get("salesbycustomer", [ReportsController::class, "CustomerReport"])->name("report.customers");
  Route::get("companies-report", [ReportsController::class, "CompaniesReport"])->name("report.companies_report");
  Route::get("users-report", [ReportsController::class, "UserReport"])->name("report.users");






  // Start Finance
  // COA
  Route::resource('coa', COAController::class);

  Route::resource('transaction', DebitCreditController::class);



  //HRMS


  Route::resource('attendance', AttendanceController::class);
  Route::post('/upload/excel', [AttendanceController::class, 'import'])->name('upload.attandence');
  Route::resource('leaves', LeavesController::class);
  Route::resource('leaves-type', LeaveTypeController::class);
});


// Add cart
Route::post('addcart', [CheckoutController::class, 'addcart'])->name('addcart');
Route::get('ajaxcart', [CheckoutController::class, 'ajaxcart'])->name('cart.ajax');
Route::get('cart', [CheckoutController::class, 'cart'])->name('cart');
Route::post('updatecart', [CheckoutController::class, 'updatecart'])->name('updatecart');
Route::get('deletecart', [CheckoutController::class, 'deletecart'])->name('deletecart');
Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('post_checkout', [CheckoutController::class, 'post_checkout'])->name('post_checkout');

// Add Wishlist
Route::post('addwishlist', [CheckoutController::class, 'addwishlist'])->name('addwishlist');
