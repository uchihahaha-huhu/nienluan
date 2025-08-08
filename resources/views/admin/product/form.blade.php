<form role="form" action="" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-sm-8">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Thông tin cơ bản</h3>
            </div>
            <div class="box-body">

                <div class="form-group ">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="pro_name" placeholder="Sản phẩm ...."
                        autocomplete="off" value="{{  $product->pro_name ?? old('pro_name') }}">
                    <span class="text-danger js-error-pro_name"></span>
                    @if ($errors->first('pro_name'))
                    <span class="text-danger">{{ $errors->first('pro_name') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Giá sản phẩm</label>
                            <input type="text" name="pro_price" value="{{  $product->pro_price ?? old('pro_price',0) }}"
                                class="form-control" data-type="currency" placeholder="15.000.000">
                            <span class="text-danger js-error-pro_price"></span>
                            @if ($errors->first('pro_price'))
                            <span class="text-danger">{{ $errors->first('pro_price') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Giảm giá (%)</label>
                            <input type="number" name="pro_sale" value="{{  $product->pro_sale ?? old('pro_sale',0) }}"
                                class="form-control" placeholder="5" min="0" max="100">
                            <span class="text-danger js-error-pro_sale"></span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Số lượng</label>
                            <input type="number" name="pro_number"
                                value="{{  $product->pro_number ?? old('pro_number',0) }}" class="form-control"
                                placeholder="5" min="0" step="1">
                            <span class="text-danger js-error-pro_number"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="control-label">Danh mục <b class="col-red">(*)</b></label>
                    <select name="pro_category_id" class="form-control ">
                        <option value="">__Click__</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ ($product->pro_category_id ?? '') == $category->id ? "selected='selected'" : "" }}>
                            {{ $category->c_name }}
                        </option>
                        @endforeach
                    </select>
                    <span class="text-danger js-error-pro_category_id"></span>
                    @if ($errors->first('pro_category_id'))
                    <span class="text-danger">{{ $errors->first('pro_category_id') }}</span>
                    @endif
                </div>

                <div class="form-group ">
                    <label class="control-label">Nhà CC <b class="col-red">(*)</b></label>
                    <select name="pro_supplier_id" class="form-control ">
                        <option value="">__Click__</option>
                        @foreach($supplier as $item)
                        <option value="{{ $item->id }}"
                            {{ ($product->pro_supplier_id ?? 0) == $item->id ? "selected='selected'" : "" }}>
                            {{ $item->sl_name }}
                        </option>
                        @endforeach
                    </select>
                    <span class="text-danger js-error-pro_supplier_id"></span>
                    @if ($errors->first('pro_supplier_id'))
                    <span class="text-danger">{{ $errors->first('pro_supplier_id') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Thuộc tính</h3>
            </div>
            <div class="box-body">
                @foreach($attributes as $key => $attribute)
                <div class="form-group col-sm-3">
                    <h4 style="border-bottom: 1px solid #dedede;padding-bottom: 10px;">{{ $key }}</h4>
                    @foreach($attribute as $item)
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="attribute[]"
                                {{ in_array($item['id'], $attributeOld ) ? "checked"  : '' }} value="{{ $item['id'] }}">
                            {{ $item['atb_name'] }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            <hr>
        </div>

        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Nội dung</h3>
            </div>
            <div class="box-body">
                <div class="form-group ">
                    <label for="exampleInputEmail1">Content</label>
                    <textarea name="pro_content" id="pro_content" class="form-control textarea" cols="5"
                        rows="2">{{ $product->pro_content ?? '' }}</textarea>
                    <span class="text-danger js-error-pro_content"></span>
                </div>
            </div>
        </div>
    </div>

<div class="col-sm-4">
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Ảnh đại diện</h3>
        </div>
        <div class="box-body block-images">
            <div style="margin-bottom: 10px">
                <img src="{{ pare_url_file($product->pro_avatar ?? '') ?: '/images/no-image.jpg' }}"
                     onerror="this.onerror=null;this.src='/images/no-image.jpg';" alt="" class="img-thumbnail"
                     style="width: 200px; height: 200px;">
            </div>
            <div style="position:relative;">
                <a class="btn btn-primary" href="javascript:;">
                    Choose File...
                    <input type="file"
                           name="pro_avatar"
                           size="40"
                           class="js-upload"
                           accept=".png,.jpg,.jpeg"
                           style="position:absolute; z-index:2; top:0; left:0; 
                                  filter: alpha(opacity=0); -ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';
                                  opacity:0; background-color:transparent; color:transparent;">
                </a>
                &nbsp;
                <span class="label label-info" id="upload-file-info"></span>
            </div>

            <!-- Note hướng dẫn định dạng file -->
            <small style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                Chỉ chấp nhận định dạng PNG, JPG hoặc JPEG.
            </small>

            <!-- Hiển thị lỗi validaton (nếu có) -->
            @if ($errors->first('pro_avatar'))
                <small class="text-danger" style="display: block;">
                    {{ $errors->first('pro_avatar') }}
                </small>
            @else
                <small class="text-danger error-pro-avatar" style="display:none;"></small>
            @endif
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Album ảnh</h3>
        </div>
        <div class="box-body">
            @if (isset($images))
                <div class="row" style="margin-bottom: 15px;">
                    @foreach($images as $item)
                        <div class="col-sm-2">
                            <a href="{{ route('admin.product.delete_image', $item->id) }}" style="display: block;">
                                <img src="{{ pare_url_file($item->pi_slug) }}" style="width: 100%; height: auto;">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <div class="file-loading">
                    <input id="images" type="file" name="file[]" multiple class="file"
                           data-overwrite-initial="false" data-min-file-count="0" accept=".png,.jpg,.jpeg">
                </div>
            </div>
        </div>
    </div>

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Thao Tác</h3>
        </div>
        <div class="box-body">
            <div class="box-footer text-center">
                <a href="{{ route('admin.product.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> {{ isset($product) ? "Cập nhật" : "Thêm mới" }}
                </button>
            </div>
        </div>
    </div>
</div>

</form>

<script>
ckeditor('pro_content');
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all"
    rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" type="text/javascript">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js"
    type="text/javascript"></script>

<script>
$(document).ready(function() {

    $('form[role="form"]').submit(function(e) {
        // Xóa các thông báo lỗi cũ
        $('.text-danger.js-error-pro_name, \
          .text-danger.js-error-pro_price, \
          .text-danger.js-error-pro_sale, \
          .text-danger.js-error-pro_number, \
          .text-danger.js-error-pro_category_id, \
          .text-danger.js-error-pro_supplier_id, \
          .text-danger.js-error-pro_content').text('');

        let valid = true;

        // Lấy giá trị
        const pro_name = $('input[name="pro_name"]').val().trim();
        const pro_price = $('input[name="pro_price"]').val().replace(/[.,]/g, '')
    .trim(); // Loại bỏ dấu '.' ',' nếu có
        const pro_sale = $('input[name="pro_sale"]').val().trim();
        const pro_number = $('input[name="pro_number"]').val().trim();
        const pro_category_id = $('select[name="pro_category_id"]').val();
        const pro_supplier_id = $('select[name="pro_supplier_id"]').val();
        // const pro_content = $('#pro_content').val().trim();
console.log({
  pro_name, pro_price, pro_sale, pro_number, pro_category_id, pro_supplier_id
});

        // Validate pro_name
        if (pro_name.length === 0) {
            $('.js-error-pro_name').text('Vui lòng nhập tên sản phẩm.');
            valid = false;
        }

        // Validate pro_price - số, >=0
        if (pro_price.length === 0 || isNaN(pro_price) || Number(pro_price) < 0) {
            $('.js-error-pro_price').text('Giá sản phẩm phải là số không âm.');
            valid = false;
        }

        // Validate pro_sale - số từ 0 đến 100
        if (pro_sale.length > 0) {
            if (isNaN(pro_sale) || Number(pro_sale) < 0 || Number(pro_sale) > 100) {
                $('.js-error-pro_sale').text('Giảm giá phải là số từ 0 đến 100.');
                valid = false;
            }
        }

        // Validate pro_number - số nguyên >=0
        if (pro_number.length === 0 || isNaN(pro_number) || parseInt(pro_number) < 0 || !Number
            .isInteger(Number(pro_number))) {
            $('.js-error-pro_number').text('Số lượng phải là số nguyên không âm.');
            valid = false;
        }

        // Validate pro_category_id
        if (!pro_category_id) {
            $('.js-error-pro_category_id').text('Vui lòng chọn danh mục.');
            valid = false;
        }

        // Validate pro_supplier_id
        if (!pro_supplier_id) {
            $('.js-error-pro_supplier_id').text('Vui lòng chọn nhà cung cấp.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            // Scroll lên chỗ đầu form có lỗi để người dùng thấy
            $('html, body').animate({
                scrollTop: $('.text-danger:visible').first().offset().top - 100
            }, 500);
        }

    });

});
</script>