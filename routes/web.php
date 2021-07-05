<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('google-line-chart', 'App\Http\Controllers\PostController@googleLineChart');
Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('home');
    }else{
        return view('auth/login');
    }
})->name('/');

Route::group(['prefix' => '/recipes', 'middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\RecipeManagement\RecipeListController@index')->name('RecipeList');
    Route::get('/productMap', 'App\Http\Controllers\RecipeManagement\RecipeListController@mapProductStock')->name('Product.Mapping');
    Route::get('/productMapClear', 'App\Http\Controllers\RecipeManagement\RecipeListController@clearProductStock')->name('Product.Mapping.Clear');
    Route::post('/confirmMapping', 'App\Http\Controllers\RecipeManagement\RecipeListController@confirmMapping')->name('Product.Confirm.Mapping');
    Route::get('/search', 'App\Http\Controllers\RecipeManagement\RecipeListController@search')->name('RecipeSearch');
    Route::get('/favorites', 'App\Http\Controllers\RecipeManagement\RecipeListController@showFavorites')->name('Favorites');
    Route::get('/favorites/addcategory', 'App\Http\Controllers\RecipeManagement\RecipeListController@addCategory')->name('Favorites.Add.Category');
    Route::post('/favorites/editCategory', 'App\Http\Controllers\RecipeManagement\RecipeListController@editCategory')->name('Favorites.Edit.Category');
    Route::get('/favorites/deleteCategory/{id_Category}', 'App\Http\Controllers\RecipeManagement\RecipeListController@deleteCategory')->name('Favorites.Delete.Category');
    Route::get('/favorites/filter/{category}/{check}', 'App\Http\Controllers\RecipeManagement\RecipeListController@filterCategory')->name('Favorites.Filter');
    Route::get('/filter', 'App\Http\Controllers\RecipeManagement\RecipeListController@filter')->name('Recipes.filter');
});
Route::group(['prefix' => '/recipes/create', 'middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\RecipeManagement\RecipeListController@createRecipeView')->name('CreateRecipe');
    Route::post('/fetch', 'App\Http\Controllers\RecipeManagement\RecipeController@autoComplete')->name('Recipe.create.fetch');
    Route::post('/add/{id_Recipe}', 'App\Http\Controllers\RecipeManagement\RecipeController@addRecipe')->name('AddRecipe');
    Route::get('/add/url', 'App\Http\Controllers\RecipeManagement\GoutteController@webCrawling')->name('AddRecipeURL');
});
Route::group(['prefix' => '/recipes/comment/{id_Comment}', 'middleware' => 'auth'], function () {
    Route::get('/delete', 'App\Http\Controllers\RecipeManagement\RecipeController@deleteComment')->name('Comment.Delete');
    Route::post('/update', 'App\Http\Controllers\RecipeManagement\RecipeController@updateComment')->name('Comment.update');
});
Route::group(['prefix' => '/recipes/{recipe}', 'middleware' => 'auth'], function () {
    Route::get('/edit', 'App\Http\Controllers\RecipeManagement\RecipeController@editView')->name('Recipe.edit');
    Route::get('/newVersion', 'App\Http\Controllers\RecipeManagement\RecipeController@newVersionView')->name('Recipe.newVersion');
    Route::post('/update', 'App\Http\Controllers\RecipeManagement\RecipeController@updateRecipe')->name('Recipe.update');
    Route::get('/delete', 'App\Http\Controllers\RecipeManagement\RecipeController@deleteRecipe')->name('Recipe.delete');
    Route::get('/rate', 'App\Http\Controllers\RecipeManagement\RecipeController@rateView')->name('Recipe.rate');
    Route::get('/metric/{calc}', 'App\Http\Controllers\RecipeManagement\RecipeController@showMetric')->name('Recipe.calculate.metric');
});
Route::group(['prefix' => '/recipes/{id_Recipe}', 'middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\RecipeManagement\RecipeController@index')->name('Recipe');
    Route::get('/addToOffer/{calc}', 'App\Http\Controllers\RecipeManagement\RecipeController@generateIngredients')->name('Recipe.GeneratePurchaseOffer');
    Route::get('/useComponents/{calc}', 'App\Http\Controllers\RecipeManagement\RecipeController@useComponents')->name('Recipe.Use.Components');
    Route::get('/calc/{calc}', 'App\Http\Controllers\RecipeManagement\RecipeController@showCalculatedRecipe')->name('Recipe.calculateServings');
    Route::post('/rate/save', 'App\Http\Controllers\RecipeManagement\RecipeController@rateSave')->name('Recipe.rate.save');
    Route::get('/fav', 'App\Http\Controllers\RecipeManagement\RecipeController@addToFavorites')->name('Recipe.add.fav');
    Route::get('/fav/delete', 'App\Http\Controllers\RecipeManagement\RecipeController@blockFavorite')->name('Recipe.fav.delete');
    Route::get('/favorites/addtocategory', 'App\Http\Controllers\RecipeManagement\RecipeListController@addToCategory')->name('Favorites.AddToCategory');
    Route::post('/{id_Comment}/comment', 'App\Http\Controllers\RecipeManagement\RecipeController@addComment')->name('Recipe.add.comment');
});

//Resource Operations
Route::group([ 'middleware' => 'auth'], function () {
    Route::get('/activeResourcesList/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@indexActiveOnly')->name('resourcesList');
    Route::get('/resourcesList/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@index')->name('resourcesList2');
    Route::get('/resourcesList/{Id}/{filter_id}/{button}', 'App\Http\Controllers\ResourceManagement\ResourceController@filter')->name('filterResourcesList');

    Route::get('/resource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@OpenResourceView')->name('resourceView');
    Route::get('/resourceAll/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@OpenResourceViewAll')->name('resourceViewAll');


    Route::get('/addResource/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@addResource')->name('addResource');
    Route::get('/addResource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@addResourceFromResourceView')->name('addResourceFromResourceView');
    Route::get('/getResourceCards/{Id}/{type}', 'App\Http\Controllers\ResourceManagement\ResourceController@getResourceCards')->name('getResourceCards');


    Route::get('/checkBatch/{Id}/{id_resource}/{b}', 'App\Http\Controllers\ResourceManagement\ResourceController@checkBatch')->name('checkBatch');
    Route::get('/checkBatch2/{Id}/{id_resource}/{id_place}/{b}', 'App\Http\Controllers\ResourceManagement\ResourceController@checkBatch2')->name('checkBatch2');

    Route::get('/getChildPlaces/{Id}/{topPlace}', 'App\Http\Controllers\ResourceManagement\ResourceController@getChildPlaces')->name('getChildPlaces');
    Route::get('/getChildPlaces2/{Id}/{topPlace}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@getChildPlaces2')->name('getChildPlaces2');

    Route::get('/getAllPlaces/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@getAllPlaces')->name('getAllPlaces');

//    Route::get('search', 'App\Http\Controllers\Select2SearchController@index');
//    Route::get('ajax-autocomplete-search', 'App\Http\Controllers\Select2SearchController@selectSearch');

    Route::post('/saveResource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@addResourceFromResource')->name('addResourceFromResource');


    Route::post('/saveResource/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@addResource2')->name('addResource2');
    Route::get('/moveResource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@moveResource')->name('moveResource');
    Route::get('/getCategoryMove/{Id}/{id}/{id_place}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategoryMove')->name('getCategoryMove');

    Route::post('/moveResource2/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@moveResource2')->name('moveResource2');
    Route::get('/getCategoryDelete1/{Id}/{id}/{id_place}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategoryDelete1')->name('getCategoryDelete1');
    Route::get('/getCategoryDelete/{Id}/{id}/{id_place}/{id_batch}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategoryDelete')->name('getCategoryDelete');
    Route::get('/getCategoryDelete2/{Id}/{id}/{id_place}/{id_batch}/{id_exp}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategoryDelete2')->name('getCategoryDelete2');
    Route::get('/getCategoryMove6/{Id}/{id}/{id_place}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategoryMove6')->name('getCategoryMove6');

    Route::post('/confirmDeleteResource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@confirmDeleteResource')->name('confirmDeleteResource');
    Route::get('/deleteResource/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@deleteResource')->name('deleteResource');
    Route::post('/deleteResource2/{Id}/{id_resource}', 'App\Http\Controllers\ResourceManagement\ResourceController@deleteResource2')->name('deleteResource2');


    Route::get('/addBatch/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@addBatch')->name('addBatch');
    Route::post('/saveBatch/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@addBatch2')->name('addBatch2');
    Route::get('/batches/{Id}/{stock_id}', 'App\Http\Controllers\ResourceManagement\ResourceController@batchesList')->name('batchesList');
    Route::get('/allbatches/{Id}/{stock_id}', 'App\Http\Controllers\ResourceManagement\ResourceController@allbatchesList')->name('allBatchesList');
    Route::get('/editBatch/{Id}/{card}', 'App\Http\Controllers\ResourceManagement\ResourceController@editBatch')->name('editBatch');
    Route::post('/saveEditedBatch/{Id}/{card}/{currentBatch}', 'App\Http\Controllers\ResourceManagement\ResourceController@saveEditBatch')->name('saveEditBatch');
    Route::get('/deleteBatch/{Id}/{batch_id}', 'App\Http\Controllers\ResourceManagement\ResourceController@deleteBatch')->name('deleteBatch');
    Route::get('/getCategory/{Id}/{id}', 'App\Http\Controllers\ResourceManagement\ResourceController@getCategory')->name('getCategory');

    Route::get('/minMaxQuantities/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@openMinMax')->name('openMinMax');
    Route::get('/setminMaxQuantities/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@setMinMax')->name('setMinMax');
    Route::post('/saveminMaxQuantities/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@setMinMax2')->name('setMinMax2');
    Route::post('/saveminMaxQuantities/{Id}/{stock_card_id}', 'App\Http\Controllers\ResourceManagement\ResourceController@setMinMax2FromResource')->name('setMinMax2FromResource');

    Route::get('/editMinMax/{Id}/{set_id}/{set_id2}', 'App\Http\Controllers\ResourceManagement\ResourceController@editMinMax')->name('editMinMax');
    Route::post('/saveUpdatedMinMax/{Id}/{set_id}/{set_id2}', 'App\Http\Controllers\ResourceManagement\ResourceController@saveMinMax')->name('saveMinMax');

    Route::get('/setCheckTimes/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@setCheckTimes')->name('setCheckTimes');
    Route::post('/saveCheckTimes/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@saveCheckTimes')->name('saveCheckTimes');

//    Route::get('/nu', function () {
//        return view('ResourceManagement/modal');
//    })->name('wat');
    Route::post('/addResourcePP/{Id}/{resource_id}/{place}', 'App\Http\Controllers\ResourceManagement\ResourceController@addResourcePP')->name('addResourcePP');


    Route::get('/purchaseOffer/{Id}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@index')->name('purchaseOffer');
    Route::get('/purchaseOfferFiltered/{Id}/{user}/{supplier}/{type}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@filteredPurchaseOffer')->name('filteredPurchaseOffer');

    Route::get('/userPurchaseOffer/{Id}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@userPurchaseOffer')->name('userPurchaseOffer');
    Route::get('/insertToPurchaseOffer/{Id}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@insertToPurchaseOffer')->name('insertToPurchaseOffer');
    Route::post('/saveInsertToPurchaseOffer/{Id}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@saveInsertToPurchaseOffer')->name('saveInsertToPurchaseOffer');
    Route::post('/itemedit/{Id}/{id}/{card}/{place}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@itemedit')->name('itemedit');
    Route::post('/insertToOwn/{Id}/{item}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@insertToOwn')->name('insertToOwn');
    Route::post('/removeFromOwn/{Id}/{item}/{user}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@removeFromOwn')->name('removeFromOwn');

    Route::get('/clearPurchaseOffer/{Id}', 'App\Http\Controllers\ResourceManagement\PurchaseOfferController@clearPurchaseOffer')->name('clearPurchaseOffer');

    //unused
    Route::get('/makeWarehousePlace/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@makeWarehousePlace')->name('makeWarehousePlace');

    Route::get('/calculatePurchaseOffer/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@calculatePurchaseOffer')->name('calculatePurchaseOffer');
    Route::get('/calculatePurchaseOfferExpiration/{Id}', 'App\Http\Controllers\ResourceManagement\ResourceController@calculatePurchaseOfferExpiration')->name('calculatePurchaseOfferExpiration');


    Route::get('/searchResource/{Id}/{filter_id}/{button}', 'App\Http\Controllers\ResourceManagement\ResourceController@search')->name('searchResource');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    //Account
    Route::get('/account', [App\Http\Controllers\UserManagement\AccountController::class, 'index'])->name('account');
    Route::post('/confirmEditAccount/{Id}', [App\Http\Controllers\UserManagement\AccountController::class, 'confirmEditAccount'])->name('confirmEditAccount');
    Route::get('/account/{Id}', [App\Http\Controllers\UserManagement\AccountController::class, 'removeAccount'])->name('removeAccount');
    //Household
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/createHousehold', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'createHousehold'])->name('CreateHousehold');
    Route::post('/addHousehold', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'addHousehold'])->name('addHousehold');
    Route::get('/household/{id_house}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'index'])->name('Household');
    Route::get('/household/manageHousehold/{Id}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'manageHousehold'])->name('manageHousehold');
    Route::get('/manageHousehold/{Id}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'removeHousehold'])->name('removeHousehold');
    Route::get('/activateHousehold/{Id}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'activateHousehold'])->name('activateHousehold');
    Route::post('/confirmEditHousehold/{Id}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'confirmEditHousehold'])->name('confirmEditHousehold');
    Route::get('/manageHouseholdMembers/{Id}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'manageHouseholdMembers'])->name('manageHouseholdMembers');
    Route::get('/manageHouseholdMembers/membersEdit/{Id}/{user}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'manageMembersEdit'])->name('MembersEdit');
    Route::get('/confirmAddPermission/{Id}/{user}/{permission}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'confirmAddPermission'])->name('confirmAddPermission');
    Route::get('/confirmRemovePermission/{Id}/{user}/{permission}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'confirmRemovePermission'])->name('confirmRemovePermission');
    Route::get('/unlockWarehouse/{Id}/{user}/{warehouse}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'unlockWarehouse'])->name('unlockWarehouse');
    Route::post('/lockWarehouse/{Id}/{user}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'lockWarehouse'])->name('lockWarehouse');
    Route::get('/membersEdit/{Id}/{user}', [App\Http\Controllers\HouseholdManagement\HouseholdController::class, 'removeMember'])->name('removeMember');
    Route::get('/warehousePlaces/{id_house}/{type}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'getTypeWare'])->name('getTypeWare');
    Route::post('/warehousePlaces/{id_house}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'SortWarehouse'])->name('SortWarehouse');
    Route::post('/warehousePlaces/{id_house}/{type}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'SortFilteredWarehouse'])->name('SortFilteredWarehouse');
    Route::get('/searchWare/{Id}/{filter_id}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'searchWarehouse'])->name('searchWarehouse');
    Route::get('/activateWare/{id_house}/{Id}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'activateWarehouse'])->name('activateWarehouse');
    //Invites
    Route::get('/invites', [App\Http\Controllers\UserManagement\InviteController::class, 'index'])->name('invites');
    Route::get('/invites/{Id}/{call}', [App\Http\Controllers\UserManagement\InviteController::class, 'deleteInvite'])->name('deleteInvite');
    Route::get('/inviteMember/{Id}', [App\Http\Controllers\UserManagement\InviteController::class, 'inviteMember'])->name('inviteMember');
    Route::post('/createInvite/{Id}', [App\Http\Controllers\UserManagement\InviteController::class, 'createInvite'])->name('createInvite');
    //Suppliers
    Route::get('/suppliers/{id_house}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'index'])->name('suppliers');
    Route::get('/suppliers/{id_house}/{type}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'getType'])->name('getType');
    Route::post('/suppliers/{id_house}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'SortSuppliers'])->name('SortSuppliers');
    Route::post('/suppliers/{id_house}/{type}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'SortFilteredSuppliers'])->name('SortFilteredSuppliers');
    Route::get('/supplierEdit/{id_house}/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'manageSupplierEdit'])->name('SupplierEdit');
    Route::post('/confirmEditSupplier/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'confirmEditSupplier'])->name('confirmEditSupplier');
    Route::get('/manageSupplier/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'removeSupplier'])->name('removeSupplier');
    Route::get('/supplier/{id_house}/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'manageSupplier'])->name('Supplier');
    Route::get('/createSupplier/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'createSupplier'])->name('CreateSupplier');
    Route::post('/addSuppliers/{Id}/{title}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'addSuppliers'])->name('addSuppliers');
    Route::get('/createSupplierType/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'createSupplierType'])->name('CreateSupplierType');
    Route::post('/confirmEditSupplierType/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'confirmEditSupplierType'])->name('confirmEditSupplierType');
    Route::get('/supplierTypeEdit/{id_house}/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'manageSupplierEditType'])->name('SupplierTypeEdit');
    Route::get('/search/{Id}/{filter_id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'searchSupplier'])->name('searchSupplier');
    Route::get('/activate/{id_house}/{Id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'activateSupplier'])->name('activateSupplier');
    //Cards, types and units
    Route::get('/warehousePlaces/{id_house}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'index'])->name('warehousePlaces');
    Route::get('/stockCards/{id_house}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'index'])->name('stockCards');
    Route::get('/stockTypes/{id_house}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'stockTypeList'])->name('stockTypes');
    Route::get('/createStockCard/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'createStockCard'])->name('CreateStockCard');
    Route::get('/createStockType/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'createStockType'])->name('CreateStockType');
    Route::post('/addCard/{Id}/{title}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'addCard'])->name('addCard');
    Route::get('/card/{id_house}/{Id}/{title}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'Card'])->name('card');
    Route::get('/cardEdit/{id_house}/{Id}/{title}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'manageCardEdit'])->name('CardEdit');
    Route::post('/confirmEditCard/{Id}/{title}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'confirmEditCard'])->name('confirmEditCard');
    Route::get('/manageStockCard/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'removeStockCard'])->name('removeStockCard');
    Route::get('/manageWarCard/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'removeWarCard'])->name('removeWarCard');
    Route::get('/createWarehouse/{Id}', [App\Http\Controllers\ResourceManagement\WarehousePlaceController::class, 'createWarehouseCard'])->name('CreateWarehouse');
    Route::post('/addSupplierForStock/{fk_stock_card}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'addSupplierForStock'])->name('addSupplierForStock');
    Route::get('/removeSupplierFromStock/{fk_stock_card}/{fk_suppliers}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'removeSupplierFromStock'])->name('removeSupplierFromStock');
    Route::get('/manageType/{Id}/{title}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'manageType'])->name('manageType');
    Route::get('/removeSupplierType/{type_id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'removeSupplierType'])->name('removeSupplierType');
    Route::get('/removeStockType/{id_Stock_type}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'removeStockType'])->name('removeStockType');
    Route::post('/confirmEditStockType/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'confirmEditStockType'])->name('confirmEditStockType');
    Route::get('/stockTypeEdit/{id_house}/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'manageStockEditType'])->name('StockTypeEdit');
    Route::get('/deleteSupplier/{type_id}', [App\Http\Controllers\HouseholdManagement\SuppliersController::class, 'deleteSupplier'])->name('deleteSupplier');
    Route::get('/deleteStock/{type_id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'deleteStock'])->name('deleteStock');
    Route::get('/deleteWare/{type_id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'deleteWare'])->name('deleteWare');





    Route::get('/stockCards/{id_house}/{type}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'getTypeStock'])->name('getTypeStock');
    Route::post('/stockCards/{id_house}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'SortStock'])->name('SortStock');
    Route::post('/stockCards/{id_house}/{type}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'SortFilteredStock'])->name('SortFilteredStock');
    Route::get('/searchStock/{Id}/{filter_id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'searchStock'])->name('searchStock');
    Route::get('/activateStock/{id_house}/{Id}', [App\Http\Controllers\ResourceManagement\ResourceListController::class, 'activateStock'])->name('activateStock');
});

