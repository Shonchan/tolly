/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/admin.js":
/***/ (function(module, exports) {

        $('#add-variant').click(function () {

            var tr = '<tr><td><input type="hidden" name="variants[id][]">' +
                '<input type="hidden" name="variants[feats][]">' +
                '<input class="form-control" type="text" name="variants[name][]"></td>' +
                '<td><input class="form-control" type="text" name="variants[sku][]"></td>' +
                '<td><input class="form-control" type="text" name="variants[price][]"></td>' +
                '<td><input class="form-control" type="text" name="variants[compare_price][]"></td>' +
                '<td><input class="form-control" type="text" name="variants[stock][]"></td>' +
                '<td><div class=\'btn btn-warning btn-tb btn-feats\'>...</div><div class=\'btn btn-danger btn-tb btn-remV\'>Удалить</div></td>' +
                '</tr>';
            $(this).closest('tr').before(tr);
            setHookRemV();
            setHookFeats();
        });


        setHookRemV();
        setHookRemO();
        setHookFeats();
        setHookBlock();

        $('.add-opt').click(function () {
            if($(this).closest('li').find('.btn-block').hasClass("blocked") === false) {
                var li = $(this).closest('li').clone();

                li.find('button.add-opt').removeClass('add-opt btn-success').addClass('rem-opt btn-danger').text("-");
                li.find('div.col-md-4').html("");
                li.find('.btn-block').remove();
                li.find('input[name^="features"]').val("");
                $(this).closest('li').after(li);
                setHookRemO();
            }
        });





        $('select[name="parent_id"], select[name="type"]').change(function () {
            if($('select[name="type"]').val() == 'mc') {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/ajax/filter",
                    data: {'id': $('select[name="parent_id"]').val()},
                    dataType: "json",
                    //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
                    success: function (data) {
                        $('#filter').html(data);

                    }
                });
            } else {
                $('#filter').html("");
            }
        });

        function setHookRemV() {
            $('.btn-remV').click(function () {
                $(this).closest('tr').remove();
            });
        }

        function setHookRemO() {
            $('.rem-opt').click(function () {
                $(this).closest('li').remove();
            });
        }

        function setHookBlock() {
            $('.btn-block').click(function () {
                var name =  $(this).closest('li').find('input[name^="features"]').attr('name');
                var result = name.match(/features\[([\d]+)]/i);
                var fid= result[1];


                $(this).toggleClass('blocked');
                if($(this).hasClass('blocked')) {
                    $('input[name="' + name + '"]').prop('disabled', true);
                    var inOld = $('input[name="var_feats[]"]').first();
                    var inNew = inOld.clone();
                    inNew.val(fid);
                    inOld.after(inNew);
                } else {
                    $('input[name="' + name + '"]').prop('disabled', false);
                    console.log($('input[name="var_feats[]"][value=' + fid + ']'));
                    $('input[name="var_feats[]"][value=' + fid + ']').remove();
                }
            });
        }

        function setHookFeats() {
            $('.btn-feats').click(function () {
                var vid = $(this).closest('tr').find('input[name="variants[id][]"]').val();
                var vfeats = $(this).closest('tr').find('input[name="variants[feats][]"]').val();
                var vfa = $('input[name="var_feats[]"]').serializeArray();
                var dd = $(this).closest('tr').find('input').serializeArray();
                var tr = $(this);
                // console.log(dd);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/ajax/variant_features",
                    data: {'id': vid, 'feats':vfeats, 'vfeats':vfa},
                    dataType: "json",
                    //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
                    success: function (data) {
                        $.fancybox.open(data); // fa
                        $('.save-vfeats').click(function (e) {
                            e.preventDefault();
                            var ff = $('form#v_features').serializeArray();


                            var feats = {};
                            for (var i=0; i<ff.length; i++){
                                if(ff[i].value) {
                                    var name = ff[i].name;
                                    // var it = {};
                                    feats[ff[i].name]=ff[i].value;

                                }
                            }
                            tr.closest('tr').find('input[name="variants[feats][]"]').val(JSON.stringify(feats));
                            $.fancybox.close();
                        });
                        // $.fancybox.open('<div class="message"><h2>Hello!</h2><p>You are awesome!</p></div>');

                    }
                });
            });
        }

        // $('#refresh_filter').click(function (e) {
        //     e.preventDefault();
        //     var filter = $('#filter').serializeArray();
        //     $('textarea[name="filter"]').val(filter);
        //     // alert(cat_id);
        //     // $.ajax({
        //     //     headers: {
        //     //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     //     },
        //     //     type:"POST",
        //     //     url:"/ajax/products",
        //     //     data:{'id':cat_id},
        //     //     dataType:"json",
        //     //     //beforeSend:function(){ $('#ul_pro').html('<div class="load"></div>'); },
        //     //     success:function(data){
        //     //         $('.product div.row').append(data.offers);
        //     //         scrto = 1;
        //     //     }
        //     // });
        // });

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/admin.js");


/***/ })

/******/ });