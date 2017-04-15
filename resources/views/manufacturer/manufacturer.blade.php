@extends('layouts.app')

@section('title')
    Danh sách nguồn gốc / xuất sứ
@endsection

@section('location')
    <li> Danh sách nhà sản xuất </li>
@endsection

@section('content')
    <div ng-controller="ManufacturerController">

        {{-- !TÌM KIẾM NGUỒN GỐC XUẤT SỨ!--}}
        <div class="row">
            <div class="col-lg-2 col-xs-4">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#createManufacturer"> Thêm mới
                    </button>
                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li> <a href="#" data-toggle="modal" data-target="#inputFromFile">
                                <span class="glyphicon glyphicon-file"></span> Nhập từ file </a> </li>
                        <li class="divider"></li>
                        <li> <a href="">
                                <span class="glyphicon glyphicon-download-alt"></span> Tải mẫu nhập </a> </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-xs-6">
                <button class="btn btn-sm btn-info"> Tổng số: @{{manufacturers.length}} mục </button>
            </div>
            <div class="col-lg-4 col-xs-6">
                <input ng-change="searchManufacturer()" ng-model="term" class="form-control input-sm" placeholder="Nhập tên nguồn gốc...">
            </div>
        </div>

        <hr/>

        {{--!DANH SÁCH NGUỒN GỐC!--}}
        <table class="w3-table table-hover table-bordered w3-centered">
            <thead class="w3-blue-grey">
                <th> STT </th>
                <th> Thương hiệu </th>
                <th> Quốc gia </th>
                <th> Xóa </th>
            </thead>
            <tbody>
            <tr ng-show="manufacturers.length > 0" class="item" dir-paginate="manufacturer in manufacturers | itemsPerPage: 7" ng-click="readManufacturer(manufacturer)">
                <td data-toggle="modal" data-target="#readManufacturer"> @{{ $index+1 }}</td>
                <td data-toggle="modal" data-target="#readManufacturer"> @{{ manufacturer.name }} </td>
                <td data-toggle="modal" data-target="#readManufacturer"> @{{ manufacturer.country }}</td>
                <td>
                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteManufacturer">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>
            <tr class="item" ng-show="manufacturers.length==0">
                <td colspan="4"> Không có dữ liệu </td>
            </tr>
            </tbody>
        </table>

        {{-- PHÂN TRANG --}}
        <div style="margin-left: 35%; bottom:0; position: fixed;">
            <dir-pagination-controls></dir-pagination-controls>
        </div>

        {{-- !TẠO NHÀ CUNG CẤP MỚI! --}}
        <div class="modal fade" id="createManufacturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <form class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Thêm nhà sản xuất mới </h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3"> Thương hiệu </label>
                                <div class="col-sm-9">
                                    <input ng-model="new.name" type="text" class="form-control input-sm" placeholder="Nhập tên...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Quốc gia </label>
                                <div class="col-sm-9">
                                    <input ng-model="new.country" type="text" class="form-control input-sm" placeholder="Nhập quốc gia...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Mô tả </label>
                                <div class="col-sm-9">
                                    <textarea ng-model="new.description" class="form-control"> </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button ng-click="createManufacturer()" type="button" class="btn btn-sm btn-info"> Xác nhận </button>
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Hủy </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- !NHẬP TỪ FILE! --}}
        <div class="modal fade" id="inputFromFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="{{route('importManufacturerFromFile')}}" method="post"> {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Nhập từ File </h4>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="file" accept=".xlsx">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info"> Xác nhận </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"> Hủy </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- !XEM, SỬA THÔNG TIN NHÀ SẢN XUẤT! --}}
        <div class="modal fade" id="readManufacturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <form class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> </h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3"> Thương hiệu </label>
                                <div class="col-sm-9">
                                    <input id="name" ng-model="selected.name" type="text" class="form-control input-sm" placeholder="Nhập tên...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Quốc gia </label>
                                <div class="col-sm-9">
                                    <input id="country" ng-model="selected.country" type="text" class="form-control input-sm" placeholder="Nhập quốc gia...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"> Mô tả </label>
                                <div class="col-sm-9">
                                    <textarea id="description" ng-model="selected.description" class="form-control"> </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="updateManufacturer" type="button" class="btn btn-sm btn-info"> Chỉnh sửa </button>
                            <button id="submit" ng-click="updateManufacturer()" type="submit" class="btn btn-sm btn-success" hidden> Xác nhận </button>
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Hủy </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- !NHẬP TỪ FILE! --}}
        <div class="modal fade" id="inputFromFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="" method="post"> {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Nhập từ File </h4>
                        </div>
                        <div class="modal-body">
                            <input type="file" name="file" accept=".xlsx">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info"> Xác nhận </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"> Hủy </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- !XÓA NHÀ CUNG CẤP!--}}
        <div class="modal fade" id="deleteManufacturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title w3-text-red" id="myModalLabel"> Xóa nhà cung cấp </h4>
                    </div>
                    <div class="modal-body">
                        Bạn thực sự muốn xóa nhà sản xuất này?
                    </div>
                    <div class="modal-footer">
                        <button ng-click="deleteManufacturer()" type="submit" class="btn btn-danger"> Xác nhận </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"> Hủy </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

{{-- !ANGULARJS! --}}
@section('script')
    <script src="{{ asset('angularJS/ManufacturerController.js') }}"></script>
@endsection