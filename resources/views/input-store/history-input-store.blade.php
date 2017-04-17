@extends('layouts.app')

@section('title')
    Larose | Lịch sử nhập kho
@endsection

@section('location')
    <li> Danh sách nhập kho </li>
@endsection

@section('content')
    <div ng-controller="InputStoreController">

        {{-- !TÌM KIẾM SẢN PHẨM!--}}
        <div class="row">
            <div class="col-lg-6 col-xs-6">
                <a href="{{route('createInputStore')}}" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-plus"></span> Nhập hàng </a>
                <a href="{{route('downloadProductTemplate')}}" class="btn btn-sm btn-warning">
                    <span class="glyphicon glyphicon-download-alt"></span> Mẫu nhập </a>
            </div>
            <div class="col-lg-4 col-xs-4">
                <input ng-model="term" class="form-control input-sm" placeholder="Nhập mã đơn hàng...">
            </div>
            <div class="col-lg-2 col-xs-2">
                <button class="btn btn-sm btn-info"> Tổng số: @{{inputStores.length}} mục </button>
            </div>
        </div>

        <hr/>

        @if (session('status'))
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session('status') }}
            </div>
        @endif

        {{--!DANH SÁCH NHÀ CUNG CẤP!--}}
        <table class="w3-table table-hover table-bordered w3-centered">
            <thead class="w3-blue-grey">
            <th> STT </th>
            <th> Mã đơn  </th>
            <th> Nhà cung cấp </th>
            <th> Ngày tạo </th>
            <th> Tổng tiền </th>
            <th> Trạng thái </th>
            <th> Duyệt </th>
            </thead>
            <tbody>
            <tr ng-show="inputStores.length > 0" class="item"
                dir-paginate="inputStore in inputStores | filter:term | itemsPerPage: 7" ng-click="readInputStore(inputStore)">
                <td data-toggle="modal" data-target="#readInputStore"> @{{ $index+1 }} </td>
                <td data-toggle="modal" data-target="#readInputStore"> @{{ inputStore.id }} </td>
                <td data-toggle="modal" data-target="#readInputStore">
                    <p ng-repeat="supplier in suppliers" ng-show="supplier.id==inputStore.supplier_id"> @{{ supplier.name }}
                    </p>
                </td>
                <td data-toggle="modal" data-target="#readInputStore"> @{{ inputStore.created_at | date: "dd/MM/yyyy" }} </td>
                <td data-toggle="modal" data-target="#readInputStore"> @{{ inputStore.total | number:0 }} VNĐ </td>
                <td data-toggle="modal" data-target="#readInputStore">
                    <p ng-show="0==inputStore.status"> Yêu cầu đã bị hủy </p>
                    <p ng-show="1==inputStore.status"> Chờ duyệt </p>
                    <p ng-show="2==inputStore.status"> Đã duyệt </p>
                    <p ng-show="2==inputStore.status"> Đã nhập kho </p>
                </td>
                <td data-toggle="modal" data-target="#changeInputStore">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#deleteSupplier">
                        <span class="glyphicon glyphicon-hand-up"></span>
                    </button>
                </td>
            </tr>
            <tr class="item" ng-show="inputStores.length==0">
                <td colspan="8"> Không có dữ liệu </td>
            </tr>
            </tbody>
        </table>

        {{-- PHÂN TRANG --}}
        <dir-pagination-controls max-size="4"></dir-pagination-controls>

        {{-- Xem biểu mẫu --}}
        <div class="modal fade" id="readInputStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="" method="post"> {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Biểu mẫu </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    Công ty TNHH Larose <br/>
                                    142 Võ Văn Tân, TP.HCM <br/>
                                    ĐT: 0979369407
                                </div>
                                <div class="col-sm-4">
                                    Số: <br/>
                                    Ngày...tháng...năm...
                                </div>
                            </div>
                            <div class="row">
                                <h2 align="center"> <b> Phiếu mua hàng </b> </h2>
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div ng-repeat="supplier in suppliers" ng-show="supplier.id==selected.supplier_id">
                                        Nhà cung cấp: @{{ supplier.name }} <br/>
                                        Địa chỉ: @{{ supplier.address }} <br/>
                                        Số điện thoại: @{{ supplier.phone }}
                                    </div>
                                    <div ng-repeat="account in accounts" ng-show="account.id==selected.account_id">
                                        Hình thức thanh toán: @{{account.name}}
                                    </div>
                                    Tổng tiền: @{{ selected.total | number:0 }} <br/>
                                </div>
                                <div class="col-sm-6">
                                    <div ng-repeat="store in stores" ng-show="store.id==selected.store_id">
                                        Nhập về kho: @{{ store.name }} <br/>
                                        Địa chỉ: @{{ store.address }} <br/>
                                        Số điện thoại: @{{ store.phone }}
                                    </div>
                                    Ngày giao hàng: @{{selected.input_date | date: "dd/MM/yyyy"}}<br/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12"> Ghi chú: @{{ selected.note }}</div>
                            </div>
                            <h1></h1>
                            <div class="row">
                                <table class="w3-table table-bordered w3-centered">
                                    <thead>
                                    <th> STT </th>
                                    <th> Mã vạch </th>
                                    <th> Tên </th>
                                    <th> Đơn vị tính </th>
                                    <th> Số lượng </th>
                                    <th> Giá nhập (VNĐ) </th>
                                    <th> Hạn sử dụng </th>
                                    <th> Thành tiền (VNĐ) </th>
                                    </thead>
                                    <tbody>
                                    <tr ng-show="detail.length > 0" class="item" ng-repeat="item in detail">
                                        <td> @{{ $index+1 }}</td>
                                        <td>
                                            <p ng-repeat="product in products" ng-show="product.id==item.product_id"> @{{ product.code }} </p>
                                        </td>
                                        <td>
                                            <p ng-repeat="product in products" ng-show="product.id==item.product_id"> @{{ product.name }} </p>
                                        </td>
                                        <td> <p ng-repeat="unit in units" ng-show="unit.id==item.unit_id"> @{{ unit.name }} </p> </td>
                                        <td> @{{ item.quantity }} </td>
                                        <td> @{{ item.price | number:0 }} </td>
                                        <td> @{{ item.expried_date | date: "dd/MM/yyyy"}} </td>
                                        <td> @{{ item.quantity * item.price | number: 0 }} </td>
                                    </tr>
                                    <tr class="item" ng-show="detail.length==0">
                                        <td colspan="9"> Không có dữ liệu </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h1></h1>
                            </div>
                            <div class="row">
                                <div class="col-sm-4" align="center">
                                    <b> Giám đốc </b><br/> (Ký tên)
                                </div>
                                <div class="col-sm-4" align="center">
                                    <b> Kế toán </b> <br/> (Ký tên)
                                </div>
                                <div class="col-sm-4" align="center">
                                    <b> Người lập phiếu </b> <br/> (Ký tên)
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"> Đóng </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- THAY ĐỔI TRẠNG THÁI NHẬP HÀNG --}}
        <div class="modal fade" id="changeInputStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <form class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Xác nhận đơn hàng </h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3"> Trạng thái </label>
                                <div class="col-sm-9">
                                    <select ng-model="changeStatus" class="form-control">
                                        <option value=""> -- Trạng thái -- </option>
                                        <option value="1"> Duyệt đơn hàng </option>
                                        <option value="2"> Hủy đơn hàng </option>
                                        <option value="3"> Đã nhập vào kho </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button ng-click="" type="button" class="btn btn-sm btn-info"> Xác nhận </button>
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Hủy </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

{{-- !ANGULARJS! --}}
@section('script')
    <script src="{{ asset('angularJS/InputStoreController.js') }}"></script>
@endsection