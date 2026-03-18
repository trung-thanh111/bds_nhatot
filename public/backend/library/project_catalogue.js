(function ($) {
    "use strict";
    var HT = window.HT || {};

    HT.changeCatalogueType = () => {
        $(document).on('change', '#type_code_catalogue', function () {
            let _this = $(this);
            let groupId = _this.find(':selected').data('group');
            if (groupId) {
                // Trigger Select2 update even if disabled
                $('#property_group_id_catalogue').val(groupId).trigger('change');
            }
        });
    };

    HT.ensureSubmittable = () => {
        $(document).on('submit', 'form.box', function () {
            // Enable disabled fields right before submit so Laravel receives the data
            $(this).find('#property_group_id_catalogue').prop('disabled', false);
        });
    };

    $(document).ready(function () {
        HT.changeCatalogueType();
        HT.ensureSubmittable();
    });

})(jQuery);
