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
                    <input type="text" class="form-control" name="a_name" placeholder="" autocomplete="off" value="{{  $article->a_name ?? old('a_name') }}">
                    <span class="text-danger js-error-a_name"></span>
                    @if ($errors->first('a_name'))
                        <span class="text-danger">{{ $errors->first('a_name') }}</span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="a_position_1" {{ ($article->a_position_1 ?? 0) == 1 ? "checked" : "" }} value="1"> Trung tâm
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="a_position_2" {{ ($article->a_position_2 ?? 0) == 1 ? "checked" : "" }} value="1"> Sidebar
                            </label>
                        </div>
                    </div>
                </div>
                <span class="text-danger js-error-position" style="display: block; margin-top: -10px; margin-bottom: 15px;"></span>
                {{-- <div class="form-group ">
                    <label for="exampleInputEmail1">Description</label>
                    <textarea name="a_description" class="form-control" cols="5" rows="2" autocomplete="off">{{  $article->a_description ?? old('a_description') }}</textarea>
                    @if ($errors->first('a_description'))
                        <span class="text-danger">{{ $errors->first('a_description') }}</span>
                    @endif
                </div> --}}
                <div class="form-group ">
                    <label class="control-label">Danh mục <b class="col-red">(*)</b></label>
                    <select name="a_menu_id" class="form-control ">
                        <option value="">__Click__</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" {{ ($article->a_menu_id ?? 0) == $menu->id ? "selected='selected'" : "" }}>
                                {{  $menu->mn_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger js-error-a_menu_id"></span>
                    @if ($errors->first('a_menu_id'))
                        <span class="text-danger">{{ $errors->first('a_menu_id') }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Nội dung</h3>
            </div>
            <div class="box-body">
                <div class="form-group ">
                    <label for="exampleInputEmail1">Content</label>
                    <textarea name="a_content" id="content" class="form-control textarea" cols="5" rows="2" >{{ $article->a_content ?? '' }}</textarea>
                    <span class="text-danger js-error-a_content"></span>
                    @if ($errors->first('a_content'))
                        <span class="text-danger">{{ $errors->first('a_content') }}</span>
                    @endif
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
                <img src="{{ pare_url_file($article->a_avatar ?? '') ?: '/images/no-image.jpg' }}" 
                     onerror="this.onerror=null;this.src='/images/no-image.jpg';" alt="" 
                     class="img-thumbnail" style="width: 200px; height: 200px;"> 
            </div>
            <div style="position:relative;">
                <a class="btn btn-primary" href="javascript:;">
                    Choose File...
                    <input type="file" 
                           name="a_avatar" 
                           size="40" 
                           class="js-upload" 
                           accept=".png,.jpg,.jpeg"
                           style="position:absolute; z-index:2; top:0; left:0; 
                                  filter: alpha(opacity=0);
                                  -ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)'; 
                                  opacity:0; background-color:transparent; color:transparent;">
                </a>
                &nbsp;
                <span class="label label-info" id="upload-file-info"></span>
            </div>

            <!-- Chú thích định dạng file -->
            <small style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                Chỉ chấp nhận định dạng PNG, JPG hoặc JPEG.
            </small>

            <!-- Hiển thị lỗi validate (nếu có) -->
            @if ($errors->first('a_avatar'))
                <small class="text-danger" style="display: block;">
                    {{ $errors->first('a_avatar') }}
                </small>
            @else
                <small class="text-danger error-a-avatar" style="display:none;"></small>
            @endif
        </div>
    </div>
</div>

    <div class="col-sm-12 clearfix">
        <div class="box-footer text-center"> 
            <a href="{{ route('admin.article.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</a> 
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ isset($article) ? "Cập nhật" : "Thêm mới" }} </button> 
        </div>
    </div>
</form>

<script src="{{  asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    var options = {
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
    };
    CKEDITOR.replace('content', options);
</script>

<script>
$(document).ready(function() {
    $('form[role="form"]').submit(function(e) {
        // Reset lỗi
        $('.js-error-a_name').text('');
        $('.js-error-position').text('');
        $('.js-error-a_menu_id').text('');
        $('.js-error-a_content').text('');

        let valid = true;

        // Các giá trị input
        let a_name = $('input[name="a_name"]').val().trim();
        let a_menu_id = $('select[name="a_menu_id"]').val();
        // Lấy nội dung ckeditor
        let a_content = CKEDITOR.instances['content'].getData().trim();

        // Kiểm tra position (ít nhất 1 checkbox được chọn)
        let position1 = $('input[name="a_position_1"]').is(':checked');
        let position2 = $('input[name="a_position_2"]').is(':checked');

        // Validate a_name
        if(a_name.length === 0) {
            $('.js-error-a_name').text('Vui lòng nhập tên bài viết.');
            valid = false;
        }

        // Validate position
        if(!position1 && !position2) {
            $('.js-error-position').text('Vui lòng chọn ít nhất một vị trí.');
            valid = false;
        }

        // Validate a_menu_id
        if(!a_menu_id) {
            $('.js-error-a_menu_id').text('Vui lòng chọn danh mục.');
            valid = false;
        }

        // Validate nội dung bài viết
        if(a_content.length === 0) {
            $('.js-error-a_content').text('Vui lòng nhập nội dung bài viết.');
            valid = false;
        }

        if(!valid) {
            e.preventDefault();
            // Scroll lên vị trí lỗi đầu tiên
            $('html, body').animate({
                scrollTop: $('.text-danger:visible').first().offset().top - 100
            }, 500);
        }
    });
});
</script>
