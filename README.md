# 📊 MyApp Ledger

ระบบบันทึกรายรับ-รายจ่าย พัฒนาด้วย Laravel รองรับการจัดการข้อมูลทางการเงินส่วนบุคคล พร้อมระบบกำหนดสิทธิ์ผู้ใช้งาน และหน้าจอ Responsive รองรับทั้ง Desktop และ Mobile

## ✨ ฟีเจอร์หลัก

* 📈 Dashboard แสดงสรุปรายรับ-รายจ่าย พร้อมกราฟวิเคราะห์ข้อมูล
* 📝 จัดการรายการรายรับ-รายจ่าย (เพิ่ม แก้ไข ลบ ดูข้อมูล)
* 📷 รองรับการอัปโหลดรูปภาพใบเสร็จ
* 👥 ระบบจัดการผู้ใช้งานและสิทธิ์การเข้าถึง

  * ผู้ใช้ทั่วไป: ดูและจัดการข้อมูลของตนเอง
  * Super User: จัดการผู้ใช้ กลุ่มผู้ใช้ และดูข้อมูลของทุกคนในระบบ
* 🌗 รองรับ Dark Mode / Light Mode
* 📱 Responsive Design รองรับทุกขนาดหน้าจอ

## 🛠️ Tech Stack

* Laravel 11 (PHP 8.2+)
* MySQL
* Tailwind CSS
* Alpine.js
* Vite
* Chart.js

## 🚀 การติดตั้ง

```bash
composer install
npm install

cp .env.example .env

php artisan key:generate
php artisan migrate --seed

php artisan serve
npm run dev
```

ตั้งค่าฐานข้อมูลในไฟล์ `.env` ให้ถูกต้องก่อนรันคำสั่ง `migrate`

## 🔑 บัญชีทดสอบ

### Super User

* Username: `admin`
* Password: `password`

### User

* Username: `user`
* Password: `password`

## 📂 โครงสร้างหลัก

* `routes/web.php` — Routing ของระบบ
* `app/Http/Controllers` — Business Logic
* `app/Models` — Model ของฐานข้อมูล
* `app/Http/Middleware` — Middleware และการตรวจสอบสิทธิ์
* `resources/views` — Blade Templates สำหรับ UI
