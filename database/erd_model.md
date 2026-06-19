# Mô hình ERD - Green Credit Platform (8 Tables)

Dưới đây là sơ đồ quan hệ thực thể (ERD) chi tiết cho hệ thống Green Credit với đầy đủ 8 bảng.

## 1. Sơ đồ Mermaid

```mermaid
erDiagram
    CATEGORIES ||--o{ PRODUCTS : "phân loại"
    USERS ||--o{ NEWS : "viết bài"
    USERS ||--o{ ORDERS : "thực hiện"
    USERS ||--o{ HISTORY : "có lịch sử"
    USERS ||--o{ QR_CODES : "quét mã"
    ORDERS ||--o{ ORDER_ITEMS : "bao gồm"
    PRODUCTS ||--o{ ORDER_ITEMS : "trong đơn"

    USERS {
        int id PK
        string fullname "Họ và tên"
        string email "Email"
        string phone "Số điện thoại"
        string password "Mật khẩu (hashed)"
        int points "Điểm tích lũy"
        enum role "Vai trò (user/admin)"
        timestamp created_at "Ngày tạo"
    }

    CATEGORIES {
        int id PK
        string name "Tên danh mục"
        text description "Mô tả"
    }

    PRODUCTS {
        int id PK
        int category_id FK "ID danh mục"
        string name "Tên sản phẩm"
        text description "Mô tả"
        int price_points "Giá bằng điểm"
        int stock "Số lượng tồn"
        string image_url "Đường dẫn ảnh"
        timestamp created_at "Ngày tạo"
    }

    NEWS {
        int id PK
        string title "Tiêu đề tin tức"
        text content "Nội dung"
        int author_id FK "ID người viết"
        string image_url "Ảnh minh họa"
        timestamp published_at "Ngày đăng"
    }

    QR_CODES {
        int id PK
        string code "Mã định danh"
        int points "Số điểm cộng"
        int used_by FK "Người đã dùng"
        tinyint is_used "Đã dùng chưa"
        datetime used_at "Thời điểm dùng"
    }

    ORDERS {
        int id PK
        int user_id FK "Người đổi quà"
        int total_points "Tổng điểm trừ"
        enum status "Trạng thái"
        timestamp created_at "Thời gian"
    }

    ORDER_ITEMS {
        int id PK
        int order_id FK "ID đơn hàng"
        int product_id FK "ID sản phẩm"
        int quantity "Số lượng"
        int points_at_time "Giá điểm lúc đổi"
    }

    HISTORY {
        int id PK
        int user_id FK "Người thực hiện"
        string action_type "Loại (earn/spend)"
        int points "Số điểm thay đổi"
        string description "Mô tả"
        timestamp created_at "Thời gian"
    }
```

## 2. Chi tiết các mối quan hệ

- **Danh mục và Sản phẩm (1-N):** Một danh mục có thể chứa nhiều sản phẩm.
- **Người dùng và Đơn hàng (1-N):** Một người dùng có thể thực hiện nhiều lần đổi quà.
- **Đơn hàng và Chi tiết đơn hàng (1-N):** Một đơn hàng bao gồm nhiều sản phẩm khác nhau.
- **Sản phẩm và Chi tiết đơn hàng (1-N):** Một sản phẩm có thể xuất hiện trong nhiều đơn hàng khác nhau.
- **Người dùng và QR Code (1-N):** Một người dùng có thể quét nhiều mã QR khác nhau để tích điểm.
- **Người dùng và Tin tức (1-N):** Một Admin có thể viết nhiều bài tin tức.
- **Người dùng và Lịch sử (1-N):** Mọi giao dịch điểm đều được lưu vết chi tiết.

---
*Tài liệu được cập nhật để khớp với Database thực tế.*
