<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestCategory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;

class AdminCategoryController extends AdminController
{
    public function index()
    {
        $categories = Category::paginate(10);

        $viewData = [
            'categories' => $categories
        ];

        return view('admin.category.index', $viewData);
    }

    public function create()
    {
        $categories = $this->getCategoriesSort();
        return view('admin.category.create', compact('categories'));
    }

    public function store(AdminRequestCategory $request)
    {
        $data = $request->except('_token', 'c_avatar');
        $data['c_slug'] = Str::slug($request->c_name);
        $data['created_at'] = Carbon::now();

        if ($request->c_avatar) {
            $image = upload_image('c_avatar');
            if ($image['code'] == 1) {
                $data['c_avatar'] = $image['name'];
            }
        }

        try {
            $id = Category::insertGetId($data);
            return redirect()->route('admin.category.index')->with('success', 'Thêm danh mục thành công!');
        } catch (\Exception $e) {
            // Log error nếu cần: \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Thêm danh mục thất bại, vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $categories = $this->getCategoriesSort();
        return view('admin.category.update', compact('category', 'categories'));
    }

    public function update(AdminRequestCategory $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.category.index')->with('error', 'Danh mục không tồn tại!');
        }

        $data = $request->except('_token', 'c_avatar');
        $data['c_slug'] = Str::slug($request->c_name);
        $data['updated_at'] = Carbon::now();

        if ($request->c_avatar) {
            $image = upload_image('c_avatar');
            if ($image['code'] == 1) {
                $data['c_avatar'] = $image['name'];
            }
        }

        try {
            $category->update($data);
            return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật danh mục thất bại, vui lòng thử lại.');
        }
    }

    public function active($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại!');
        }

        try {
            $category->c_status = !$category->c_status;
            $category->save();
            return redirect()->back()->with('success', 'Thay đổi trạng thái danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thay đổi trạng thái danh mục thất bại.');
        }
    }

    public function hot($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại!');
        }

        try {
            $category->c_hot = !$category->c_hot;
            $category->save();
            return redirect()->back()->with('success', 'Thay đổi trạng thái nổi bật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Thay đổi trạng thái nổi bật thất bại.');
        }
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại!');
        }

        try {
            $category->delete();
            return redirect()->back()->with('success', 'Xóa danh mục thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa danh mục thất bại, vui lòng thử lại.');
        }
    }

    protected function getCategoriesSort()
    {
        $categories = Category::where('c_status', Category::STATUS_ACTIVE)
            ->select('id', 'c_parent_id', 'c_name')->get();

        $listCategoriesSort = [];
        Category::recursive($categories, $parent = 0, $level = 1, $listCategoriesSort);
        return $listCategoriesSort;
    }
}
