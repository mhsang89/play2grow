# INSTALLATION CHECKLIST – Cài đặt, kiểm tra, sao lưu và khôi phục

> Theme ZIP **không phải** bản sao lưu website. ZIP không chứa database, trang, bài viết, tài khoản, plugin khác hay cấu hình hosting.

## A. Trước khi cài (bắt buộc)

- [ ] **Sao lưu toàn bộ**: database + thư mục `wp-content` (dùng công cụ backup của hosting, hoặc plugin như UpdraftPlus/All-in-One WP Migration). Tải bản sao lưu về máy.
- [ ] Thử khôi phục bản sao lưu trên staging (hoặc ít nhất kiểm tra file tải về mở được).
- [ ] **Ưu tiên staging**: tạo bản sao website (nhiều hosting có tính năng "Staging"). Làm toàn bộ checklist trên staging trước.
- [ ] Ghi lại theme đang dùng: **LittleDino Child** (theme cha **LittleDino**). **Không xóa** hai theme này.
- [ ] Ghi lại cấu hình hiện tại: Giao diện → Menu (chụp màn hình vị trí menu), Cài đặt → Đọc (trang chủ đang dùng), Widget.
- [ ] Chụp màn hình các trang quan trọng để so sánh.

## B. Cài đặt

- [ ] Giao diện → Giao diện → **Thêm giao diện mới → Tải giao diện lên** → `play2grow-theme.zip` → Cài đặt. **Chưa kích hoạt** nếu muốn xem trước bằng "Xem trước trực tiếp".
- [ ] Plugin → Cài mới → **Tải plugin lên** → `play2grow-core.zip` → Cài đặt → **Kích hoạt**.
- [ ] Kích hoạt theme **Play2Grow**.
- [ ] Mở **Giao diện → Play2Grow**: xem danh sách kiểm tra (✓/⚠).

## C. Nội dung

- [ ] (Tùy chọn) **Bước 1: Tạo nội dung khởi đầu** – để mặc định "bản nháp". Trang `/gioi-thieu/` đã tồn tại sẽ được tạo thành "[Bản mới] Giới thiệu Play2Grow" (trang cũ giữ nguyên).
- [ ] Đọc từng bản nháp; xử lý mọi **ghi chú nội bộ** (khung viền cam); xác nhận sứ mệnh, tầm nhìn, nguyên tắc.
- [ ] Với "[Bản mới] Giới thiệu": sao chép nội dung vào trang `/gioi-thieu/` gốc (giữ URL), rồi xóa bản nháp "[Bản mới]".
- [ ] Xuất bản các trang/bài đã duyệt. Chính sách quyền riêng tư và Điều khoản **chỉ xuất bản sau khi rà soát pháp lý**.
- [ ] **Bước 2: Tạo menu & cài đặt trang chủ** (chỉ dùng trang đã xuất bản; không đè menu đang có ở vị trí đó).
- [ ] Gỡ khỏi menu các chuyên mục chưa có bài (Con vật quanh em, Đồ ăn & bữa ăn, Địa điểm quanh ta) cho đến khi có bài.
- [ ] Gỡ nội dung demo LittleDino (lời chứng thực giả, địa chỉ New York, số +1 888…, email littledino@wgl.com, bài blog mẫu 2019, CPT portfolio/team demo) – sau khi đã sao lưu.

## D. Thông tin doanh nghiệp và form

- [ ] Tùy biến → **Play2Grow – Thông tin doanh nghiệp**: chỉ nhập thông tin đã xác minh (COMPANY_INFO_REQUIRED.md). Đã có (chủ sở hữu cung cấp 11/10/2026): người liên hệ Bùi Thủy, email buithuy@play2grow.edu.vn, địa chỉ Tân Phú – Bước 2 tự điền nếu trường còn trống. Hotline: nhập khi đã xác nhận số đang dùng.
- [ ] Logo: theme đã có logo, biểu tượng trang và ảnh chia sẻ mặc định (thư mục `brand/`). Chỉ cần tải lên tại Tùy biến → Nhận diện website nếu muốn thay bằng logo chính thức khác; có thể tải thêm **Biểu tượng trang** (`brand/site-icon-512.png`).
- [ ] Tùy biến → Play2Grow – Mạng xã hội: đường dẫn tài khoản chính thức (Zalo sẽ hiện nút nổi).
- [ ] **Cài đặt → Play2Grow**: email nhận thông báo, thời gian lưu trữ (mặc định 180 ngày, khớp với chính sách).
- [ ] Cài/kiểm tra plugin SMTP; **gửi thử 1 đăng ký** từ trang Liên hệ trên điện thoại và máy tính → kiểm tra email đến (cả thư rác) và mục **Đăng ký liên hệ**.
- [ ] Quyết định giữ form Contact Form 7 ở `/dang-ki/` hay chuyển sang form mới ở `/lien-he/`. Nếu chuyển: tạo chuyển hướng 301 `/dang-ki/` → `/lien-he/`. Nếu giữ: chọn `/dang-ki/` làm trang đích tại Tùy biến → Play2Grow → Đầu trang & nút đăng ký.

## E. Kỹ thuật

- [ ] Cài đặt → Đường dẫn tĩnh: giữ cấu trúc hiện tại (website đang dùng `/ten-trang/`) → bấm **Lưu** một lần để làm mới quy tắc.
- [ ] Cài đặt → Tổng quan → **Định dạng ngày**: `d/m/Y`.
- [ ] **LiteSpeed Cache**: xóa toàn bộ cache sau khi đổi theme; nên loại trừ trang Liên hệ khỏi cache (không bắt buộc vì form tự làm mới mã).
- [ ] Nếu dùng CDN/Cloudflare: đảm bảo máy chủ nhận IP thật của khách (giới hạn tần suất của form dựa trên IP).
- [ ] **Bản tiếng Anh (WPML)**: (1) WPML → Languages: Tiếng Việt là ngôn ngữ mặc định, thêm English. (2) Giao diện → Play2Grow → **Bước 1b: Tạo bản tiếng Anh** (tạo bản nháp, liên kết làm bản dịch của trang/bài/chuyên mục tiếng Việt; trang đã có bản dịch được giữ nguyên). (3) Rà soát và xuất bản các trang tiếng Anh. (4) Bước 2 tạo menu tiếng Anh; nếu menu chưa hiện ở `/en/`, vào WPML → WP Menus Sync. (5) Kiểm tra bộ chuyển VI/EN, form `/en/contact/`, Chính sách `/en/privacy/`. Nếu dùng Polylang thay cho WPML, các bước tương tự.
- [ ] Trang tiếng Anh đã có sẵn trên website cũ (`/en/`): Bước 1b **không ghi đè**; trang trùng đường dẫn được tạo thành bản nháp mới để so sánh.
- [ ] **Elementor**: mở từng trang cũ dựng bằng Elementor; nếu dùng widget riêng của LittleDino và bị vỡ, dựng lại bằng khối Gutenberg/pattern Play2Grow hoặc bằng widget Elementor chuẩn.
- [ ] Sau khi chạy ổn định ít nhất 1–2 tuần: cân nhắc tắt Revolution Slider (không còn dùng), giữ LittleDino để có thể quay lại.
- [ ] Cài đặt → Đọc: **bỏ chọn** "Ngăn chặn các công cụ tìm kiếm…" (nếu đang bật trên production).
- [ ] Nếu cài Yoast/Rank Math: xác nhận "SEO cơ bản (Play2Grow)" đã tự ẩn; bật breadcrumbs của plugin nếu muốn.
- [ ] Gửi lại sitemap (`/wp-sitemap.xml` hoặc của plugin SEO) trong Google Search Console.

## F. Kiểm tra sau cài (desktop + điện thoại thật)

- [ ] Trang chủ, Giới thiệu, Phương pháp, Sản phẩm, Phụ huynh, Góc học tập, 1 bài viết, Hỏi đáp, Liên hệ, Chính sách, 404.
- [ ] Menu mobile: mở, mở menu con, đóng; nút "Đăng ký nhận thông tin" ở đáy màn hình điện thoại.
- [ ] Mọi nút CTA đến đúng trang; không có liên kết 404 (dùng plugin kiểm tra liên kết hoặc Search Console).
- [ ] Footer: chỉ hiện thông tin đã nhập; không còn dữ liệu demo.
- [ ] Tiếng Việt hiển thị đúng dấu trên iOS/Android.
- [ ] Chạy PageSpeed Insights cho trang chủ và 1 bài viết; ghi lại kết quả.

## G. Khôi phục nếu có sự cố

1. **Lỗi nhẹ (giao diện)**: Giao diện → Giao diện → kích hoạt lại **LittleDino Child**. Nội dung cũ không bị xóa; menu cũ vẫn còn (gán lại vị trí menu nếu cần). Xóa cache LiteSpeed.
2. **Không vào được trang quản trị**: qua FTP/File Manager đổi tên thư mục `wp-content/themes/play2grow-theme` → WordPress tự chuyển về theme mặc định; sau đó kích hoạt lại LittleDino Child.
3. **Lỗi do plugin**: đổi tên `wp-content/plugins/play2grow-core` để tắt plugin. Dữ liệu đăng ký vẫn còn trong database.
4. **Nghiêm trọng**: khôi phục bản sao lưu ở mục A.
5. Dọn dẹp tùy chọn sau khi quay lại: xóa các trang/bài khởi đầu (ID được lưu trong option `p2g_starter_ids`), menu "Menu chính/Khám phá/Hỗ trợ/Chính sách/Liên kết nhanh" mới tạo, dữ liệu đăng ký (sau khi xuất nếu cần).
