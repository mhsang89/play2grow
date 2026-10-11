# INITIAL AUDIT – play2grow.edu.vn

Ngày kiểm tra: 11/10/2026. Người thực hiện: Claude (Claude Code, môi trường cloud).

## 0. Phạm vi và giới hạn truy cập

| Nguồn | Kết quả | Ghi chú |
|---|---|---|
| Repository `mhsang89/play2grow` | Trống (chưa có commit) | Không có mã nguồn theme/child theme/plugin hiện tại để kiểm tra. |
| https://play2grow.edu.vn/ | **Truy cập được** (sau khi chủ tài khoản mở Network access = Full) | Ban đầu bị proxy chặn (`403 CONNECT`). Chỉ đọc HTML công khai, **không** có quyền WP Admin, database, FTP hay hosting. |
| https://ila.edu.vn/ (và `/tieng-anh-tre-em-english-kids`, `/hoc-thu-mien-phi`) | **Không xem được nội dung** | Cả curl lẫn Chromium headless đều nhận **HTTP 403 + trang thử thách Cloudflare Turnstile** (tiêu đề "Chờ một chút…"). Đây là cơ chế chống bot; chúng tôi không tìm cách vượt qua. Mọi quyết định thiết kế "theo phong cách ILA" là **suy luận**, xem DESIGN_SPEC.md. |

> Những gì dưới đây là **quan sát từ HTML công khai** của website, không phải từ mã nguồn.

## 1. Nền tảng kỹ thuật (quan sát được)

| Thành phần | Giá trị | Nguồn |
|---|---|---|
| WordPress | 7.1.3 | thẻ `meta generator` |
| Theme | **LittleDino** (theme trả phí, ThemeForest) + child theme **littledino-child** | đường dẫn `wp-content/themes/` |
| Page builder | **Elementor 3.32.3** + **Elementor Pro** | meta generator, `ElementorProFrontendConfig` |
| Slider | **Slider Revolution 6.7.37** | meta generator |
| Đa ngôn ngữ | **WPML 4.7.6** – Tiếng Việt (mặc định) + English (`/en/`) | meta generator, liên kết ngôn ngữ |
| Bộ nhớ đệm | **LiteSpeed Cache** | đường dẫn plugin |
| Form | **Contact Form 7** (trang `/dang-ki/`) | class `wpcf7` |
| Custom post types của theme | `team`, `footer`, `portfolio` (thấy trong sitemap) | `wp-sitemap.xml` |
| robots.txt | Chuẩn WordPress, không chặn website | `/robots.txt` |
| Sitemap | `wp-sitemap.xml` (sitemap lõi của WordPress, không thấy plugin SEO) | |
| Phiên bản PHP | Không xác định được từ bên ngoài | |

## 2. Danh sách trang và tình trạng nội dung

| URL | Menu | Tình trạng |
|---|---|---|
| `/` | Trang chủ | Phần lớn là **nội dung demo của LittleDino** (tiếng Anh): "Why Early Education Matters", "At Littledino Center…", bộ đếm số liệu (Total Groups, Qualified Teachers… đều hiển thị `0`), "What We Offer", **lời chứng thực giả** (Kate Brown, Tina Roberts, James Colins, Clair Denvers), "Healthy Food", số điện thoại demo **+1 888 561 795 1**, bài blog mẫu năm 2019 của tác giả `sangmai9`. Chỉ có hero và footer là nội dung Play2Grow. |
| `/gioi-thieu/` (cũng là đích của `/about-us/`) | Giới thiệu | **Toàn bộ là nội dung demo LittleDino** ("Welcome To Little Dino", lời chứng thực giả, "Our Classes: Science, Music, Painting, Yoga"). |
| `/steam-english/` | Chương trình → STEAM English | **Chỉ có tiêu đề**, không có nội dung. |
| `/lop-ve/` | Lớp Mỹ thuật → Lớp Vẽ | **Chỉ có tiêu đề**. |
| (Lớp thủ công, Lớp đàn Piano, Lớp đàn Keyboard, Tiktok Dance, Nhảy Hiện Đại) | Có trong menu | Mục menu không có liên kết riêng hoặc trang chưa tồn tại (`/lop-thu-cong/` trả về 404). |
| `/co-so-vat-chat/` | Cơ Sở Vật Chất | **Chỉ có tiêu đề**. |
| `/giao-vien/` | Giáo Viên | **Chỉ có tiêu đề**. |
| `/tuyen-dung/` | Tuyển dụng | **Chỉ có tiêu đề**. |
| `/dang-ki/` | Đăng kí | Form Contact Form 7 (tên, email, điện thoại, lời nhắn) nhưng khối thông tin hiển thị **địa chỉ demo "27 Division St, New York"**, **"+1 (888) 561 795 1"** và **"littledino@wgl.com"**. |
| `/en/` | English | Phiên bản tiếng Anh (WPML). |
| `/contacts/`, `/blog-grid/` | Nút "Enroll Online", "View More" trên trang chủ | **404** – liên kết hỏng. |
| `/portfolio-grid/` | Nút "View More" | Chuyển hướng tới `/portfolio-grid-under/` (trang demo). |

## 3. Thông tin Play2Grow tìm thấy trên website (chưa được xác minh pháp lý)

| Thông tin | Giá trị công bố trên website | Đánh giá |
|---|---|---|
| Tiêu đề website | "Play2Grow – Thực hành 100% tiếng Anh với người bản xứ" | **Tuyên bố chưa có bằng chứng** (giáo viên bản xứ, "100%"). |
| Thông điệp hero | "Môi trường thực hành 100% tiếng Anh với người bản xứ, giúp các bé phát triển kỹ năng ngôn ngữ một cách tự nhiên và hiệu quả nhất." | Chứa so sánh tuyệt đối ("hiệu quả nhất") – nên bỏ. |
| Chương trình (menu/biểu tượng) | STEAM English, Âm nhạc (Piano, Keyboard), Nhảy (Tiktok Dance, Nhảy hiện đại), Mỹ thuật (Vẽ, Thủ công), Mini Party, Kỹ năng tự vệ | Chưa có trang mô tả nào – xem PRODUCT_AUDIT.md. |
| Địa chỉ (footer) | 1442/29, đường 3/2, P2, Q11, TPHCM | Do website tự công bố; cần chủ sở hữu xác nhận còn đúng (lưu ý cải cách địa giới hành chính có thể đã đổi tên phường). |
| Điện thoại (footer) | 0982534460 | Cần xác nhận. |
| Email (footer) | play2grow9@gmail.com | Cần xác nhận (email Gmail, không theo tên miền). |
| Giờ làm việc | T2–T6 8:00–19:00; T7 9:00–16:00; CN nghỉ | Cần xác nhận. |
| Bản quyền | "Copyright © 2024 by Play2Grow" | |

## 4. Mâu thuẫn định vị cần chủ sở hữu quyết định

- **Website hiện tại** mô tả Play2Grow như một **trung tâm có lớp học trực tiếp** (STEAM English, vẽ, đàn, nhảy, cơ sở vật chất, giáo viên, tuyển dụng, địa chỉ tại Q11).
- **Brief hiện tại** mô tả Play2Grow là **thương hiệu/nền tảng EdTech** học tiếng Anh qua trải nghiệm, có thể ứng dụng AI.
- Không tìm thấy bằng chứng nào về sản phẩm số, ứng dụng hay tính năng AI đang hoạt động.

**Đã giải quyết (11/10/2026):** chủ sở hữu xác nhận Play2Grow là **trung tâm có lớp học** và các chương trình đang hoạt động thật. Website mới được chuyển sang định vị này: trang Chương trình học, 7 trang lớp giữ URL cũ, CTA "Đăng ký tư vấn". Chi tiết từng lớp vẫn cần bổ sung.

## 5. Vấn đề và rủi ro, xếp theo mức độ ưu tiên

| # | Mức độ | Vấn đề | Đề xuất |
|---|---|---|---|
| 1 | **Nghiêm trọng** | Dữ liệu demo giả trên website thật: lời chứng thực giả, địa chỉ New York, số điện thoại Mỹ, email `littledino@wgl.com`, bộ đếm số liệu, chữ "Littledino Center". Ảnh hưởng uy tín và có thể gây hiểu lầm cho khách hàng. | Gỡ ngay, kể cả khi chưa đổi theme (Elementor → sửa trang Trang chủ, Giới thiệu, Đăng kí; Theme Options của LittleDino → thông tin liên hệ). |
| 2 | **Nghiêm trọng** | Tuyên bố chưa có bằng chứng: "100% tiếng Anh với người bản xứ", "hiệu quả nhất". | Chỉ giữ khi có bằng chứng (hợp đồng giáo viên, chương trình thực tế); bỏ so sánh tuyệt đối. |
| 3 | Cao | 6 trang chỉ có tiêu đề (nội dung mỏng) và các mục menu không có trang. | Viết nội dung thật hoặc tạm ẩn khỏi menu và đặt `noindex`. |
| 4 | Cao | Liên kết hỏng: `/contacts/`, `/blog-grid/` (404). | Sửa nút hoặc tạo chuyển hướng 301 tới `/dang-ki/` (hoặc `/lien-he/`) và `/goc-hoc-tap/`. |
| 5 | Cao | Thiếu chính sách quyền riêng tư dù có form thu thập dữ liệu cá nhân; không có thông tin pháp nhân. | Dùng các trang chính sách trong nội dung khởi đầu (bản nháp, cần rà soát pháp lý). |
| 6 | Trung bình | Phụ thuộc nhiều plugin nặng (Revolution Slider, Elementor Pro, theme trả phí cần cập nhật bản quyền). | Theme mới không cần các plugin này; có thể tắt dần sau khi chuyển đổi và kiểm thử trên staging. |
| 7 | Trung bình | Bài blog demo năm 2019 ("How to Teach a Child to Clean Himself"), CPT portfolio/team demo vẫn được lập chỉ mục trong sitemap. | Xóa hoặc chuyển về bản nháp sau khi sao lưu. |
| 8 | Thấp | Không có plugin SEO; không có meta description riêng. | Theme mới tự xuất meta description, Open Graph, JSON-LD cơ bản; có thể cài Yoast/Rank Math sau (theme tự tắt phần trùng). |

## 6. Phần có thể chỉnh sửa an toàn

Vì không có quyền truy cập WP Admin/mã nguồn, không có thay đổi nào được thực hiện trên website production. Phương án an toàn là **cài theme mới + plugin Play2Grow Core trên staging**, giữ nguyên LittleDino để có thể quay lại (xem INSTALLATION_CHECKLIST.md). Theme mới:

- không xóa hay ghi đè nội dung, trang, menu hiện có;
- hiển thị trang Elementor ở chế độ toàn chiều rộng (nhưng **các widget riêng của LittleDino có thể không còn hiển thị** khi đổi theme – cần kiểm tra trên staging);
- tự dùng trang `/dang-ki/` hiện có cho nút đăng ký nếu chưa tạo `/lien-he/`;
- khi nhập nội dung khởi đầu, trang nào trùng đường dẫn (ví dụ `/gioi-thieu/`) sẽ được tạo thành **bản nháp "[Bản mới] …"** riêng để so sánh, không động vào trang cũ.
