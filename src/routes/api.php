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

Route::get('test', [tests::class, 'sendPutProfileCustomer']);

//Login Customer
Route::post("login_customer", [LoginCustomer::class, 'LoginCustomer']); 						//interface 1

//Search for STORE, all or by full / part name
Route::get("search/{storeName?}", [SearchStore::class, 'SearchStore']); 						//interface 3

//See STORE in detail + Save search in History
Route::get("store/{storeName}", [Store::class, 'SeeStore']); 									//interface 3
Route::post("store/{storeName}", [Store::class, 'SaveHistory']); 								//interface 4

//Display the History of USER
Route::get("history/{CurrentUser}", [History::class, 'SeeHistory']); 							//interface 5

//Search for ITEM, all or by full / part name
Route::get("search_item/{itemName?}", [SearchItem::class, 'SearchItem']); 						//interface 6

//Display MAP
Route::post("map", [Map::class, 'DisplayMap']); 												//interface 7

//Save Feedback of STORE
Route::post("feedback_store", [Feedback::class, 'SaveFeedbackStore']);							//interface 8

//Save Feedback of ITEM
Route::post("feedback_item", [Feedback::class, 'SaveFeedbackItem']);							//interface 9

Route::post("profile_customer", [ProfileCustomer::class, 'UpdateProfile']);						//interface 10 ****NEED TO BE PUT****
Route::get("profile_customer/{CurrentUser}", [ProfileCustomer::class, 'SeeProfile']);			//interface 11

Route::post("registration_customer", [RegistrationCustomer::class, 'RegistrationCustomer']); 	//interface 12

Route::post("registration_owner", [RegistrationOwner::class, 'RegistrationOwner']); 			//interface 18

Route::post("login_owner", [LoginOwner::class, 'LoginOwner']); 									//interface 19

Route::get("user_profile/{userName?}", [UserProfile::class, 'SeeUser']);						//interface 20
Route::post("update_user", [UserProfile::class, 'UpdateUser']);									//interface 21 ****NEED TO BE PUT****
Route::post("insert_user", [UserProfile::class, 'InsertUser']);									//interface 21
Route::get("delete_user/{UserId}", [UserProfile::class, 'DeleteUser']);							//interface 22 ****NEED TO BE DELETE****

Route::get("owner_profile/{ownerName?}", [OwnerProfile::class, 'SeeOwner']);						//interface 23
Route::post("update_owner", [OwnerProfile::class, 'UpdateOwner']);									//interface 24 ****NEED TO BE PUT****
Route::post("insert_owner", [OwnerProfile::class, 'InsertOwner']);									//interface 24
Route::get("delete_owner/{OwnerId}", [OwnerProfile::class, 'DeleteOwner']);							//interface 25 ****NEED TO BE DELETE****

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
