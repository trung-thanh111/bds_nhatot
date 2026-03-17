window.HT = window.HT || {};
var HT = window.HT;

let isInitializing = false;
let initPending = 0;
const provinceCache = {}; // Cache for province HTML

function _initStart() {
    initPending++;
    isInitializing = true;
}
function _initDone() {
    initPending = Math.max(0, initPending - 1);
    if (initPending === 0) {
        isInitializing = false;
    }
}

let triggerCount = 0;

HT._findMatch = (nativeSelect, val) => {
    if (!val || val === '0') return null;

    const PREFIXES = [
        'thanh_pho_', 'tinh_',
        'quan_', 'huyen_', 'thi_xa_',
        'phuong_', 'xa_'
    ];

    let cleanVal = val;
    for (const p of PREFIXES) {
        if (val.startsWith(p)) { cleanVal = val.slice(p.length); break; }
    }

    const tries = [val, cleanVal, ...PREFIXES.map(p => p + cleanVal)];

    for (const t of tries) {
        for (const opt of nativeSelect.options) {
            if (opt.value === t) return t;
        }
    }

    return null;
};

HT.smartSet = (selector, val) => {
    const $el = $(selector);
    if (!$el.length) {
        console.warn('[smartSet] Element not found:', selector);
        return false;
    }

    const matched = HT._findMatch($el[0], val);
    if (matched !== null) {
        $el[0].value = matched;
        $el.trigger('change');
        return true;
    }
    return false;
};

HT.loadAjaxDirect = (target, locationId, source, callback) => {
    $.ajax({
        url: 'ajax/location/getLocation',
        type: 'GET',
        data: { data: { location_id: locationId }, target: target, source: source },
        dataType: 'json',
        success: function (res) {
            $('.' + target).html(res.html);
            setTimeout(() => {
                if (typeof callback === 'function') callback();
            }, 0);
        },
        error: function (jqXHR, status, err) {
            console.error('[loadAjaxDirect] Error for', target, ':', status, err);
            _initDone();
        }
    });
};

HT.getLocation = () => {
    $(document).on('change', '.location', function () {
        const $this = $(this);
        const locationId = $this.val();
        const target = $this.attr('data-target');

        let text = $this.find('option:selected').text();
        if (!locationId || locationId === '0') text = '';
        $('input[name="' + $this.attr('name').replace('_code', '_name') + '"]').val(text);

        if (isInitializing) {
            return false;
        }

        if (!target) return false;

        if (triggerCount > 0 && (!locationId || locationId === '0')) {
            return false;
        }

        if (!locationId || locationId === '0') {
            const resetHtml = target.includes('districts')
                ? '<option value="0">[Chọn Quận/Huyện]</option>'
                : '<option value="0">[Chọn Phường/Xã]</option>';
            $('.' + target).html(resetHtml).trigger('change');
            return false;
        }

        HT.sendDataTogetLocation({
            data: { location_id: locationId },
            target: target,
            source: $this.attr('data-source') || 'database'
        });
    });

    const handleLocationTypeChange = function () {
        const $this = $(this);
        const val = $this.val();
        const $province = $('.province');

        if (val === '0' || !val) {
            $province.html('<option value="0">Chọn Tỉnh/TP</option>').trigger('change.select2');
            $('.districts-old, .wards-old, .wards-new').html('<option value="0">[Chọn]</option>').trigger('change.select2');
            return;
        }

        const source = (val === 'old') ? 'before' : 'after';

        // Chỉ reset nếu chưa có trong cache để tránh giật lag hoặc gán value = 0
        if (!provinceCache[source]) {
            $province.html('<option value="0">Chọn Tỉnh/TP</option>').trigger('change.select2');
        }

        $province.attr('data-source', source);
        HT.sendDataTogetLocation({
            data: { location_id: 0 },
            target: 'province',
            source: source
        });

        // Reset các cấp con
        $('.districts-old, .wards-old, .wards-new').html('<option value="0">[Chọn]</option>').trigger('change.select2');
    };

    $(document).on('change select2:select', '.location-type', handleLocationTypeChange);

    $(document).on('change', '.location', function () {
        const $this = $(this);
        const locationId = $this.val();
        const target = $this.attr('data-target');

        let text = $this.find('option:selected').text();
        if (!locationId || locationId === '0') text = '';
        $('input[name="' + $this.attr('name').replace('_code', '_name') + '"]').val(text);

        if (isInitializing) return false;
        if (!target) return false;

        if (triggerCount > 0 && (!locationId || locationId === '0')) return false;

        if (!locationId || locationId === '0') {
            const resetHtml = target.includes('districts')
                ? '<option value="0">[Chọn Quận/Huyện]</option>'
                : '<option value="0">[Chọn Phường/Xã]</option>';
            $('.' + target).html(resetHtml).trigger('change');
            return false;
        }

        HT.sendDataTogetLocation({
            data: { location_id: locationId },
            target: target,
            source: $this.attr('data-source') || 'database'
        });
    });
};

HT.sendDataTogetLocation = (option) => {
    // If it's a province request and we have it in cache, use it immediately
    if (option.target === 'province' && provinceCache[option.source]) {
        const $target = $('.' + option.target);
        $target.html(provinceCache[option.source]);
        if ($target.data('select2')) {
            $target.trigger('change.select2');
        } else {
            $target.trigger('change');
        }
        return;
    }

    triggerCount++;
    $.ajax({
        url: 'ajax/location/getLocation',
        type: 'GET',
        data: option,
        dataType: 'json',
        success: function (res) {
            // Cache current province list for next time
            if (option.target === 'province') {
                provinceCache[option.source] = res.html;
            }

            const $target = $('.' + option.target);
            $target.html(res.html);
            if ($target.data('select2')) {
                $target.trigger('change.select2');
            } else {
                $target.trigger('change');
            }
            setTimeout(() => {
                triggerCount--;
            }, 0);
        },
        error: function (jqXHR, status, err) {
            console.error('[sendData] Error:', status, err);
            triggerCount = Math.max(0, triggerCount - 1);
        }
    });
};

HT.loadCity = () => {
    if ($('body').data('ht-location-init')) {
        return;
    }
    $('body').data('ht-location-init', true);

    const snap = {
        province: _safeVar('province_id'),
        district: _safeVar('district_id'),
        ward: _safeVar('ward_id'),
        province_new: _safeVar('province_new_id'),
        district_new: _safeVar('district_new_id'), // reserved
        ward_new: _safeVar('ward_new_id'),
    };

    if (!snap.province && !snap.province_new) {
        return;
    }

    setTimeout(() => {
        if (snap.province) {
            HT.smartSet('.province', snap.province);

            if (snap.district) {
                _initStart();
                HT.loadAjaxDirect('districts-old', snap.province, 'before', () => {
                    HT.smartSet('.districts-old', snap.district);

                    if (snap.ward) {
                        _initStart();
                        HT.loadAjaxDirect('wards-old', snap.district, 'before', () => {
                            HT.smartSet('.wards-old', snap.ward);
                            _initDone();
                        });
                    }

                    _initDone();
                });
            }
        }

        if (snap.province_new) {
            HT.smartSet('.province-new', snap.province_new);

            if (snap.ward_new) {
                _initStart();
                HT.loadAjaxDirect('wards-new', snap.province_new, 'after', () => {
                    HT.smartSet('.wards-new', snap.ward_new);
                    _initDone();
                });
            }
        }

    }, 300);
};

function _safeVar(name) {
    try {
        const val = window[name];
        return (typeof val !== 'undefined' && val && val !== '0') ? val : null;
    } catch (e) {
        return null;
    }
}

$(document).ready(function () {
    HT.getLocation();
    HT.loadCity();
});