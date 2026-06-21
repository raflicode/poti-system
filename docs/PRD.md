# PRODUCT REQUIREMENTS DOCUMENT

# POTI System

## Background

POTI (Pojok TI) merupakan unit usaha HMJ TI yang menjual makanan dan minuman kepada mahasiswa.

Saat ini proses operasional masih dilakukan secara manual sehingga sering terjadi kesalahan pencatatan transaksi, kesulitan mengelola stok, serta tidak tersedianya laporan penjualan yang terstruktur.

Diperlukan sebuah sistem berbasis web yang dapat membantu proses operasional POTI secara digital.

---

# Objectives

* Mempermudah transaksi penjualan.
* Mengurangi kesalahan perhitungan.
* Mengelola stok secara otomatis.
* Menyediakan laporan penjualan.
* Mengelola jadwal dan absensi piket.

---

# User Roles

## Admin

Mengelola seluruh sistem.

## Piket

Melakukan transaksi penjualan dan absensi.

---

# Features

## Authentication

### Login

* Login menggunakan email atau username.
* Password terenkripsi.
* Role-based access control.

---

## Dashboard Admin

Menampilkan:

* Total pemasukan hari ini.
* Total transaksi hari ini.
* Jumlah produk.
* Produk stok menipis.

---

## Product Management

Admin dapat:

* Menambah produk.
* Mengubah produk.
* Menghapus produk.
* Melihat daftar produk.
* Mencari produk.

Data produk:

* Nama produk
* Harga
* Stok
* Status

---

## Transaction Management

Piket dapat:

* Mencari produk.
* Menambah produk ke keranjang.
* Mengubah jumlah produk.
* Menghapus produk dari keranjang.
* Checkout transaksi.

Sistem:

* Menghitung total otomatis.
* Menghitung kembalian otomatis.
* Mengurangi stok otomatis.

---

## Transaction History

Admin:

* Melihat seluruh transaksi.

Piket:

* Melihat transaksi yang dilakukan sendiri.

---

## Schedule Management

Admin:

* Tambah jadwal.
* Edit jadwal.
* Hapus jadwal.

Piket:

* Melihat jadwal hari ini.
* Melihat jadwal mingguan.

---

## Attendance

Piket:

* Check In.
* Check Out.

Admin:

* Melihat riwayat absensi.

---

# Non Functional Requirements

Performance:

* Waktu muat halaman < 3 detik.

Security:

* Password hashing.
* Role-based authorization.

Usability:

* Mudah digunakan mahasiswa.

Responsive:

* Desktop dan tablet friendly.

---

# MVP

* Login
* Role Management
* CRUD Produk
* Transaksi Penjualan
* Pengurangan Stok Otomatis
* Jadwal Piket
* Absensi
* Riwayat Transaksi
