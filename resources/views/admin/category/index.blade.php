@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Quản lý danh mục sản phẩm</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{  route('admin.category.index') }}"> Category</a></li>
            <li class="active"> List </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-header">
                    <h3 class="box-title"><a href="{{ route('admin.category.create') }}" class="btn btn-primary">Thêm mới <i class="fa fa-plus"></i></a></h3>
               </div>
                @if(session('success'))
    <div class="alert alert-success alert-dismissible" style="margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible" style="margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('error') }}
    </div>
@endif
                <div class="box-body">
                   <div class="col-md-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">STT</th>
                                    <th>Name</th>
                                    <th>Avatar</th>
                                    <th>Status</th>
                                    <th>Hot</th>
                                    {{-- <th>Time</th> --}}
                                    <th>Action</th>
                                </tr>
                                @if ($categories)
                                    @foreach($categories as $key => $category)
                                        <tr>
                                            <td>{{ (($categories->currentPage() - 1) * $categories->perPage()) + ( $key + 1)  }}</td>
                                            <td>{{ $category->c_name }}</td>
                                            <td>
                                                <img src="{{ pare_url_file($category->c_avatar ?? '') ?? '/images/no-image.jpg' }}" onerror="this.onerror=null;this.src='/images/no-image.jpg';"
                                                     alt="" class="img-thumbnail" style="width: 80px;height: 80px;">
                                            </td>
                                            <td>
                                                @if ($category->c_status == 1)
                                                    <a href="{{ route('admin.category.active', $category->id) }}" class="label label-info">Hiển thị</a>
                                                @else
                                                    <a href="{{ route('admin.category.active', $category->id) }}" class="label label-default">Ẩn</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($category->c_hot == 1)
                                                    <a href="{{ route('admin.category.hot', $category->id) }}" class="label label-info">Mặc định</a>
                                                @else
                                                    <a href="{{ route('admin.category.hot', $category->id) }}" class="label label-default">Nổi bật</a>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $category->created_at->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s') }}</td> --}}

                                            <td>
                                                <a href="{{ route('admin.category.update', $category->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="{{  route('admin.category.delete', $category->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! $categories->links() !!}
                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
    </section>
    <!-- /.content -->
@stop
