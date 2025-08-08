<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;

class AdminEventController extends Controller
{
    /**
     * Hiển thị danh sách sự kiện
     */
    public function index()
    {
        if (!check_admin()) {
            return redirect()->route('get.admin.index');
        }

        $events = Event::orderByDesc('id')->get();

        return view('admin.event.index', compact('events'));
    }

    /**
     * Hiển thị form tạo mới sự kiện
     */
    public function create()
    {
        return view('admin.event.create');
    }

    /**
     * Lưu sự kiện mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'e_name' => 'required|string|max:255',
            'e_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'e_date_start' => 'nullable|date',
            'e_date_end' => 'nullable|date|after_or_equal:e_date_start',
        ], [
            'e_name.required' => 'Tên sự kiện là bắt buộc',
            'e_banner.image' => 'Ảnh banner phải là file ảnh hợp lệ',
        ]);

        $data = $request->except('_token', 'e_banner', 'e_position_1', 'e_position_2', 'e_position_3', 'e_position_4');

        // Gán các vị trí checkbox, nếu không có được gán 0
        $data['e_position_1'] = $request->has('e_position_1') ? 1 : 0;
        $data['e_position_2'] = $request->has('e_position_2') ? 1 : 0;
        $data['e_position_3'] = $request->has('e_position_3') ? 1 : 0;
        $data['e_position_4'] = $request->has('e_position_4') ? 1 : 0;

        // Upload ảnh banner nếu có
        if ($request->hasFile('e_banner')) {
            $image = upload_image('e_banner');
            if ($image['code'] == 1) {
                $data['e_banner'] = $image['name'];
            } else {
                return redirect()->back()->withInput()->with('error', 'Upload ảnh banner thất bại. Vui lòng thử lại.');
            }
        }

        try {
            Event::create($data);
            return redirect()->route('admin.event.index')->with('success', 'Thêm sự kiện thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Thêm sự kiện thất bại. Vui lòng thử lại!');
        }
    }

    /**
     * Hiển thị form chỉnh sửa sự kiện
     */
    public function edit($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->route('admin.event.index')->with('error', 'Sự kiện không tồn tại!');
        }

        return view('admin.event.update', compact('event'));
    }

    /**
     * Cập nhật sự kiện
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->route('admin.event.index')->with('error', 'Sự kiện không tồn tại!');
        }

        // Validate dữ liệu
        $request->validate([
            'e_name' => 'required|string|max:255',
            'e_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'e_date_start' => 'nullable|date',
            'e_date_end' => 'nullable|date|after_or_equal:e_date_start',
        ], [
            'e_name.required' => 'Tên sự kiện là bắt buộc',
            'e_banner.image' => 'Ảnh banner phải là file ảnh hợp lệ',
        ]);

        $data = $request->except('_token', 'e_banner', 'e_position_1', 'e_position_2', 'e_position_3', 'e_position_4');

        // Gán các vị trí checkbox
        $data['e_position_1'] = $request->has('e_position_1') ? 1 : 0;
        $data['e_position_2'] = $request->has('e_position_2') ? 1 : 0;
        $data['e_position_3'] = $request->has('e_position_3') ? 1 : 0;
        $data['e_position_4'] = $request->has('e_position_4') ? 1 : 0;

        // Upload ảnh banner nếu có
        if ($request->hasFile('e_banner')) {
            $image = upload_image('e_banner');
            if ($image['code'] == 1) {
                $data['e_banner'] = $image['name'];
            } else {
                return redirect()->back()->withInput()->with('error', 'Upload ảnh banner thất bại. Vui lòng thử lại.');
            }
        }

        try {
            $event->update($data);
            return redirect()->route('admin.event.index')->with('success', 'Cập nhật sự kiện thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Cập nhật sự kiện thất bại. Vui lòng thử lại!');
        }
    }

    /**
     * Xóa sự kiện
     */
    public function delete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->back()->with('error', 'Sự kiện không tồn tại hoặc đã bị xóa!');
        }

        try {
            $event->delete();
            return redirect()->back()->with('success', 'Xóa sự kiện thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Xóa sự kiện thất bại. Vui lòng thử lại!');
        }
    }
}
