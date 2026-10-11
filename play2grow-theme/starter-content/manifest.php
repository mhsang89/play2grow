<?php
/**
 * Starter content manifest (used by inc/starter-content.php).
 * Content files are generated from /content-src by tools/md2blocks.py.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

return array(
	'categories' => array(
		'phuong-phap-hoc-ngon-ngu'    => array(
			'name'        => 'Phương pháp học ngôn ngữ',
			'description' => 'Hiểu đúng về học tiếng Anh qua trải nghiệm, vui chơi và ngữ cảnh thực tế.',
		),
		'hoc-qua-hoat-dong-hang-ngay' => array(
			'name'        => 'Học qua hoạt động hằng ngày',
			'description' => 'Biến bữa ăn, giờ tắm, việc nhà và những chuyến đi thành cơ hội dùng tiếng Anh.',
		),
		'tro-choi-tieng-anh'          => array(
			'name'        => 'Trò chơi tiếng Anh',
			'description' => 'Trò chơi đơn giản giúp trẻ nghe và nói tiếng Anh một cách tự nhiên.',
		),
		'kham-pha-thien-nhien'        => array(
			'name'        => 'Khám phá thiên nhiên',
			'description' => 'Hoạt động ngoài trời và quan sát thiên nhiên bằng tiếng Anh.',
		),
		'dong-hanh-cung-con'          => array(
			'name'        => 'Đồng hành cùng con',
			'description' => 'Kinh nghiệm giúp cha mẹ tự tin đồng hành cùng con học tiếng Anh.',
		),
		'cong-nghe-va-tre-em'         => array(
			'name'        => 'Công nghệ & trẻ em',
			'description' => 'Sử dụng thiết bị, ứng dụng và AI một cách có trách nhiệm trong việc học của trẻ.',
		),
	),

	// Order matters: parents before children.
	'pages'      => array(
		array(
			'slug'        => 'trang-chu',
			'title'       => 'Trang chủ',
			'file'        => 'pages/trang-chu.html',
			'excerpt'     => '',
			'description' => 'Play2Grow giúp trẻ học tiếng Anh qua vui chơi, khám phá và trải nghiệm gắn với đời sống hằng ngày. Play. Explore. Learn. Grow.',
			'order'       => 0,
		),
		array(
			'slug'        => 'gioi-thieu',
			'title'       => 'Giới thiệu Play2Grow',
			'file'        => 'pages/gioi-thieu.html',
			'excerpt'     => 'Học qua khám phá. Trưởng thành qua trải nghiệm. Tìm hiểu sứ mệnh, triết lý giáo dục và cách Play2Grow tiếp cận việc học tiếng Anh của trẻ.',
			'description' => 'Sứ mệnh, triết lý giáo dục và cách tiếp cận công nghệ có trách nhiệm của Play2Grow – thương hiệu học tiếng Anh qua trải nghiệm dành cho trẻ.',
			'order'       => 1,
		),
		array(
			'slug'        => 'phuong-phap-hoc',
			'title'       => 'Phương pháp học tiếng Anh qua trải nghiệm',
			'file'        => 'pages/phuong-phap-hoc.html',
			'excerpt'     => 'Tiếng Anh trở nên gần gũi khi trẻ có lý do để dùng nó: quan sát, khám phá, sử dụng và kể lại trong những tình huống quen thuộc.',
			'description' => 'Học tiếng Anh qua trải nghiệm là gì, bốn nhịp của một hoạt động ngôn ngữ, ví dụ cụ thể và những điểm mạnh, giới hạn phụ huynh nên biết.',
			'order'       => 2,
		),
		array(
			'slug'        => 'san-pham',
			'title'       => 'Sản phẩm',
			'file'        => 'pages/san-pham.html',
			'template'    => 'page-templates/template-programs.php',
			'excerpt'     => 'Sản phẩm và trải nghiệm học tập của Play2Grow, kèm trạng thái rõ ràng để phụ huynh biết điều gì đã sẵn sàng sử dụng.',
			'description' => 'Thông tin minh bạch về sản phẩm và trải nghiệm học tiếng Anh của Play2Grow: dành cho ai, hoạt động thế nào và trạng thái hiện tại.',
			'order'       => 3,
		),
		array(
			'slug'        => 'phu-huynh',
			'title'       => 'Dành cho phụ huynh',
			'file'        => 'pages/phu-huynh.html',
			'excerpt'     => 'Cách đồng hành cùng con học tiếng Anh trong đời sống hằng ngày: chọn hoạt động theo độ tuổi, duy trì thói quen và cân bằng thời gian dùng thiết bị.',
			'description' => 'Hướng dẫn phụ huynh đồng hành cùng con học tiếng Anh tại nhà: mẫu câu theo sinh hoạt, hoạt động theo độ tuổi, cân bằng màn hình.',
			'order'       => 4,
		),
		array(
			'slug'        => 'goc-hoc-tap',
			'title'       => 'Góc học tập',
			'file'        => 'pages/goc-hoc-tap.html',
			'excerpt'     => 'Ý tưởng hoạt động, trò chơi và kinh nghiệm giúp phụ huynh đồng hành cùng con học tiếng Anh qua trải nghiệm.',
			'description' => 'Ý tưởng hoạt động tiếng Anh tại nhà, trò chơi cho trẻ, khám phá thiên nhiên và kinh nghiệm đồng hành cùng con từ Play2Grow.',
			'order'       => 5,
		),
		array(
			'slug'        => 'cau-hoi-thuong-gap',
			'title'       => 'Câu hỏi thường gặp',
			'file'        => 'pages/cau-hoi-thuong-gap.html',
			'excerpt'     => 'Giải đáp những câu hỏi phổ biến về Play2Grow, phương pháp học tiếng Anh qua trải nghiệm, việc đồng hành tại nhà và quyền riêng tư.',
			'description' => 'Câu hỏi thường gặp về Play2Grow, học tiếng Anh qua trải nghiệm, đồng hành cùng con tại nhà và cách website xử lý dữ liệu.',
			'order'       => 6,
		),
		array(
			'slug'        => 'lien-he',
			'title'       => 'Liên hệ',
			'file'        => 'pages/lien-he.html',
			'template'    => 'page-templates/template-contact.php',
			'excerpt'     => 'Gửi câu hỏi hoặc đăng ký nhận thông tin từ Play2Grow.',
			'description' => 'Liên hệ Play2Grow: gửi câu hỏi về phương pháp học tiếng Anh qua trải nghiệm hoặc đăng ký nhận thông tin sản phẩm.',
			'order'       => 7,
		),
		array(
			'slug'        => 'chinh-sach-quyen-rieng-tu',
			'title'       => 'Chính sách quyền riêng tư',
			'file'        => 'pages/chinh-sach-quyen-rieng-tu.html',
			'excerpt'     => '',
			'description' => 'Cách website Play2Grow thu thập, sử dụng, lưu trữ và bảo vệ thông tin cá nhân, và quyền của anh/chị đối với dữ liệu của mình.',
			'order'       => 8,
		),
		array(
			'slug'        => 'dieu-khoan-su-dung',
			'title'       => 'Điều khoản sử dụng',
			'file'        => 'pages/dieu-khoan-su-dung.html',
			'excerpt'     => '',
			'description' => 'Điều khoản sử dụng website Play2Grow: nội dung tham khảo, an toàn khi thực hiện hoạt động cùng trẻ, quyền sở hữu trí tuệ.',
			'order'       => 9,
		),
	),

	'posts'      => array(
		array(
			'slug'        => 'hoc-tieng-anh-qua-trai-nghiem-la-gi',
			'title'       => 'Học tiếng Anh qua trải nghiệm là gì? Hiểu đúng để đồng hành cùng con',
			'file'        => 'posts/hoc-tieng-anh-qua-trai-nghiem-la-gi.html',
			'category'    => 'phuong-phap-hoc-ngon-ngu',
			'tags'        => array( 'học qua trải nghiệm', 'phương pháp' ),
			'excerpt'     => 'Học qua trải nghiệm không chỉ là dã ngoại hay lớp học ngoài trời. Tìm hiểu cách tiếp cận này, vì sao phù hợp với trẻ nhỏ và cách bắt đầu ngay tại nhà.',
			'description' => 'Học tiếng Anh qua trải nghiệm là gì, vì sao phù hợp với trẻ nhỏ, những hiểu lầm thường gặp và 5 nguyên tắc để phụ huynh bắt đầu tại nhà.',
		),
		array(
			'slug'        => 'hoat-dong-tieng-anh-tai-nha-cho-tre',
			'title'       => '15 hoạt động tiếng Anh tại nhà cho trẻ từ những việc quen thuộc hằng ngày',
			'file'        => 'posts/hoat-dong-tieng-anh-tai-nha-cho-tre.html',
			'category'    => 'hoc-qua-hoat-dong-hang-ngay',
			'tags'        => array( 'hoạt động tại nhà', 'mẫu câu' ),
			'excerpt'     => 'Ăn sáng, tắm, phơi quần áo, đi chợ – 15 hoạt động theo từng thời điểm trong ngày kèm mẫu câu tiếng Anh để phụ huynh áp dụng ngay.',
			'description' => '15 hoạt động tiếng Anh tại nhà cho trẻ theo từng thời điểm trong ngày, kèm mẫu câu đơn giản để phụ huynh áp dụng ngay.',
		),
		array(
			'slug'        => 'tro-choi-tieng-anh-cho-tre-khong-can-thiet-bi',
			'title'       => '10 trò chơi tiếng Anh cho trẻ không cần thiết bị, chơi được ngay',
			'file'        => 'posts/tro-choi-tieng-anh-cho-tre-khong-can-thiet-bi.html',
			'category'    => 'tro-choi-tieng-anh',
			'tags'        => array( 'trò chơi', 'hoạt động tại nhà' ),
			'excerpt'     => 'Simon says, I spy, truy tìm kho báu, đóng vai bán hàng… 10 trò chơi tiếng Anh chỉ cần vài món đồ trong nhà, kèm độ tuổi phù hợp và mẫu câu.',
			'description' => '10 trò chơi tiếng Anh cho trẻ không cần điện thoại hay máy tính bảng, kèm độ tuổi phù hợp, mục tiêu ngôn ngữ và mẫu câu.',
		),
		array(
			'slug'        => 'kham-pha-thien-nhien-bang-tieng-anh',
			'title'       => 'Khám phá thiên nhiên bằng tiếng Anh: 6 hoạt động cho cả nhà',
			'file'        => 'posts/kham-pha-thien-nhien-bang-tieng-anh.html',
			'category'    => 'kham-pha-thien-nhien',
			'tags'        => array( 'thiên nhiên', 'hoạt động ngoài trời', 'từ vựng' ),
			'excerpt'     => 'Từ công viên gần nhà đến ban công nhỏ: 6 hoạt động khám phá thiên nhiên giúp trẻ học tiếng Anh qua quan sát và trải nghiệm giác quan.',
			'description' => '6 hoạt động khám phá thiên nhiên bằng tiếng Anh cho trẻ, bảng từ vựng theo chủ đề và lưu ý an toàn khi khám phá ngoài trời.',
		),
		array(
			'slug'        => 'cha-me-khong-gioi-tieng-anh-giup-con-hoc',
			'title'       => 'Cha mẹ không giỏi tiếng Anh có giúp con học được không?',
			'file'        => 'posts/cha-me-khong-gioi-tieng-anh-giup-con-hoc.html',
			'category'    => 'dong-hanh-cung-con',
			'tags'        => array( 'phụ huynh', 'đồng hành' ),
			'excerpt'     => 'Cha mẹ không cần giỏi tiếng Anh mới đồng hành được cùng con. Vai trò thật sự của cha mẹ, chuyện phát âm và 8 việc có thể làm ngay từ hôm nay.',
			'description' => 'Cha mẹ không giỏi tiếng Anh vẫn có thể đồng hành cùng con: vai trò của cha mẹ, chuyện phát âm, 8 việc làm ngay và những điều nên tránh.',
		),
		array(
			'slug'        => 'thoi-gian-dung-thiet-bi-va-hoc-tieng-anh',
			'title'       => 'Thời gian dùng thiết bị của trẻ: khuyến nghị và cách cân bằng khi học tiếng Anh',
			'file'        => 'posts/thoi-gian-dung-thiet-bi-va-hoc-tieng-anh.html',
			'category'    => 'cong-nghe-va-tre-em',
			'tags'        => array( 'thời gian màn hình', 'công nghệ', 'AI' ),
			'excerpt'     => 'WHO và AAP khuyến nghị gì về thời gian dùng màn hình của trẻ? Năm nguyên tắc cân bằng khi học tiếng Anh với thiết bị và những câu hỏi nên đặt ra với ứng dụng có AI.',
			'description' => 'Khuyến nghị của WHO và AAP về thời gian dùng màn hình của trẻ, 5 nguyên tắc cân bằng khi học tiếng Anh với thiết bị và lưu ý về AI.',
		),
	),

	// location => [ name, items: [ page path | "cat:slug", label, parent label ] ].
	'menus'      => array(
		'topbar'         => array(
			'name'  => 'Liên kết nhanh',
			'items' => array(
				array( 'goc-hoc-tap', 'Góc học tập' ),
				array( 'cau-hoi-thuong-gap', 'Hỏi đáp' ),
				array( 'lien-he', 'Liên hệ' ),
			),
		),
		'primary'        => array(
			'name'  => 'Menu chính',
			'items' => array(
				array( 'gioi-thieu', 'Giới thiệu' ),
				array( 'gioi-thieu', 'Giới thiệu chung', 'Giới thiệu' ),
				array( 'phuong-phap-hoc', 'Phương pháp học qua trải nghiệm', 'Giới thiệu' ),
				array( 'phuong-phap-hoc', 'Phương pháp' ),
				array( 'san-pham', 'Sản phẩm' ),
				array( 'phu-huynh', 'Phụ huynh' ),
				array( 'phu-huynh', 'Hướng dẫn đồng hành cùng con', 'Phụ huynh' ),
				array( 'cau-hoi-thuong-gap', 'Câu hỏi thường gặp', 'Phụ huynh' ),
				array( 'goc-hoc-tap', 'Góc học tập' ),
				array( 'cat:hoc-qua-hoat-dong-hang-ngay', 'Học qua hoạt động hằng ngày', 'Góc học tập' ),
				array( 'cat:tro-choi-tieng-anh', 'Trò chơi tiếng Anh', 'Góc học tập' ),
				array( 'cat:kham-pha-thien-nhien', 'Khám phá thiên nhiên', 'Góc học tập' ),
				array( 'cat:dong-hanh-cung-con', 'Đồng hành cùng con', 'Góc học tập' ),
				array( 'cat:phuong-phap-hoc-ngon-ngu', 'Phương pháp học ngôn ngữ', 'Góc học tập' ),
				array( 'cat:cong-nghe-va-tre-em', 'Công nghệ & trẻ em', 'Góc học tập' ),
				array( 'lien-he', 'Liên hệ' ),
			),
		),
		'footer_explore' => array(
			'name'  => 'Khám phá',
			'items' => array(
				array( 'gioi-thieu', 'Giới thiệu Play2Grow' ),
				array( 'phuong-phap-hoc', 'Phương pháp học' ),
				array( 'san-pham', 'Sản phẩm' ),
				array( 'goc-hoc-tap', 'Góc học tập' ),
			),
		),
		'footer_support' => array(
			'name'  => 'Hỗ trợ',
			'items' => array(
				array( 'phu-huynh', 'Dành cho phụ huynh' ),
				array( 'cau-hoi-thuong-gap', 'Câu hỏi thường gặp' ),
				array( 'lien-he', 'Liên hệ' ),
			),
		),
		'legal'          => array(
			'name'  => 'Chính sách',
			'items' => array(
				array( 'chinh-sach-quyen-rieng-tu', 'Chính sách quyền riêng tư' ),
				array( 'dieu-khoan-su-dung', 'Điều khoản sử dụng' ),
			),
		),
	),
);
