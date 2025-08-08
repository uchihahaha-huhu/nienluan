<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ProductChatController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductDetailController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\BlogMenuController;
use App\Http\Controllers\Frontend\ArticleDetailController;
use App\Http\Controllers\Frontend\ShoppingCartController;
use App\Http\Controllers\Frontend\TrackOrderController;
use App\Http\Controllers\Frontend\CommentsController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PageStaticController;
use App\Http\Controllers\Frontend\AddressBookController;
use App\Http\Controllers\Test\TestController;


use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserFavouriteController;
use App\Http\Controllers\User\UserInfoController;
use App\Http\Controllers\User\UserManagementTransaction;
use App\Http\Controllers\User\UserRatingController;
use App\Http\Controllers\User\UserTransactionController;
use App\Http\Controllers\User\LogLoginUserController;
use App\Http\Controllers\User\CaptchaController;


use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSlideController;
use App\Http\Controllers\Admin\AdminStatisticalController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminStaticController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\AdminKeywordController;
use App\Http\Controllers\Admin\SystemPay\AdminPayInController;
use App\Http\Controllers\Admin\AdminInventoryController;


Route::group(['namespace' => 'Auth', 'prefix' => 'account'], function () {
    Route::get('register', [RegisterController::class, 'getFormRegister'])->name('get.register');
    Route::post('register', [RegisterController::class, 'postRegister']);

    Route::get('login', [LoginController::class, 'getFormLogin'])->name('get.login');
    Route::post('login', [LoginController::class, 'postLogin']);

    Route::get('logout', [LoginController::class, 'getLogout'])->name('get.logout');
    Route::get('reset-password', [ResetPasswordController::class, 'getEmailReset'])->name('get.email_reset_password');
    Route::post('reset-password', [ResetPasswordController::class, 'checkEmailResetPassword']);

    Route::get('new-password', [ResetPasswordController::class, 'newPassword'])->name('get.new_password');
    Route::post('new-password', [ResetPasswordController::class, 'savePassword']);
    Route::get('verify-email', [VerificationController::class, 'verify'])->name('get.verify_email');
        Route::get('verify-account', [VerificationController::class, 'showVerifyEmailForm'])->name('get.verify_account_form');

    // Xử lý gửi mail xác thực lại
    Route::post('verify-account', [VerificationController::class, 'sendVerifyEmail'])->name('post.verify_account');
    Route::get('change-password', [ChangePasswordController::class, 'showForm'])->name('get.change_password_form');

    // Xử lý đổi mật khẩu
    Route::post('change-password', [ChangePasswordController::class, 'changePassword'])->name('post.change_password');

    Route::get('/{social}/redirect', [SocialAuthController::class, 'redirect'])->name('get.login.social');
    Route::get('/{social}/callback', [SocialAuthController::class, 'callback'])->name('get.login.social_callback');
});

// Login admin
Route::group(['prefix' => 'admin-auth', 'namespace' => 'Admin\Auth'], function () {
    Route::get('login', [AdminLoginController::class, 'getLoginAdmin'])->name('get.login.admin');
    Route::post('login', [AdminLoginController::class, 'postLoginAdmin']);

    Route::get('logout', [AdminLoginController::class, 'getLogoutAdmin'])->name('get.logout.admin');
});
Route::group(['prefix' => 'address-book', 'middleware' => 'auth'], function () {
    Route::get('user/{userId}', [AddressBookController::class, 'getAddressBookForUser'])
        ->name('address_book.list');
    Route::post('create', [AddressBookController::class, 'createAddressBook'])
        ->name('address_book.create');
    Route::put('update/{id}', [AddressBookController::class, 'updateAddressBook'])
        ->name('address_book.update');
    Route::delete('delete/{id}', [AddressBookController::class, 'deleteAddressBook'])
        ->name('address_book.delete');
    Route::get('user/{userId}/default', [AddressBookController::class, 'getDefaultAddressBookForUser'])
        ->name('address_book.get_default');
    Route::post('set-default', [AddressBookController::class, 'setDefaultAddress'])
        ->name('address_book.set_default');
});
Route::group(['namespace' => 'Frontend'], function () {
    Route::get('', [HomeController::class, 'index'])->name('get.home');
    Route::get('ajax-load-product-recently', [HomeController::class, 'getLoadProductRecently'])->name('ajax_get.product_recently');
    Route::get('ajax-load-product-by-category', [HomeController::class, 'getLoadProductByCategory'])->name('ajax_get.product_by_category');
    Route::get('ajax-load-slide', [HomeController::class, 'loadSlideHome'])->name('ajax_get.slide');
    Route::get('san-pham', [ProductController::class, 'index'])->name('get.product.list');
    Route::get('dan-muc/{slug}', [CategoryController::class, 'index'])->name('get.category.list');
    Route::get('san-pham/{slug}', [ProductDetailController::class, 'getProductDetail'])->name('get.product.detail');
    Route::get('san-pham/{slug}/danh-gia', [ProductDetailController::class, 'getListRatingProduct'])->name('get.product.rating_list');

    Route::get('bai-viet', [BlogController::class, 'index'])->name('get.blog.home');
    Route::get('menu/{slug}', [BlogMenuController::class, 'getArticleByMenu'])->name('get.article.by_menu');
    Route::get('bai-viet/{slug}', [ArticleDetailController::class, 'index'])->name('get.blog.detail');
    Route::post('product-chat', [ProductChatController::class, 'chat'])->name('get.product-chat');
    // Shopping cart
    Route::get('don-hang', [ShoppingCartController::class, 'index'])->name('get.shopping.list');
    Route::prefix('shopping')->group(function () {
        Route::get('add/{id}', [ShoppingCartController::class, 'add'])->name('get.shopping.add');
        Route::get('delete/{id}', [ShoppingCartController::class, 'delete'])->name('get.shopping.delete');
        Route::get('update/{id}', [ShoppingCartController::class, 'update'])->name('ajax_get.shopping.update');
        Route::get('theo-doi-don-hang', [TrackOrderController::class, 'index'])->name('get.track.transaction');
        Route::post('pay', [ShoppingCartController::class, 'postPay'])->name('post.shopping.pay');
        Route::get('hook', [ShoppingCartController::class, 'hookCallback'])->name('get.shopping.callback');
        Route::get('update/cart/discount', [ShoppingCartController::class, 'cartDiscount'])->name('ajax_get.update.cart.discount');
        Route::get('/shopping/cart/discount/remove', [ShoppingCartController::class, 'removeDiscount'])->name('ajax_get.remove.cart.discount');
    });

    // Comment
    Route::group(['prefix' => 'comment', 'middleware' => 'check_user_login'], function () {
        Route::post('ajax-comment', [CommentsController::class, 'store'])->name('ajax_post.comment');
    });

    Route::get('lien-he', [ContactController::class, 'index'])->name('get.contact');
    Route::post('lien-he', [ContactController::class, 'store']);
    Route::get('san-pham-ban-da-xem', [PageStaticController::class, 'getProductView'])->name('get.static.product_view');
    Route::get('ajax/san-pham-ban-da-xem', [PageStaticController::class, 'getListProductView'])->name('ajax_get.product_view');
    Route::get('huong-dan-mua-hang', [PageStaticController::class, 'getShoppingGuide'])->name('get.static.shopping_guide');
    Route::get('chinh-sach-doi-tra', [PageStaticController::class, 'getReturnPolicy'])->name('get.static.return_policy');
    Route::get('cham-soc-khach-hang', [PageStaticController::class, 'getCustomerCare'])->name('get.static.customer_care');

    Route::get('ajax/load-document', [PageStaticController::class, 'getDocumentAjax'])->name('get_ajax.static.document');
    Route::get('demo/view-file', [PageStaticController::class, 'getDemoViewFile']);
});

Route::group(['prefix' => 'test', 'namespace' => 'Test'], function () {
    Route::get('hoa-don-ban', [TestController::class, 'index']);
});

Route::get('/home', [HomeController::class, 'index'])->name('get.home');



//Router User
Route::group(['prefix' => 'account', 'namespace' => 'User', 'middleware' => 'check_user_login'], function () {
    Route::get('', [UserDashboardController::class, 'dashboard'])->name('get.user.dashboard');

    Route::get('update-info', [UserInfoController::class, 'updateInfo'])->name('get.user.update_info');
    Route::post('update-info', [UserInfoController::class, 'saveUpdateInfo']);

    Route::get('transaction', [UserTransactionController::class, 'index'])->name('get.user.transaction');
    Route::get('transaction/cancel/{id}', [UserTransactionController::class, 'cancelTransaction'])->name('get.user.transaction.cancel');
    Route::get('order/view/{id}', [UserTransactionController::class, 'viewOrder'])->name('get.user.order');

    Route::get('rating', [UserRatingController::class, 'index'])->name('get.user.rating');
    Route::get('rating/delete/{id}', [UserRatingController::class, 'delete'])->name('get.user.rating.delete');

    Route::get('comment', [UserCommentController::class, 'index'])->name('get.user.comment');
    Route::get('comment/delete/{id}', [UserCommentController::class, 'delete'])->name('get.user.comment.delete');


    Route::get('log-login', [LogLoginUserController::class, 'index'])->name('get.user.log_login');

    Route::get('tracking/view/{id}', [UserTransactionController::class, 'getTrackingTransaction'])->name('get.user.tracking_order');
    Route::get('favourite', [UserFavouriteController::class, 'index'])->name('get.user.favourite');
    Route::get('favourite-delete/{id}', [UserFavouriteController::class, 'delete'])->name('get.user.favourite.delete');

    Route::get('management-transaction', [UserManagementTransaction::class, 'index'])->name('get.user.management_transaction');

    Route::post('ajax-favourite/{idProduct}', [UserFavouriteController::class, 'addFavourite'])->name('ajax_get.user.add_favourite');
    Route::post('ajax-rating', [UserRatingController::class, 'addRatingProduct'])->name('ajax_post.user.rating.add');
    Route::post('captcha', [CaptchaController::class, 'authCaptchaResume'])->name('ajax_post.captcha.resume');
    Route::get('ajax-invoice-transaction/{id}', [UserTransactionController::class, 'exportInvoiceTransaction'])
        ->name('ajax_get.user.invoice_transaction');
});



// Router Admin
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => 'check_admin_login'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['prefix' => 'api-admin', 'namespace' => 'Admin', 'middleware' => 'check_admin_login'], function () {
    Route::get('', [AdminController::class, 'index'])->name('get.admin.index');

    Route::get('statistical', [AdminStatisticalController::class, 'index'])->name('admin.statistical');
    Route::get('contact', [AdminContactController::class, 'index'])->name('admin.contact');
    Route::get('contact/delete/{id}', [AdminContactController::class, 'delete'])->name('admin.contact.delete');

    Route::get('profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::post('profile/{id}', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    /**
     * Route danh mục sản phẩm
     **/
    // Route::group(['prefix' => 'system-pay', 'namespace' => 'SystemPay'], function () {
    //     Route::group(['prefix' => 'pay-in'], function () {
    //         Route::get('', 'AdminPayInController@index')->name('admin.system_pay_in.index');
    //         Route::get('create', 'AdminPayInController@create')->name('admin.system_pay_in.create');
    //         Route::post('create', 'AdminPayInController@store');

    //         Route::get('update/{id}', 'AdminPayInController@edit')->name('admin.system_pay_in.update');
    //         Route::post('update/{id}', 'AdminPayInController@update');

    //         Route::get('delete/{id}', 'AdminPayInController@delete')->name('admin.system_pay_in.delete');
    //     });
    // });

    /**
     * Route danh mục sản phẩm
     **/
    Route::group(['prefix' => 'category'], function () {
        Route::get('', [AdminCategoryController::class, 'index'])->name('admin.category.index');
        Route::get('create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
        Route::post('create', [AdminCategoryController::class, 'store']);

        Route::get('update/{id}', [AdminCategoryController::class, 'edit'])->name('admin.category.update');
        Route::post('update/{id}', [AdminCategoryController::class, 'update']);

        Route::get('active/{id}', [AdminCategoryController::class, 'active'])->name('admin.category.active');
        Route::get('hot/{id}', [AdminCategoryController::class, 'hot'])->name('admin.category.hot');
        Route::get('delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
    });

    Route::group(['prefix' => 'account-admin'], function () {
        Route::get('', [AdminAccountController::class, 'index'])->name('admin.account_admin.index');
        Route::get('create', [AdminAccountController::class, 'create'])->name('admin.account_admin.create');
        Route::post('create', [AdminAccountController::class, 'store']);

        Route::get('update/{id}', [AdminAccountController::class, 'edit'])->name('admin.account_admin.update');
        Route::post('update/{id}', [AdminAccountController::class, 'update']);

        Route::get('delete/{id}', [AdminAccountController::class, 'delete'])->name('admin.account_admin.delete');
    });

    Route::group(['prefix' => 'ncc'], function () {
        Route::get('', [AdminSupplierController::class, 'index'])->name('admin.ncc.index');
        Route::get('create', [AdminSupplierController::class, 'create'])->name('admin.ncc.create');
        Route::post('create', [AdminSupplierController::class, 'store']);

        Route::get('update/{id}', [AdminSupplierController::class, 'edit'])->name('admin.ncc.update');
        Route::post('update/{id}', [AdminSupplierController::class, 'update']);

        Route::get('delete/{id}', [AdminSupplierController::class, 'delete'])->name('admin.ncc.delete');
    });

    Route::group(['prefix' => 'keyword'], function () {
        Route::get('', [AdminKeywordController::class, 'index'])->name('admin.keyword.index');
        Route::get('create', [AdminKeywordController::class, 'create'])->name('admin.keyword.create');
        Route::post('create', [AdminKeywordController::class, 'store']);

        Route::get('update/{id}', [AdminKeywordController::class, 'edit'])->name('admin.keyword.update');
        Route::post('update/{id}', [AdminKeywordController::class, 'update']);
        Route::get('hot/{id}', [AdminKeywordController::class, 'hot'])->name('admin.keyword.hot');

        Route::get('delete/{id}', [AdminKeywordController::class, 'delete'])->name('admin.keyword.delete');
    });

    Route::group(['prefix' => 'attribute'], function () {
        Route::get('', [AdminAttributeController::class, 'index'])->name('admin.attribute.index');
        Route::get('create', [AdminAttributeController::class, 'create'])->name('admin.attribute.create');
        Route::post('create', [AdminAttributeController::class, 'store']);

        Route::get('update/{id}', [AdminAttributeController::class, 'edit'])->name('admin.attribute.update');
        Route::post('update/{id}', [AdminAttributeController::class, 'update']);
        Route::get('hot/{id}', [AdminAttributeController::class, 'hot'])->name('admin.attribute.hot');

        Route::get('delete/{id}', [AdminAttributeController::class, 'delete'])->name('admin.attribute.delete');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('', [AdminUserController::class, 'index'])->name('admin.user.index');

        Route::get('update/{id}', [AdminUserController::class, 'edit'])->name('admin.user.update');
        Route::post('update/{id}', [AdminUserController::class, 'update']);

        Route::get('delete/{id}', [AdminUserController::class, 'delete'])->name('admin.user.delete');
        Route::get('ajax/transaction/{userId}', [AdminUserController::class, 'transaction'])->name('admin.user.transaction');
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::get('', [AdminTransactionController::class, 'index'])->name('admin.transaction.index');
        Route::get('delete/{id}', [AdminTransactionController::class, 'delete'])->name('admin.transaction.delete');
        Route::get('order-delete/{id}', [AdminTransactionController::class, 'deleteOrderItem'])->name('ajax_admin.transaction.order_item');
        Route::get('view-transaction/{id}', [AdminTransactionController::class, 'getTransactionDetail'])->name('ajax.admin.transaction.detail');
        Route::get('action/{action}/{id}', [AdminTransactionController::class, 'getAction'])->name('admin.action.transaction');
    });


    Route::group(['prefix' => 'product'], function () {
        Route::get('', [AdminProductController::class, 'index'])->name('admin.product.index');
        Route::get('create', [AdminProductController::class, 'create'])->name('admin.product.create');
        Route::post('create', [AdminProductController::class, 'store']);

        Route::get('hot/{id}', [AdminProductController::class, 'hot'])->name('admin.product.hot');
        Route::get('active/{id}', [AdminProductController::class, 'active'])->name('admin.product.active');
        Route::get('update/{id}', [AdminProductController::class, 'edit'])->name('admin.product.update');
        Route::post('update/{id}', [AdminProductController::class, 'update']);

        Route::get('delete/{id}', [AdminProductController::class, 'delete'])->name('admin.product.delete');
        Route::get('delete-image/{id}', [AdminProductController::class, 'deleteImage'])->name('admin.product.delete_image');
    });

    Route::group(['prefix' => 'rating'], function () {
        Route::get('', [AdminRatingController::class, 'index'])->name('admin.rating.index');
        Route::get('delete/{id}', [AdminRatingController::class, 'delete'])->name('admin.rating.delete');
    });
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('import', [AdminInventoryController::class, 'getWarehousing'])->name('admin.inventory.warehousing');
        Route::get('import/add', [AdminInventoryController::class, 'add'])->name('admin.warehousing.add');
        Route::post('import/add', [AdminInventoryController::class, 'store']);
        Route::get('import/update/{id}', [AdminInventoryController::class, 'edit'])->name('admin.warehousing.update');
        Route::post('import/update/{id}', [AdminInventoryController::class, 'update']);
        Route::get('import/delete/{id}', [AdminInventoryController::class, 'delete'])->name('admin.warehousing.delete');

        Route::get('export', [AdminInventoryController::class, 'getOutOfStock'])->name('admin.export.out_of_stock');
        Route::get('export/add', [AdminInventoryController::class, 'exportAdd'])->name('admin.export.add');
        Route::post('export/add', [AdminInventoryController::class, 'exportStore']);
        Route::get('export/update/{id}', [AdminInventoryController::class, 'exportEdit'])->name('admin.export.update');
        Route::post('export/update/{id}', [AdminInventoryController::class, 'exportUpdate']);
        Route::get('export/delete/{id}', [AdminInventoryController::class, 'exportDelete'])->name('admin.export.delete');
    });

    Route::group(['prefix' => 'menu'], function () {
        Route::get('', [AdminMenuController::class, 'index'])->name('admin.menu.index');
        Route::get('create', [AdminMenuController::class, 'create'])->name('admin.menu.create');
        Route::post('create', [AdminMenuController::class, 'store']);

        Route::get('update/{id}', [AdminMenuController::class, 'edit'])->name('admin.menu.update');
        Route::post('update/{id}', [AdminMenuController::class, 'update']);

        Route::get('active/{id}', [AdminMenuController::class, 'active'])->name('admin.menu.active');
        Route::get('hot/{id}', [AdminMenuController::class, 'hot'])->name('admin.menu.hot');
        Route::get('delete/{id}', [AdminMenuController::class, 'delete'])->name('admin.menu.delete');
    });
    Route::group(['prefix' => 'comment'], function () {
        Route::get('', [AdminCommentController::class, 'index'])->name('admin.comment.index');
        Route::get('delete/{id}', [AdminCommentController::class, 'delete'])->name('admin.comment.delete');
    });

    Route::group(['prefix' => 'article'], function () {
        Route::get('', [AdminArticleController::class, 'index'])->name('admin.article.index');
        Route::get('create', [AdminArticleController::class, 'create'])->name('admin.article.create');
        Route::post('create', [AdminArticleController::class, 'store']);

        Route::get('update/{id}', [AdminArticleController::class, 'edit'])->name('admin.article.update');
        Route::post('update/{id}', [AdminArticleController::class, 'update']);

        Route::get('active/{id}', [AdminArticleController::class, 'active'])->name('admin.article.active');
        Route::get('hot/{id}', [AdminArticleController::class, 'hot'])->name('admin.article.hot');
        Route::get('delete/{id}', [AdminArticleController::class, 'delete'])->name('admin.article.delete');
    });

    Route::group(['prefix' => 'slide'], function () {
        Route::get('', [AdminSlideController::class, 'index'])->name('admin.slide.index');
        Route::get('create', [AdminSlideController::class, 'create'])->name('admin.slide.create');
        Route::post('create', [AdminSlideController::class, 'store']);

        Route::get('update/{id}', [AdminSlideController::class, 'edit'])->name('admin.slide.update');
        Route::post('update/{id}', [AdminSlideController::class, 'update']);

        Route::get('active/{id}', [AdminSlideController::class, 'active'])->name('admin.slide.active');
        Route::get('hot/{id}', [AdminSlideController::class, 'hot'])->name('admin.slide.hot');
        Route::get('delete/{id}', [AdminSlideController::class, 'delete'])->name('admin.slide.delete');
    });

    Route::group(['prefix' => 'event'], function () {
        Route::get('', [AdminEventController::class, 'index'])->name('admin.event.index');
        Route::get('create', [AdminEventController::class, 'create'])->name('admin.event.create');
        Route::post('create', [AdminEventController::class, 'store']);

        Route::get('update/{id}', [AdminEventController::class, 'edit'])->name('admin.event.update');
        Route::post('update/{id}', [AdminEventController::class, 'update']);

        Route::get('delete/{id}', [AdminEventController::class, 'delete'])->name('admin.event.delete');
    });

    Route::group(['prefix' => 'page-static'], function () {
        Route::get('', [AdminStaticController::class, 'index'])->name('admin.static.index');
        Route::get('create', [AdminStaticController::class, 'create'])->name('admin.static.create');
        Route::post('create', [AdminStaticController::class, 'store']);

        Route::get('update/{id}', [AdminStaticController::class, 'edit'])->name('admin.static.update');
        Route::post('update/{id}', [AdminStaticController::class, 'update']);

        Route::get('delete/{id}', [AdminStaticController::class, 'delete'])->name('admin.static.delete');
    });

    //        Route::group(['prefix' => 'setting'], function(){
    //			Route::get('','AdminSettingController@index')->name('admin.setting.index');
    //		});

    Route::group(['prefix' => 'discount-code'], function () {
        Route::get('', [DiscountCodeController::class, 'index'])->name('admin.discount.code.index');
        Route::get('create', [DiscountCodeController::class, 'create'])->name('admin.discount.code.create');
        Route::post('create', [DiscountCodeController::class, 'store']);

        Route::get('update/{id}', [DiscountCodeController::class, 'edit'])->name('admin.discount.code.update');
        Route::post('update/{id}', [DiscountCodeController::class, 'update']);

        Route::get('delete/{id}', [DiscountCodeController::class, 'delete'])->name('admin.discount.code.delete');
    });
});
