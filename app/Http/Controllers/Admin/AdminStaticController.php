<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PageStatic;

class AdminStaticController extends Controller
{
    public function index()
    {
        if (!check_admin()) {
            return redirect()->route('get.admin.index');
        }

        $statics = PageStatic::all();
        return view('admin.static.index', compact('statics'));
    }

    public function create()
    {
        $type = (new PageStatic())->getType();
        return view('admin.static.create', compact('type'));
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['created_at'] = Carbon::now();

        try {
            PageStatic::insert($data);
            return redirect()->back()->with('success', 'Thêm trang tĩnh thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi thêm trang tĩnh, vui lòng thử lại!');
        }
    }

    public function edit($id)
    {
        $static = PageStatic::find($id);
        if (!$static) {
            return redirect()->route('admin.static.index')->with('error', 'Trang tĩnh không tồn tại!');
        }

        $type = (new PageStatic())->getType();
        return view('admin.static.update', compact('static', 'type'));
    }

    public function update(Request $request, $id)
    {
        $static = PageStatic::find($id);
        if (!$static) {
            return redirect()->route('admin.static.index')->with('error', 'Trang tĩnh không tồn tại!');
        }

        $data = $request->except('_token');
        // Khi cập nhật nên gán updated_at, không gán created_at
        $data['updated_at'] = Carbon::now();

        try {
            $static->update($data);
            return redirect()->back()->with('success', 'Cập nhật trang tĩnh thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi cập nhật trang tĩnh, vui lòng thử lại!');
        }
    }

    public function delete($id)
    {
        $static = PageStatic::find($id);
        if (!$static) {
            return redirect()->back()->with('error', 'Trang tĩnh không tồn tại hoặc đã bị xóa!');
        }

        try {
            $static->delete();
            return redirect()->back()->with('success', 'Xóa trang tĩnh thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa trang tĩnh thất bại, vui lòng thử lại!');
        }
    }
}
