<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestAttribute;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminAttributeController extends Controller
{
    public function index()
    {
        $attibutes = Attribute::with('category:id,c_name')->orderByDesc('id')
            ->get();

        $viewData = [
            'attibutes' => $attibutes
        ];

        return view('admin.attribute.index', $viewData);
    }

    public function create()
    {
        $categories = Category::select('id', 'c_name')->get();
        $attribute_type = Attribute::getListType();

        return view('admin.attribute.create', compact('categories', 'attribute_type'));
    }

    public function store(AdminRequestAttribute $request)
    {
        $data = $request->except('_token');
        $data['atb_slug'] = Str::slug($request->atb_name);
        $data['created_at'] = Carbon::now();

        try {
            $id = Attribute::insertGetId($data);
            return redirect()->route('admin.attribute.index')->with('success', 'Thêm thuộc tính thành công!');
        } catch (\Exception $e) {
            // Log lỗi nếu cần \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Thêm thuộc tính thất bại, vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $attribute = Attribute::find($id);
        if (!$attribute) {
            return redirect()->route('admin.attribute.index')->with('error', 'Thuộc tính không tồn tại!');
        }

        $categories = Category::select('id', 'c_name')->get();
        $attribute_type = Attribute::getListType();

        return view('admin.attribute.update', compact('attribute', 'categories', 'attribute_type'));
    }

    public function update(AdminRequestAttribute $request, $id)
    {
        $attribute = Attribute::find($id);
        if (!$attribute) {
            return redirect()->route('admin.attribute.index')->with('error', 'Thuộc tính không tồn tại!');
        }

        $data = $request->except('_token');
        $data['atb_slug'] = Str::slug($request->atb_name);
        $data['updated_at'] = Carbon::now();

        try {
            $attribute->update($data);
            return redirect()->route('admin.attribute.index')->with('success', 'Cập nhật thuộc tính thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật thuộc tính thất bại, vui lòng thử lại.');
        }
    }

    public function delete($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return redirect()->back()->with('error', 'Thuộc tính không tồn tại!');
        }

        try {
            $attribute->delete();
            return redirect()->back()->with('success', 'Xóa thuộc tính thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa thuộc tính thất bại, vui lòng thử lại.');
        }
    }
}
