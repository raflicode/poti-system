# PROJECT CONTEXT

## Project Name

POTI System (Pojok TI HMJ TI)

## Overview

POTI System adalah aplikasi kasir dan manajemen operasional untuk Pojok TI (POTI) milik HMJ TI.

Aplikasi ini bertujuan untuk menggantikan proses pencatatan manual menjadi sistem digital yang lebih cepat, akurat, dan mudah digunakan oleh mahasiswa.

## Main Problems

Saat ini POTI masih menggunakan sistem manual:

* Harga produk dicari dari daftar kertas.
* Perhitungan total dan kembalian dilakukan manual.
* Stok barang dihitung secara manual.
* Tidak ada laporan penjualan otomatis.
* Jadwal piket tidak terintegrasi.
* Sulit mengetahui siapa yang melakukan transaksi.

## Goals

* Mempermudah transaksi penjualan.
* Mengotomatisasi pengurangan stok.
* Menyediakan laporan transaksi.
* Mengelola jadwal piket.
* Mengelola absensi petugas.
* Menyediakan dashboard sederhana bagi admin.

## Tech Stack

Frontend:

* Vue 3
* Vue Router
* Pinia
* Axios
* Tailwind CSS

Backend:

* Laravel 12
* Laravel Sanctum

Database:

* MySQL

Version Control:

* Git
* GitHub

## User Roles

### Admin

Bertugas mengelola sistem.

Hak akses:

* Dashboard
* CRUD Produk
* Kelola Stok
* Lihat Transaksi
* Kelola Jadwal Piket
* Lihat Absensi

### Piket

Bertugas melakukan penjualan.

Hak akses:

* Login
* Melihat Jadwal
* Absensi
* Transaksi Penjualan
* Riwayat Transaksi

## Design Principles

* Clean UI
* Human centered
* Fast cashier workflow
* Responsive
* Modern campus application
* Light theme
* White background
* Soft gray surfaces
* Blue accent color
* Easy to use for students

## Project Scope

Version 1 (MVP):

* Authentication
* Role Management
* Product Management
* Transaction System
* Stock Management
* Schedule Management
* Attendance
* Transaction History

Future Version:

* QRIS Payment
* Export PDF
* Export Excel
* Analytics Dashboard
* PWA Support
