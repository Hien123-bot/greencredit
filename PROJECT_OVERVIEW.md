# 🌿 Green Credit  - Project Overview

## 📖 Giới thiệu (Introduction)
**Green Credit** là một nền tảng Web ứng dụng nhằm khuyến khích lối sống xanh và bảo vệ môi trường thông qua hệ thống tích điểm thưởng (Gamification). Người dùng thực hiện các hành động xanh (tái chế, tham gia sự kiện môi trường, tiết kiệm năng lượng) để nhận "Green Points" và đổi lấy các sản phẩm, voucher thân thiện với môi trường.

---

## 🛠️ Công nghệ sử dụng (Tech Stack)
- **Backend:** PHP (tương thích XAMPP/Apache)
- **Database:** MySQL
- **Frontend:**
  - **HTML5 & CSS3:** Sử dụng phong cách thiết kế hiện đại (Glassmorphism, Vibrant Colors).
  - **JavaScript:** Xử lý logic giỏ hàng, tìm kiếm và tương tác thời gian thực.
  - **Thư viện:** [AOS](https://michalsnik.github.io/aos/) (Animate On Scroll), Google Material Symbols.
- **Tools:** PHP PDO cho kết nối CSDL bảo mật.

---

## 🚀 Tính năng cốt lõi (Core Features)

### 1. Hệ thống Người dùng (User System)
- Đăng ký/Đăng nhập bảo mật.
- Dashboard cá nhân: Theo dõi số dư điểm, lịch sử hoạt động và tiến trình sống xanh.
- Quản lý hồ sơ người dùng.

### 2. Kinh tế Điểm thưởng (Points Economy)
- **Tích điểm (Earning):** Người dùng quét mã QR tại các địa điểm/sự kiện môi trường thông qua `qr_handler.php`.
- **Đổi quà (Spending):** Sử dụng điểm để giảm giá trực tiếp khi mua sắm tại "Eco Marketplace".
- **Tỷ lệ quy đổi:** Hiện tại thiết lập 1 Point (PTS) = 1,000 VNĐ.

### 3. Eco Marketplace (Cửa hàng Xanh)
- Danh mục sản phẩm đa dạng (Bình nước tre, túi vải, voucher...).
- Tìm kiếm sản phẩm thông minh (hỗ trợ định hướng AI Search).
- Giỏ hàng (Cart) tích hợp logic tính toán điểm trừ linh hoạt.

### 4. Nội dung & Cộng đồng
- **Tin tức (News):** Cập nhật các kiến thức sống xanh và thông tin sự kiện.
- **Bảng xếp hạng (Leaderboard):** Vinh danh những cá nhân tích cực nhất.
- **Lịch sử (History):** Nhật ký chi tiết các giao dịch điểm earn/spend.

---

## 📂 Cấu trúc Thư mục (Directory Structure)
```text
/green_credit
├── auth/               # Xử lý đăng ký, đăng nhập, đăng xuất
├── database/           # Chứa file schema.sql và dữ liệu mẫu
├── includes/           # Các thành phần dùng chung (header, footer, db config)
├── assets/             # Hình ảnh, CSS custom, JS
├── models/             # Logic xử lý dữ liệu (nếu có)
├── controllers/        # Điều hướng logic
├── index.php           # Trang chủ (Landing Page)
├── dashboard.php       # Trang quản lý cá nhân
├── rewards.php         # Cửa hàng đổi quà
├── qr_handler.php      # Xử lý quét mã QR cộng điểm
└── news.php            # Trang tin tức môi trường
```

---

## 📊 Sơ đồ Cơ sở Dữ liệu (Database Schema)
- `users`: Lưu thông tin tài khoản và số dư `points`.
- `products`: Thông tin sản phẩm quà tặng và giá trị quy đổi điểm.
- `news`: Các bài viết blog/tin tức.
- `cart`: Lưu trữ tạm thời giỏ hàng của người dùng.
- `history`: Lưu vết mọi hành động tăng/giảm điểm để đảm bảo minh bạch.
- `qr_codes`: Lưu trữ các mã QR hợp lệ để người dùng quét nhận điểm.

---

## 🌟 Trạng thái Dự án (Current Status)
Dự án hiện đang ở giai đoạn hoàn thiện các tính năng cốt lõi. Giao diện được tối ưu hóa theo phong cách **Premium & Modern**, mang lại trải nghiệm người dùng mượt mà và chuyên nghiệp.

---
*Tài liệu được tạo bởi Antigravity AI.*
