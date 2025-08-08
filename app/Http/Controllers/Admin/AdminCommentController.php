<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index()
    {
        $comments = Comments::with([
            'user:id,name',
            'product:id,pro_name'
        ])->paginate(10);

        $viewData = [
            'comments' => $comments
        ];

        return view('admin.comment.index', $viewData);
    }

    public function delete($id)
    {
        $comment = Comments::find($id);

        if (!$comment) {
            return redirect()->back()->with('error', 'Bình luận không tồn tại!');
        }

        try {
            $comment->delete();
            return redirect()->back()->with('success', 'Xóa bình luận thành công!');
        } catch (\Exception $e) {
            // Bạn có thể log lỗi nếu muốn: \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa bình luận thất bại. Vui lòng thử lại.');
        }
    }
}
