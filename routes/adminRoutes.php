<?php

use App\Http\Controllers\AboutServiceController;
use App\Models\PackageMaster;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OurGuestController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EnquiryFormController;
use App\Http\Controllers\DownloadFileController;
use App\Http\Controllers\PackageMasterController;
use App\Http\Controllers\PackageCategoryController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\OurServicesModelController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get("login",[AdminController::class,"Login"])->name("login");
Route::post("AdminUserLogin",[AdminController::class,"AdminLoginUser"])->name("AdminLogin");
Route::get("getmenu-items",[HomePageController::class,"getMenu"]);
//pages

Route::middleware(['auth'])->group(function () {
    Route::get("/new-dashboard",[AdminController::class,"dashboard"])->name("new-dashboard");
    
    Route::get("manage-gallery",[AdminController::class,"manageGallery"])->name("manageGallery");
    Route::post("addGalleryItems",[AdminController::class,"addGalleryItems"])->name("addGalleryItems");
    Route::post("addGalleryDataTable",[AdminController::class,"addGalleryDataTable"])->name("addGalleryDataTable");

 
    Route::get("our-services-master", [OurServicesModelController::class, "viewOurServicesMaster"])->name("viewOurServicesMaster");
    Route::post("save-our-services", [OurServicesModelController::class, "saveOurServicesMaster"])->name("saveOurServicesMaster");
    Route::post("our-services-data", [OurServicesModelController::class, "ourServicesData"])->name("ourServicesData");
    Route::match(['get', 'post'], 'logout',  [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    //package Master
    
    Route::resource('packageMaster', PackageMasterController::class);
    Route::post("package-master-data-table",[PackageMasterController::class,"dataTable"])->name("packageMasterDataTable");
    Route::post("add-city",[PackageMasterController::class,"addCity"])->name("add-city");
    Route::post("enable-disable",[PackageMasterController::class,"enableDisablePackage"])->name("enableDisablePackage");

    //contactUsData
    Route::get("contact-us-admin-page", [ContactUsController::class, "contactUsAdminPage"])->name("contactUsData");
    Route::post("contact-us-data-table", [ContactUsController::class, "contactUsDataTable"])->name("contactUsDataTable");

    //enquiryData
    Route::get("enquiry-admin-page", [EnquiryFormController::class, "enquiryAdminPage"])->name("enquiryAdminPage");
    Route::post("enquiry-data-table", [EnquiryFormController::class, "enquiryDataTable"])->name("enquiryDataTable");

    Route::get("manage-package-categories",[PackageCategoryController::class,"managePackageCategories"])->name("managePackageCategories");
    Route::post("add-package-category-data",[PackageCategoryController::class,"addPackageCategoryData"])->name("addPackageCategoryData");
    Route::post("packageCategoryData",[PackageCategoryController::class,"packageCategoryData"])->name("packageCategoryData");
    
    Route::get("manage-news-letter-data",[NewsLetterController::class,"manageNewsLetterAdmin"])->name("manageNewsLetterData");
    Route::post("get-news-letter-data",[NewsLetterController::class,"getNewsLetterData"])->name("getNewsLetterData");

Route::get("blog-admin", [BlogController::class, "blogSlider"])->name("blogSlider");
Route::post("save-blog", [BlogController::class, "saveBlog"])->name("saveBlog");
Route::post("blog-data", [BlogController::class, "blogData"])->name("blogData");

Route::get("add-web-site-elements", [WebSiteElementsController::class, "addWebSiteElements"])->name("webSiteElements");
Route::post("save-web-site-element", [WebSiteElementsController::class, "saveWebSiteElement"])->name("saveWebSiteElement");
Route::post("web-site-elements-data", [WebSiteElementsController::class, "getWebElementsData"])->name("webSiteElementsData");

Route::get("view-team-info", [TeamInfoController::class, "viewTeam"])->name("viewTeamInfo");
Route::post("save-team-info", [TeamInfoController::class, "saveTeamInfo"])->name("saveTeamInfo");
Route::post("get-team-info", [TeamInfoController::class, "getTeamInfo"])->name("getTeamInfo");


Route::get("view-about-info", [AboutUsController::class, "viewAboutUs"])->name("viewAboutInfo");
Route::post("save-about-info", [AboutUsController::class, "saveAboutUs"])->name("saveAbout");
Route::post("get-about-info", [AboutUsController::class, "aboutData"])->name("getAboutData");


});

