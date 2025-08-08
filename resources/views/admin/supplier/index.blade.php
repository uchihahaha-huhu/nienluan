@extends('layouts.app_master_admin')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Quản lý nhà cung cấp</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{  route('admin.ncc.index') }}"> Nhà cung cấp</a></li>
            <li class="active"> List </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <div class="box-header">
                    <h3 class="box-title"><a href="{{ route('admin.ncc.create') }}" class="btn btn-primary">Thêm mới <i class="fa fa-plus"></i></a></h3>
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
                                    <th style="width: 10px">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>SDT</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </tbody>
                            @if (isset($supplieres))
                                    @foreach($supplieres as  $key => $item)
                                        <tr>
                                            <td>{{ ($key + 1) }}</td>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->sl_name }}</td>
                                            <td>{{ $item->sl_email }}</td>
                                            <td>{{ $item->sl_phone }}</td>
                                                                        <td>
                                {{ $item->created_at ? $item->created_at->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s') : 'N/A' }}
                            </td>
                                            <td>
                                                <a href="{{ route('admin.ncc.update', $item->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="{{  route('admin.ncc.delete', $item->id) }}" class="btn btn-xs btn-danger js-delete-confirm"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box -->
    </section>
    <!-- /.content -->
@stop
