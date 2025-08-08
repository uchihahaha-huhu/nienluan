<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestMenu;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of menus
     */
    public function index()
    {
        $menus = Menu::all();

        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show form to create a new menu
     */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Store a newly created menu in storage
     */
    public function store(AdminRequestMenu $request)
    {
        $data = $request->except('_token');
        $data['mn_slug'] = Str::slug($request->mn_name);
        // Nếu Menu model có $timestamps = true thì bạn không cần gán created_at manually:
        // $data['created_at'] = Carbon::now();

        try {
            Menu::create($data);
            return redirect()->route('admin.menu.index')->with('success', 'Thêm menu thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Thêm menu thất bại, vui lòng thử lại!');
        }
    }

    /**
     * Show form to edit specified menu
     */
    public function edit($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu không tồn tại!');
        }

        return view('admin.menu.update', compact('menu'));
    }

    /**
     * Update the specified menu in storage
     */
    public function update(AdminRequestMenu $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu không tồn tại!');
        }

        $data = $request->except('_token');
        $data['mn_slug'] = Str::slug($request->mn_name);
        // Nếu Menu model có $timestamps = true thì không cần gán updated_at manually:
        // $data['updated_at'] = Carbon::now();

        try {
            $menu->update($data);
            return redirect()->route('admin.menu.index')->with('success', 'Cập nhật menu thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật menu thất bại, vui lòng thử lại!');
        }
    }

    /**
     * Toggle active status of menu
     */
    public function active($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu không tồn tại!');
        }

        try {
            $menu->mn_status = !$menu->mn_status;
            $menu->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái menu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật trạng thái menu thất bại!');
        }
    }

    /**
     * Toggle hot status of menu
     */
    public function hot($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu không tồn tại!');
        }

        try {
            $menu->mn_hot = !$menu->mn_hot;
            $menu->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái hot menu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật trạng thái hot menu thất bại!');
        }
    }

    /**
     * Delete menu by id
     */
    public function delete($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return redirect()->route('admin.menu.index')->with('error', 'Menu không tồn tại hoặc đã bị xóa!');
        }

        try {
            $menu->delete();
            return redirect()->back()->with('success', 'Xóa menu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa menu thất bại, vui lòng thử lại!');
        }
    }
}
