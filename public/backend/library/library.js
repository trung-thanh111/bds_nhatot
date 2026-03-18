window.HT = window.HT || {}; var HT = window.HT;
var _token = $('meta[name="csrf-token"]').attr('content');

HT.switchery = () => {
    $('.js-switch').each(function () {
        // let _this = $(this)
        var switchery = new Switchery(this, { color: '#1AB394', size: 'small' });
    })
}

HT.select2 = () => {
    if ($('.setupSelect2').length) {
        $('.setupSelect2').select2({
            width: '100%'
        });
    }
}

HT.ajaxSearch = () => {
    if ($('.ajaxSearch').length) {
        $('.ajaxSearch').each(function () {
            let _this = $(this)
            let model = _this.attr('data-model')
            _this.select2({
                minimumInputLength: 2,
                placeholder: _this.attr('data-placeholder'),
                ajax: {
                    url: 'ajax/dashboard/findPromotionObject',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            option: {
                                model: model
                            },
                        }
                    },
                    processResults: function (data) {
                        return {
                            results: data.items
                        }
                    },
                    cache: true
                }
            });
        })
    }
}

HT.sortui = () => {
    $("#sortable").sortable();
    $("#sortable").disableSelection();
}

HT.changeStatus = () => {
    $(document).on('change', '.status', function (e) {

        let _this = $(this)
        let option = {
            'value': _this.val(),
            'modelId': _this.attr('data-modelId'),
            'model': _this.attr('data-model'),
            'field': _this.attr('data-field'),
            '_token': _token
        }


        $.ajax({
            url: 'ajax/dashboard/changeStatus',
            type: 'POST',
            data: option,
            dataType: 'json',
            success: function (res) {
                let inputValue = ((option.value == 1) ? 2 : 1)
                if (res.flag == true) {
                    _this.val(inputValue)
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {

                console.log('Lỗi: ' + textStatus + ' ' + errorThrown);
            }
        });

        e.preventDefault()
    })
}

HT.changeStatusAll = () => {
    if ($('.changeStatusAll').length) {
        $(document).on('click', '.changeStatusAll', function (e) {
            let _this = $(this)
            let id = []
            $('.checkBoxItem').each(function () {
                let checkBox = $(this)
                if (checkBox.prop('checked')) {
                    id.push(checkBox.val())
                }
            })

            let option = {
                'value': _this.attr('data-value'),
                'model': _this.attr('data-model'),
                'field': _this.attr('data-field'),
                'id': id,
                '_token': _token
            }

            $.ajax({
                url: 'ajax/dashboard/changeStatusAll',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function (res) {
                    if (res.flag == true) {
                        let cssActive1 = 'background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;';
                        let cssActive2 = 'left: 13px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;';
                        let cssUnActive = 'background-color: rgb(255, 255, 255); border-color: rgb(223, 223, 223); box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;'
                        let cssUnActive2 = 'left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;'

                        for (let i = 0; i < id.length; i++) {
                            if (option.value == 2) {
                                $('.js-switch-' + id[i]).find('span.switchery').attr('style', cssActive1).find('small').attr('style', cssActive2)
                            } else if (option.value == 1) {
                                $('.js-switch-' + id[i]).find('span.switchery').attr('style', cssUnActive).find('small').attr('style', cssUnActive2)
                            }
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    console.log('Lỗi: ' + textStatus + ' ' + errorThrown);
                }
            });

            e.preventDefault()
        })
    }
}

HT.checkAll = () => {
    if ($('#checkAll').length) {
        $(document).on('click', '#checkAll', function () {
            let isChecked = $(this).prop('checked')
            $('.checkBoxItem').prop('checked', isChecked);
            $('.checkBoxItem').each(function () {
                let _this = $(this)
                HT.changeBackground(_this)
            })
        })
    }
}

HT.checkBoxItem = () => {
    if ($('.checkBoxItem').length) {
        $(document).on('click', '.checkBoxItem', function () {
            let _this = $(this)
            HT.changeBackground(_this)
            HT.allChecked()
        })
    }
}

HT.changeBackground = (object) => {
    let isChecked = object.prop('checked')
    if (isChecked) {
        object.closest('tr').addClass('active-bg')
    } else {
        object.closest('tr').removeClass('active-bg')
    }
}

HT.allChecked = () => {
    let allChecked = $('.checkBoxItem:checked').length === $('.checkBoxItem').length;
    $('#checkAll').prop('checked', allChecked);
}

HT.int = () => {
    $(document).on('change keyup blur', '.int', function () {
        let _this = $(this)
        let value = _this.val()
        if (value === '') {
            $(this).val('0')
        }
        value = value.replace(/\./gi, "")
        _this.val(HT.addCommas(value))
        if (isNaN(value)) {
            _this.val('0')
        }
    })

    $(document).on('keydown', '.int', function (e) {
        let _this = $(this)
        let data = _this.val()
        if (data == 0) {
            let unicode = e.keyCode || e.which;
            if (unicode != 190) {
                _this.val('')
            }
        }
    })
}



HT.addCommas = (nStr) => {
    nStr = String(nStr);
    nStr = nStr.replace(/\./gi, "");
    let str = '';
    for (let i = nStr.length; i > 0; i -= 3) {
        let a = ((i - 3) < 0) ? 0 : (i - 3);
        str = nStr.slice(a, i) + '.' + str;
    }
    str = str.slice(0, str.length - 1);
    return str;
}

HT.setupDatepicker = () => {
    if ($('.datepicker').length) {
        $('.datepicker').datetimepicker({
            timepicker: true,
            format: 'd/m/Y H:i',
            minDate: new Date(),
        });
    }

}


HT.setupDateRangePicker = () => {
    if ($('.rangepicker').length > 0) {
        $('.rangepicker').daterangepicker({
            timePicker: true,
            locale: {
                format: 'dd-mm-yy'
            }
        })
    }
}


HT.approve = () => {
    $(document).on('change', '#status-review', function (e) {
        e.preventDefault()
        let _this = $(this)
        let id = _this.data('id')
        let status = _this.val()
        $.ajax({
            url: 'ajax/review/changeStatus',
            type: 'POST',
            data: {
                '_token': _token,
                'id': id,
                'status': status
            },
            dataType: 'json',
            success: function (res) {
                if (res) {
                    toastr.success('Cập nhật trạng thái thành công !')
                    _this.closest('tr').find('#review-link').removeClass()
                }
            },
        })
    })
}

HT.changeOrder = () => {
    $(document).on('change', '.sort-order', function () {
        let _this = $(this)
        let option = {
            id: _this.data('id'),
            model: _this.data('model'),
            order: _this.val(),
        }
        $.ajax({
            url: 'ajax/product/updateOrder',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function (res) {
                if (res.code === 10) {
                    toastr.success('Cập nhật thứ tự thành công', 'Thông báo từ hệ thống!')
                }
            },
            beforeSend: function () {

            },
        });
    })
}

HT.exportExcel = () => {
    $(document).on('click', '#confirmExport', function (e) {
        e.preventDefault()
        let _this = $(this)
        const model = _this.data('model')
        let option = {
            model: model
        }
        HT.setupDataForExport(option)
    })
}

HT.setupDataForExport = (option) => {
    const loadingOverlay = $('<div class="loading-overlay">Đang tải file...</div>');
    $('body').append(loadingOverlay);
    const startDate = $('input[name="startDate"]').val();
    const endDate = $('input[name="endDate"]').val();

    $.ajax({
        url: 'ajax/excel/export',
        type: 'POST',
        data: {
            ...option,
            startDate: startDate,
            endDate: endDate,
            _token: $('meta[name="csrf-token"]').attr('content') // Thêm CSRF token
        },
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                const link = document.createElement('a');
                link.href = res.file_url;
                link.download = res.filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                console.error('Error:', res.message);
            }

            loadingOverlay.remove();
            window.location.reload()
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
            loadingOverlay.remove();
        }
    });
}


$(document).ready(function () {
    HT.addProgram()
    HT.removeProgram()
    HT.exportExcel()
    HT.changeOrder()
    HT.approve()
    HT.switchery()
    HT.select2()
    HT.ajaxSearch()
    HT.changeStatus()
    HT.checkAll()
    HT.checkBoxItem()
    HT.allChecked()
    HT.changeStatusAll()
    HT.sortui()
    HT.int()
    HT.setupDatepicker()
    HT.setupDateRangePicker()
    HT.slugListener()
});

HT.slugListener = () => {
    $(document).on('keyup', '.title', function () {
        let _this = $(this)
        let slug = HT.slug(_this.val())
        $('.slug').val(slug)
        $('#slug-text').text(slug)
    })
}

HT.slug = (str) => {
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g, " ");
    str = str.replace(/ + /g, " ");
    str = str.trim();
    str = str.replace(/ /g, "-");
    return str;
}

const addCommas = (nStr) => {
    nStr = String(nStr);
    nStr = nStr.replace(/\./gi, "");
    let str = '';
    for (let i = nStr.length; i > 0; i -= 3) {
        let a = ((i - 3) < 0) ? 0 : (i - 3);
        str = nStr.slice(a, i) + '.' + str;
    }
    str = str.slice(0, str.length - 1);
    return str;
}