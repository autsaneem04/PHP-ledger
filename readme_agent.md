# 📘 คู่มือโปรเจกต์ MyApp (สำหรับนักพัฒนาและ AI Agent)

คู่มือนี้สรุปโครงสร้างและการทำงานของระบบ "MyApp" เขียนขึ้นเพื่อให้นักพัฒนามือใหม่ และ AI สามารถอ่านและเข้าใจภาพรวมของโปรเจกต์ได้อย่างรวดเร็ว

---

## 🚀 1. ภาพรวมของโปรเจกต์ (Overview)
โปรเจกต์นี้เป็นระบบ **บันทึกรายรับ-รายจ่าย (Ledger)** ที่พัฒนาด้วย **Laravel** โดยมีระบบจัดการผู้ใช้งานและสิทธิ์การเข้าถึง 

**เทคโนโลยีที่ใช้ (Tech Stack):**
*   **Backend:** Laravel (PHP)
*   **Database:** MySQL
*   **Frontend Styling:** Tailwind CSS (สำหรับจัดหน้าตาเว็บ)
*   **Frontend Logic:** Alpine.js (สำหรับทำลูกเล่นหน้าเว็บ เช่น เปิด/ปิดเมนู, สลับ Dark Mode)
*   **Asset Bundler:** Vite (ตัวจัดการไฟล์ CSS/JS)
*   **Charts:** Chart.js (สำหรับแสดงกราฟในหน้า Dashboard)

---

## 📂 2. โครงสร้างไฟล์ที่สำคัญ (Key Folders)
หากต้องการแก้ไขโค้ด นี่คือตำแหน่งหลักๆ ที่คุณต้องรู้:
*   `routes/web.php` 👉 กำหนดว่า URL ไหน จะเรียกใช้งานไฟล์อะไร
*   `app/Http/Controllers/` 👉 ที่เก็บ "สมอง" ของระบบ (ควบคุมการทำงาน เช่น ดึงข้อมูล, บันทึกข้อมูล)
*   `app/Models/` 👉 ที่เก็บตัวแทนของ "ตารางฐานข้อมูล" (ใช้ดึงข้อมูลจาก Database)
*   `resources/views/` 👉 ที่เก็บ "หน้าตาเว็บ" (ไฟล์ `.blade.php`)
    *   `resources/views/layouts/app.blade.php` 👉 โครงร่างหลักของเว็บ (มีแถบเมนูบน และ Sidebar ด้านข้าง)

---

## 🗄️ 3. ระบบฐานข้อมูล (Database Schema)
ระบบมีตารางหลักๆ 3 ตารางที่ทำงานร่วมกัน:

1.  **`group_users` (กลุ่มผู้ใช้):** ใช้กำหนดสิทธิ์ 
    *   ถ้า `is_super_user` = 1 แปลว่าเป็น แอดมิน (Super User)
2.  **`users` (ผู้ใช้งาน):** เก็บข้อมูลคนใช้ระบบ (ชื่อ, รหัสผ่าน)
    *   แต่ละคนจะถูกจัดให้อยู่ใน `group_users` (`group_user_id`)
3.  **`ledgers` (รายรับ-รายจ่าย):** เก็บข้อมูลการเงิน
    *   ผูกกับผู้ใช้ผ่าน `user_id` (ใครเป็นคนบันทึก)
    *   มี `type` แยกประเภท (`income` = รายรับ, `expense` = รายจ่าย)
    *   อัปโหลดรูปใบเสร็จได้ (`image`)

---

## 🔐 4. สิทธิ์การใช้งาน (Roles & Middleware)
ระบบมีการแบ่งสิทธิ์การเข้าถึงหน้าต่างๆ เพื่อความปลอดภัย ผ่านสิ่งที่เรียกว่า "Middleware":

*   **🟢 สิทธิ์ผู้ใช้ทั่วไป (Middleware: `auth`)**
    *   ดูหน้า Dashboard (`/dashboard`)
    *   จัดการบัญชีรายรับ-รายจ่ายของตัวเอง (`/ledgers`)
    *   แก้ไขโปรไฟล์ส่วนตัว (`/profile`)
*   **🔴 สิทธิ์ผู้ดูแลระบบ / Super User (Middleware: `super`)**
    *   ทำได้ทุกอย่างเหมือนผู้ใช้ทั่วไป
    *   **เพิ่มพิเศษ:** เข้าถึงเมนูจัดการตั้งค่าระบบ (`/group_users` และ `/users`)
    *   *หมายเหตุ: สิทธิ์นี้ดูจากตาราง `group_users.is_super_user`*

---

## 🎨 5. การปรับแต่งหน้าตา (Frontend & Styling)
เว็บนี้ใช้ Tailwind CSS และอิงโครงสร้างหลักจาก `layouts/app.blade.php`

**กฎเหล็ก 2 ข้อในการทำ Frontend:**
1.  **ต้องเปิด Vite ไว้เสมอ:** ขณะเขียนโค้ด ต้องพิมพ์คำสั่ง `npm run dev` ใน Terminal ทิ้งไว้ เพื่อให้ระบบอัปเดตหน้าตาเว็บให้ใหม่ตลอดเวลา หากปิดไว้หน้าเว็บจะพัง (Sidebar หาย, เปลี่ยนธีมไม่ได้)
2.  **วิธีเขียน CSS/JS เพิ่มเติมในหน้าต่างๆ:**
    ในแต่ละหน้าย่อย (เช่น `dashboard.blade.php`) หากต้องการเขียน CSS หรือ Script เฉพาะหน้านั้น ให้ใส่ไว้ใน Block นี้:
    
    ```blade
    {{-- สำหรับใส่ CSS วางไว้ตรงไหนก็ได้ในไฟล์ --}}
    @push('styles')
    <style>
        .my-box { background-color: red; }
    </style>
    @endpush

    {{-- สำหรับใส่ JavaScript วางไว้ตรงไหนก็ได้ในไฟล์ --}}
    @push('scripts')
    <script>
        alert("โหลดหน้าเสร็จแล้ว!");
    </script>
    @endpush
    ```

---

## 🤖 6. AI Prompting Guide (สำหรับ AI Agent)
*ข้อความส่วนนี้สำหรับ AI เพื่อใช้เป็น Context ในการเขียนโค้ดครั้งต่อไป*

*   **Architecture:** It's a standard Laravel MVC application.
*   **Auth System:** Uses standard Laravel Auth for authentication. Uses custom `super` middleware for authorization (Admin access).
*   **Frontend Approach:** 
    *   Use **Tailwind CSS utility classes** for ALL styling. Avoid writing custom CSS unless absolutely necessary.
    *   Use **Alpine.js** (`x-data`, `x-show`, etc.) for lightweight interactions. Avoid jQuery.
    *   The main layout `<x-app-layout>` provides `@stack('styles')` in the `<head>` and `@stack('scripts')` before `</body>`. Use `@push` to inject page-specific assets.
*   **Database:** The system does NOT use queues (queue connection is `sync` and job migrations were removed). Do not suggest queue implementations.
*   **Relationships:** `User` `belongsTo` `GroupUser`. `Ledger` `belongsTo` `User`.
*   **Check Super User:** Use `$user->isSuperUser()` method defined in the `User` model.
