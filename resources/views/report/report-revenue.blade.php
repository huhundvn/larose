@extends('layouts.app')

@section('title')
    Doanh thu cửa hàng
@endsection

@section('location')
    <li> Báo cáo </li>
    <li> Thống kê doanh thu bán hàng </li>
@endsection

@section('content')
    <div ng-controller="ReportController">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label " for="email"> Kho hàng </label>
                <div class="">
                    <select ng-model="info.store_id" class="form-control">
                        <option value=""> -- Kho hàng -- </option>
                        <option ng-repeat="store in stores" value="@{{store.id}}"> @{{ store.name }} </option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label " for="email"> Ngày bắt đầu </label>
                <div class="">
                    <input ng-model="info.start_date" type="date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label " for="pwd"> Ngày kết thúc </label>
                <div class="">
                    <input ng-model="info.end_date" type="date" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 ">
                    <button ng-click="loadStoreOutput()" data-toggle="modal" data-target="#reportInputStore" class="btn btn-success"> Tạo báo cáo </button>
                </div>
            </div>
        </form>

        {{-- Xem biểu mẫu --}}
        <div class="modal fade" id="reportInputStore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="" method="post"> {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Biểu mẫu </h4>
                        </div>
                        <div id="print-content">
                            <style>
                                @media print {
                                    body * {
                                        visibility: hidden;
                                    }
                                    #print-content * {
                                        visibility: visible;
                                    }
                                    .modal{
                                        position: absolute;
                                        left: 0;
                                        top: 0;
                                        margin: 0;
                                        padding: 0;
                                    }
                                }
                            </style>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="">
                                        Công ty TNHH Larose <br/>
                                        142 Võ Văn Tân, TP.HCM <br/>
                                        ĐT: 0979369407
                                    </div>
                                    <div class="">
                                        Số: <br/>
                                        Ngày...tháng...năm...
                                    </div>
                                </div>
                                <div class="row">
                                    <h2 align="center"> <b> Doanh thu bán hàng </b> </h2>
                                    <p align="center"> Từ ngày @{{ info.start_date | date: "dd/MM/yyyy" }} đến ngày @{{ info.end_date | date: "dd/MM/yyyy"}} </p>
                                    <p align="center" ng-repeat="store in stores" ng-show="store.id==info.store_id"> Thống kê tại @{{store.name}} </p>
                                </div>
                                <div class="row">
                                    <div class="table-responsive"><table class="w3-table w3-center table-bordered">
                                        <thead>
                                        <th> STT </th>
                                        <th> Tên sản phẩm </th>
                                        <th> Đơn vị tính </th>
                                        <th> Số lượng </th>
                                        <th> Giá mua (VNĐ) </th>
                                        <th> Giá bán (VNĐ) </th>
                                        <th> Tổng tiền mua vào (VNĐ) </th>
                                        <th> Tổng tiền bán ra (VNĐ) </th>
                                        <th> Lợi nhuận (VNĐ) </th>
                                        <th> Tỷ suất lợi nhuận </th>
                                        </thead>
                                        <tbody>
                                        <tr ng-show="storeOutputs.length > 0" ng-repeat="item in storeOutputs">
                                            <td> @{{ $index+1 }}</td>
                                            <td> @{{ item.product.name }} </td>
                                            <td ng-repeat="unit in units" ng-if="unit.id==item.product.unit_id"> @{{ unit.name }} </td>
                                            <td> @{{item.quantity | number: 0}} </td>
                                            <td> @{{item.price_input | number: 0}} </td>
                                            <td> @{{item.price_output | number: 0}}  </td>
                							<td> @{{item.price_input * item.quantity | number: 0}} </td>
                							<td> @{{item.price_output * item.quantity | number: 0}} </td>
                							<td> @{{(item.price_output * item.quantity) - (item.price_input * item.quantity) | number: 0}} </td>
                							<td> @{{(item.price_output * item.quantity - item.price_input * item.quantity) / (item.price_input * item.quantity) * 100 | number: 2}} % </td>
                                        </tr>
                                        <tr class="item" ng-show="storeOutputs.length == 0">
                                            <td colspan="6" align="center"> Không có dữ liệu </td>
                                        </tr>
                                        </tbody>
                                    </table></div>
                                    <h1></h1>
                                </div>
                                <div class="row">
                                    <div class="" align="center">
                                        <b> Giám đốc </b><br/> (Ký tên)
                                    </div>
                                    <div class="" align="center">
                                        <b> Kế toán </b> <br/> (Ký tên)
                                    </div>
                                    <div class="" align="center">
                                        <b> Người lập báo cáo </b> <br/> (Ký tên)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer hidden-print">
                            <button type="button" class="btn btn-success btn-sm" ng-click="print()">
                                <span class="glyphicon glyphicon-print"></span> In
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"> Đóng </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- !ANGULARJS! --}}
@section('script')
    <script src="{{ asset('angularJS/ReportController.js') }}"></script>
@endsection