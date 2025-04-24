<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\OurServicesModelController;
use App\Http\Controllers\TestModelController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\HeroBannerController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\GrowthJourneyController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\WhyChooseUsController;
use App\Http\Controllers\ApplyPositionController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\GetQuotesController;
use App\Http\Controllers\BlogReviewController;
use App\Http\Controllers\VideoGalleryController;
use App\Http\Controllers\ChooseNowCardController;
use App\Http\Controllers\FaqController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home-elements',[WebSiteElementsController::class,'homeElements']);
Route::get('social-link',[WebSiteElementsController::class,'socialMedia']);

Route::get('get-blogs',[BlogController::class,'getBlogs']);
Route::get('blog-details/{id}',[BlogController::class,'blogDetails']);
Route::get('blog-banner',[BlogController::class,'blogBanner']);


Route::get('get-service',[OurServicesModelController::class,'getService']);
Route::get('service-details/{id}',[OurServicesModelController::class,'serviceDetail']);
Route::get('service-banner',[OurServicesModelController::class,'serviceBanner']);


// About Api 
Route::get('get-about',[AboutUsController::class,'getAbout']);
// Route::get('about-details/{id}',[AboutUsController::class,'aboutDetails']);

// team api
Route::get('get-team',[TeamInfoController::class,'getTeam']);
Route::get('other-team',[TeamInfoController::class,'otherTeam']);
Route::get('team-details/{id}',[TeamInfoController::class,'teamDetail']);

// Partner's api
Route::get('get-partner',[PartnerController::class,'getPartners']);
Route::get('partner-details/{id}',[PartnerController::class,'PartnerDetails']);

// hero Banners Api 
Route::get('get-banner',[HeroBannerController::class,'getHeroBanner']);
Route::get('banner-details/{id}',[HeroBannerController::class,'bannerDetails']);

// testimonial 
Route::get('get-testimonial',[TestimonialController::class,'testimonialData']);
Route::get('testimonial-details/{id}',[TestimonialController::class,'TestimonialDetails']);

// Gallryies Api
Route::get('get-gallery',[GalleryController::class,'GalleryData']);
Route::get('gallery-details/{id}',[GalleryController::class,'GalleryDetails']);

// technology api
Route::get('get-technology',[TechnologyController::class,'technologyData']);
Route::get('technology-details/{id}',[TechnologyController::class,'technologyDetails']);

// technology api
Route::get('get-vacancy',[VacancyController::class,'vacancyData']);
Route::get('vacancy-details/{id}',[VacancyController::class,'vacancyDetails']);

// growth journey api
Route::get('get-team-growth',[GrowthJourneyController::class,'GrowthJourneyData']);
Route::get('team-growth-details/{id}',[GrowthJourneyController::class,'GrowthJourneyDetails']);

// Contact us Api 
Route::post('save-contact-us',[ContactUsController::class,'saveContactUsDetails']);
Route::get('contact-element',[ContactUsController::class,'contactWebelement']);


// news and letter (subscribe ) api
Route::post('save-subscribe',[SubscribeController::class,'saveSubscribe']);

// seo form submission api
Route::post('save-seo',[SeoController::class,'storeSeo']);

// why choose us api
Route::get('get-choose-data',[WhyChooseUsController::class,'getChooseUs']);
Route::post('save-apply-data',[ApplyPositionController::class,'savePosition']);
Route::post('save-quotes',[GetQuotesController::class,'saveQuotes']);

Route::get('get-packages',[PackageController::class,'getPackage']);

Route::post('save-review',[BlogReviewController::class,'saveReview']);

Route::get('get-video-gallery',[VideoGalleryController::class,'videoGalleryData']);

Route::get('get-card-data',[ChooseNowCardController::class,'chooseNowCardData']);

Route::get('get-faq-data',[FaqController::class,'faqData']);






