<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Product;

class AdminRatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::with([
            'product:id,pro_name,pro_slug',
            'user:id,name'
        ])->orderByDesc('id')->paginate(10);

        return view('admin.rating.index', compact('ratings'));
    }

    public function delete($id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return redirect()->back()->with('error', 'Đánh giá không tồn tại!');
        }

        try {
            $product = Product::find($rating->r_product_id);

            if ($product) {
                // Giảm số lượng review tổng và tổng sao, tránh âm
                $product->pro_review_total = max(0, $product->pro_review_total - 1);
                $product->pro_review_star = max(0, $product->pro_review_star - $rating->r_number);
                $product->save();
            }

            $rating->delete();

            return redirect()->back()->with('success', 'Xóa đánh giá thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa đánh giá thất bại, vui lòng thử lại!');
        }
    }
}
