# CHANGELOG

## 1.2.0 – 2026-10-11

Chủ sở hữu cung cấp người sáng lập/người liên hệ, email, địa chỉ; yêu cầu logo và phiên bản tiếng Anh.

### Thương hiệu
- Logo mới (biểu tượng bong bóng hội thoại + mầm cây + nụ cười, chữ "Play2Grow" đã chuyển thành đường nét): `brand/` có SVG/PNG, bản nền tối, biểu tượng vuông, favicon, apple-touch-icon và ảnh chia sẻ mạng xã hội 1200×630.
- Theme tự dùng logo, biểu tượng trang (favicon) và ảnh chia sẻ mặc định khi chưa tải lên logo/biểu tượng riêng (tắt bằng filter `p2g_use_default_logo`).

### Thông tin liên hệ
- Nội dung khởi đầu điền sẵn: người liên hệ **Bùi Thủy**, email **buithuy@play2grow.edu.vn**, tên đơn vị vận hành **Công ty TNHH Giáo dục Play2Grow** (chủ sở hữu yêu cầu hiển thị, chưa đối chiếu giấy tờ), người sáng lập **Bùi Thủy**, hotline **+84982534460**, địa chỉ **Khối đế P1, Khu A5 Diamond, số 3 đường Tân Thạnh, phường Tân Sơn Nhì, TP. Hồ Chí Minh** (Bước 2 chỉ điền vào trường còn trống, không ghi đè).
- Plugin: trường mới "Người liên hệ"; nhãn "Địa chỉ cơ sở / lớp học" hiển thị là "Địa chỉ:".
- Trang Giới thiệu có phần người sáng lập.

### Phiên bản tiếng Anh
- Hỗ trợ WPML (website hiện tại) và Polylang: bộ chuyển ngôn ngữ VI/EN ở thanh trên cùng và menu mobile, liên kết nội bộ/menu/thẻ chủ đề/thẻ chương trình theo đúng ngôn ngữ, `og:locale` và `inLanguage` theo ngôn ngữ.
- **Bước 1b** trong Giao diện → Play2Grow: tạo 17 trang + 6 bài viết + 9 chuyên mục tiếng Anh, liên kết làm bản dịch của nội dung tiếng Việt (bản nháp mặc định, bỏ qua mục đã có bản dịch). Bước 2 tạo thêm 5 menu tiếng Anh.
- Bản dịch giao diện `en_US` (và `en_GB`) cho theme (345 chuỗi) và plugin (148 chuỗi), gồm cả các block pattern và form liên hệ.
- Hàm `p2g_home_url()` để logo, breadcrumbs và tìm kiếm trỏ về trang chủ đúng ngôn ngữ. Thêm `Domain Path: /languages`.

## 1.1.0 – 2026-10-11

Chủ sở hữu xác nhận Play2Grow là **trung tâm có lớp học**, các chương trình đang hoạt động thật.

- "Sản phẩm" → **Chương trình học** (`/chuong-trinh/`), nhóm theo Tiếng Anh / Mỹ thuật / Âm nhạc / Nhảy.
- 7 trang lớp (STEAM English, Lớp Vẽ, Lớp thủ công, Lớp đàn Piano, Lớp đàn Keyboard, Tiktok Dance, Nhảy hiện đại) giữ URL ở gốc như website cũ; breadcrumbs đặt dưới "Chương trình học".
- Mẫu "Chi tiết chương trình": trường mới (nhóm, lịch học, sĩ số, giáo viên); độ tuổi/lịch/học phí hiển thị "Đang cập nhật – liên hệ để được tư vấn" khi trống; nhãn "Đang mở lớp / Sắp khai giảng / Lớp thử nghiệm / Tạm ngưng tuyển sinh".
- CTA mặc định "Đăng ký tư vấn"; form tự liệt kê các lớp đã xuất bản.
- Cập nhật nội dung trang chủ, Giới thiệu, Hỏi đáp, Phụ huynh, Liên hệ, Chính sách quyền riêng tư theo mô hình trung tâm.


## 1.0.0 – 2026-10-11

### Theme `play2grow-theme`
- Theme cổ điển độc lập: header 2 tầng (thanh tiện ích + menu 2 cấp có dropdown truy cập được bằng bàn phím), banner trang con, footer 4 cột, thanh CTA dính trên mobile, nút Gọi/Zalo nổi (chỉ khi đã cấu hình).
- 13 block pattern (nhóm "Play2Grow"); trang chủ dựng từ pattern, chỉnh sửa trong trình soạn thảo.
- Mẫu trang: Danh sách sản phẩm, Chi tiết sản phẩm (nhãn trạng thái Đang hoạt động/Thử nghiệm/Sắp ra mắt), Liên hệ, Toàn chiều rộng.
- Góc học tập: lưới bài viết, bộ lọc chuyên mục, bài liên quan, breadcrumbs, tác giả, ngày đăng/cập nhật, thời gian đọc.
- SEO: meta description, Open Graph, canonical cho trang lưu trữ, JSON-LD chỉ từ dữ liệu có thật, noindex theo trang; tự tắt khi có plugin SEO.
- Font Be Vietnam Pro tự lưu trữ; minh họa SVG nguyên bản; không tải tài nguyên bên thứ ba; tắt emoji script.
- Trình nhập nội dung khởi đầu (10 trang, 6 bài viết, 9 chuyên mục, menu) – mặc định bản nháp, không ghi đè; tạo "[Bản mới]" cho URL đã tồn tại.
- Tương thích: Elementor (toàn chiều rộng), LiteSpeed Cache, WPML (gettext), Contact Form 7 (CSS).

### Plugin `play2grow-core`
- Form liên hệ/đăng ký nhận thông tin: xác thực hai phía, nonce, honeypot, kiểm tra thời gian điền, giới hạn tần suất, làm mới mã khi trang bị cache, fallback không JavaScript.
- Lưu đăng ký riêng tư (chỉ quản trị viên), trạng thái xử lý, tự xóa sau N ngày, xuất/xóa dữ liệu cá nhân, gợi ý nội dung chính sách quyền riêng tư.
- Thông tin doanh nghiệp và mạng xã hội trong Tùy biến (lưu dạng option).
- Shortcode: `[p2g_contact_form]`, `[p2g_programs]`, `[p2g_latest_posts]`, `[p2g_topics]`, `[p2g_cta_button]`, `[p2g_company_info]`, `[p2g_note]`, `[p2g_retention]`.

### Công cụ và tài liệu
- `tools/md2blocks.py`, `tools/build.sh`; nội dung gốc trong `content-src/`.
- Tài liệu: README, INITIAL_AUDIT, PRODUCT_AUDIT, DESIGN_SPEC, IMPLEMENTATION_PLAN, CONTENT_AND_SITEMAP, COMPANY_INFO_REQUIRED, QA_REPORT, INSTALLATION_CHECKLIST, NETWORK_ACCESS_GUIDE.
