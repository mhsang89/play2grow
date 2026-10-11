# DESIGN SPEC – Design audit tham chiếu và hệ thống thiết kế Play2Grow

## 1. Kiểm tra website tham chiếu ILA (Phase 1)

| Bước | Kết quả |
|---|---|
| curl `https://ila.edu.vn/` | Ban đầu proxy của môi trường chặn (`403 CONNECT`). Sau khi mở Network access = Full: **HTTP 403** từ máy chủ ILA. |
| Chromium headless (Playwright), desktop 1440×900 và mobile 390×844, 3 URL: `/`, `/tieng-anh-tre-em-english-kids`, `/hoc-thu-mien-phi` | Cả 6 lần đều trả về **HTTP 403** với trang **Cloudflare managed challenge / Turnstile** (tiêu đề "Chờ một chút…", có `challenge-platform`, `cf-chl`, `Ray ID`). |
| Hành động | Dừng lại, **không** tìm cách vượt qua cơ chế chống bot. |

**Kết luận:** chúng tôi **chưa quan sát được** giao diện, màu sắc, font hay bố cục thật của ILA. Các mục "Tham chiếu ILA" bên dưới là **giả định thiết kế** dựa trên các mẫu thường gặp ở website trung tâm giáo dục lớn tại Việt Nam và theo yêu cầu "phong cách tương tự ILA" của chủ sở hữu. Không sử dụng bất kỳ logo, hình ảnh, nội dung hay tài sản thương hiệu nào của ILA.

## 2. Design audit (Phase 2): mẫu cấu trúc áp dụng

Mỗi mục ghi rõ là **[Suy luận]** (không kiểm chứng được trên ILA) hay **[Áp dụng]** (đã triển khai trong theme Play2Grow).

| Hạng mục | Mẫu cấu trúc website giáo dục [Suy luận] | Cách áp dụng cho Play2Grow [Áp dụng] |
|---|---|---|
| Kiến trúc thông tin | Menu chính 5–7 mục, dropdown theo nhóm (chương trình/độ tuổi, về chúng tôi, tin tức); các liên kết phụ đưa lên thanh trên cùng | Menu: **Khám phá ▾ · Hoạt động tiếng Anh ▾ · Sản phẩm · Phụ huynh ▾ · Liên hệ**; thanh trên cùng: Góc học tập · Hỏi đáp · Liên hệ |
| Header | Thanh tiện ích màu thương hiệu ở trên; header trắng cố định; nút CTA nổi bật bên phải | Topbar xanh đậm (khẩu hiệu tiếng Anh, liên kết nhanh, hotline/email khi đã nhập); header trắng dính trên cùng, bóng nhẹ; nút "Đăng ký nhận thông tin" |
| Footer | Footer tối nhiều cột: thương hiệu, liên kết, thông tin liên hệ/pháp lý, mạng xã hội; dòng bản quyền + chính sách | 4 cột: thương hiệu + mô tả + mạng xã hội · Khám phá · Hỗ trợ · Thông tin liên hệ (chỉ các trường đã xác minh); dòng cuối: bản quyền + menu chính sách |
| Thứ tự trang chủ | Hero lớn → truy cập nhanh → chương trình → lợi ích → bằng chứng xã hội → tin tức → form | Hero → **thẻ truy cập nhanh** nổi chồng lên hero → ví dụ hoạt động → khám phá theo chủ đề → cách bắt đầu → triết lý → giá trị cho trẻ/phụ huynh → sản phẩm (có trạng thái) → Góc học tập → hỏi đáp → CTA. **Không có** phần số liệu hay lời chứng thực (chưa có dữ liệu thật). |
| Trang con | Banner tiêu đề màu thương hiệu + breadcrumbs | Banner xanh gradient + "mặt trời" vàng, breadcrumbs, tiêu đề H1, đoạn dẫn |
| Chuyển đổi | Nút đăng ký lặp lại ở header, cuối section, cuối trang; nút liên hệ nổi; thanh CTA dính trên mobile | Header CTA · CTA cuối trang (template part) · thanh CTA dính đáy trên mobile · nút Gọi/Zalo nổi (**chỉ khi** đã nhập hotline/Zalo) · form ngắn, chỉ bắt buộc tên + SĐT **hoặc** email |
| Mobile/tablet | Menu hamburger dạng panel, CTA cố định | Breakpoint menu 1200px; panel menu có nút mở menu con; thanh CTA dính < 768px; lưới thẻ 1 → 2 → 3/4 cột |

## 3. Hệ thống thiết kế Play2Grow (Phase 3) – nguyên bản

**Định vị:** *Explore the world. Play with English. Grow every day.* – "Học qua khám phá. Trưởng thành qua trải nghiệm."

### Màu (CSS custom properties trong `assets/css/main.css`; có thể đổi màu chủ đạo/nhấn trong Tùy biến → Màu sắc)

| Token | Giá trị | Vai trò | Tương phản (đo) |
|---|---|---|---|
| `--p2g-primary` | `#0E6B5C` xanh lá – "grow" | Nút, liên kết, banner | 6.41:1 với trắng |
| `--p2g-primary-dark` | `#0A4F44` | Topbar, hover | 9.48:1 với trắng |
| `--p2g-accent` | `#FFB938` vàng nắng – "play" | Điểm nhấn, CTA trên nền tối | Chữ mực trên vàng: 8.91:1 |
| `--p2g-coral` | `#F0694B` | Trang trí (không dùng cho chữ nhỏ) | — |
| `--p2g-mint` / `--p2g-sky` / `--p2g-sun` / `--p2g-blush` / `--p2g-cream` | Nền nhạt thiên nhiên | Nền section, thẻ | — |
| `--p2g-ink` | `#17282B` | Chữ chính | 14.47:1 trên kem |
| `--p2g-muted` | `#55666A` | Chữ phụ | 5.69:1 trên kem |

### Typography
- **Be Vietnam Pro** (OFL 1.1), tự lưu trữ, subset Latin + tiếng Việt, 5 file WOFF2 khoảng 26–28 KB/file, `font-display: swap`, preload 2 độ đậm.
- Cỡ chữ thân bài 17px, line-height 1.7; tiêu đề dùng `clamp()` (H1 32→52px, H2 26→38px).

### Lưới, khoảng cách, bo góc
- Container 1200px + gutter `clamp(16px, 4vw, 32px)`; cột nội dung đọc 760px.
- Khoảng cách section `clamp(56px, 8vw, 104px)`.
- Bo góc 10 / 18 / 28px; bóng đổ 2 cấp, rất nhẹ.

### Thành phần
Nút (primary, outline, accent, secondary, light), chip lọc chủ đề, badge, nhãn trạng thái sản phẩm, thẻ bài viết, thẻ sản phẩm, thẻ chủ đề, thẻ truy cập nhanh, ô hoạt động mẫu (phrase chips + các bước), callout, danh sách check, các bước đánh số, hỏi đáp dạng `<details>`, form, ghi chú nội bộ (chỉ biên tập viên thấy).

### Hình ảnh
- Minh họa SVG nguyên bản: hero (kính lúp, lá, mặt trời, bong bóng "Hello!", khối ABC) và 6 icon minh họa. **Không có hình người/trẻ em giả lập** và không dùng ảnh stock.
- Khi có ảnh thật của Play2Grow (đã có quyền sử dụng và sự đồng ý của phụ huynh nếu có trẻ em): thay ảnh minh họa hero bằng khối Ảnh trong trình soạn thảo, đặt ảnh đại diện cho bài viết.

### Chuyển động và khả năng truy cập
- Chỉ có hiệu ứng hover nhẹ (nâng thẻ 2–3px); tôn trọng `prefers-reduced-motion`.
- Focus ring vàng + viền tối; skip link; menu dùng `aria-expanded`; form có label, thông báo lỗi `aria-describedby`, vùng trạng thái `aria-live`.

## 4. Các mục trong brief chưa triển khai và lý do

| Mục trong brief | Quyết định |
|---|---|
| "Featured interactive English activities" | Thay bằng **ví dụ hoạt động cho phụ huynh** và bài viết – vì chưa có hoạt động tương tác nào trong sản phẩm được xác minh. |
| "Learning Paths", "Learning journeys and progress" | **Không triển khai** – chưa có sản phẩm/lộ trình được xác minh. Mục "Sản phẩm" có nhãn trạng thái để bổ sung sau. |
| "Personalized learning and AI-powered feedback" | **Không triển khai** – brief yêu cầu chỉ thêm khi tính năng có thật; hiện chưa có (PRODUCT_AUDIT.md). |
| "Authentic real-world imagery" | Chưa có ảnh thật được cấp quyền; dùng minh họa nguyên bản và chừa vị trí để thay. |
