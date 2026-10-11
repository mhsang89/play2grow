# CHANGELOG

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
