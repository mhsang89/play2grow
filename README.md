# Play2Grow – WordPress theme + plugin

Bộ bàn giao giao diện mới cho **play2grow.edu.vn**: học tiếng Anh qua trải nghiệm dành cho trẻ. *Explore the world. Play with English. Grow every day.*

## File cài đặt

| File | Cài ở đâu | Bắt buộc? |
|---|---|---|
| [`dist/play2grow-theme.zip`](dist/play2grow-theme.zip) | Giao diện → Giao diện → Thêm mới → **Tải giao diện lên** | Có |
| [`dist/play2grow-core.zip`](dist/play2grow-core.zip) | Plugin → Cài mới → **Tải plugin lên** | Khuyến nghị (form liên hệ, thông tin doanh nghiệp, shortcode nội dung) |
| [`dist/SHA256SUMS.txt`](dist/SHA256SUMS.txt) | — | Mã kiểm tra tính toàn vẹn |

Đã kiểm thử: WordPress **7.1.3**, PHP **8.3**. Tối thiểu khai báo: WordPress 6.4, PHP 7.4 (chưa kiểm thử ở mức tối thiểu).

**Trước khi cài:** sao lưu website và database, ưu tiên làm trên staging. Theme ZIP không phải bản sao lưu. Làm theo [INSTALLATION_CHECKLIST.md](INSTALLATION_CHECKLIST.md).

## Logo và thương hiệu

Thư mục [`brand/`](brand/) có logo ngang, logo nền tối, biểu tượng vuông, favicon, apple-touch-icon và ảnh chia sẻ 1200×630 (SVG + PNG). Tạo lại bằng `python3 tools/make_logo.py` (cần fontTools và font Be Vietnam Pro).

## Vì sao có cả theme và plugin

Website hiện tại dùng LittleDino + Elementor Pro + Revolution Slider + WPML + LiteSpeed Cache, và còn nhiều nội dung demo (xem [INITIAL_AUDIT.md](INITIAL_AUDIT.md)). Bộ bàn giao gồm:

- **Theme `play2grow-theme`**: toàn bộ phần trình bày (header, footer, mẫu trang, block pattern, CSS/JS, SEO cơ bản, trình nhập nội dung khởi đầu).
- **Plugin `play2grow-core`**: phần dữ liệu và nghiệp vụ (form, dữ liệu đăng ký có thời hạn lưu trữ, thông tin doanh nghiệp, shortcode). Tách riêng để dữ liệu cá nhân và nội dung không phụ thuộc vào giao diện. Đây cũng là yêu cầu bắt buộc của bộ kiểm tra Theme Check.

Theme vẫn chạy khi chưa có plugin; khi đó form và thông tin doanh nghiệp sẽ được ẩn.

## Tài liệu

| Tài liệu | Nội dung |
|---|---|
| [INITIAL_AUDIT.md](INITIAL_AUDIT.md) | Audit website hiện tại, nền tảng, vấn đề và rủi ro |
| [PRODUCT_AUDIT.md](PRODUCT_AUDIT.md) | Trạng thái xác minh từng tính năng/chương trình |
| [DESIGN_SPEC.md](DESIGN_SPEC.md) | Kết quả truy cập ILA (bị Cloudflare chặn), mẫu cấu trúc áp dụng, hệ thống thiết kế |
| [IMPLEMENTATION_PLAN.md](IMPLEMENTATION_PLAN.md) | Kiến trúc, giai đoạn, cấu trúc mã nguồn, việc còn lại |
| [CONTENT_AND_SITEMAP.md](CONTENT_AND_SITEMAP.md) | Sitemap, menu, danh sách trang/bài, URL giữ lại, chuyển hướng đề xuất |
| [COMPANY_INFO_REQUIRED.md](COMPANY_INFO_REQUIRED.md) | Thông tin doanh nghiệp/pháp lý/sản phẩm cần xác nhận |
| [QA_REPORT.md](QA_REPORT.md) | Đã kiểm thử gì, kết quả, giới hạn |
| [INSTALLATION_CHECKLIST.md](INSTALLATION_CHECKLIST.md) | Sao lưu, cài đặt, kiểm tra sau cài, khôi phục |
| [CHANGELOG.md](CHANGELOG.md) | Lịch sử thay đổi |
| [NETWORK_ACCESS_GUIDE.md](NETWORK_ACCESS_GUIDE.md) | Cách mở quyền mạng cho môi trường Claude Cloud |
| [play2grow-theme/README.md](play2grow-theme/README.md) | Hướng dẫn cấu hình và quản lý nội dung của theme |
| [docs/preview/](docs/preview/) | Ảnh chụp giao diện từ WordPress thử nghiệm |

## Phát triển

```bash
# Sửa nội dung khởi đầu trong content-src/*.md, sau đó:
tools/build.sh      # sinh lại block markup + dist/*.zip + SHA256SUMS.txt
```

## Giới hạn quan trọng

- Không có quyền truy cập WP Admin, database hay hosting của website thật: **chưa có thay đổi nào trên production**.
- Chưa kiểm thử thực tế với Elementor Pro, WPML, LiteSpeed Cache, Revolution Slider (plugin trả phí) và gửi email thật.
- Đã xác nhận: Play2Grow là trung tâm có lớp học (7 chương trình đang hoạt động); người sáng lập/liên hệ và đơn vị vận hành BÙI THỦY, email buithuy@play2grow.edu.vn, hotline +84982534460, địa chỉ phường Tân Sơn Nhì (TP.HCM). Chi tiết từng lớp, hotline, thông tin pháp lý và giấy phép giáo dục vẫn cần bổ sung – xem COMPANY_INFO_REQUIRED.md.
- Bản tiếng Anh (WPML/Polylang) đã được thử với **Polylang** trên WordPress thử nghiệm; **chưa thử với WPML** (plugin trả phí). Nội dung tiếng Anh là bản dịch/viết mới từ nội dung tiếng Việt, cần chủ sở hữu rà soát.
- Logo do chúng tôi thiết kế (`brand/`), chưa kiểm tra trùng lặp nhãn hiệu.
