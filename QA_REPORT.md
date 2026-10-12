# QA REPORT

Ngày: 11/10/2026. Môi trường thử nghiệm: WordPress **7.1.3** (mã nguồn chính thức từ GitHub WordPress/WordPress), PHP **8.3.6** CLI server, cơ sở dữ liệu SQLite (plugin SQLite Database Integration 3.1.0), Chromium (bản đi kèm Playwright 1.56, build 1194), Lighthouse 12.8.2. Không có MySQL, máy chủ mail, hay các plugin trả phí của website thật.

Phân loại: ✅ **Kiểm thử thực tế** (chạy trên WordPress thật) · 🔎 **Kiểm tra tĩnh** · ⛔ **Chưa kiểm thử** (thiếu môi trường hoặc quyền).

## 1. Cấu trúc và cài đặt

| Hạng mục | Loại | Kết quả |
|---|---|---|
| ZIP theme có thư mục gốc `play2grow-theme/` chứa `style.css` | ✅ | Đạt (101 file, 328 KB) |
| ZIP plugin có thư mục gốc `play2grow-core/` chứa `play2grow-core.php` | ✅ | Đạt (13 file, 28 KB) |
| Upload ZIP qua **WP Admin → Giao diện → Thêm mới → Tải giao diện lên** trên WordPress mới cài | ✅ | "Theme installed successfully", kích hoạt được, hiện thông báo hướng dẫn |
| Upload plugin qua **Plugin → Cài mới → Tải plugin lên** | ✅ | Cài và kích hoạt được; lịch tự xóa (cron) được đăng ký |
| Theme Check (plugin chính thức WordPress/theme-check, bản GitHub mới nhất) | ✅ | **PASS**. Còn: khuyến nghị `register_block_style`, `custom-header`, `custom-background` (không áp dụng); cảnh báo tên thư mục `play2grow-theme` khác slug suy ra từ tên theme `play2grow` (giữ đúng tên ZIP theo yêu cầu; chỉ ảnh hưởng nếu nộp lên WordPress.org). |
| Cú pháp PHP (`php -l`) toàn bộ file | 🔎 | 0 lỗi |
| PHPCS – WordPress-Extra + I18n (WPCS 3) | 🔎 | 0 lỗi; 6 cảnh báo còn lại là khuyến nghị đặt tên biến (`$class`, `$parent`), tham số chưa dùng (chữ ký hook bắt buộc), `posts_per_page = 200` trong tác vụ dọn dữ liệu định kỳ |
| Cú pháp JavaScript | 🔎 | Đạt (`node` parse) |
| Lỗi PHP trong `debug.log` (WP_DEBUG bật) khi duyệt toàn site, admin, form | ✅ | Không có lỗi/cảnh báo từ theme hoặc plugin (chỉ có cảnh báo của WordPress core không kết nối được WordPress.org để kiểm tra cập nhật) |

## 2. Trang, điều hướng, liên kết

| Hạng mục | Loại | Kết quả |
|---|---|---|
| 10 trang + 7 trang lớp + 6 bài + chuyên mục + tìm kiếm + tác giả + thẻ trả về 200; trang không tồn tại trả về 404 | ✅ | Đạt |
| Quét liên kết nội bộ (crawler: 46 trang, 56 URL gồm CSS/JS/font/ảnh) | ✅ | **0 liên kết hỏng** |
| Không còn token `{{…}}` hay shortcode thô `[p2g_…]` trong HTML | ✅ | Đạt, kể cả khi **tắt** plugin (shortcode tự bị loại bỏ) |
| Mỗi trang đúng 1 thẻ H1 | ✅ | Đạt |
| Block Gutenberg hợp lệ khi mở trình soạn thảo (23 trang/bài) | ✅ | **0 block lỗi** |
| Trang Chương trình học nhóm đúng 4 nhóm; trang lớp có "Thông tin nhanh", breadcrumbs "Trang chủ / Chương trình học / Lớp" (cả JSON-LD), mục "Chương trình khác" | ✅ | Đạt |
| Danh sách "Anh/chị quan tâm đến" của form tự lấy 7 lớp đã xuất bản | ✅ | Đạt |
| Menu mobile: mở/đóng, `aria-expanded`, nút mở menu con, Esc đóng và trả focus | ✅ | Đạt |
| Bàn phím desktop: skip link, dropdown khi focus (`:focus-within`), ô tìm kiếm nhận focus | ✅ | Đạt |
| Tràn ngang ở 390px / 820px / 1366px trên 5 loại trang | ✅ | 0px |
| Ghi chú nội bộ: khách thấy 0, biên tập viên thấy đủ | ✅ | Đạt (0 / 6 trên trang Giới thiệu) |

## 3. Form liên hệ (plugin Play2Grow Core)

| Kịch bản | Loại | Kết quả |
|---|---|---|
| Gửi rỗng → lỗi ở 3 trường bắt buộc, focus vào trường đầu | ✅ | Đạt |
| Số điện thoại sai định dạng | ✅ | Hiện lỗi tiếng Việt |
| Gửi hợp lệ (AJAX) → thông báo thành công, ẩn form, lưu đăng ký, tạo email (Reply-To đúng) | ✅ | Đạt (email được chặn và ghi ra file trong môi trường thử nghiệm) |
| **Không JavaScript** → lỗi hiển thị, **giữ lại dữ liệu đã nhập**, URL chỉ chứa token ngẫu nhiên (không có dữ liệu cá nhân) | ✅ | Đạt |
| Trang được phục vụ từ cache cũ (nonce hết hạn + stamp cũ) | ✅ | Vẫn gửi thành công nhờ cơ chế làm mới mã (~3,5 giây) |
| Nonce sai / stamp giả / gửi dưới 3 giây | ✅ | Bị từ chối, thông báo rõ |
| Honeypot có giá trị | ✅ | Trả "thành công" giả cho bot, **không lưu** |
| Giới hạn 5 lần / 10 phút / IP (đã băm) | ✅ | Lần thứ 6 bị từ chối |
| Mã độc trong tên/tin nhắn (`<script>`) | ✅ | Bị loại bỏ; giá trị "quan tâm" ngoài danh sách bị bỏ qua |
| Phân quyền: biên tập viên mở danh sách đăng ký | ✅ | **403** (chỉ quản trị viên) |
| Đăng ký không truy cập công khai được (`?post_type=p2g_lead&p=…`) | ✅ | 404 |
| Công cụ Xuất / Xóa dữ liệu cá nhân của WordPress | ✅ | Xuất 2 mục; xóa thành công |
| Tự xóa đăng ký quá hạn | ✅ | Đăng ký ghi lùi ngày bị xóa khi chạy tác vụ |
| Gửi email thật qua SMTP/hosting | ⛔ | Không có máy chủ mail |

## 4. SEO và hiệu năng

| Hạng mục | Loại | Kết quả |
|---|---|---|
| Title riêng, meta description riêng, Open Graph, canonical (core cho trang đơn, theme cho lưu trữ) | ✅ | Đạt |
| JSON-LD: chỉ `Organization` (tên, URL), `WebSite`, `BlogPosting`, `BreadcrumbList`; không có đánh giá/địa chỉ khi chưa nhập | ✅ | Đạt |
| `wp-sitemap.xml` hoạt động; trang noindex bị loại khỏi sitemap | ✅ | Đạt |
| Tự tắt meta/schema khi có plugin SEO | 🔎 | Đã kiểm tra mã (dựa trên hằng số của từng plugin); chưa cài Yoast/Rank Math thật |
| **Lighthouse 12.8.2** (mô phỏng mobile, máy chủ thử nghiệm **không nén gzip, không cache**) | ✅ | Trang chủ: **Performance 93 · Accessibility 98 · Best Practices 100 · SEO 100** (LCP 2,7 s, CLS 0, TBT 10 ms). Bài viết: **96 · 100 · 100 · 100**. Lỗi thứ tự heading ở trang chủ đã được sửa sau lần đo này (chưa đo lại). |
| Dung lượng trang chủ (mobile, chưa nén) | ✅ | 12 request, ~251 KB (font 106 KB, CSS 58 KB, JS 3 KB) |
| Số đo trên website production | ⛔ | Chưa cài lên production; kết quả thật phụ thuộc hosting, LiteSpeed, ảnh do chủ sở hữu tải lên |

## 5. Tương thích

| Hạng mục | Loại | Ghi chú |
|---|---|---|
| WordPress 7.1.3 / PHP 8.3 | ✅ | Đạt |
| Theme chạy **không có** plugin Play2Grow Core | ✅ | Đạt; form/thông tin doanh nghiệp ẩn; admin có thông báo |
| Elementor / Elementor Pro, WPML, Revolution Slider, LiteSpeed Cache, Contact Form 7 | ⛔ | Plugin trả phí/không có trong môi trường. Đã viết mã tương thích (trang Elementor toàn chiều rộng, tương thích cache, CSS cho CF7) nhưng **chưa chạy thật**. **Rủi ro**: widget riêng của LittleDino trong các trang Elementor cũ có thể không hiển thị khi đổi theme. |
| PHP 7.4–8.2, WordPress 6.4–7.0 | ⛔ | Chưa kiểm thử |
| Safari iOS, Chrome Android trên thiết bị thật | ⛔ | Chỉ giả lập viewport trong Chromium |
| Trình đọc màn hình (NVDA/VoiceOver) | ⛔ | Chỉ kiểm tra ARIA/semantic tĩnh và Lighthouse |

## 6. Kiểm tra nội dung (checklist mục 7 của brief)

| Câu hỏi | Kết quả |
|---|---|
| Còn Lorem Ipsum? | Không |
| Còn tiếng Anh không cần thiết? | Chỉ có khẩu hiệu thương hiệu, mẫu câu tiếng Anh cho trẻ (có chủ đích) và tên tài liệu tham khảo |
| Lỗi chính tả / dấu? | Đã rà soát thủ công; không phát hiện lỗi. Nên có người bản ngữ đọc lại lần cuối. |
| Đoạn lặp lại giữa các trang? | Các định nghĩa được viết lại theo từng ngữ cảnh; câu hỏi thường gặp ở trang chủ là bản rút gọn có chủ đích của trang Hỏi đáp |
| Thông tin bịa đặt? | Không có số liệu, lời chứng thực, giải thưởng, đối tác, tính năng hay thông tin doanh nghiệp nào được tạo ra. Sứ mệnh/tầm nhìn/nguyên tắc là **đề xuất**, có ghi chú cần xác nhận. |
| Lời hứa chưa có căn cứ? | Không cam kết kết quả học tập; các trích dẫn khuyến nghị WHO/AAP có ghi nguồn và lưu ý tham khảo |
| CTA dẫn tới trang không tồn tại? | Không. Nút tự ẩn khi trang đích chưa xuất bản. |
| Trang chỉ có tiêu đề? | Không trong nội dung khởi đầu. 3 chuyên mục chưa có bài tự ẩn khỏi khu chủ đề (nhưng vẫn nằm trong menu sau Bước 2; xem CONTENT_AND_SITEMAP.md) |

## 7. Lỗi đã phát hiện và sửa trong quá trình QA

1. `form.action` trả về input ẩn tên `action` → AJAX gửi sai URL. Đã sửa bằng `getAttribute('action')`.
2. Bố cục section lệch do WordPress bọc `.wp-block-group__inner-container` ở classic theme → đã viết lại selector.
3. Menu desktop xuống dòng ở 1366px → đổi breakpoint sang 1200px, `nowrap`.
4. Nút trong khối CTA cùng màu nền → đổi sang màu nhấn.
5. Cài lại menu khi `nav_menu_locations` không phải mảng gây lỗi → đã thêm kiểm tra.
6. Nonce hết hạn khi trang được cache → thêm endpoint làm mới mã.
7. Thứ tự heading (h3 ngay sau h1) ở thẻ truy cập nhanh → đổi thành h2.
8. Theme Check bắt buộc: shortcode/CPT trong theme → tách sang plugin Play2Grow Core; thêm readme.txt, screenshot.

### Bổ sung 1.2.0 – logo, thông tin liên hệ, tiếng Anh

Đã kiểm thử với **Polylang 3.8** (vi mặc định + en) trên WordPress 7.1.3 / PHP 8.3 thử nghiệm:
- Nhập nội dung: 32 mục tiếng Việt + 32 mục tiếng Anh (17 trang + 6 bài + 9 chuyên mục) liên kết làm bản dịch; 5 menu tiếng Anh gán đúng vị trí.
- Bộ chuyển ngôn ngữ, logo/breadcrumbs/tìm kiếm trỏ đúng trang chủ theo ngôn ngữ; `<html lang>` `vi` / `en-US`.
- Quét văn bản: trang tiếng Anh không còn chữ tiếng Việt ngoài tên ngôn ngữ "Tiếng Việt" và địa chỉ; trang tiếng Việt không còn chữ Anh ngoài khẩu hiệu thương hiệu.
- Form `/en/contact/`: thông báo lỗi/thành công, xác thực, gửi AJAX và không JavaScript hoạt động bằng tiếng Anh.
- Mọi trang/bài (cả hai ngôn ngữ) 0 khối không hợp lệ; không lỗi console, không tràn ngang ở 390 px và 1366 px.
- Kiểm tra tĩnh: `php -l` sạch; PHPCS (WordPress-Extra + I18n) 0 lỗi.

Chưa kiểm thử: **WPML thật** (plugin trả phí: dùng API `wpml_*` đã viết nhưng chưa chạy), WPML Menu Sync, và trường hợp website cũ đã có trang `/en/` do WPML sinh. Nội dung tiếng Anh chưa được người bản ngữ hoặc chủ sở hữu rà soát.

Lỗi phát hiện và sửa khi thêm tiếng Anh: (1) thư viện bản dịch của plugin không tải do thiếu `Domain Path`; (2) liên kết logo/breadcrumb trỏ về trang chủ tiếng Việt → `p2g_home_url()`; (3) bộ nhập tiếng Việt render pattern theo ngôn ngữ của site → nay luôn dùng chuỗi gốc tiếng Việt (`p2g_force_source_strings()`); (4) 10 lỗi định dạng PHPCS trong hai pattern.

## 8. Lỗi/giới hạn còn tồn tại

- Ngày tháng hiển thị theo định dạng trong Cài đặt → Tổng quan; môi trường thử nghiệm chưa có gói tiếng Việt nên ảnh chụp hiển thị "October 11, 2026" và tiêu đề 404 "Page not found" (chuỗi của WordPress core). Trên website dùng tiếng Việt sẽ hiển thị tiếng Việt.
- Khi tắt plugin, câu "tự động xóa sau [số ngày]" trong Chính sách quyền riêng tư mất phần số ngày (lúc đó cũng không còn form).
- Giới hạn tần suất dựa trên `REMOTE_ADDR`; nếu website đứng sau CDN/proxy không chuyển IP thật, tất cả khách có thể dùng chung giới hạn → cấu hình IP thật hoặc dùng filter `p2g_rate_limit`.
