/**
 * Created by Good on 3/28/2017.
 */

app.controller('ProductController', function($scope, $http, API, $timeout) {
    // Load danh sách sản phẩm
    $http.get(API + 'product').then(function (response) {
        $scope.products = response.data;
    });

    $scope.loadProduct = function () {
        $http.get(API + 'product').then(function (response) {
            $scope.products = response.data;
        });

        $http.get(API + 'category').then(function (response) {
            $scope.categorys = response.data;
        }); // Load nhóm sản phẩm

        $http.get(API + 'unit').then(function (response) {
            $scope.units = response.data;
        }); // Load đơn vị tính

        $http.get(API + 'manufacturer').then(function (response) {
            $scope.manufacturers = response.data;
        }); // Load nhà sản xuất

        $http.get(API + 'attribute').then(function (response) {
            $scope.attributes = response.data;
        }); // Load thuộc tính sản phẩm
    };
    $scope.loadProduct();
    $timeout($scope.loadProduct, 5000);

    // THÊM ĐƠN VỊ TÍNH
    $scope.createUnit = function () {
        $http({
            method : 'POST',
            url : API + 'unit',
            data : $scope.newUnit,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $scope.loadProduct();
                $scope.newUnit = {};
            }
            else
                toastr.error(response.data[0]);
        });
    };

    //TẠO NHÀ SẢN XUẤT MỚI
    $scope.createManufacturer = function () {
        $http({
            method : 'POST',
            url : API + 'manufacturer',
            data : $scope.newManufacturer,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $scope.loadProduct();
                $scope.newManufacturer = {};
            }
            else
                toastr.error(response.data[0]);
        });
    };

    // TẠO NHÓM SẢN PHẨM 
    $scope.createCategory = function () {
        $http({
            method : 'POST',
            url : API + 'category',
            data : $scope.newCategory,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $scope.loadProduct();
                $scope.newCategory = {};
            }
            else
                toastr.error(response.data[0]);
        });
    };

    $scope.data = []; // Lưu danh sách thuộc tính tạm thời
    $scope.image = []; // Lưu danh sách hình ảnh tạm thời

    // THÊM THUỘC TÍNH SẢN PHẨM
    $scope.createAttributeForProduct = function (item) {
        $http({
            method : 'POST',
            url : API + 'product-attribute',
            data : item,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                console.log('1');
            }
        });
    };

    // THÊM HÌNH ẢNH SẢN PHẨM
    $scope.createImageForProduct = function (item) {
        $http({
            method : 'POST',
            url : API + 'product-image',
            data : item,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                console.log('1');
            }
        });
    };

    // UP ẢNH LÊN FLICKR
    $scope.uploadImage = function () {
        var myDropzone = Dropzone.forElement("#my_dropzone");
        var myDropzone02 = Dropzone.forElement("#my_dropzone02");
        myDropzone.processQueue();
        myDropzone02.processQueue();
        myDropzone.on("success", function(file, response) {
            $scope.tmp = {};
            if($scope.new.default_image == null)
                $scope.new.default_image = response;
            else {
                $scope.tmp.image = response;
                $scope.image.push($scope.tmp);
                $scope.tmp = {};
            }
        });
        myDropzone02.on("success", function(file, response) {
            $scope.tmp = {};
            if($scope.selected.default_image == null)
                $scope.selected.default_image = response;
            else {
                $scope.tmp.image = response;
                $scope.image.push($scope.tmp);
                $scope.tmp = {};
            }
        });
    };

    // TẠO SẢN PHẨM MỚI
    $scope.createProduct = function () {
        if( CKEDITOR.instances.newDescription.getData() )
            $scope.new.description = CKEDITOR.instances.newDescription.getData();
        if ( CKEDITOR.instances.newUserGuide.getData() )
            $scope.new.user_guide = CKEDITOR.instances.newUserGuide.getData();
        $http({
            method : 'POST',
            url : API + 'product',
            data : $scope.new,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                // thêm thuộc tính
                for (var i=0; i<$scope.data.length; i++) {
                    $scope.data[i].product_id = response.data.success;
                    $scope.createAttributeForProduct($scope.data[i]);
                }
                //thêm hình ảnh
                for (var i=0; i<$scope.image.length; i++) {
                    $scope.image[i].product_id = response.data.success;
                    $scope.createImageForProduct($scope.image[i]);
                }
                toastr.success('Đã thêm sản phẩm thành công.');
                $("[data-dismiss=modal]").trigger({ type: "click" });
                $scope.loadProduct();
                $scope.data = [];
                $scope.image = [];
                $scope.new = {};
            }
            else
                toastr.error(response.data[0]);
        });
    };

    // XEM THÔNG TIN SẢN PHẨM
    $scope.readProduct = function (product) {
        $http.get(API + 'product/' + product.id).then(function (response) {
            $scope.selected = response.data;
            CKEDITOR.instances.description.setData($scope.selected.description);
            CKEDITOR.instances.user_guide.setData($scope.selected.user_guide);
        });
    };

    // THÊM THUỘC TÍNH VÀO DANH SÁCH
    $scope.addAttribute = function(selected) {
        if($scope.data.indexOf(selected) == -1) {
            $scope.data.push(selected);
            toastr.info('Đã thêm một thuộc tính vào danh sách.');
        } else
            toastr.info('Thuộc tính đã có trong danh sách.');
    };

    // XÓA THUỘC TÍNH KHỎI DANH SÁCH
    $scope.deleteAttribute = function(selected) {
        $scope.data.splice($scope.data.indexOf(selected), 1);
        toastr.info('Đã xóa 1 thuộc tính khỏi danh sách.');

    };

    // XÓA THUỘC TÍNH SẢN PHẨM
    $scope.deleteProductAttribute = function(selected) {
        $http({
            method : 'DELETE',
            url : API + 'product-attribute/' + selected.id,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.info('Đã xóa 1 thuộc tính sản phẩm.');
                $scope.readProduct($scope.selected);
            } else
                toastr.error(response.data[0]);
        });
    };

    // XÓA HÌNH ẢNH SẢN PHẨM
    $scope.deleteProductImage = function(selected) {
        $http({
            method : 'DELETE',
            url : API + 'product-image/' + selected.id,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.info('Đã xóa 1 hình ảnh sản phẩm.');
                $scope.readProduct($scope.selected);
            } else
                toastr.error(response.data[0]);
        });
    };

    // CHỈNH SỬA THÔNG TIN SẢN PHẨM
    $scope.updateProduct = function () {
        for (var i=0; i<$scope.data.length; i++) {
            $scope.data[i].product_id = $scope.selected.id;
            $scope.createAttributeForProduct($scope.data[i]);
        }
        for (var j=0; j<$scope.image.length; j++) {
            $scope.image[j].product_id = $scope.selected.id;
            $scope.createImageForProduct($scope.image[j]);
        }
        if( CKEDITOR.instances.description.getData() )
            $scope.selected.description = CKEDITOR.instances.description.getData();
        if ( CKEDITOR.instances.user_guide.getData() )
            $scope.selected.user_guide = CKEDITOR.instances.user_guide.getData();
        $http({
            method : 'PUT',
            url : API + 'product/' + $scope.selected.id,
            data : $scope.selected,
            cache : false,
            header : {'Content-Type':'application/x-www-form-urlencoded'}
        }).then(function (response) {
            if(response.data.success) {
                toastr.success(response.data.success);
                $scope.loadProduct();
                $scope.readProduct($scope.selected);
                $("[data-dismiss=modal]").trigger({ type: "click" });
                $scope.data = [];
                $scope.image = [];
            }
            else
                toastr.error(response.data[0]);
        });
    };

    // XÓA SẢN PHẨM
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
                $http.get(API + 'product').then(function (response) {
                    $scope.products = response.data;
                });
            } else
                toastr.error(response.data[0]);
        });
    };

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

$('#createProduct').on('hidden.bs.modal', function(){
    $(this).find('form')[0].reset();
    var myDropzone = Dropzone.forElement(".dropzone");
    myDropzone.removeAllFiles();
    CKEDITOR.instances.newDescription.setData('');
    CKEDITOR.instances.newUserGuide.setData('');
});

$('#readProduct').on('hidden.bs.modal', function(){
    $(this).find('form')[0].reset();
    var myDropzone = Dropzone.forElement("#my_dropzone02");
    myDropzone.removeAllFiles();
    CKEDITOR.instances.newDescription.setData('');
    CKEDITOR.instances.newUserGuide.setData('');
});

$('#readProduct').on('show.bs.modal', function (event) {
    var modal = $(this);
    modal.find('.modal-title').text('Xem thông tin sản phẩm');
    modal.find('.modal-title').removeClass('w3-text-green');
    modal.find('.modal-title').addClass('w3-text-blue');
    modal.find('#name').attr('readOnly', true);
    modal.find('#code').attr('readOnly', true);
    CKEDITOR.instances.description.setReadOnly(true);
    CKEDITOR.instances.user_guide.setReadOnly(true);
    var myDropzone = Dropzone.forElement(".dropzone");
    myDropzone.removeAllFiles();
    modal.find('#category').attr('disabled', true);
    modal.find('#manufacturer').attr('disabled', true);
    modal.find('#unit').attr('disabled', true);
    modal.find('#web_price').attr('readOnly', true);
    modal.find('#min_inventory').attr('readOnly', true);
    modal.find('#max_inventory').attr('readOnly', true);
    modal.find('#warranty_period').attr('readOnly', true);
    modal.find('#return_period').attr('readOnly', true);
    modal.find('#weight').attr('readOnly', true);
    modal.find('#size').attr('readOnly', true);
    modal.find('#volume').attr('readOnly', true);
    modal.find('#addAttribute').attr('disabled', true);
    modal.find('#addImage').attr('disabled', true);
    modal.find('#submit').hide();
    modal.find('#updateProduct').show();
    modal.find('#updateProduct').click(function () {
        modal.find('.modal-title').text('Sửa thông tin sản phẩm');
        modal.find('.modal-title').removeClass('w3-text-blue');
        modal.find('.modal-title').addClass('w3-text-green');
        modal.find('#name').removeAttr('readOnly');
        modal.find('#code').removeAttr('readOnly');
        CKEDITOR.instances.description.setReadOnly(false);
        CKEDITOR.instances.user_guide.setReadOnly(false);
        modal.find('#user_guide').removeAttr('disabled');
        modal.find('#category').removeAttr('disabled');
        modal.find('#manufacturer').removeAttr('disabled');
        modal.find('#unit').removeAttr('disabled');
        modal.find('#web_price').removeAttr('readOnly');
        modal.find('#min_inventory').removeAttr('readOnly');
        modal.find('#max_inventory').removeAttr('readOnly');
        modal.find('#warranty_period').removeAttr('readOnly');
        modal.find('#return_period').removeAttr('readOnly');
        modal.find('#weight').removeAttr('readOnly');
        modal.find('#size').removeAttr('readOnly', true);
        modal.find('#volume').removeAttr('readOnly', true);
        modal.find('#addAttribute').removeAttr('disabled');
        modal.find('#addImage').removeAttr('disabled');
        modal.find('#updateProduct').hide();
        modal.find('#submit').show();
    });
});

$("#my_dropzone").dropzone({
    maxFilesize: 2,
    autoProcessQueue: false,
    addRemoveLinks: true
});

$("#my_dropzone02").dropzone({
    maxFilesize: 2,
    autoProcessQueue: false,
    addRemoveLinks: true,
});

$("#viewList").click(function () {
    $("#viewList").addClass('w3-blue-grey');
    $("#viewGrid").removeClass('w3-blue-grey');
    $("#grid").attr('hidden', true);
    $("#list").removeAttr('hidden');
});

$("#viewGrid").click(function () {
    $("#viewList").removeClass('w3-blue-grey');
    $("#viewGrid").addClass('w3-blue-grey');
    $("#list").attr('hidden', true);
    $("#grid").removeAttr('hidden');
});