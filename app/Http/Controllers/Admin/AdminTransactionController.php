<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Product;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query để lọc dữ liệu
        $transactions = Transaction::query();

        if ($request->id) {
            $transactions->where('id', $request->id);
        }

        if ($email = $request->email) {
            $transactions->where('tst_email', 'like', '%' . $email . '%');
        }

        if ($type = $request->type) {
            if ($type == 1) {
                $transactions->where('tst_user_id', '<>', 0);
            } else {
                $transactions->where('tst_user_id', 0);
            }
        }

        if ($status = $request->status) {
            $transactions->where('tst_status', $status);
        }

        // Nếu export, trả về file Excel không paginate
        if ($request->export) {
            $allTransactions = $transactions->orderByDesc('id')->get();
            return Excel::download(new TransactionExport($allTransactions), 'don-hang.xlsx');
        }

        // Lấy dữ liệu có phân trang cho trang index
        $transactions = $transactions->orderByDesc('id')->paginate(10);

        return view('admin.transaction.index', [
            'transactions' => $transactions,
            'query' => $request->query(),
        ]);
    }

    public function getTransactionDetail(Request $request, $id)
    {
        if ($request->ajax()) {
            $orders = Order::with('product:id,pro_name,pro_slug,pro_avatar')
                ->where('od_transaction_id', $id)
                ->get();

            $html = view('components.orders', compact('orders'))->render();

            return response()->json(['html' => $html]);
        }

        abort(404);
    }

    public function deleteOrderItem(Request $request, $id)
    {
        if ($request->ajax()) {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['code' => 404, 'message' => 'Order không tồn tại'], 404);
            }

            $money = $order->od_qty * $order->od_price;

            try {
                // Giảm tổng tiền trong transaction
                Transaction::where('id', $order->od_transaction_id)->decrement('tst_total_money', $money);

                // Xóa order item
                $order->delete();

                return response()->json(['code' => 200, 'message' => 'Xóa đơn hàng thành công']);
            } catch (\Exception $e) {
                // \Log::error($e->getMessage());
                return response()->json(['code' => 500, 'message' => 'Xóa đơn hàng thất bại'], 500);
            }
        }

        abort(404);
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Giao dịch không tồn tại hoặc đã bị xóa!');
        }

        try {
            // Xóa tất cả đơn hàng thuộc transaction trước
            Order::where('od_transaction_id', $id)->delete();

            // Xóa transaction
            $transaction->delete();

            return redirect()->back()->with('success', 'Xóa giao dịch thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa giao dịch thất bại, vui lòng thử lại!');
        }
    }

    /**
     * Xử lý các trạng thái action của giao dịch:
     * - process: xử lý
     * - success: thành công (đẩy tồn kho)
     * - cancel: hủy (trả tồn kho)
     */
    public function getAction(Request $request, $action, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            if ($request->ajax()) {
                return response()->json(['code' => 404, 'message' => 'Giao dịch không tồn tại'], 404);
            }
            return redirect()->back()->with('error', 'Giao dịch không tồn tại!');
        }

        try {
            switch ($action) {
                case 'process':
                    $transaction->tst_status = 2;
                    break;

                case 'success':
                    $transaction->tst_status = 3;
                    $this->syncDecrementProduct($id);
                    break;

                case 'cancel':
                    $transaction->tst_status = -1;
                    // Bỏ comment để trả lại tồn kho khi hủy đơn
                    $this->syncIncrementProduct($id);
                    break;

                default:
                    if ($request->ajax()) {
                        return response()->json(['code' => 400, 'message' => 'Action không hợp lệ'], 400);
                    }
                    return redirect()->back()->with('error', 'Action không hợp lệ!');
            }

            $transaction->tst_admin_id = get_data_user('admins');
            $transaction->save();

            if ($request->ajax()) {
                return response()->json(['code' => 200, 'message' => 'Cập nhật trạng thái giao dịch thành công']);
            }

            return redirect()->back()->with('success', 'Cập nhật trạng thái giao dịch thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            if ($request->ajax()) {
                return response()->json(['code' => 500, 'message' => 'Cập nhật trạng thái thất bại'], 500);
            }
            return redirect()->back()->with('error', 'Cập nhật trạng thái thất bại, vui lòng thử lại!');
        }
    }

    protected function syncIncrementProduct($transactionID)
    {
        $orders = Order::where('od_transaction_id', $transactionID)->get();

        foreach ($orders as $order) {
            \DB::table('products')
                ->where('id', $order->od_product_id)
                ->increment('pro_number', $order->od_qty);
        }
    }

    protected function syncDecrementProduct($transactionID)
    {
        $orders = Order::where('od_transaction_id', $transactionID)->get();

        foreach ($orders as $order) {
            \DB::table('products')
                ->where('id', $order->od_product_id)
                ->decrement('pro_number', $order->od_qty);
        }
    }
}
