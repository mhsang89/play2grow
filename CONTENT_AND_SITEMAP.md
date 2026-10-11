# CONTENT AND SITEMAP

## 1. Sitemap đề xuất

```
/                                   Trang chủ
├── /gioi-thieu/                    Giới thiệu Play2Grow       (URL hiện có – giữ nguyên)
├── /phuong-phap-hoc/               Phương pháp học tiếng Anh qua trải nghiệm
├── /chuong-trinh/                  Chương trình học (danh sách theo nhóm)
├── /steam-english/                 STEAM English          (URL hiện có – giữ nguyên)
├── /lop-ve/                        Lớp Vẽ                 (URL hiện có – giữ nguyên)
├── /lop-thu-cong/                  Lớp thủ công           (URL mới; trang cũ đang 404)
├── /lop-dan-piano/                 Lớp đàn Piano
├── /lop-dan-keyboard/              Lớp đàn Keyboard
├── /tiktok-dance/                  Tiktok Dance
├── /nhay-hien-dai/                 Nhảy hiện đại
├── /phu-huynh/                     Dành cho phụ huynh
├── /goc-hoc-tap/                   Góc học tập (trang bài viết)
│   ├── /category/<chuyen-muc>/     9 chuyên mục
│   └── /<slug-bai-viet>/           Bài viết
├── /cau-hoi-thuong-gap/            Câu hỏi thường gặp
├── /lien-he/                       Liên hệ (form Play2Grow Core)
├── /dang-ki/                       Đăng kí (URL hiện có – Contact Form 7, giữ nguyên)
├── /chinh-sach-quyen-rieng-tu/     Chính sách quyền riêng tư (bản nháp pháp lý)
└── /dieu-khoan-su-dung/            Điều khoản sử dụng (bản nháp pháp lý)
```

Các trang lớp **giữ nguyên URL ở gốc** như website cũ (`/steam-english/`, `/lop-ve/`); breadcrumbs vẫn hiển thị "Trang chủ / Chương trình học / Tên lớp". Trang lớp nào đã tồn tại sẽ được nhập thành bản nháp "[Bản mới] …" để chủ sở hữu sao chép nội dung vào trang gốc, sau đó đặt mẫu "Chi tiết chương trình". `/co-so-vat-chat/`, `/giao-vien/`, `/tuyen-dung/` (chỉ có tiêu đề): bổ sung nội dung thật hoặc ẩn khỏi menu và đặt noindex. Chuyển hướng 301 đề xuất: `/contacts/` → `/lien-he/` (hoặc `/dang-ki/`), `/blog-grid/` → `/goc-hoc-tap/`. Theme không tự tạo chuyển hướng; dùng plugin Redirection hoặc cấu hình máy chủ.

## 2. Menu

| Vị trí | Mục |
|---|---|
| Thanh trên cùng | Góc học tập · Hỏi đáp · Liên hệ |
| Menu chính | **Khám phá** ▾ (Về Play2Grow, Phương pháp học qua trải nghiệm) · **Hoạt động tiếng Anh** ▾ (Khám phá thiên nhiên, Học qua hoạt động hằng ngày, Trò chơi tiếng Anh, Con vật quanh em, Đồ ăn & bữa ăn, Địa điểm quanh ta) · **Chương trình** ▾ (Tất cả chương trình + 7 lớp) · **Phụ huynh** ▾ (Hướng dẫn đồng hành cùng con, Bài viết cho phụ huynh, Công nghệ & trẻ em, Câu hỏi thường gặp) · **Liên hệ** + nút CTA |
| Chân trang – Khám phá | Giới thiệu Play2Grow · Phương pháp học · Chương trình học · Góc học tập |
| Chân trang – Hỗ trợ | Dành cho phụ huynh · Câu hỏi thường gặp · Liên hệ |
| Chân trang – Chính sách | Chính sách quyền riêng tư · Điều khoản sử dụng |

Lưu ý: chuyên mục chưa có bài viết vẫn có trong menu khi chạy "Bước 2"; nên gỡ khỏi menu (Giao diện → Menu) cho đến khi có bài, hoặc viết bài trước.

## 3. Danh sách trang và bài viết

| Trang / bài | Slug | Mục đích | Nội dung chính | Cần xác nhận |
|---|---|---|---|---|
| Trang chủ | `trang-chu` | Hiểu nhanh Play2Grow là gì, bắt đầu từ đâu | Hero, truy cập nhanh, ví dụ hoạt động, chủ đề, cách bắt đầu, triết lý, giá trị, sản phẩm, bài viết, hỏi đáp, CTA | Khẩu hiệu, mô tả định vị |
| Giới thiệu Play2Grow | `gioi-thieu` (đã tồn tại → tạo "[Bản mới]") | Sứ mệnh, triết lý, công nghệ có trách nhiệm | Sứ mệnh/tầm nhìn (đề xuất), lý do chọn học qua trải nghiệm, 5 nguyên tắc, nguyên tắc công nghệ, giá trị, mục người sáng lập (ẩn), thông tin pháp lý | Sứ mệnh/tầm nhìn, nguyên tắc công nghệ, người sáng lập |
| Phương pháp | `phuong-phap-hoc` | Giải thích cách học | Định nghĩa, 4 nhịp (quan sát – khám phá – sử dụng – kể lại), 6 ví dụ tình huống (có nhãn "gợi ý cho phụ huynh"), bảng điểm mạnh/giới hạn, vai trò cha mẹ, tài liệu tham khảo | Khung "4 nhịp" có phải phương pháp chính thức |
| Chương trình học | `chuong-trinh` | Danh sách lớp theo nhóm | Thẻ 7 lớp (Tiếng Anh, Mỹ thuật, Âm nhạc, Nhảy), tinh thần học qua trải nghiệm, gợi ý chọn lớp | Mini Party, Kỹ năng tự vệ |
| 7 trang lớp | `steam-english`, `lop-ve`, `lop-thu-cong`, `lop-dan-piano`, `lop-dan-keyboard`, `tiktok-dance`, `nhay-hien-dai` | Chuyển đổi: tư vấn lớp | Mô tả chung (đánh dấu cần xác nhận), "Thông tin nhanh" (độ tuổi, lịch, học phí hiển thị "Đang cập nhật" khi trống), nút đăng ký tư vấn, chương trình khác | **Toàn bộ chi tiết lớp** |
| Dành cho phụ huynh | `phu-huynh` | Hướng dẫn đồng hành | Vai trò cha mẹ, bảng mẫu câu theo sinh hoạt, bảng hoạt động theo độ tuổi, duy trì thói quen, cân bằng màn hình, checklist chọn sản phẩm | — |
| Góc học tập | `goc-hoc-tap` | Trang bài viết | (WordPress tự hiển thị danh sách + bộ lọc chuyên mục) | — |
| Câu hỏi thường gặp | `cau-hoi-thuong-gap` | Giải đáp | 13 câu hỏi trong 4 nhóm | Chi phí, độ tuổi |
| Liên hệ | `lien-he` | Chuyển đổi: đăng ký tư vấn | Hướng dẫn trước khi gửi + form (danh sách lớp tự cập nhật) + thông tin liên hệ đã xác minh | Email nhận thông báo |
| Chính sách quyền riêng tư | `chinh-sach-quyen-rieng-tu` | Pháp lý | 14 mục, mô tả đúng dữ liệu form/cookie | **Rà soát pháp lý** |
| Điều khoản sử dụng | `dieu-khoan-su-dung` | Pháp lý | 10 mục, gồm an toàn khi làm hoạt động cùng trẻ | **Rà soát pháp lý** |
| Bài: Học tiếng Anh qua trải nghiệm là gì? | `hoc-tieng-anh-qua-trai-nghiem-la-gi` | SEO thông tin | Định nghĩa, lý do, hiểu lầm, ví dụ pha nước cam, 5 nguyên tắc, nguồn tham khảo | — |
| Bài: 15 hoạt động tiếng Anh tại nhà | `hoat-dong-tieng-anh-tai-nha-cho-tre` | SEO thực hành | 15 hoạt động theo thời điểm trong ngày + mẫu câu | — |
| Bài: 10 trò chơi tiếng Anh không cần thiết bị | `tro-choi-tieng-anh-cho-tre-khong-can-thiet-bi` | SEO thực hành | 10 trò chơi, độ tuổi, mục tiêu ngôn ngữ | — |
| Bài: Khám phá thiên nhiên bằng tiếng Anh | `kham-pha-thien-nhien-bang-tieng-anh` | SEO thực hành | 6 hoạt động, bảng từ vựng, an toàn | — |
| Bài: Cha mẹ không giỏi tiếng Anh… | `cha-me-khong-gioi-tieng-anh-giup-con-hoc` | Phụ huynh | Vai trò, phát âm, 8 việc làm ngay, điều nên tránh | — |
| Bài: Thời gian dùng thiết bị của trẻ | `thoi-gian-dung-thiet-bi-va-hoc-tieng-anh` | Công nghệ & AI | Khuyến nghị WHO 2019, AAP; 5 nguyên tắc; câu hỏi về AI | — |

Mỗi trang/bài đều có tiêu đề riêng, meta description riêng (khai báo trong `starter-content/manifest.php`), slug không dấu và liên kết nội bộ chéo.

## 4. Chuyên mục (Góc học tập)

Phương pháp học ngôn ngữ · Học qua hoạt động hằng ngày · Trò chơi tiếng Anh · Khám phá thiên nhiên · Đồng hành cùng con · Công nghệ & trẻ em (mỗi chuyên mục đã có 1 bài) · Con vật quanh em · Đồ ăn & bữa ăn · Địa điểm quanh ta (**chưa có bài** – tự ẩn khỏi khu "Khám phá theo chủ đề" cho đến khi có bài).

## 5. Chủ đề bài viết đề xuất tiếp theo (không bịa số liệu tìm kiếm)

Từ vựng con vật qua chuyến đi sở thú; tiếng Anh khi đi siêu thị; nấu ăn cùng con bằng tiếng Anh; bài hát tiếng Anh có động tác; đọc truyện tranh cùng con; trò chơi đóng vai nghề nghiệp. Chưa có công cụ nghiên cứu từ khóa nên không đưa ra lượng tìm kiếm.

## 6. Nguồn nội dung

Nội dung gốc nằm trong `content-src/` (Markdown). Chạy `tools/build.sh` để sinh lại block markup và ZIP.
