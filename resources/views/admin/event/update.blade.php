@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Cập nhật sự kiện</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-body">
                    <form role="form" action="" method="POST" enctype="multipart/form-data">
                         @csrf
                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('e_name') ? 'has-error' : '' }}">
                                <label for="name">Title <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" name="e_name" value="{{ $event->e_name }}"  placeholder="Name ...">
                                @if ($errors->first('e_name'))
                                    <span class="text-danger">{{ $errors->first('e_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group {{ $errors->first('e_link') ? 'has-error' : '' }}">
                                <label for="name">Link <span class="text-danger">(*)</span></label>
                                <input type="text" class="form-control" name="e_link"  value="{{ $event->e_link }}" placeholder="Link ...">
                                @if ($errors->first('e_link'))
                                    <span class="text-danger">{{ $errors->first('e_link') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="name">Home 1 </label>
                                        <input type="checkbox" name="e_position_1" {{ $event->e_position_1 ?? 0 == 1 ? "checked='checked'" : "" }} value="1">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="name">Home 2 </label>
                                        <input type="checkbox" name="e_position_2" {{ $event->e_position_2 ?? 0 == 1 ? "checked='checked'" : "" }} value="1">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="name">Home 3 </label>
                                        <input type="checkbox" name="e_position_3" {{ $event->e_position_3 ?? 0 == 1 ? "checked='checked'" : "" }} value="1">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="name">Home 4 </label>
                                        <input type="checkbox" name="e_position_4" {{ $event->e_position_4 ?? 0 == 1 ? "checked='checked'" : "" }} value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="col-sm-8">
    <h3 class="box-title">Banner</h3>
    <div class="box-body block-images">
        <div style="margin-bottom: 10px">
            <img src="{{ pare_url_file($event->e_banner ?? '') ?: '/images/no-image.jpg' }}"
                 onerror="this.onerror=null;this.src='/images/no-image.jpg';"
                 alt=""
                 class="img-thumbnail"
                 style="width: 100%; height: 250px;">
        </div>
        <div style="position:relative;">
            <a class="btn btn-primary" href="javascript:;">
                Choose File...
                <input type="file"
                       name="e_banner"
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
        <!-- Dòng chú thích định dạng file -->
        <small style="color: red; font-size: 12px; display: block; margin-top: 5px;">
            Chỉ chấp nhận định dạng PNG, JPG hoặc JPEG.
        </small>
        <!-- Hiển thị lỗi validate nếu có -->
        @if ($errors->first('e_banner'))
            <small class="text-danger" style="display: block;">
                {{ $errors->first('e_banner') }}
            </small>
        @else
            <small class="text-danger error-e-banner" style="display:none;"></small>
        @endif
    </div>
</div>

                        <div class="col-sm-12">
                            <div class="box-footer text-center">
                                <a href="{{ route('admin.event.index') }}" class="btn btn-danger">
                                Quay lại <i class="fa fa-undo"></i></a>
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