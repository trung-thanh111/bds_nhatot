window.HT = window.HT || {}; var HT = window.HT;

HT.getLocationOld = () => {
    $(document).on('change', '.location-old', function () {
        let _this = $(this)
        let option = {
            'data': {
                'location_id': _this.val(),
            },
            'target': _this.attr('data-target'),
            'source': 'before'
        }
        $.ajax({
            url: 'ajax/location/getLocationBefore',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function (res) {
                let targetElement = $('.' + option.target)
                targetElement.html(res.html)

                if (option.target == 'districts-old') {
                    if (typeof district_id !== 'undefined' && district_id != '' && district_id != '0') {
                        $('.districts-old').val(district_id).trigger('change')
                        district_id = '' 
                    }
                }

                if (option.target == 'wards-old') {
                    if (typeof ward_id !== 'undefined' && ward_id != '' && ward_id != '0') {
                        $('.wards-old').val(ward_id).trigger('change')
                        ward_id = ''
                    }
                }
                HT.select2();
            }
        });
    })
}

HT.getLocationNew = () => {
    $(document).on('change', '.location-new', function () {
        let _this = $(this)
        let option = {
            'data': {
                'location_id': _this.val(),
            },
            'target': _this.attr('data-target'),
            'source': 'after'
        }
        $.ajax({
            url: 'ajax/location/getLocationAfter',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function (res) {
                let targetElement = $('.' + option.target)
                targetElement.html(res.html)

                if (option.target == 'wards-new') {
                    if (typeof ward_new_id !== 'undefined' && ward_new_id != '' && ward_new_id != '0') {
                        $('.wards-new').val(ward_new_id).trigger('change')
                        ward_new_id = ''
                    }
                }
                HT.select2();
            }
        });
    })
}

HT.loadCity = () => {
    if (typeof province_id !== 'undefined' && province_id != '' && province_id != '0') {
        $(".province").val(province_id).trigger('change');
    }
    if (typeof province_new_id !== 'undefined' && province_new_id != '' && province_new_id != '0') {
        $(".province-new").val(province_new_id).trigger('change');
    }
}

$(function () {
    HT.getLocationOld();
    HT.getLocationNew();
    HT.loadCity();
});
