=== Play2Grow Core ===
Contributors: play2grow
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Chức năng dữ liệu cho website Play2Grow, độc lập với giao diện.

== Description ==

* Form liên hệ / đăng ký nhận thông tin: shortcode [p2g_contact_form]. Xác thực phía trình duyệt và máy chủ, nonce, honeypot, kiểm tra thời gian điền, giới hạn tần suất, tương thích bộ nhớ đệm trang (LiteSpeed Cache…).
* Lưu đăng ký trong mục "Đăng ký liên hệ" (chỉ quản trị viên xem được), tự xóa sau số ngày cấu hình, tích hợp công cụ Xuất / Xóa dữ liệu cá nhân của WordPress. Không lưu địa chỉ IP.
* Thông tin doanh nghiệp và mạng xã hội (Giao diện → Tùy biến → Play2Grow – …), lưu dưới dạng option nên không mất khi đổi giao diện.
* Shortcode nội dung: [p2g_programs], [p2g_latest_posts], [p2g_topics], [p2g_cta_button], [p2g_company_info], [p2g_note]…[/p2g_note], [p2g_retention].

Cài đặt: Cài đặt → Play2Grow. Email chỉ được gửi khi đã nhập email nhận thông báo; nên dùng plugin SMTP để đảm bảo email không vào thư rác.

Khi gỡ plugin: dữ liệu đăng ký và cài đặt KHÔNG tự xóa. Nếu không còn cần, hãy xóa các đăng ký trong mục "Đăng ký liên hệ" trước khi gỡ.

== Changelog ==

= 1.0.0 =
* Phiên bản đầu tiên.
