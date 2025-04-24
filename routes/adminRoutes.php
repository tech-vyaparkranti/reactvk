<?php

use App\Http\Controllers\AboutServiceController;
use App\Models\PackageMaster;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OurGuestController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EnquiryFormController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\DownloadFileController;
use App\Http\Controllers\PackageMasterController;
use App\Http\Controllers\PackageCategoryController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\OurServicesModelController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HeroBannerController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\GrowthJourneyController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\WhyChooseUsController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\GetQuotesController;
use App\Http\Controllers\ApplyPositionController;
use App\Http\Controllers\BlogReviewController;
use App\Http\Controllers\VideoGalleryController;
use App\Http\Controllers\ChooseNowCardController;
use App\Http\Controllers\FaqController;



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


Route::get("view-partner", [PartnerController::class, "viewPartner"])->name("viewPartner");
Route::post("save-partner", [PartnerController::class, "savePartner"])->name("savePartner");
Route::post("get-partner", [PartnerController::class, "getPartner"])->name("getPartner");

Route::get("view-hero-banner", [HeroBannerController::class, "viewBanner"])->name("viewHeroBanner");
Route::post("save-hero-banner", [HeroBannerController::class, "saveBanner"])->name("saveHeroBanner");
Route::post("get-hero-banner", [HeroBannerController::class, "getBanner"])->name("getHeroBanner");


Route::get("view-testimonial", [TestimonialController::class, "viewTestimonial"])->name("viewTestimonial");
Route::post("save-testimonial", [TestimonialController::class, "saveTestimonial"])->name("saveTestimonial");
Route::post("get-testimonial", [TestimonialController::class, "getTestimonial"])->name("getTestimonial");

Route::get("view-gallery", [GalleryController::class, "viewGallery"])->name("viewGallery");
Route::post("save-gallery", [GalleryController::class, "saveGallery"])->name("saveGallery");
Route::post("get-gallery", [GalleryController::class, "getGallery"])->name("getGallery");

Route::get("view-technology", [TechnologyController::class, "viewTechnology"])->name("viewTechnology");
Route::post("save-technology", [TechnologyController::class, "saveTechnology"])->name("saveTechnology");
Route::post("get-technology", [TechnologyController::class, "getTechnology"])->name("getTechnology");

Route::get("view-vacancy", [VacancyController::class, "viewVacancy"])->name("viewVacancy");
Route::post("save-vacancy", [VacancyController::class, "saveVacancy"])->name("saveVacancy");
Route::post("get-vacancy", [VacancyController::class, "getVacancy"])->name("getVacancy");


Route::get("view-growth", [GrowthJourneyController::class, "viewGrowthJourney"])->name("viewGrowth");
Route::post("save-growth", [GrowthJourneyController::class, "saveGrowthJourney"])->name("saveGrowth");
Route::post("get-growth", [GrowthJourneyController::class, "getGrowthJourney"])->name("getGrowth");

Route::get("subscribe-page", [SubscribeController::class, "viewSubscribe"])->name("viewSubscribe");
Route::post("subscribe-data-table", [SubscribeController::class, "SubscribeDataTable"])->name("SubscribeDataTable");

Route::get("seo-page", [SeoController::class, "viewSeo"])->name("viewSeo");
Route::post("seo-data-table", [SeoController::class, "seoDataTable"])->name("seoDataTable");

Route::get("view-choose", [WhyChooseUsController::class, "viewWhyChooseUs"])->name("viewWhyChooseUs");
Route::post("save-choose", [WhyChooseUsController::class, "saveWhyChooseUs"])->name("saveWhyChooseUs");
Route::post("get-choose", [WhyChooseUsController::class, "chooseData"])->name("chooseData");

Route::get("view-package", [PackageController::class, "viewPackages"])->name("viewPackages");
Route::post("save-package", [PackageController::class, "savePackages"])->name("savePackages");
Route::post("get-package", [PackageController::class, "packageData"])->name("packageData");

Route::get("quotes-page", [GetQuotesController::class, "viewQuotes"])->name("viewQuotes");
Route::post("quotes-data-table", [GetQuotesController::class, "quotesDataTable"])->name("quotesDataTable");

Route::get("applied-position-page", [ApplyPositionController::class, "viewApplied"])->name("viewApplied");
Route::post("applied-data-table", [ApplyPositionController::class, "appliedDataTable"])->name("appliedDataTable");

Route::get("review-page", [BlogReviewController::class, "viewReview"])->name("viewReview");
Route::post("review-data-table", [BlogReviewController::class, "reviewDataTable"])->name("reviewDataTable");
Route::post("change-status-review", [BlogReviewController::class, "changeReview"])->name("changeReview");

Route::get("show-video", [VideoGalleryController::class, "viewVideoGallery"])->name("viewVideoGallery");
Route::post("save-video-gallery", [VideoGalleryController::class, "saveVideoGallery"])->name("saveVideoGallery");
Route::post("get-video", [VideoGalleryController::class, "getVideoGallery"])->name("getVideoGallery");

Route::get("show-choose-card", [ChooseNowCardController::class, "viewChooseNowCard"])->name("viewChooseNowCard");
Route::post("save-choose-card", [ChooseNowCardController::class, "saveChooseNowCard"])->name("saveChooseNowCard");
Route::post("get-choose-card", [ChooseNowCardController::class, "getChooseNowCard"])->name("getChooseNowCard");

Route::get("show-faq", [FaqController::class, "viewFaq"])->name("viewFaq");
Route::post("save-faq", [FaqController::class, "saveFaq"])->name("saveFaq");
Route::post("get-faq", [FaqController::class, "getFaq"])->name("getFaq");


});

