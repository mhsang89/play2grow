# PRODUCT AUDIT – Trạng thái tính năng và chương trình Play2Grow

Ngày: 11/10/2026. Nguồn kiểm tra: HTML công khai của play2grow.edu.vn, brief của chủ sở hữu. Không có quyền truy cập ứng dụng, mã nguồn sản phẩm hay tài liệu nội bộ.

Quy ước trạng thái: `LIVE_VERIFIED` · `PARTIALLY_IMPLEMENTED` · `BETA_OR_PROTOTYPE` · `PLANNED` · `UNABLE_TO_VERIFY`.

## 1. Sản phẩm số / EdTech (theo brief)

| Tính năng | Trạng thái | Căn cứ | Website mới thể hiện thế nào |
|---|---|---|---|
| Nền tảng / ứng dụng học tiếng Anh của Play2Grow | UNABLE_TO_VERIFY | Không tìm thấy ứng dụng, đường dẫn tải hay trang đăng nhập nào | Trang "Sản phẩm" hiển thị trạng thái trống trung thực; chưa có trang sản phẩm nào |
| Hoạt động / trò chơi tiếng Anh tương tác trong sản phẩm | UNABLE_TO_VERIFY | Không có | Chỉ có **gợi ý hoạt động cho phụ huynh** (bài viết), được ghi rõ "không phải tính năng sản phẩm" |
| Lộ trình học (learning paths), theo dõi tiến độ | UNABLE_TO_VERIFY | Không có | Không giới thiệu |
| AI: gợi ý hoạt động, mẫu câu, thử thách, luyện giao tiếp, phản hồi cá nhân hóa | UNABLE_TO_VERIFY | Không có | Câu hỏi thường gặp ghi rõ: "Hiện website chưa giới thiệu tính năng AI nào" |
| Tài khoản phụ huynh, báo cáo tiến độ | UNABLE_TO_VERIFY | Không có | Không giới thiệu |
| Chấm điểm phát âm, hội thoại AI, AI tutor | UNABLE_TO_VERIFY | Không có | Không giới thiệu |
| Miễn phí / trả phí | UNABLE_TO_VERIFY | Không có | Câu hỏi thường gặp: chi phí sẽ được công bố cùng từng sản phẩm |

## 2. Chương trình tại trung tâm (theo website hiện tại)

| Chương trình | Trạng thái | Căn cứ |
|---|---|---|
| STEAM English | UNABLE_TO_VERIFY | Có mục menu và trang `/steam-english/` nhưng trang **không có nội dung** |
| Lớp Vẽ / Lớp thủ công (Mỹ thuật) | UNABLE_TO_VERIFY | `/lop-ve/` chỉ có tiêu đề; `/lop-thu-cong/` 404 |
| Lớp đàn Piano / Keyboard | UNABLE_TO_VERIFY | Chỉ có mục menu |
| Tiktok Dance / Nhảy hiện đại | UNABLE_TO_VERIFY | Chỉ có mục menu |
| Mini Party, Kỹ năng tự vệ | UNABLE_TO_VERIFY | Chỉ có biểu tượng trên trang chủ |
| "Thực hành 100% tiếng Anh với người bản xứ" | UNABLE_TO_VERIFY | Chỉ là khẩu hiệu; không có thông tin giáo viên (`/giao-vien/` trống) |
| Cơ sở vật chất | UNABLE_TO_VERIFY | `/co-so-vat-chat/` trống |

## 3. Chức năng website do bản bàn giao này cung cấp

| Chức năng | Trạng thái | Căn cứ kiểm thử |
|---|---|---|
| Form liên hệ / đăng ký nhận thông tin (plugin Play2Grow Core) | LIVE_VERIFIED trên WordPress 7.1.3 thử nghiệm | Gửi thành công có JS và không JS; lưu vào "Đăng ký liên hệ"; email được tạo đúng (đã chặn gửi thật trong môi trường thử nghiệm); xem QA_REPORT.md |
| Chống spam (honeypot, thời gian điền, nonce, giới hạn tần suất) | LIVE_VERIFIED (thử nghiệm) | QA_REPORT.md |
| Tự xóa đăng ký sau N ngày; xuất/xóa dữ liệu cá nhân | PARTIALLY verified | Lịch chạy (cron) được đăng ký và đã kiểm tra; chưa chờ đủ 180 ngày thực tế |
| Gửi email thông báo thật | UNABLE_TO_VERIFY | Môi trường thử nghiệm không có máy chủ mail; cần thử trên hosting thật |

## 4. Cách thêm sản phẩm hoặc chương trình khi đã xác minh

1. Trang → Thêm mới, chọn **Trang cha = Sản phẩm**, **Mẫu = Chi tiết sản phẩm**.
2. Chèn mẫu khối **"Bố cục trang chi tiết sản phẩm"** (thanh chèn khối → Mẫu → Play2Grow), trả lời từng mục, xóa ghi chú nội bộ.
3. Điền hộp **"Thông tin sản phẩm / trải nghiệm học tập"** và chọn trạng thái **Đang hoạt động / Đang thử nghiệm / Sắp ra mắt**.
4. Cập nhật bảng trong file này trước khi xuất bản.
