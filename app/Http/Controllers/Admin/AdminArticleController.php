<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestArticle;
use App\Models\Article;
use App\Models\Menu;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('menu:id,mn_name')->paginate(10);
        $viewData = [
            'articles' => $articles
        ];

        return view('admin.article.index', $viewData);
    }

    public function create()
    {
        $menus = Menu::all();

        return view('admin.article.create',compact('menus'));
    }

    public function store(AdminRequestArticle $request)
    {
        $data = $request->except('_token','a_avatar','a_position_1','a_position_2');
        $data['a_slug']     = Str::slug($request->a_name);
        $data['created_at'] = Carbon::now();

        if ($request->a_position_1) {
            $data['a_position_1'] = 1;
        }

        if ($request->a_position_2) {
            $data['a_position_2'] = 1;
        }
        
        if ($request->a_avatar) {
            $image = upload_image('a_avatar');
            if ($image['code'] == 1) 
                $data['a_avatar'] = $image['name'];
        } 

        try {
            $id = Article::insertGetId($data);
            return redirect()->route('admin.article.index')->with('success', 'Thêm mới bài viết thành công!');
        } catch (\Exception $e) {
            // Log lỗi nếu cần: \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Thêm mới bài viết thất bại. Vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->route('admin.article.index')->with('error', 'Bài viết không tồn tại!');
        }

        $menus = Menu::all();
        return view('admin.article.update',compact('menus','article'));
    }

    public function update(AdminRequestArticle $request, $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->route('admin.article.index')->with('error', 'Bài viết không tồn tại!');
        }

        $data = $request->except('_token','a_avatar','a_position_1','a_position_2');
        $data['a_slug']     = Str::slug($request->a_name);
        $data['updated_at'] = Carbon::now();

        $data['a_position_1'] = $request->a_position_1 ? 1 : 0;
        $data['a_position_2'] = $request->a_position_2 ? 1 : 0;

        if ($request->a_avatar) {
            $image = upload_image('a_avatar');
            if ($image['code'] == 1) 
                $data['a_avatar'] = $image['name'];
        } 

        try {
            $article->update($data);
            return redirect()->route('admin.article.index')->with('success', 'Cập nhật bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật bài viết thất bại. Vui lòng thử lại.');
        }
    }

    public function active($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->back()->with('error', 'Bài viết không tồn tại!');
        }
        try {
            $article->a_active = ! $article->a_active;
            $article->save();

            return redirect()->back()->with('success', 'Thay đổi trạng thái kích hoạt thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thay đổi trạng thái kích hoạt thất bại.');
        }
    }

    public function hot($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->back()->with('error', 'Bài viết không tồn tại!');
        }
        try {
            $article->a_hot = ! $article->a_hot;
            $article->save();

            return redirect()->back()->with('success', 'Thay đổi trạng thái nổi bật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thay đổi trạng thái nổi bật thất bại.');
        }
    }

    public function delete($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return redirect()->back()->with('error', 'Bài viết không tồn tại!');
        }

        try {
            $article->delete();
            return redirect()->back()->with('success', 'Xóa bài viết thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa bài viết thất bại.');
        }
    }
}
