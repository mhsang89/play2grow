# Hướng dẫn mở quyền truy cập mạng cho môi trường Claude Cloud

## Vì sao cần làm

Môi trường cloud của phiên làm việc hiện chặn các tên miền sau (proxy trả về `403 – CONNECT tunnel failed`):

- `play2grow.edu.vn` → Claude không audit được website hiện tại.
- `ila.edu.vn` → Claude không xem được website tham chiếu.
- `wordpress.org` → không tải được gói tiếng Việt của WordPress để kiểm thử.

Claude **không thể tự thay đổi** cài đặt này. Chủ tài khoản cần tự thực hiện các bước dưới đây.

## Các bước thực hiện

1. Mở phiên làm việc trên **claude.ai/code** (hoặc ứng dụng Claude trên máy tính/điện thoại).
2. Trên **thanh tiêu đề** của phiên, bấm vào **tên môi trường cloud** để mở menu, rồi chọn **Edit**.
3. Trong cửa sổ cài đặt, tìm mục **Network access**.
4. Chọn mức **Limited**. Nếu ứng dụng chưa cập nhật, mục này có thể tên là **Custom**. Ô **Allowed domains** sẽ hiện ra.
5. Thêm các tên miền sau vào **Allowed domains**, mỗi tên miền một dòng:

   ```
   play2grow.edu.vn
   www.play2grow.edu.vn
   ila.edu.vn
   www.ila.edu.vn
   wordpress.org
   api.wordpress.org
   downloads.wordpress.org
   ```

   Ba dòng `wordpress.org` là tùy chọn. Chúng chỉ dùng để kiểm thử theme với gói tiếng Việt chính thức của WordPress.

6. **Giữ nguyên dấu tích** ở ô **Allow package managers** (hoặc "include the default list of package managers"). Nếu bỏ tích, Claude sẽ mất quyền tải npm/PyPI/GitHub đang dùng để kiểm thử.
7. Bấm **Save**.
8. Quay lại phiên và nhắn cho Claude: *"Đã mở quyền mạng, hãy audit lại website"*.
   - Nếu Claude vẫn báo bị chặn, có thể phiên hiện tại chưa nhận cấu hình mới. Khi đó hãy mở **phiên mới** trong cùng môi trường. Mã nguồn đã được đẩy lên nhánh GitHub nên không mất dữ liệu.

## Cách khác

Ở bước 4, có thể chọn một mức truy cập rộng hơn (cho phép mọi tên miền) thay vì Limited. Cách này đơn giản hơn nhưng kém an toàn hơn. Chỉ thêm đúng các tên miền cần thiết vẫn là cách được khuyến nghị.

## Tài liệu chính thức

https://code.claude.com/docs/en/cloud-environments#network-access
