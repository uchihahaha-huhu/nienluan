<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::paginate(10);

        $viewData = [
            'contacts' => $contacts
        ];

        return view('admin.contact.index', $viewData);
    }

    public function delete($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return redirect()->back()->with('error', 'Liên hệ không tồn tại!');
        }

        try {
            $contact->delete();
            return redirect()->back()->with('success', 'Xóa liên hệ thành công!');
        } catch (\Exception $e) {
            // Có thể ghi log lỗi nếu cần: \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa liên hệ thất bại. Vui lòng thử lại.');
        }
    }
}
