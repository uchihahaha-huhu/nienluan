<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestSlide;
use Carbon\Carbon;
use App\Models\Slide;

class AdminSlideController extends Controller
{
    public function index()
    {
        if (!check_admin()) {
            return redirect()->route('get.admin.index');
        }

        $slides = Slide::paginate(20);
        return view('admin.slide.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slide.create');
    }

    public function store(AdminRequestSlide $request)
    {
        $data = $request->except('_token', 'sd_avatar');
        $data['created_at'] = Carbon::now();

        if ($request->hasFile('sd_avatar')) {
            $image = upload_image('sd_avatar');
            if ($image['code'] == 1) {
                $data['sd_image'] = $image['name'];
            }
        }

        try {
            Slide::insert($data);
            return redirect()->route('admin.slide.index')->with('success', 'Thêm slide thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Thêm slide thất bại, vui lòng thử lại!');
        }
    }

    public function edit($id)
    {
        $slide = Slide::find($id);

        if (!$slide) {
            return redirect()->route('admin.slide.index')->with('error', 'Slide không tồn tại!');
        }

        return view('admin.slide.update', compact('slide'));
    }

    public function update(AdminRequestSlide $request, $id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return redirect()->route('admin.slide.index')->with('error', 'Slide không tồn tại!');
        }

        $data = $request->except('_token', 'sd_avatar');
        // Không gán created_at khi update, nếu muốn có thể dùng updated_at hoặc để Eloquent tự động
        $data['updated_at'] = Carbon::now();

        if ($request->hasFile('sd_avatar')) {
            $image = upload_image('sd_avatar');
            if ($image['code'] == 1) {
                $data['sd_image'] = $image['name'];
            }
        }

        try {
            $slide->update($data);
            return redirect()->route('admin.slide.index')->with('success', 'Cập nhật slide thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật slide thất bại, vui lòng thử lại!');
        }
    }

    public function active($id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return redirect()->route('admin.slide.index')->with('error', 'Slide không tồn tại!');
        }

        try {
            $slide->sd_active = ! $slide->sd_active;
            $slide->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái slide thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật trạng thái slide thất bại!');
        }
    }

    public function delete($id)
    {
        $slide = Slide::find($id);
        if (!$slide) {
            return redirect()->route('admin.slide.index')->with('error', 'Slide không tồn tại hoặc đã bị xóa!');
        }

        try {
            $slide->delete();
            return redirect()->back()->with('success', 'Xóa slide thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa slide thất bại, vui lòng thử lại!');
        }
    }
}
