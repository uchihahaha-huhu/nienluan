<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        try{
            $categories = Category::with('children:id,c_name,c_slug,c_parent_id')
                ->where('c_parent_id',0)
                ->where('c_status',1)
                ->select('id','c_name','c_slug','c_avatar','c_parent_id')
                ->get();

            View::share('categories', $categories);


            $categoriesHot = Category::where('c_hot', 1)->select('id','c_name','c_slug','c_avatar')->get();
            View::share('categoriesHot', $categoriesHot);

           $menus = Menu::where('mn_status', 1)->select('id', 'mn_name', 'mn_slug')->get();
            View::share('menus',$menus);
        }catch (\Exception $exception) {

        }
    }
}
