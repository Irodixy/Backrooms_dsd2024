<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\tests;

use App\Http\Controllers\LoginCustomerController as LoginCustomer;
use App\Http\Controllers\LoginOwnerController as LoginOwner;
use App\Http\Controllers\SearchStore;
use App\Http\Controllers\StoreController as Store;
use App\Http\Controllers\HistoryController as History;
use App\Http\Controllers\SearchItemController as SearchItem;
use App\Http\Controllers\MapController as Map;
use App\Http\Controllers\FeedbackController as Feedback;
use App\Http\Controllers\ProfileCustomerController as ProfileCustomer;
use App\Http\Controllers\UserProfileController as UserProfile;
use App\Http\Controllers\OwnerProfileController as OwnerProfile;
use App\Http\Controllers\RegistrationCustomerController as RegistrationCustomer;
use App\Http\Controllers\RegistrationOwnerController as RegistrationOwner;

/******* TESTING POSTS!!! ********/

//POST FOR LOGIN!!!!!!!
Route::get('test_login', [tests::class, 'sendPostLogin']);

//POST FOR SEARCH STORE!!!!
Route::get('test_search_store/{type?}', [tests::class, 'sendPostSearchStore']);

//POST FOR DISPLAY SEARCH_HISTORY!!!!
Route::get('test_history', [tests::class, 'sendPostHistory']);

//POST FOR UPDATE CUSTOMER!!!!!
Route::get('test_update_customer/{type}', [tests::class, 'sendPostProfileCustomer']);

//POST FOR REGISTRATION OWNER!!!!
Route::get('test_registration_owner', [tests::class, 'sendPostOwnerRegistration']);

//POST FOR LOGIN OWNER!!!!
Route::get('test_login_owner', [tests::class, 'sendPostOwnerLogin']);

//POST FOR UPDATE/INSERT BY ADMIN, CUSTOMER!!!!!
Route::get('test_update_byAdmin_customer/{type?}', [tests::class, 'sendPostProfileUser']);
/******* TESTING POSTS!!! ********/


//Login Customer
Route::post("interface1", [LoginCustomer::class, 'LoginCustomer']); 							//interface 1							#TESTED#

//Search for STORE, all or by full / part name
Route::post("interface3", [SearchStore::class, 'SearchStore']); 								//interface 3							#TESTED#

//See STORE in detail + Save search in History
//Route::get("store/{storeName}", [Store::class, 'SeeStore']); 									//interface 3							#TESTED#
Route::post("interface4/{storeName}", [Store::class, 'SaveHistory']); 							//interface 4							#TESTED#

//Display the History of USER
Route::post("interface5", [History::class, 'SeeHistory']); 										//interface 5							#TESTED#

//Search for ITEM, all or by full / part name
Route::get("search_item/{itemName?}", [SearchItem::class, 'SearchItem']); 						//interface 6							#TESTED#

//Display MAP
Route::post("interface7", [Map::class, 'DisplayMap']); 											//interface 7							

//Save Feedback of STORE
Route::post("feedback_store", [Feedback::class, 'SaveFeedbackStore']);							//interface 8							#TESTED#

//Save Feedback of ITEM
Route::post("feedback_item", [Feedback::class, 'SaveFeedbackItem']);							//interface 9							#TESTED#

Route::post("profile_customer", [ProfileCustomer::class, 'UpdateProfile']);						//interface 10 ****NEED TO BE PUT****	#TESTED#
Route::get("profile_customer/{CurrentUser}", [ProfileCustomer::class, 'SeeProfile']);			//interface 11							#TESTED#

Route::post("registration_customer", [RegistrationCustomer::class, 'RegistrationCustomer']); 	//interface 12							#TESTED#

Route::post("interface18", [RegistrationOwner::class, 'RegistrationOwner']); 					//interface 18							#TESTED#

Route::post("interface19", [LoginOwner::class, 'LoginOwner']); 									//interface 19							#TESTED#

Route::get("interface20/{userName?}", [UserProfile::class, 'SeeUser']);							//interface 20							#TESTED#
Route::post("interface21", [UserProfile::class, 'UpdateOrInsert']);								//interface 21(COMBINE)					#TESTED#
/*Route::post("interface21", [UserProfile::class, 'UpdateUser']);								//interface 21 ****NEED TO BE PUT****	#NOT USE#
Route::post("insert_user", [UserProfile::class, 'InsertUser']);	*/								//interface 21							#NOT USE#
Route::post("interface22", [UserProfile::class, 'DeleteUser']);									//interface 22 ****NEED TO BE DELETE****#TESTED#

Route::get("interface23/{ownerName?}", [OwnerProfile::class, 'SeeOwner']);						//interface 23							#TESTED#
Route::post("interface21", [OwnerProfile::class, 'UpdateOrInsert']);							//interface 24
/*Route::post("interface24", [OwnerProfile::class, 'UpdateOwner']);								//interface 24 ****NEED TO BE PUT****	#NOT USE#
Route::post("insert_owner", [OwnerProfile::class, 'InsertOwner']);*/							//interface 24							#NOT USE#
Route::post("interface25", [OwnerProfile::class, 'DeleteOwner']);								//interface 25 ****NEED TO BE DELETE****

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//VEIGA CODE
/*
	<?php

	use App\Http\Controllers\Api\V1\CompleteTaskController;
	use App\Http\Controllers\Api\V1\TaskController;
	use App\Http\Controllers\Api\V1\UserController;
	use App\Http\Controllers\Api\V1\StoreController;
	use App\Http\Controllers\Api\V1\ItemController;
	use App\Http\Controllers\Api\V1\FeedbackController;
	use App\Http\Controllers\Api\V1\PurchaseController;
	use App\Http\Controllers\Api\V1\LocationController;
	use App\Http\Controllers\Api\V1\InterestsController;
	use App\Http\Controllers\Api\V1\HuntedStoreController;
	use App\Http\Controllers\Api\V1\StatisticsController;
	use App\Http\Controllers\Api\V1\ConfigController;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Route;


	Route::prefix('v1')->group(function(){  
		// Rotas para User
		Route::apiResource('users', UserController::class);

		// Rotas para Item
		Route::apiResource('items', ItemController::class);

		// Rotas para Store
		Route::apiResource('stores', StoreController::class);

		// Rotas para Feedback
		Route::apiResource('feedbacks', FeedbackController::class);

		// Rotas para Purchase
		Route::apiResource('purchases', PurchaseController::class);

		// Rotas para Location
		Route::apiResource('locations', LocationController::class);

		// Rotas para Interests
		Route::apiResource('interests', InterestsController::class);

		// Rotas para HuntedStore
		Route::apiResource('huntedstores', HuntedStoreController::class);

		// Rotas para Statistics
		Route::apiResource('statistics', StatisticsController::class);

		// Rotas para Config
		Route::apiResource('configs', ConfigController::class);
	});

	Route::get('/user', function (Request $request) {
		return $request->user();
	})->middleware('auth:sanctum');
*/
