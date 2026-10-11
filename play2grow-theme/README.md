# Play2Grow – WordPress theme

Phiên bản **1.1.0** · Giấy phép GPLv2+ · Text domain `play2grow-theme`

Giao diện cổ điển (classic theme) độc lập cho website Play2Grow – học tiếng Anh qua trải nghiệm dành cho trẻ. Không cần Elementor hay plugin trả phí. Không tải font, script hay CSS từ bên thứ ba.

## Yêu cầu

| | Tối thiểu khai báo | Đã kiểm thử |
|---|---|---|
| WordPress | 6.4 | **7.1.3** (cùng phiên bản đang chạy trên play2grow.edu.vn) |
| PHP | 7.4 | **8.3.6** |
| Plugin đi kèm (khuyến nghị) | Play2Grow Core 1.0.0 (`play2grow-core.zip`) | Có |

PHP 7.4–8.2 và WordPress 6.4–7.0 chưa được kiểm thử thực tế.

## Cài đặt

1. **Sao lưu** toàn bộ website và database (xem INSTALLATION_CHECKLIST.md). Nên thử trên staging trước.
2. WP Admin → **Giao diện → Giao diện → Thêm giao diện mới → Tải giao diện lên** → chọn `play2grow-theme.zip` → **Cài đặt** → **Kích hoạt**.
3. WP Admin → **Plugin → Cài mới → Tải plugin lên** → `play2grow-core.zip` → **Kích hoạt**.
4. Mở **Giao diện → Play2Grow** để xem danh sách kiểm tra sau cài đặt.

## Cấu hình

| Việc | Ở đâu |
|---|---|
| Logo | Giao diện → Tùy biến → Nhận diện website → Logo |
| Tên website, khẩu hiệu | Cài đặt → Tổng quan |
| Màu chủ đạo / màu nhấn | Giao diện → Tùy biến → Màu sắc |
| Chữ trên nút CTA, trang đích của nút, thanh trên cùng, nút liên hệ nổi | Giao diện → Tùy biến → Play2Grow → Đầu trang & nút đăng ký |
| Mô tả và bản quyền ở chân trang | Giao diện → Tùy biến → Play2Grow → Chân trang |
| Thông tin doanh nghiệp, mạng xã hội *(plugin)* | Giao diện → Tùy biến → Play2Grow – Thông tin doanh nghiệp / Mạng xã hội |
| Email nhận form, thời gian lưu trữ *(plugin)* | Cài đặt → Play2Grow |
| Menu | Giao diện → Menu. Vị trí: Thanh trên cùng, Menu chính (2 cấp), Chân trang – Khám phá, Chân trang – Hỗ trợ, Chân trang – Chính sách |
| Trang chủ / trang bài viết | Cài đặt → Đọc → Trang chủ tĩnh |
| Thanh bên bài viết (tùy chọn) | Giao diện → Widget → "Thanh bên bài viết" |

## Quản lý nội dung

- **Trang chủ** là một Trang bình thường, dựng từ các mẫu khối (Block patterns) trong nhóm **Play2Grow**. Sửa chữ, đổi ảnh, thêm/bớt section ngay trong trình soạn thảo.
- **Mẫu trang**: *Danh sách chương trình* (tự hiện thẻ cho mọi trang dùng mẫu Chi tiết chương trình, nhóm theo Tiếng Anh/Mỹ thuật/Âm nhạc/Nhảy), *Chi tiết chương trình* (hộp Thông tin nhanh + nhãn trạng thái lớp), *Liên hệ* (form + thông tin doanh nghiệp), *Toàn chiều rộng (không tiêu đề)* cho trang dựng bằng khối/Elementor.
- **Hộp "SEO cơ bản"** (meta description, noindex) trên mỗi trang/bài. Hộp này tự ẩn khi có Yoast/Rank Math/AIOSEO/SEOPress/The SEO Framework/Slim SEO.
- **Ghi chú nội bộ** `[p2g_note]…[/p2g_note]`: chỉ người dùng có quyền biên tập mới thấy; dùng để đánh dấu chỗ cần xác nhận. Xóa trước khi coi trang là hoàn chỉnh.
- **Nội dung khởi đầu (tùy chọn)**: Giao diện → Play2Grow → *Bước 1* tạo 10 trang + 7 trang lớp học + 6 bài viết + 9 chuyên mục (mặc định là **bản nháp**, không ghi đè; trang trùng đường dẫn được tạo thành "[Bản mới] …"). Sau khi rà soát và xuất bản, *Bước 2* tạo menu (chỉ cho vị trí còn trống) và đặt Trang chủ/Góc học tập.

## Tính năng kỹ thuật

- Breadcrumbs (dùng breadcrumbs của Yoast/Rank Math nếu được bật), JSON-LD `Organization`/`WebSite`/`BlogPosting`/`BreadcrumbList` chỉ chứa dữ liệu có thật, Open Graph, canonical cho trang lưu trữ, loại trang noindex khỏi `wp-sitemap.xml`.
- Font Be Vietnam Pro tự lưu trữ (subset tiếng Việt, ~27 KB/độ đậm); không có emoji script; JS ~6 KB, `defer`.
- Trang dựng bằng Elementor được hiển thị toàn chiều rộng (Elementor tự quản lý bố cục).
- Tương thích LiteSpeed Cache: form lấy mã bảo mật mới khi người dùng bắt đầu nhập.
- Hỗ trợ WPML: chuỗi giao diện dùng gettext; menu theo từng ngôn ngữ cấu hình trong WPML.

## Chưa thể kiểm thử

Gửi email thật (cần SMTP trên hosting), giao diện trên thiết bị iOS/Android thật, WPML/Elementor Pro/LiteSpeed thực tế (các plugin trả phí này không có trong môi trường thử nghiệm), PHP < 8.3. Xem QA_REPORT.md.

## Khôi phục (rollback)

Giao diện → Giao diện → kích hoạt lại **LittleDino Child**. Nội dung, trang, menu cũ không bị xóa. Dữ liệu tạo bởi theme (trang/bài khởi đầu, menu mới, cài đặt Tùy biến) vẫn nằm trong database và có thể xóa thủ công. Nếu cần khôi phục hoàn toàn, dùng bản sao lưu đã tạo trước khi cài.
