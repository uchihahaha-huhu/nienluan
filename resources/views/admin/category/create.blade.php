@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Thêm mới danh mục sản phẩm</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.category.index') }}"> Category</a></li>
            <li class="active"> Create </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-body">
                    <form id="form-create-category" role="form" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('c_name') ? 'has-error' : '' }}">
                                <label for="name">Name <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" name="c_name" placeholder="Name ...">
                                @if ($errors->first('c_name'))
                                    <span class="text-danger">{{ $errors->first('c_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name">Parent <span class="text-danger">(*)</span></label>
                                <select name="c_parent_id" class="form-control" id="">
                                    <option value="0">__ROOT__</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            <?php 
                                            $str = '';
                                            for($i = 0; $i < $category->level; $i++) { 
                                                echo $str; 
                                                $str .= '-- '; 
                                            }
                                            ?>
                                            {{ $category->c_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Avatar</h3>
                                </div>
                                <div class="box-body block-images">
                                    <div style="margin-bottom: 10px">
                                        <img src="/images/no-image.jpg" onerror="this.onerror=null;this.src='/images/no-image.jpg';" alt="" class="img-thumbnail" 
                                            style="width: 200px; height: 200px;">
                                    </div>
                                    <div style="position:relative;">
                                        <a class="btn btn-primary" href="javascript:;">
                                            Choose File...
                                            <input type="file" 
                                                style="position:absolute; z-index:2; top:0; left:0; 
                                                        filter: alpha(opacity=0); -ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';
                                                        opacity:0; background-color:transparent; color:transparent;" 
                                                name="c_avatar" size="40" class="js-upload" accept=".png,.jpg,.jpeg">
                                        </a>
                                        &nbsp;
                                        <span class="label label-info" id="upload-file-info"></span>
                                    </div>
                                    <!-- Note hiển thị định dạng file cho người dùng -->
                                    <small style="color: red; font-size: 12px; display: block; margin-top: 5px;">
                                        Chỉ chấp nhận định dạng PNG, JPG hoặc JPEG.
                                    </small>
                                    <!-- Hiển thị lỗi validate file avatar -->
                                    @if ($errors->first('c_avatar'))
                                        <small class="text-danger" style="display: block;">
                                            {{ $errors->first('c_avatar') }}
                                        </small>
                                    @else
                                        <small class="text-danger error-avatar" style="display:none;"></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="box-footer text-center">
                                <a href="{{ route('admin.category.index') }}" class="btn btn-danger">
                                    Quay lại <i class="fa fa-undo"></i>
                                </a>
                                <button type="submit" class="btn btn-success">Lưu dữ liệu <i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->
    </section>
    <!-- /.content -->
@stop

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-create-category');
        if (!form) return;

        // Hiển thị tên file upload bên cạnh nút Choose File
        const fileInput = form.querySelector('input[name="c_avatar"]');
        const uploadInfo = document.getElementById('upload-file-info');

        if (fileInput && uploadInfo) {
            fileInput.addEventListener('change', function() {
                uploadInfo.textContent = this.files.length > 0 ? this.files[0].name : '';
            });
        }

        form.addEventListener('submit', function(e) {
            // Reset lỗi cũ
            const errorEl = form.querySelector('.error-avatar');
            if (errorEl) {
                errorEl.style.display = 'none';
                errorEl.textContent = '';
            }

            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (!allowedTypes.includes(file.type)) {
                    if (errorEl) {
                        errorEl.textContent = 'Định dạng file không hợp lệ. Vui lòng chọn file PNG, JPG hoặc JPEG.';
                        errorEl.style.display = 'block';
                    } else {
                        alert('Định dạng file không hợp lệ. Vui lòng chọn file PNG, JPG hoặc JPEG.');
                    }
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>
@stop
