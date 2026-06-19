# Từ điển dữ liệu (Data Dictionary) - Green Credit (Updated)

Tài liệu này cung cấp chi tiết về cấu trúc của toàn bộ 8 bảng hiện có trong hệ thống Green Credit.

## 1. Bảng `categories` (Danh mục)
Phân loại các loại sản phẩm/phần thưởng.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID duy nhất của danh mục |
| `name` | VARCHAR(100) | NOT NULL | Tên danh mục (VD: Voucher, Tái chế...) |
| `description` | TEXT | NULL | Mô tả chi tiết về danh mục |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ngày tạo danh mục |

## 2. Bảng `users` (Người dùng)
Lưu trữ thông tin tài khoản và điểm tích lũy.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID người dùng |
| `fullname` | VARCHAR(100) | NOT NULL | Họ và tên (Khớp với register_process.php) |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Địa chỉ email đăng nhập |
| `phone` | VARCHAR(20) | NULL | Số điện thoại liên lạc |
| `password` | VARCHAR(255) | NOT NULL | Mật khẩu đã mã hóa |
| `points` | INT | DEFAULT 0 | Số dư điểm xanh hiện tại |
| `role` | ENUM | 'user' hoặc 'admin' | Vai trò người dùng |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ngày đăng ký tài khoản |

## 3. Bảng `products` (Sản phẩm/Phần thưởng)
Danh sách các món quà người dùng có thể đổi.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID sản phẩm |
| `category_id` | INT | FK (categories.id) | Liên kết với danh mục sản phẩm |
| `name` | VARCHAR(100) | NOT NULL | Tên sản phẩm/phần thưởng |
| `description` | TEXT | NULL | Mô tả chi tiết sản phẩm |
| `price_points` | INT | NOT NULL | Giá trị quy đổi bằng điểm |
| `stock` | INT | DEFAULT 0 | Số lượng còn trong kho |
| `image_url` | VARCHAR(255) | NULL | Đường dẫn ảnh minh họa |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ngày thêm sản phẩm |

## 4. Bảng `news` (Tin tức)
Các bài viết về môi trường.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID bài viết |
| `title` | VARCHAR(255) | NOT NULL | Tiêu đề tin tức |
| `content` | TEXT | NOT NULL | Nội dung chi tiết |
| `author_id` | INT | FK (users.id) | ID người viết bài |
| `image_url` | VARCHAR(255) | NULL | Ảnh bìa tin tức |
| `published_at`| TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ngày đăng tin |

## 5. Bảng `qr_codes` (Mã tích điểm)
Quản lý các mã QR để người dùng quét nhận điểm.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID mã QR |
| `code` | VARCHAR(50) | UNIQUE, NOT NULL | Chuỗi mã định danh (VD: GREEN_ABC) |
| `points` | INT | NOT NULL | Số điểm sẽ nhận khi quét mã này |
| `is_used` | TINYINT | DEFAULT 0 | 0: Chưa dùng, 1: Đã dùng |
| `used_by` | INT | FK (users.id) | ID người đã sử dụng mã |
| `used_at` | DATETIME | NULL | Thời điểm mã được quét |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ngày tạo mã |

## 6. Bảng `orders` (Đơn hàng đổi quà)
Thông tin tổng quát các giao dịch đổi quà.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | Mã đơn hàng |
| `user_id` | INT | FK (users.id) | Người thực hiện đổi quà |
| `total_points` | INT | NOT NULL | Tổng số điểm đã trừ cho đơn này |
| `status` | ENUM | DEFAULT 'completed' | Trạng thái (pending/completed/cancelled) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Thời gian giao dịch |

## 7. Bảng `order_items` (Chi tiết đơn hàng)
Chi tiết các món quà trong một đơn hàng.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID chi tiết |
| `order_id` | INT | FK (orders.id) | Liên kết với đơn hàng chính |
| `product_id` | INT | FK (products.id) | ID sản phẩm được đổi |
| `quantity` | INT | DEFAULT 1 | Số lượng đổi |
| `points_at_time`| INT | NOT NULL | Giá điểm tại thời điểm đổi |

## 8. Bảng `history` (Lịch sử điểm)
Nhật ký biến động điểm số của người dùng.

| Tên trường | Kiểu dữ liệu | Ràng buộc | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | INT | PK, AUTO_INCREMENT | ID bản ghi |
| `user_id` | INT | FK (users.id) | ID người dùng |
| `action_type` | VARCHAR(50) | NOT NULL | Loại ('earn' - tích, 'spend' - tiêu) |
| `points` | INT | NOT NULL | Số điểm cộng (+) hoặc trừ (-) |
| `description` | VARCHAR(255) | NULL | Lý do chi tiết (Quét QR, Đổi quà...) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Thời gian thực hiện |
