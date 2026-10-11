# PRODUCT AUDIT – Trạng thái tính năng và chương trình Play2Grow

Ngày: 11/10/2026. Nguồn kiểm tra: HTML công khai của play2grow.edu.vn, brief của chủ sở hữu. Không có quyền truy cập ứng dụng, mã nguồn sản phẩm hay tài liệu nội bộ.

Quy ước trạng thái: `LIVE_VERIFIED` · `PARTIALLY_IMPLEMENTED` · `BETA_OR_PROTOTYPE` · `PLANNED` · `UNABLE_TO_VERIFY`.

## 1. Sản phẩm số / EdTech (theo brief ban đầu)

Không có sản phẩm số nào được xác minh; mục này giữ lại để theo dõi nếu sau này Play2Grow phát triển ứng dụng.

| Tính năng | Trạng thái | Căn cứ | Website mới thể hiện thế nào |
|---|---|---|---|
| Nền tảng / ứng dụng học tiếng Anh của Play2Grow | UNABLE_TO_VERIFY | Không tìm thấy ứng dụng, đường dẫn tải hay trang đăng nhập nào | Không giới thiệu |
| Hoạt động / trò chơi tiếng Anh tương tác trong sản phẩm | UNABLE_TO_VERIFY | Không có | Chỉ có **gợi ý hoạt động cho phụ huynh** (bài viết), được ghi rõ "không phải tính năng sản phẩm" |
| Lộ trình học (learning paths), theo dõi tiến độ | UNABLE_TO_VERIFY | Không có | Không giới thiệu |
| AI: gợi ý hoạt động, mẫu câu, thử thách, luyện giao tiếp, phản hồi cá nhân hóa | UNABLE_TO_VERIFY | Không có | Câu hỏi thường gặp ghi rõ: "Hiện website chưa giới thiệu tính năng AI nào" |
| Tài khoản phụ huynh, báo cáo tiến độ | UNABLE_TO_VERIFY | Không có | Không giới thiệu |
| Chấm điểm phát âm, hội thoại AI, AI tutor | UNABLE_TO_VERIFY | Không có | Không giới thiệu |


## 2. Chương trình tại trung tâm

**Cập nhật 11/10/2026:** chủ sở hữu xác nhận Play2Grow là **trung tâm có lớp học**, các chương trình trên website **đang hoạt động thật**. Chi tiết của từng lớp (độ tuổi, lịch, học phí, giáo viên, giáo trình) vẫn chưa được cung cấp.

| Chương trình | Trạng thái | Căn cứ | Còn thiếu |
|---|---|---|---|
| STEAM English | LIVE_VERIFIED (chủ sở hữu xác nhận) | Xác nhận của chủ sở hữu + menu website | Độ tuổi, lịch, học phí, giáo trình, giáo viên |
| Lớp Vẽ | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên | như trên |
| Lớp thủ công | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên (trang cũ `/lop-thu-cong/` đang 404) | như trên |
| Lớp đàn Piano | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên | như trên + có cần đàn ở nhà |
| Lớp đàn Keyboard | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên | như trên |
| Tiktok Dance | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên | như trên + chính sách quay/đăng video học viên |
| Nhảy hiện đại | LIVE_VERIFIED (chủ sở hữu xác nhận) | như trên | như trên |
| Mini Party, Kỹ năng tự vệ | UNABLE_TO_VERIFY | Chỉ là biểu tượng trên trang chủ cũ | Có phải chương trình thường xuyên không |
| "Thực hành 100% tiếng Anh với người bản xứ" | UNABLE_TO_VERIFY | Chỉ là khẩu hiệu | Bằng chứng về giáo viên bản xứ; nếu không có, không dùng |
| Cơ sở vật chất, đội ngũ giáo viên | UNABLE_TO_VERIFY | Trang cũ trống | Ảnh thật, thông tin được phép công bố |

**Website mới thể hiện:** trang `/chuong-trinh/` nhóm các lớp theo Tiếng Anh / Mỹ thuật / Âm nhạc / Nhảy với nhãn "Đang mở lớp". Mỗi trang lớp có phần mô tả chung (đánh dấu cần xác nhận) và hộp "Thông tin nhanh". Độ tuổi, lịch học, học phí hiển thị **"Đang cập nhật – liên hệ để được tư vấn"** cho đến khi được điền.

## 3. Chức năng website do bản bàn giao này cung cấp

| Chức năng | Trạng thái | Căn cứ kiểm thử |
|---|---|---|
| Form liên hệ / đăng ký nhận thông tin (plugin Play2Grow Core) | LIVE_VERIFIED trên WordPress 7.1.3 thử nghiệm | Gửi thành công có JS và không JS; lưu vào "Đăng ký liên hệ"; email được tạo đúng (đã chặn gửi thật trong môi trường thử nghiệm); xem QA_REPORT.md |
| Chống spam (honeypot, thời gian điền, nonce, giới hạn tần suất) | LIVE_VERIFIED (thử nghiệm) | QA_REPORT.md |
| Tự xóa đăng ký sau N ngày; xuất/xóa dữ liệu cá nhân | PARTIALLY verified | Lịch chạy (cron) được đăng ký và đã kiểm tra; chưa chờ đủ 180 ngày thực tế |
| Gửi email thông báo thật | UNABLE_TO_VERIFY | Môi trường thử nghiệm không có máy chủ mail; cần thử trên hosting thật |

## 4. Cách hoàn thiện một trang lớp học

1. Mở trang lớp (ví dụ `/steam-english/`). Nếu dùng nội dung khởi đầu, trang mới nằm ở bản nháp "[Bản mới] STEAM English"; sao chép nội dung sang trang gốc để giữ URL.
2. Ở trang gốc, chọn **Mẫu = Chi tiết chương trình**.
3. Điền hộp **"Thông tin chương trình / lớp học"**: nhóm, trạng thái, độ tuổi, nội dung chính, hình thức, thời lượng, lịch học, sĩ số, giáo viên, học phí, cần chuẩn bị.
4. Thay phần mô tả chung bằng nội dung thật; xóa các ghi chú nội bộ.
5. Lớp mới: tạo trang, chọn mẫu "Chi tiết chương trình", chèn mẫu khối **"Bố cục trang chi tiết chương trình"**. Lớp sẽ tự xuất hiện trên trang Chương trình học, trang chủ và trong danh sách "Anh/chị quan tâm đến" của form.
