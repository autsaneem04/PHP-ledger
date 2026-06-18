# 📊 MyApp Ledger - ระบบบันทึกรายรับ-รายจ่าย

**MyApp Ledger** เป็นระบบจัดการและบันทึกบัญชีรายรับ-รายจ่ายที่พัฒนาด้วย **Laravel** พร้อมระบบการจัดการสิทธิ์การเข้าถึงสำหรับผู้ดูแลระบบ (Super User) และผู้ใช้งานทั่วไป หน้าเว็บออกแบบมาให้สวยงาม ทันสมัย รองรับ Responsive สำหรับทุกอุปกรณ์ และรองรับการสลับธีม Dark / Light Mode

---

## ✨ คุณสมบัติเด่น (Key Features)

*   **📈 แดชบอร์ดสรุปยอดเงิน (Dashboard & Charts):** แสดงกราฟแท่ง (Bar Chart) วิเคราะห์สัดส่วนรายรับและรายจ่าย พร้อมฟิลเตอร์ตัวกรองช่วงเวลา (Date Filter)
*   **📝 ระบบจัดการรายรับ-รายจ่าย (Ledger CRUD):** บันทึกยอดเงิน ประเภท รายละเอียด พร้อมความสามารถในการอัปโหลดรูปภาพใบเสร็จ
*   **👥 ระบบจัดการผู้ใช้งานและกลุ่มสิทธิ์ (User & Group Management):** 
    *   **ผู้ใช้ทั่วไป:** จัดการและดูข้อมูลเฉพาะบัญชีของตนเอง
    *   **ผู้ดูแลระบบ (Super User):** สามารถจัดการกลุ่มผู้ใช้งาน (Group Users) ผู้ใช้ทั้งหมด (Users) และเลือกดูรายรับ-รายจ่ายของทุกคนในระบบแบบเจาะจงได้
*   **🌗 ระบบสลับโหมดหน้าจอ (Dark / Light Mode):** ปรับเปลี่ยนหน้าตาเว็บให้สอดคล้องกันทุกส่วน พร้อมระบบจดจำสถานะโหมดล่าสุดผ่าน Local Storage
*   **📱 รองรับอุปกรณ์เคลื่อนที่ (Responsive UI):** แสดงผลได้อย่างสวยงามทั้งบน Desktop, Tablet และ Mobile พร้อมระบบ Hamburger Menu ด้านข้าง

---

## 🛠️ เทคโนโลยีที่เลือกใช้ (Tech Stack)

*   **Backend Framework:** Laravel 11 (PHP 8.2+)
*   **Database:** MySQL
*   **Frontend UI & Styling:** Tailwind CSS
*   **Frontend Logic:** Alpine.js (สำหรับ Dynamic UI และสลับโหมดหน้าจอ)
*   **Asset Bundler:** Vite
*   **Data Visualization:** Chart.js (แสดงผลกราฟแดชบอร์ด)

---

## 🚀 ขั้นตอนการติดตั้งและเริ่มต้นใช้งาน (Installation & Setup)

ทำตามขั้นตอนด้านล่างนี้เพื่อรันระบบในเครื่องจำลอง (Local Development) ของคุณ:

### 1. โคลนโปรเจกต์และเตรียมโฟลเดอร์
```bash
# เข้าไปในโฟลเดอร์โปรเจกต์
cd myapp
```

### 2. ติดตั้ง Dependencies ของระบบ
```bash
# ติดตั้ง PHP packages ด้วย Composer
composer install

# ติดตั้ง Node.js packages ด้วย npm
npm install
```

### 3. ตั้งค่าสภาพแวดล้อม (Environment Config)
คัดลอกไฟล์ `.env.example` เป็น `.env` และกำหนดค่าที่เกี่ยวข้อง:
```bash
cp .env.example .env
```
เปิดไฟล์ `.env` เพื่อตั้งค่าการเชื่อมต่อฐานข้อมูล MySQL (ตามความเหมาะสมของเครื่องท่าน):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myapp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. สร้าง App Key และตั้งค่าฐานข้อมูล
```bash
# สร้างคีย์สำหรับระบบ
php artisan key:generate

# ทำการสร้างตารางและใส่ข้อมูลตั้งต้น (Seed)
php artisan migrate --seed
```

### 5. เปิดใช้งานระบบ
ในการทำงานร่วมกันระหว่าง Backend และ Frontend จำเป็นต้องรันเซิร์ฟเวอร์ทั้งสองตัวดังนี้:

*   **รัน Laravel Server:**
    ```bash
    php artisan serve
    ```
    *(เข้าใช้งานระบบผ่าน: [http://127.0.0.1:8000](http://127.0.0.1:8000))*

*   **รัน Vite Compiler (สำหรับ Compile CSS/JS):**
    ```bash
    npm run dev
    ```
    > ⚠️ **ข้อสำคัญ:** ห้ามปิดหน้าต่าง `npm run dev` นี้เด็ดขาดในขณะใช้งาน มิฉะนั้นหน้าตาเว็บ ไซด์บาร์ และฟังก์ชันเปลี่ยนธีมอาจทำงานผิดพลาด

---

## 📂 โครงสร้างไฟล์ที่สำคัญสำหรับนักพัฒนา (Key Folders)

*   `routes/web.php` : ไฟล์จัดการ Routing และ URL ทั้งหมดของระบบ
*   `app/Http/Controllers/` : ตัวควบคุม Logic หลักการประมวลผล (เช่น `LedgerController`, `UserController`)
*   `app/Http/Middleware/` : ตัวกรองสิทธิ์การเข้าถึง เช่น `SuperUserMiddleware` สำหรับป้องกันหลังบ้านของ Super User
*   `app/Models/` : ตัวแทนของตารางใน Database (เช่น `User`, `Ledger`, `GroupUser`)
*   `resources/views/` : ไฟล์สไตล์ Blade PHP สำหรับแสดงผลหน้าจอ (Layout หลักอยู่ที่ `layouts/app.blade.php`)

---

## 🔑 บัญชีเข้าใช้งานสำหรับทดสอบระบบ (Seed Accounts)

ระบบใช้ **Username** ในการเข้าสู่ระบบ สามารถใช้ข้อมูลจำลองด้านล่างนี้เพื่อทดสอบได้ทันทีหลังรันคำสั่ง Seed:

*   **ผู้ดูแลระบบ (Super User):**
    *   **Username:** `admin` (อีเมล: `admin@example.com`)
    *   **Password:** `password`
*   **ผู้ใช้งานทั่วไป:**
    *   **Username:** `user` (อีเมล: `user@example.com`)
    *   **Password:** `password`

---

## 📝 คู่มือการทดสอบระบบเพิ่มเติม
สามารถอ่านเงื่อนไขการทดสอบฟังก์ชันต่างๆ เพิ่มเติมได้ที่ไฟล์ [testcases.txt](file:///d:/work_space/web/myapp/testcases.txt) ในโปรเจกต์นี้
