<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Export;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    /**
     * Nhập kho
     */
    public function getWarehousing()
    {
        $warehouses = Warehouse::orderByDesc('id')->paginate(10);

        return view('admin.inventory.import', compact('warehouses'));
    }

    public function add()
    {
        $products = Product::all();
        return view('admin.inventory.import_add', compact('products'));
    }

    public function store(Request $request)
    {
        // Bạn nên thêm validate ở đây hoặc tạo DiscountCodeRequest riêng
        $data = $request->except('_token');

        try {
            Warehouse::create($data);
            return redirect()->route('admin.inventory.warehousing')->with('success', 'Nhập kho thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu dữ liệu. Vui lòng thử lại!');
        }
    }

    public function edit($id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return redirect()->route('admin.inventory.warehousing')->with('error', 'Kho không tồn tại!');
        }

        $products = Product::all();
        return view('admin.inventory.import_update', compact('products', 'warehouse'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return redirect()->route('admin.inventory.warehousing')->with('error', 'Kho không tồn tại!');
        }

        $data = $request->except('_token');

        try {
            $warehouse->fill($data)->save();
            return redirect()->route('admin.inventory.warehousing')->with('success', 'Cập nhật kho thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật kho thất bại. Vui lòng thử lại!');
        }
    }

    public function delete($id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return redirect()->route('admin.inventory.warehousing')->with('error', 'Kho không tồn tại hoặc đã bị xóa!');
        }

        try {
            $warehouse->delete();
            return redirect()->route('admin.inventory.warehousing')->with('success', 'Xóa kho thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa kho thất bại. Vui lòng thử lại!');
        }
    }

    /**
     * Xuất kho
     */
    public function getOutOfStock(Request $request)
    {
        $inventoryExport = Order::with('product');

        if ($request->time) {
            $time = $this->getStartEndTime($request->time, []);
            $inventoryExport->whereBetween('created_at', $time);
        }

        $inventoryExport = $inventoryExport->orderByDesc('id')->paginate(20);

        return view('admin.inventory.export', [
            'inventoryExport' => $inventoryExport,
            'query' => $request->query()
        ]);
    }

    public function exportAdd()
    {
        $transactions = Transaction::all();
        return view('admin.inventory.export_add', compact('transactions'));
    }

    public function exportStore(Request $request)
    {
        $data = $request->except('_token');

        try {
            Export::create($data);
            return redirect()->route('admin.export.out_of_stock')->with('success', 'Xuất kho thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi lưu dữ liệu xuất kho. Vui lòng thử lại!');
        }
    }

    public function exportEdit($id)
    {
        $export = Export::find($id);

        if (!$export) {
            return redirect()->route('admin.export.out_of_stock')->with('error', 'Phiếu xuất không tồn tại!');
        }

        $transactions = Transaction::all();
        return view('admin.inventory.export_update', compact('transactions', 'export'));
    }

    public function exportUpdate(Request $request, $id)
    {
        $export = Export::find($id);
        if (!$export) {
            return redirect()->route('admin.export.out_of_stock')->with('error', 'Phiếu xuất không tồn tại!');
        }

        $data = $request->except('_token');

        try {
            $export->fill($data)->save();
            return redirect()->route('admin.export.out_of_stock')->with('success', 'Cập nhật phiếu xuất thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật phiếu xuất thất bại. Vui lòng thử lại!');
        }
    }

    public function exportDelete($id)
    {
        $export = Export::find($id);
        if (!$export) {
            return redirect()->route('admin.export.out_of_stock')->with('error', 'Phiếu xuất không tồn tại hoặc đã bị xóa!');
        }

        try {
            $export->delete();
            return redirect()->route('admin.export.out_of_stock')->with('success', 'Xóa phiếu xuất thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa phiếu xuất thất bại. Vui lòng thử lại!');
        }
    }
}
