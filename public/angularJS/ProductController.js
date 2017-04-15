/**
 * Created by Good on 3/28/2017.
 */

app.controller('ProductController', function($scope, $http, API) {

    $http.get(API + 'category').then(function (response) {
        $scope.categorys = response.data;
    });

    $http.get(API + 'unit').then(function (response) {
        $scope.units = response.data;
    });

    $http.get(API + 'manufacturer').then(function (response) {
        $scope.manufacturers = response.data;
    });

    /**
     * Load danh sách sản phẩm
     */
    $scope.loadProduct = function () {
        $http.get(API + 'product').then(function (response) {
            $scope.products = response.data;
        });
    };
    $scope.loadProduct();

    /**
     * Tìm thông tin sản phẩm
     */
    $scope.searchProduct = function () {
        if ($scope.term == '') {
            $scope.loadProduct();
        } else {
            $http.get(API + 'product/search/' + $scope.term).then(function (response) {
                $scope.products = response.data;
            });
        }
    };

    /**
     * CRUD nhóm sản phẩm
     */
    $scope.createProduct = function () {
        $http({
            method : 'POST',
            url : API + 'product',
            data : $scope.new,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $("[data-dismiss=modal]").trigger({ type: "click" });
                $scope.loadProduct();
            }
            else
                toastr.error(response.data[0]);
        });
    };

    $scope.readProduct = function (product) {
        $http.get(API + 'product/' + product.id).then(function (response) {
            $scope.selected = response.data;
        });
    };

    $scope.updateProduct = function () {
        $http({
            method : 'PUT',
            url : API + 'product/' + $scope.selected.id,
            data : $scope.selected,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $("[data-dismiss=modal]").trigger({ type: "click" });
                $scope.loadProduct();
            }
            else
                toastr.error(response.data[0]);
        });
    };

    $scope.deleteProduct = function () {
        $http({
            method : 'DELETE',
            url : API + 'product/' + $scope.selected.id,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $("[data-dismiss=modal]").trigger({ type: "click" });
                $scope.loadProduct();
            } else
                toastr.error(response.data[0]);
        });
    };

    $('#createProduct').on('hidden.bs.modal', function(){
        $(this).find('form')[0].reset();
    });

    $scope.options = {
        numeral: {
            numeral: true
        },
        code: {
            blocks: [1, 3, 3, 3, 3],
            delimiters: ['-']
        }
    };
});

$('#readProduct').on('show.bs.modal', function (event) {
    var modal = $(this);
    modal.find('.modal-title').text('Xem thông tin sản phẩm');
    modal.find('.modal-title').removeClass('w3-text-green');
    modal.find('.modal-title').addClass('w3-text-blue');
    modal.find('#name').attr('readOnly', true);
    modal.find('#code').attr('readOnly', true);
    modal.find('#description').attr('readOnly', true);
    modal.find('#user_guide').attr('readOnly', true);
    modal.find('#category').attr('disabled', true);
    modal.find('#manufacturer').attr('disabled', true);
    modal.find('#unit').attr('disabled', true);
    modal.find('#min_inventory').attr('readOnly', true);
    modal.find('#max_inventory').attr('readOnly', true);
    modal.find('#warranty_period').attr('readOnly', true);
    modal.find('#return_period').attr('readOnly', true);
    modal.find('#weight').attr('readOnly', true);
    modal.find('#size').attr('readOnly', true);
    modal.find('#volume').attr('readOnly', true);
    modal.find('#submit').hide();
    modal.find('#updateProduct').show();
    modal.find('#updateProduct').click(function () {
        modal.find('.modal-title').text('Sửa thông tin sản phẩm');
        modal.find('.modal-title').removeClass('w3-text-blue');
        modal.find('.modal-title').addClass('w3-text-green');
        modal.find('#name').removeAttr('readOnly');
        modal.find('#code').removeAttr('readOnly');
        modal.find('#description').removeAttr('readOnly');
        modal.find('#user_guide').removeAttr('readOnly');
        modal.find('#category').removeAttr('disabled');
        modal.find('#manufacturer').removeAttr('disabled');
        modal.find('#unit').removeAttr('disabled');
        modal.find('#min_inventory').removeAttr('readOnly');
        modal.find('#max_inventory').removeAttr('readOnly');
        modal.find('#warranty_period').removeAttr('readOnly');
        modal.find('#return_period').removeAttr('readOnly');
        modal.find('#weight').removeAttr('readOnly');
        modal.find('#size').removeAttr('readOnly', true);
        modal.find('#volume').removeAttr('readOnly', true);
        modal.find('#updateProduct').hide();
        modal.find('#submit').show();
    });
});

$("#my-dropzone").dropzone({
    maxFileSize: 2,
    addRemoveLinks: true,
    paramName: 'upload[image]',
    url: '/admin/public/product',
});