<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Category;

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/admin')->namespace('Admin')->group(function(){
//All admin routes is added here

        Route::match(['get','post'],'/','AdminController@loginpage');

        Route::group(['middleware'=>['admin']],function(){

        Route::get('dashboard','AdminController@dashboard');

        Route::get('settings','AdminController@settings');

        Route::get('logout','AdminController@logout');

        Route::post ('check-present-password','AdminController@checkPresentPassword');

        Route::post ('update-present-password','AdminController@updatePresentPassword');

        Route::match(['get','post'],'update-admin-details','AdminController@updateAdminDetails');

        //sections route
        Route::get('sections','SectionControllers@sections');

        Route::post('update-section-status','SectionControllers@updateSectionStatus');

        //categories
         Route::get('categories','CategoryController@categories');

         Route::post('update-category-status','CategoryController@updateCategoryStatus');

         Route::match(['get','post'], 'add-edit-category/{id?}','CategoryController@addEditCategory');

         Route::post('include-categories-level', 'CategoryController@includeCategoriesLevel');

         Route::get('delete-category-image/{id}','CategoryController@deleteCategoryImage');

         Route::get('delete-category/{id}','CategoryController@deleteCategory');

        //product
        Route::get('products', 'ProductsController@products');

        Route::post('update-product-status','ProductsController@updateProductStatus');

        Route::get('delete-product/{id}','ProductsController@deleteProduct');

        Route::match(['get', 'post'],'add-edit-product/{id?}','ProductsController@addEditProduct');

        Route::get('delete-product-image/{id}','ProductsController@deleteProductImage');

        //Attributes
        Route::match(['get', 'post'], 'add-attributes/{id}', 'ProductsController@addAttributes');

        Route::post('edit-attributes/{id}', 'ProductsController@editAttributes');

        Route::post('update-attribute-status','ProductsController@updateAttributeStatus');

        Route::get('delete-attribute/{id}','ProductsController@deleteAttribute');

    });
});

Route::namespace('Front')->group(function(){
    //homepage route
    Route::get('/','IndexController@index');

    // //listing route
    $catUrls =  Category::select('url')->where('status', 1)->get()->pluck('url')->toArray();
    foreach ($catUrls as $url) {
        Route::get('/'.$url,'ProductsController@listing');
    }

    //product detail route
    Route::get('/product/{id}','ProductsController@detail');

    //add to cart route
    Route::post('/add-to-cart','ProductsController@addtocart');

    //shopping cart route
    Route::get('/cart','ProductsController@cart');

    ///update cart item quantity
    Route::post('/update-cart-item-qty','ProductsController@updateCartItemQty');

    //delete cart item
    Route::post('/delete-cart-item','ProductsController@deleteCartItem');

    // login/register page
    Route::get('/login-register','UsersController@loginRegister');

    //user login
    Route::post('/login','UsersController@loginUser');

    //register user
    Route::post('/register','UsersController@registerUser');

    //check email exist
    Route::match(['get','post'],'/check-email','UsersController@checkEmail');

    //user logout
    Route::get('/logout','UsersController@logoutUser');

    //users account
    Route::match(['GET','POST'], '/account','UsersController@account');

    //check user current password
    Route::post('/check-user-pwd','UsersController@chkUserPassword');

    //update user pasword
    Route::post('/update-user-pwd', 'UsersController@updateUserPassword');

});

