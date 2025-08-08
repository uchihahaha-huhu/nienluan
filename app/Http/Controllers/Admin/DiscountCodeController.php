<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Http\Requests\DiscountCodeRequest;

class DiscountCodeController extends Controller
{
    public function index()
    {
        $discountCodes = DiscountCode::orderByDesc('id')->paginate(10);
        return view('admin.discount_code.index', compact('discountCodes'));
    }

    public function create()
    {
        return view('admin.discount_code.create');
    }

    public function store(DiscountCodeRequest $request)
    {
        \DB::beginTransaction();
        try {
            $discountCode = new DiscountCode();
            $discountCode->d_code = $request->d_code;
            $discountCode->d_number_code = $request->d_number_code;
            $discountCode->d_date_start = $request->d_date_start;
            $discountCode->d_date_end = $request->d_date_end;
            $discountCode->d_percentage = $request->d_percentage;
            $discountCode->save();
            \DB::commit();

            return redirect()->route('admin.discount.code.index')->with('success', 'Thêm mới thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            // Optional: \Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function edit($id)
    {
        $discount = DiscountCode::find($id);

        if (!$discount) {
            return redirect()->route('admin.discount.code.index')->with('error', 'Mã giảm giá không tồn tại!');
        }

        return view('admin.discount_code.update', compact('discount'));
    }

    public function update(DiscountCodeRequest $request, $id)
    {
        \DB::beginTransaction();
        try {
            $discountCode = DiscountCode::find($id);

            if (!$discountCode) {
                return redirect()->route('admin.discount.code.index')->with('error', 'Mã giảm giá không tồn tại!');
            }

            $discountCode->d_code = $request->d_code;
            $discountCode->d_number_code = $request->d_number_code;
            $discountCode->d_date_start = $request->d_date_start;
            $discountCode->d_date_end = $request->d_date_end;
            $discountCode->d_percentage = $request->d_percentage;
            $discountCode->save();

            \DB::commit();

            return redirect()->route('admin.discount.code.index')->with('success', 'Cập nhật thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            // Optional: \Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    public function delete($id)
    {
        $discount = DiscountCode::find($id);

        if (!$discount) {
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại hoặc đã bị xóa');
        }

        try {
            $discount->delete();
            return redirect()->route('admin.discount.code.index')->with('success', 'Xóa mã giảm giá thành công');
        } catch (\Exception $exception) {
            // Optional: \Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Xóa mã giảm giá thất bại, vui lòng thử lại');
        }
    }
}
