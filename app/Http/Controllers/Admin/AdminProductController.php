<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequestProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Keyword;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $products = Product::with('category:id,c_name');

        if ($id = $request->id) {
            $products->where('id', $id);
        }

        if ($name = $request->name) {
            $products->where('pro_name', 'like', '%' . $name . '%');
        }

        if ($category = $request->category) {
            $products->where('pro_category_id', $category);
        }

        $products = $products->orderByDesc('id')->paginate(10);
        $categories = Category::all();

        return view('admin.product.index', [
            'products' => $products,
            'categories' => $categories,
            'query' => $request->query(),
        ]);
    }

    /**
     * Hiển thị form tạo sản phẩm mới
     */
    public function create()
    {
        $categories = Category::all();
        $attributeOld = [];
        $keywordOld = [];
        $attributes = $this->syncAttributeGroup();
        $keywords = Keyword::all();
        $supplier = Supplier::all();

        return view('admin.product.create', compact('categories', 'attributeOld', 'attributes', 'keywords', 'keywordOld', 'supplier'));
    }

    /**
     * Lưu sản phẩm mới và redirect về index với flash
     */
    public function store(AdminRequestProduct $request)
    {
        $data = $request->except('_token', 'pro_avatar', 'attribute', 'keywords', 'file', 'pro_file');

        // Thêm dòng này để trường pro_file luôn có giá trị dù không upload file
        $data['pro_file'] = null;

        $data['pro_file'] = null; // trường hợp bạn muốn NULL
        // hoặc $data['pro_file'] = ''; // trường hợp muốn rỗng, tùy thiết kế db

        $data['pro_slug'] = Str::slug($request->pro_name);
        $data['created_at'] = Carbon::now();

        if ($request->pro_sale) {
            $data['pro_sale'] = $request->pro_sale;
        }

        try {
            if ($request->hasFile('pro_avatar')) {
                $image = upload_image('pro_avatar');
                if ($image['code'] == 1) {
                    $data['pro_avatar'] = $image['name'];
                }
            }

            if ($request->hasFile('pro_file')) {
                $image = upload_image('pro_file');
                if ($image['code'] == 1) {
                    $data['pro_file'] = $image['name'];
                }
            }

            $id = Product::insertGetId($data);

            if ($id) {
                $this->syncAttribute($request->attribute, $id);
                $this->syncKeyword($request->keywords, $id);

                if ($request->file) {
                    $this->syncAlbumImageAndProduct($request->file, $id);
                }
            }

            return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi thêm sản phẩm: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('pro_avatar', 'file'), // loại bỏ file upload để tránh lỗi hiển thị log
            ]);

            return redirect()->route('admin.product.index')->withInput()->with('error', 'Thêm sản phẩm thất bại, vui lòng thử lại!');
        }
    }


    /**
     * Hiển thị form chỉnh sửa sản phẩm
     */
    public function edit($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.product.index')->with('error', 'Sản phẩm không tồn tại!');
        }

        $categories = Category::all();
        $attributes = $this->syncAttributeGroup();
        $keywords = Keyword::all();
        $supplier = Supplier::all();

        $attributeOld = DB::table('products_attributes')->where('pa_product_id', $id)->pluck('pa_attribute_id')->toArray();
        $keywordOld   = DB::table('products_keywords')->where('pk_product_id', $id)->pluck('pk_keyword_id')->toArray();
        $images       = DB::table('product_images')->where("pi_product_id", $id)->get();

        return view('admin.product.update', compact('categories', 'product', 'attributes', 'attributeOld', 'keywords', 'supplier', 'keywordOld', 'images'));
    }

    /**
     * Cập nhật sản phẩm và redirect về index với flash
     */
    public function update(AdminRequestProduct $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.product.index')->with('error', 'Sản phẩm không tồn tại!');
        }

        $data = $request->except('_token', 'pro_avatar', 'attribute', 'keywords', 'file', 'pro_sale', 'pro_file');
        $data['pro_slug'] = Str::slug($request->pro_name);
        $data['updated_at'] = Carbon::now();

        if ($request->pro_sale) {
            $data['pro_sale'] = $request->pro_sale;
        }

        try {
            if ($request->hasFile('pro_avatar')) {
                $image = upload_image('pro_avatar');
                if ($image['code'] == 1) {
                    $data['pro_avatar'] = $image['name'];
                }
            }

            if ($request->hasFile('pro_file')) {
                $image = upload_image('pro_file');
                if ($image['code'] == 1) {
                    $data['pro_file'] = $image['name'];
                }
            }

            $update = $product->update($data);

            if ($update) {
                $this->syncAttribute($request->attribute, $id);
                $this->syncKeyword($request->keywords, $id);

                if ($request->file) {
                    $this->syncAlbumImageAndProduct($request->file, $id);
                }
            }

            // Redirect về trang danh sách sản phẩm sau khi cập nhật thành công
            return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            // Redirect về trang form sửa sản phẩm (edit) với input và lỗi
            return redirect()->route('admin.product.index', $id)->withInput()->with('error', 'Cập nhật sản phẩm thất bại, vui lòng thử lại!');
        }
    }

    public function syncAlbumImageAndProduct($files, $productID)
    {
        foreach ($files as $fileImage) {
            $ext = $fileImage->getClientOriginalExtension();
            $allowedExt = ['png', 'jpg', 'jpeg', 'PNG', 'JPG'];

            if (!in_array($ext, $allowedExt)) {
                continue; // Bỏ qua file không hợp lệ
            }

            $filename = date('Y-m-d__') . Str::slug(pathinfo($fileImage->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $ext;
            $path = public_path() . '/uploads/' . date('Y/m/d/');
            if (!\File::exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileImage->move($path, $filename);

            DB::table('product_images')->insert([
                'pi_name'       => $fileImage->getClientOriginalName(),
                'pi_slug'       => $filename,
                'pi_product_id' => $productID,
                'created_at'    => Carbon::now(),
            ]);
        }
    }

    public function active($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }

        try {
            $product->pro_active = !$product->pro_active;
            $product->save();

            return redirect()->back()->with('success', 'Cập nhật trạng thái hiển thị sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật trạng thái sản phẩm thất bại!');
        }
    }

    public function hot($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }

        try {
            $product->pro_hot = !$product->pro_hot;
            $product->save();

            return redirect()->back()->with('success', 'Cập nhật trạng thái nổi bật sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật trạng thái nổi bật sản phẩm thất bại!');
        }
    }

    private function syncKeyword($keywords, $idProduct)
    {
        if (!empty($keywords)) {
            $datas = [];
            foreach ($keywords as $keyword) {
                $datas[] = [
                    'pk_product_id' => $idProduct,
                    'pk_keyword_id' => $keyword,
                ];
            }

            DB::table('products_keywords')->where('pk_product_id', $idProduct)->delete();
            DB::table('products_keywords')->insert($datas);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại hoặc đã bị xóa!');
        }

        try {
            $product->delete();
            return redirect()->back()->with('success', 'Xóa sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa sản phẩm thất bại, vui lòng thử lại!');
        }
    }

    public function deleteImage($imageID)
    {
        $image = DB::table('product_images')->where('id', $imageID)->first();
        if (!$image) {
            return redirect()->back()->with('error', 'Ảnh sản phẩm không tồn tại hoặc đã bị xóa!');
        }

        try {
            DB::table('product_images')->where('id', $imageID)->delete();
            return redirect()->back()->with('success', 'Xóa ảnh sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xóa ảnh sản phẩm thất bại, vui lòng thử lại!');
        }
    }

    protected function syncAttribute($attributes, $idProduct)
    {
        if (!empty($attributes)) {
            $datas = [];
            foreach ($attributes as $value) {
                $datas[] = [
                    'pa_product_id' => $idProduct,
                    'pa_attribute_id' => $value,
                ];
            }

            if (!empty($datas)) {
                DB::table('products_attributes')->where('pa_product_id', $idProduct)->delete();
                DB::table('products_attributes')->insert($datas);
            }
        }
    }

    public function syncAttributeGroup()
    {
        $attributes = Attribute::all();
        $groupAttribute = [];

        foreach ($attributes as $attribute) {
            $key = $attribute->gettype($attribute->atb_type)['name'];
            $groupAttribute[$key][] = $attribute->toArray();
        }

        return $groupAttribute;
    }
}
