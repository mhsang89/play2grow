# IMPLEMENTATION PLAN – Kế hoạch và những gì đã triển khai

## 1. Quyết định kiến trúc

| Quyết định | Lý do |
|---|---|
| **Theme cổ điển (classic theme) độc lập** `play2grow-theme`, không phụ thuộc Elementor hay LittleDino | Website hiện tại dựa trên theme trả phí + Elementor Pro + Revolution Slider, chủ yếu là nội dung demo. Không có mã nguồn hay quyền admin để sửa trực tiếp. Theme độc lập dễ bảo trì, nhẹ, và có thể quay lại theme cũ bất kỳ lúc nào. |
| Nội dung quản lý trong WordPress (Trang, Bài viết, Menu, Tùy biến, block pattern) | Không hardcode nội dung trang trong PHP. Trang chủ là một Trang thường dựng từ các block pattern, sửa được trong trình soạn thảo. |
| **Plugin đi kèm `play2grow-core`** cho dữ liệu/nghiệp vụ | Đăng ký liên hệ là dữ liệu cá nhân. Nếu nằm trong theme, khi đổi theme dữ liệu bị ẩn và việc tự xóa theo chính sách ngừng chạy. Shortcode trong nội dung cũng không còn bị lộ dạng `[p2g_…]` khi đổi theme. Đạt yêu cầu bắt buộc của Theme Check. |
| Theme vẫn chạy khi chưa có plugin | Form, thông tin doanh nghiệp, mạng xã hội tự ẩn; shortcode tự bị loại bỏ khỏi nội dung; admin thấy thông báo. |
| Nội dung khởi đầu nhập theo yêu cầu, **mặc định là bản nháp**, không ghi đè | Tuân thủ nguyên tắc bảo toàn production. |
| Không tải tài nguyên bên thứ ba, không tracking | Font tự lưu trữ; không Google Fonts, analytics hay pixel. |
| SEO cơ bản tự tắt khi có plugin SEO | Tránh trùng meta/schema với Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework, Slim SEO. |

## 2. Các giai đoạn

| Giai đoạn | Trạng thái | Ghi chú |
|---|---|---|
| 1. Audit | Xong | INITIAL_AUDIT.md, PRODUCT_AUDIT.md. Đã audit HTML công khai play2grow.edu.vn; ILA bị Cloudflare chặn. |
| 2. Strategy / IA | Xong | CONTENT_AND_SITEMAP.md, DESIGN_SPEC.md |
| 3. Content | Xong (bản nháp chờ xác nhận) | 10 trang chính + 7 trang lớp học, 6 bài viết chuyên sâu, 9 chuyên mục (3 chuyên mục chưa có bài nên tự ẩn trên website). Các chỗ cần xác nhận được đánh dấu bằng ghi chú nội bộ. |
| 4. UI/UX | Xong | Hệ thống thiết kế, header/topbar/dropdown, banner trang con, thẻ truy cập nhanh, thẻ chủ đề, CTA dính trên mobile |
| 5. Theme + plugin | Xong | Xem cấu trúc bên dưới |
| 6. QA | Xong trong phạm vi môi trường | QA_REPORT.md |
| 7. Packaging | Xong | `dist/play2grow-theme.zip`, `dist/play2grow-core.zip`, `dist/SHA256SUMS.txt` |
| Triển khai lên production | **Chưa làm (không có quyền)** | Làm theo INSTALLATION_CHECKLIST.md, ưu tiên staging |

## 3. Cấu trúc mã nguồn

```
play2grow-theme/
  style.css, functions.php, readme.txt, README.md, screenshot.png
  header.php, footer.php, index.php, front-page.php, page.php, single.php,
  archive.php, search.php, searchform.php, 404.php, comments.php
  page-templates/   template-full-width.php · template-programs.php (Danh sách chương trình)
                    template-program.php (Chi tiết chương trình) · template-contact.php (Liên hệ)
  template-parts/   card-post · card-program · category-filter · post-list · content-none
                    cta-band · related-posts · floating-contact
  patterns/         13 block pattern (hero, truy cập nhanh, triết lý, chủ đề, ví dụ hoạt động,
                    sản phẩm, giá trị, các bước, bài viết, hỏi đáp, CTA, bố cục trang sản phẩm…)
  inc/              helpers · setup · enqueue · customizer · template-tags · navigation
                    meta-boxes (SEO + thông tin sản phẩm) · seo · plugin-bridge · programs
                    patterns · admin (danh sách kiểm tra) · starter-content (trình nhập) · compat
  starter-content/  manifest.php + pages/*.html + posts/*.html (block markup, sinh từ content-src/)
  assets/css/main.css, editor.css · assets/js/main.js · assets/fonts/*.woff2 + OFL.txt
  assets/images/*.svg (minh họa nguyên bản)

play2grow-core/
  play2grow-core.php, readme.txt
  includes/ functions · customizer (thông tin doanh nghiệp, MXH) · leads (CPT đăng ký, xóa tự động,
            xuất/xóa dữ liệu) · contact-form · shortcodes · admin (Cài đặt → Play2Grow)
  assets/js/form.js

content-src/   Nội dung gốc dạng Markdown (nguồn để biên tập)
tools/         md2blocks.py (Markdown → block markup) · build.sh (đóng gói ZIP)
dist/          ZIP cài đặt
```

## 4. Việc còn lại (cần chủ sở hữu)

1. Trả lời các câu hỏi trong COMPANY_INFO_REQUIRED.md (mô hình kinh doanh, chương trình thật, thông tin pháp lý, logo).
2. Rà soát pháp lý Chính sách quyền riêng tư và Điều khoản.
3. Cài đặt trên staging, chạy INSTALLATION_CHECKLIST.md, rồi mới chuyển production.
4. Bổ sung chi tiết 7 lớp học (độ tuổi, lịch, học phí, giáo viên) và ảnh lớp học thật; với Giáo viên, Cơ sở vật chất, Tuyển dụng: viết nội dung thật hoặc đặt `noindex`/ẩn khỏi menu; tạo chuyển hướng 301 cho `/contacts/`, `/blog-grid/`.
5. Phiên bản tiếng Anh (WPML): nội dung khởi đầu chỉ có tiếng Việt; cần dịch bằng WPML nếu muốn giữ `/en/`.
