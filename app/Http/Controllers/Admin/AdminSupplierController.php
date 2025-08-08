<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Carbon\Carbon;

class AdminSupplierController extends Controller
{
    /**
     * Hiển thị danh sách nhà cung cấp
     */
    public function index()
    {
        $suppliers = Supplier::orderByDesc('id')->get();

        return view('admin.supplier.index', [
            'supplieres' => $suppliers
        ]);
    }

    /**
     * Hiển thị form tạo nhà cung cấp mới
     */
    public function create()
    {
        return view('admin.supplier.create');
    }

    /**
     * Lưu nhà cung cấp mới vào database
     */
    public function store(Request $request)
    {
        // Bạn có thể thêm validate tại đây hoặc dùng AdminRequestSupplier riêng cho validate
        $data = $request->except('_token');
        // Nếu model Supplier bật $timestamps = true, không cần gán created_at thủ công
        $data['created_at'] = Carbon::now();

        try {
            Supplier::create($data);
            return redirect()->route('admin.ncc.index')->with('success', 'Thêm nhà cung cấp thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Thêm nhà cung cấp thất bại, vui lòng thử lại!');
        }
    }

    /**
     * Hiển thị form chỉnh sửa nhà cung cấp
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return redirect()->route('admin.ncc.index')->with('error', 'Nhà cung cấp không tồn tại!');
        }

        return view('admin.supplier.update', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return redirect()->route('admin.ncc.index')->with('error', 'Nhà cung cấp không tồn tại!');
        }

        $data = $request->except('_token');

        try {
            $supplier->update($data);
            return redirect()->route('admin.ncc.index')->with('success', 'Cập nhật nhà cung cấp thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Cập nhật nhà cung cấp thất bại, vui lòng thử lại!');
        }
    }

    /**
     * Xóa nhà cung cấp
     */
    public function delete($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return redirect()->back()->with('error', 'Nhà cung cấp không tồn tại hoặc đã bị xóa!');
        }

        try {
            $supplier->delete();
            return redirect()->back()->with('success', 'Xóa nhà cung cấp thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa nhà cung cấp thất bại, vui lòng thử lại!');
        }
    }
}
