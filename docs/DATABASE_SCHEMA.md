# DATABASE SCHEMA

## users

Menyimpan data pengguna sistem.

| Field      | Type              |
| ---------- | ----------------- |
| id         | bigint            |
| name       | varchar           |
| email      | varchar           |
| password   | varchar           |
| role       | enum(admin,piket) |
| created_at | timestamp         |
| updated_at | timestamp         |

---

## products

Menyimpan data produk POTI.

| Field      | Type                      |
| ---------- | ------------------------- |
| id         | bigint                    |
| name       | varchar                   |
| price      | decimal                   |
| stock      | integer                   |
| status     | enum(active,out_of_stock) |
| created_at | timestamp                 |
| updated_at | timestamp                 |

---

## transactions

Menyimpan data transaksi.

| Field         | Type        |
| ------------- | ----------- |
| id            | bigint      |
| user_id       | foreign key |
| total_amount  | decimal     |
| paid_amount   | decimal     |
| change_amount | decimal     |
| created_at    | timestamp   |
| updated_at    | timestamp   |

Relationship:

* transactions belongsTo users

---

## transaction_items

Menyimpan detail item transaksi.

| Field          | Type        |
| -------------- | ----------- |
| id             | bigint      |
| transaction_id | foreign key |
| product_id     | foreign key |
| quantity       | integer     |
| price          | decimal     |
| subtotal       | decimal     |
| created_at     | timestamp   |
| updated_at     | timestamp   |

Relationship:

* transaction_items belongsTo transactions
* transaction_items belongsTo products

---

## schedules

Menyimpan jadwal piket.

| Field      | Type        |
| ---------- | ----------- |
| id         | bigint      |
| user_id    | foreign key |
| date       | date        |
| shift      | varchar     |
| created_at | timestamp   |
| updated_at | timestamp   |

Relationship:

* schedules belongsTo users

---

## attendances

Menyimpan absensi piket.

| Field      | Type        |
| ---------- | ----------- |
| id         | bigint      |
| user_id    | foreign key |
| check_in   | datetime    |
| check_out  | datetime    |
| created_at | timestamp   |
| updated_at | timestamp   |

Relationship:

* attendances belongsTo users

---

# Entity Relationship

users
├── transactions
├── schedules
└── attendances

transactions
└── transaction_items

products
└── transaction_items
