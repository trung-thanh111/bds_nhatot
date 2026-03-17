(function($) {
    "use strict";
    var RT = {};
    var _token = $('meta[name="csrf-token"]').attr('content');

    RT.changePropertyGroup = () => {
        $(document).on('change', '#project_catalogue_id', function() {
            let id = $(this).val();
            let type_code = $('#type_code').val();
            
            $.ajax({
                url: 'ajax/realestate/getPropertyGroup',
                type: 'GET',
                data: { 
                    id: id,
                    type_code: type_code
                },
                dataType: 'json',
                success: function(res) {
                    if (res.transaction_type) {
                        $('#transaction_type').val(res.transaction_type);
                    }
                    if (res.type_code != '') {
                        $('#type_code').val(res.type_code);
                    }
                    
                    // Xử lý hiển thị form field
                    $('.attr-group').hide();
                    if (res.property_group && res.property_group !== '') {
                        $('.' + res.property_group + '-group').show();
                    } else {
                        $('.house-group').show();
                    }
                    $(window).trigger('resize');
                },
                error: function() {
                    $('.attr-group').hide();
                    $('.house-group').show();
                }
            });
        });
    };

    $(document).ready(function() {
        RT.changePropertyGroup();
        
        // Khởi tạo trạng thái ban đầu
        let catId = $('#project_catalogue_id').val();
        if (catId && catId > 0) {
            $('#project_catalogue_id').trigger('change');
        } else {
            $('.attr-group').hide();
            $('.house-group').show();
        }
    });

})(jQuery);
