(function($) {
    "use strict";
    var HT = window.HT || {};

    HT.changeCatalogueType = () => {
        $(document).on('change', '#type_code_catalogue', function() {
            let _this = $(this);
            let groupId = _this.find(':selected').data('group');
            if (groupId) {
                $('#property_group_id_catalogue').val(groupId).trigger('change');
            }
        });
    };

    $(document).ready(function() {
        HT.changeCatalogueType();
    });

})(jQuery);
