/**
* main.js – BĐSPro
* Data + render + event handlers
*/

/* ── BROKER ─────────────────────────────────────────────────── */
const BROKER = {
    name: 'Nguyễn Thị Thanh',
    initials: 'NT',
    phone: '0909 999 888',
    phoneRaw: '0909999888',
};

/* ── GRADIENT PALETTE cho placeholder ảnh ───────────────────── */
const GRAD = [
    ['#fde8d8', '#f4b896'],
    ['#dbeafe', '#bfdbfe'],
    ['#dcfce7', '#bbf7d0'],
    ['#fef9c3', '#fde68a'],
    ['#fce7f3', '#fbcfe8'],
    ['#ede9fe', '#ddd6fe'],
    ['#e0f2fe', '#bae6fd'],
    ['#fff7ed', '#fed7aa'],
];

/** Placeholder ảnh – không bao giờ broken */
function imgPh(idx, icon = 'fas fa-building') {
    const [a, b] = GRAD[idx % GRAD.length];
    return `<div class="img-ph" style="background:linear-gradient(135deg,${a},${b})">
    <i class="${icon}"></i>
  </div>`;
}

/* ── 3 BADGE TYPES ───────────────────────────────────────────
   HOT     → ribbon dán góc TRÁI  (position absolute trong card-img)
   URGENT  → starburst góc PHẢI   (position absolute trong card-img)
   FEATURED→ ribbon vàng góc TRÁI (thay thế HOT khi không có HOT)
   ─────────────────────────────────────────────────────────── */
const BADGE_LEFT = {
    hot: `<div class="badge-hot"><i class="fas fa-fire"></i> HOT</div>`,
    featured: `<div class="badge-featured"><i class="fas fa-star"></i> Nổi bật</div>`,
};
const BADGE_RIGHT = {
    urgent: `<div class="badge-urgent">URGENT</div>`,
};

/* ── DATA MẪU – MUA BÁN ──────────────────────────────────────── */
const SELL = [
    {
        type: 'can-ho', badge: 'featured',
        price: '3,2 tỷ', note: '~52 tr/m²',
        title: 'Căn hộ 2PN The Vista An Phú – View sông, nội thất cao cấp, sổ hồng chính chủ',
        district: 'Quận 2', area: '62', beds: '2', baths: '2', floors: '', photos: 8, date: '15/12/2024',
    },
    {
        type: 'nha-rieng', badge: 'hot',
        price: '5,8 tỷ', note: '',
        title: 'Nhà riêng 3 tầng hẻm 8m Bình Thạnh – Ô tô vào nhà, gần chợ, tiện kinh doanh',
        district: 'Bình Thạnh', area: '67', beds: '4', baths: '3', floors: '3', photos: 12, date: '14/12/2024',
    },
    {
        type: 'dat-nen', badge: null,
        price: '1,8 tỷ', note: '',
        title: 'Đất nền khu dân cư Bình Chánh – Hạ tầng hoàn thiện, sổ riêng, thổ cư 100%',
        district: 'Bình Chánh', area: '80', beds: '', baths: '', floors: '', photos: 5, date: '13/12/2024',
    },
    {
        type: 'can-ho', badge: null,
        price: '2,1 tỷ', note: '~45 tr/m²',
        title: 'Căn hộ 1PN+1 Vinhomes Grand Park – Full nội thất, tầng cao, hướng Đông Nam',
        district: 'Thủ Đức', area: '47', beds: '2', baths: '1', floors: '', photos: 9, date: '12/12/2024',
    },
    {
        type: 'biet-thu', badge: 'hot',
        price: '18,5 tỷ', note: '',
        title: 'Biệt thự đơn lập Thảo Điền – Hồ bơi riêng, an ninh 24/7, 500m² sân vườn',
        district: 'Quận 2', area: '320', beds: '5', baths: '5', floors: '3', photos: 20, date: '11/12/2024',
    },
    {
        type: 'nha-rieng', badge: 'urgent',
        price: '4,5 tỷ', note: '',
        title: 'Nhà phố 4 tầng Tân Phú – Mặt tiền đường 12m, kinh doanh tốt, SHR',
        district: 'Tân Phú', area: '55', beds: '5', baths: '4', floors: '4', photos: 10, date: '10/12/2024',
    },
    {
        type: 'dat-nen', badge: 'urgent',
        price: '980 tr', note: '',
        title: 'Đất thổ cư Hóc Môn – Đường xe hơi, điện nước đầy đủ, giá đầu tư sinh lời cao',
        district: 'Hóc Môn', area: '90', beds: '', baths: '', floors: '', photos: 4, date: '09/12/2024',
    },
    {
        type: 'can-ho', badge: 'featured',
        price: '4,9 tỷ', note: '~59 tr/m²',
        title: 'Căn hộ 3PN Masteri Millennium Q4 – Nội thất cao cấp, view trực diện sông Sài Gòn',
        district: 'Quận 4', area: '83', beds: '3', baths: '2', floors: '', photos: 15, date: '08/12/2024',
    },
];

/* ── DATA MẪU – CHO THUÊ ─────────────────────────────────────── */
const RENT = [
    {
        type: 'can-ho', badge: 'featured',
        price: '12 tr/tháng', note: '',
        title: 'Căn hộ 2PN Sunrise City Q7 – Full nội thất, tầng cao, hồ bơi & gym',
        district: 'Quận 7', area: '68', beds: '2', baths: '2', floors: '', photos: 7, date: '15/12/2024',
    },
    {
        type: 'nha-rieng', badge: null,
        price: '8 tr/tháng', note: '',
        title: 'Nhà nguyên căn 3PN Bình Thạnh – Ô tô trước nhà, gần trường quốc tế',
        district: 'Bình Thạnh', area: '75', beds: '3', baths: '2', floors: '', photos: 6, date: '14/12/2024',
    },
    {
        type: 'mat-bang', badge: 'hot',
        price: '25 tr/tháng', note: '',
        title: 'Mặt bằng kinh doanh Quận 1 – Mặt tiền 6m, vị trí đắc địa, 2 mặt tiền',
        district: 'Quận 1', area: '120', beds: '', baths: '1', floors: '', photos: 9, date: '13/12/2024',
    },
    {
        type: 'can-ho', badge: null,
        price: '5,5 tr/tháng', note: '',
        title: 'Studio Phú Nhuận – Ban công rộng, cửa sổ thoáng, gần chợ Bà Chiểu',
        district: 'Phú Nhuận', area: '28', beds: '1', baths: '1', floors: '', photos: 5, date: '12/12/2024',
    },
    {
        type: 'can-ho', badge: 'urgent',
        price: '18 tr/tháng', note: '',
        title: 'Penthouse 3PN Gò Vấp – Tầng thượng, view thành phố cực đẹp, nội thất sang',
        district: 'Gò Vấp', area: '110', beds: '3', baths: '3', floors: '', photos: 11, date: '11/12/2024',
    },
    {
        type: 'nha-rieng', badge: null,
        price: '15 tr/tháng', note: '',
        title: 'Nhà phố 3 tầng Quận 10 – Thích hợp văn phòng hoặc ở, đường rộng 8m',
        district: 'Quận 10', area: '85', beds: '4', baths: '3', floors: '3', photos: 8, date: '10/12/2024',
    },
    {
        type: 'can-ho', badge: null,
        price: '9 tr/tháng', note: '',
        title: 'Căn hộ 2PN Vinhomes Central Park – Tiện ích cao cấp, view sông Sài Gòn',
        district: 'Bình Thạnh', area: '70', beds: '2', baths: '2', floors: '', photos: 14, date: '09/12/2024',
    },
    {
        type: 'van-phong', badge: null,
        price: '22 tr/tháng', note: '',
        title: 'Văn phòng 80m² Quận 3 – Sảnh đẹp, thang máy tốc độ cao, bảo vệ 24/7',
        district: 'Quận 3', area: '80', beds: '', baths: '1', floors: '', photos: 6, date: '08/12/2024',
    },
];

/* ── DATA MẪU – VIP ──────────────────────────────────────────── */
const VIP = [
    { price: '6,5 tỷ', title: 'Nhà mặt tiền Lê Văn Sỹ Q3 – 4 tầng 5×20m, kinh doanh sầm uất', meta: 'Quận 3 · 100 m² · 5 PN · 4 tầng' },
    { price: '3,8 tỷ', title: 'Căn hộ penthouse Sky Garden Phú Mỹ Hưng – View toàn cảnh công viên', meta: 'Quận 7 · 150 m² · 3 PN' },
    { price: '22 tỷ', title: 'Biệt thự compound Thảo Điền An Phú – Bể bơi, hầm xe, an ninh 24/7', meta: 'Quận 2 · 450 m² · 5 PN' },
    { price: '2,95 tỷ', title: 'Căn hộ 2PN Landmark 81 – Tầng 30+, nội thất Châu Âu, sổ hồng riêng', meta: 'Bình Thạnh · 72 m² · 2 PN' },
];

/* ── DATA – TIN TỨC ──────────────────────────────────────────── */
const NEWS = [
    { cat: 'Thị trường', title: 'Giá căn hộ TP.HCM tăng mạnh quý 4/2024 – Cơ hội hay thách thức cho người mua?', date: '15/12/2024', views: '2.4k' },
    { cat: 'Pháp lý', title: 'Luật Đất đai 2024 có hiệu lực – Những điểm mới quan trọng nhất người mua cần biết', date: '14/12/2024', views: '5.1k' },
    { cat: 'Đầu tư', title: 'Top 5 khu vực BĐS tiềm năng nhất TP.HCM nửa đầu 2025 theo nhận định chuyên gia', date: '13/12/2024', views: '3.8k' },
    { cat: 'Kiến thức', title: 'Hướng dẫn kiểm tra pháp lý nhà đất trước khi xuống tiền để tránh rủi ro', date: '12/12/2024', views: '1.9k' },
];

/* ── RENDER: Property Card ────────────────────────────────────── */
function renderCard(d, idx) {
    const specs = [];
    if (d.area) specs.push(`<span class="spec-item"><i class="fas fa-vector-square"></i><strong>${d.area}</strong> m²</span>`);
    if (d.beds) specs.push(`<span class="spec-item"><i class="fas fa-bed"></i><strong>${d.beds}</strong> PN</span>`);
    if (d.baths) specs.push(`<span class="spec-item"><i class="fas fa-bath"></i><strong>${d.baths}</strong> WC</span>`);
    if (d.floors) specs.push(`<span class="spec-item"><i class="fas fa-layer-group"></i><strong>${d.floors}</strong> tầng</span>`);

    /* Tách badge: left (hot/featured) và right (urgent) */
    const badgeLeft = BADGE_LEFT[d.badge] || '';
    const badgeRight = BADGE_RIGHT[d.badge] || '';

    return `
  <div class="uk-width-1-2 uk-width-1-3@s uk-width-1-4@m">
    <div class="prop-card" onclick="goDetail()">
      <div class="card-img">
        ${imgPh(idx)}
        ${badgeLeft}
        ${badgeRight}
        <div class="card-photo-count"><i class="fas fa-camera"></i>${d.photos}</div>
      </div>
      <div class="card-body">
        <div class="card-price">
          ${d.price}
          ${d.note ? `<span class="card-price-note">${d.note}</span>` : ''}
        </div>
        <div class="card-title">${d.title}</div>
        <div class="card-location">
          <i class="fas fa-map-marker-alt"></i>${d.district}, TP.HCM
        </div>
        ${specs.length ? `<div class="card-specs">${specs.join('')}</div>` : ''}
      </div>
      <div class="card-broker">
        <div class="broker-ava">${BROKER.initials}</div>
        <div class="broker-name-sm">${BROKER.name}</div>
        <a href="tel:${BROKER.phoneRaw}" class="broker-phone-sm"
           onclick="event.stopPropagation()">
          <i class="fas fa-phone-alt"></i>${BROKER.phone}
        </a>
      </div>
      <div class="card-footer">
        <span class="card-date"><i class="fas fa-clock"></i> ${d.date}</span>
        <a href="tel:${BROKER.phoneRaw}" class="card-cta"
           onclick="event.stopPropagation()">Liên hệ</a>
      </div>
    </div>
  </div>`;
}

/* ── RENDER: VIP Card (horizontal) ──────────────────────────── */
function renderVip(d, idx) {
    return `
  <div class="uk-width-1-1 uk-width-1-2@m" style="margin-bottom:12px">
    <div class="vip-card" onclick="goDetail()">
      <div class="vip-ribbon">VIP</div>
      <div class="vip-img">${imgPh(idx + 4, 'fas fa-star')}</div>
      <div class="vip-body">
        <div class="vip-price">${d.price}</div>
        <div class="vip-title">${d.title}</div>
        <div class="vip-meta"><i class="fas fa-map-marker-alt"></i> ${d.meta}</div>
        <div class="vip-actions">
          <a href="tel:${BROKER.phoneRaw}" class="btn btn-primary btn-sm"
             onclick="event.stopPropagation()">
            <i class="fas fa-phone-alt"></i> Gọi ngay
          </a>
          <a href="#" class="btn btn-ghost btn-sm" onclick="event.stopPropagation()">
            Xem chi tiết
          </a>
        </div>
      </div>
    </div>
  </div>`;
}

/* ── RENDER: News Card ───────────────────────────────────────── */
const NEWS_GRAD = [
    ['#dbeafe', '#3b82f6'], ['#dcfce7', '#16a34a'],
    ['#fef9c3', '#ca8a04'], ['#fce7f3', '#db2777'],
];
function renderNews(d, idx) {
    const [bg, ic] = NEWS_GRAD[idx % NEWS_GRAD.length];
    return `
  <div class="uk-width-1-2 uk-width-1-4@m">
    <div class="news-card" onclick="window.location.href='#'">
      <div class="news-img">
        <div class="img-ph" style="background:${bg}">
          <i class="fas fa-newspaper" style="color:${ic};font-size:2rem;opacity:.3"></i>
        </div>
      </div>
      <div class="news-body">
        <div class="news-cat">${d.cat}</div>
        <div class="news-title">${d.title}</div>
        <div class="news-meta">
          <span><i class="fas fa-clock"></i> ${d.date}</span>
          <span><i class="fas fa-eye"></i> ${d.views}</span>
        </div>
      </div>
    </div>
  </div>`;
}

/* ── RENDER ALL ──────────────────────────────────────────────── */
function renderAll() {
    const get = id => document.getElementById(id);

    const vipEl = get('vip-grid');
    if (vipEl) vipEl.innerHTML = VIP.map((d, i) => renderVip(d, i)).join('');

    const buyEl = get('buy-grid');
    if (buyEl) buyEl.innerHTML = SELL.map((d, i) => renderCard(d, i)).join('');

    const rentEl = get('rent-grid');
    if (rentEl) rentEl.innerHTML = RENT.map((d, i) => renderCard(d, i)).join('');

    const newsEl = get('news-grid');
    if (newsEl) newsEl.innerHTML = NEWS.map((d, i) => renderNews(d, i)).join('');
}

/* ── FILTER PILLS ────────────────────────────────────────────── */
function initFilter() {
    document.querySelectorAll('[data-filter-group]').forEach(group => {
        group.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', function () {
                group.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('is-active'));
                this.classList.add('is-active');

                const filter = this.dataset.filter;
                const targetId = this.dataset.target;
                const grid = document.getElementById(targetId);
                if (!grid) return;

                const src = targetId === 'rent-grid' ? RENT : SELL;
                const filtered = filter === 'all' ? src : src.filter(d => d.type === filter);

                grid.innerHTML = filtered.length
                    ? filtered.map((d, i) => renderCard(d, i)).join('')
                    : `<div class="uk-width-1-1 uk-text-center uk-padding">
               <i class="fas fa-search" style="font-size:2rem;opacity:.2;display:block;margin-bottom:8px"></i>
               <span class="uk-text-muted">Không có tin phù hợp</span>
             </div>`;
            });
        });
    });
}

/* ── SORT BUTTONS ────────────────────────────────────────────── */
function initSort() {
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('.sort-row');
            row.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
}

/* ── SUBNAV ──────────────────────────────────────────────────── */
function initSubnav() {
    document.querySelectorAll('.subnav-item a').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.subnav-item').forEach(li => li.classList.remove('is-active'));
            a.parentElement.classList.add('is-active');
        });
    });
}

/* ── SEARCH TABS ─────────────────────────────────────────────── */
function initSearchTabs() {
    document.querySelectorAll('.search-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.search-tab').forEach(t => t.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
}

/* ── CATEGORY GRID ───────────────────────────────────────────── */
function initCatGrid() {
    document.querySelectorAll('.cat-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
}

/* ── MOBILE DRAWER ───────────────────────────────────────────── */
function initDrawer() {
    const toggle = document.getElementById('nav-toggle');
    const drawer = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('drawer-overlay');
    const closeBtn = document.getElementById('drawer-close');
    if (!toggle || !drawer) return;

    const open = () => { drawer.classList.add('is-open'); document.body.style.overflow = 'hidden'; };
    const close = () => { drawer.classList.remove('is-open'); document.body.style.overflow = ''; };

    toggle.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    overlay.addEventListener('click', close);
}

/* ── BACK TO TOP ─────────────────────────────────────────────── */
function initBackTop() {
    const btn = document.getElementById('back-top');
    if (!btn) return;
    window.addEventListener('scroll', () => {
        btn.classList.toggle('is-visible', window.scrollY > 400);
    }, { passive: true });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

/* ── SMOOTH SCROLL cho anchor links ─────────────────────────── */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const id = this.getAttribute('href').slice(1);
            const target = document.getElementById(id);
            if (!target) return;
            e.preventDefault();
            const offset = 70; /* chiều cao header sticky */
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        });
    });
}

/* ── NAVBAR SHADOW on scroll ─────────────────────────────────── */
function initNavbarScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;
    window.addEventListener('scroll', () => {
        header.style.boxShadow = window.scrollY > 4
            ? '0 4px 16px rgba(0,0,0,0.12)'
            : '';
    }, { passive: true });
}

/* ── CONTACT FORM demo ───────────────────────────────────────── */
function initContactForm() {
    const btn = document.getElementById('form-submit-btn');
    if (!btn) return;
    btn.addEventListener('click', function () {
        const orig = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
        this.disabled = true;
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-check"></i> Đã gửi thành công!';
            this.style.background = '#43a047';
            setTimeout(() => {
                this.innerHTML = orig;
                this.style.background = '';
                this.disabled = false;
            }, 3000);
        }, 1500);
    });
}

/* ── LOAD MORE demo ──────────────────────────────────────────── */
function initLoadMore() {
    const btn = document.getElementById('load-more-btn');
    if (!btn) return;
    let loaded = false;
    btn.addEventListener('click', function () {
        if (loaded) return;
        const orig = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
        this.disabled = true;
        setTimeout(() => {
            const grid = document.getElementById('buy-grid');
            const extra = SELL.slice(0, 4).map((d, i) =>
                renderCard({ ...d, date: '01/12/2024' }, i + 20)
            ).join('');
            grid.insertAdjacentHTML('beforeend', extra);
            this.innerHTML = orig;
            this.disabled = false;
            loaded = true;
            this.textContent = 'Đã hiển thị tất cả';
        }, 1000);
    });
}

/* ── POPULATE broker data-attrs ──────────────────────────────── */
function populateBroker() {
    document.querySelectorAll('[data-broker-name]').forEach(el => el.textContent = BROKER.name);
    document.querySelectorAll('[data-broker-initials]').forEach(el => el.textContent = BROKER.initials);
    document.querySelectorAll('[data-broker-phone]').forEach(el => {
        el.textContent = BROKER.phone;
        if (el.tagName === 'A') el.href = `tel:${BROKER.phoneRaw}`;
    });
}

/* ── DETAIL navigate placeholder ────────────────────────────── */
function goDetail() { /* TODO: window.location.href = 'detail.html'; */ }

/* ── INIT ────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    renderAll();
    populateBroker();
    initFilter();
    initSort();
    initSubnav();
    initSearchTabs();
    initCatGrid();
    initDrawer();
    initBackTop();
    initSmoothScroll();
    initNavbarScroll();
    initContactForm();
    initLoadMore();
});