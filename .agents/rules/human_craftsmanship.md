# 🧠 Human-Grade Software Engineering & Design Craftsmanship Guidelines

หลักการปฏิบัติงานและมาตรฐานการเขียนโปรแกรมและการออกแบบให้มีความเป็นมืออาชีพ ประณีต และเป็นธรรมชาติเหมือน Senior Fullstack Developer & Product Designer ชั้นนำ

---

## 💻 1. แนวทางการเขียนโปรแกรม (Engineering Craft & Mindset)

1. **เข้าใจบริบทและสไตล์โค้ดเดิมอย่างลึกซึ้ง (Context & Harmony)**
   - สังเกตและเขียนโค้ดให้กลมกลืนกับโครงสร้างของระบบเดิม (Architecture, Naming Conventions, Framework idioms)
   - ไม่เขียนทับหรือรื้อโครงสร้างที่มีอยู่โดยไม่จำเป็น รักษาระบบเดิมให้ทำงานได้อย่างต่อเนื่องและเสถียร

2. **เขียนโค้ดที่อ่านง่าย ปลอดภัย และพร้อมใช้งานจริง (Production-Ready & Clean Code)**
   - ห้ามใช้ Mockup/Placeholder หลอกๆ ในฟังก์ชันหลัก โค้ดทุกบรรทัดต้องทำงานได้จริง
   - ป้องกัน Edge Cases: เช็คค่าว่าง (null/empty), Error Handling ที่ชัดเจน, ป้องกัน SQL Injection / XSS / CSRF
   - คอมเมนต์อธิบาย "ทำไม (Why)" ถึงเขียนแบบนี้ โดยเฉพาะจุดที่มีเงื่อนไขทางธุรกิจที่ซับซ้อน

3. **User-Centric & Developer-Friendly**
   - คิดเผื่อผู้ใช้เสมอ: มี Feedback ทันทีเมื่อเกิด Action (Loading, Success Toast, Error Modal)
   - คืนค่า Response หรือ Output ที่มีโครงสร้างมาตรฐาน เช่น Status Code, Metadata, Error Messages ที่สื่อความหมายชัดเจน

---

## 🎨 2. แนวทางการออกแบบ UI/UX (Design & Aesthetic Excellence)

1. **ความประณีตของสายตาและจังหวะ (Visual Hierarchy & Spacing)**
   - ใช้โทนสีที่ผ่านการคัดสรร (Curated Palettes) เช่น Slate, Indigo, Emerald, Cyan ที่ดูพรีเมียม สบายตา
   - ลำดับความสำคัญของข้อมูลชัดเจน (Typography Hierarchy: Heading, Subheading, Body, Badges)
   - การจัดวางระยะห่าง (Padding/Margin Rhythm) สม่ำเสมอ ไม่เบียดหรือโล่งจนเกินไป

2. **การตอบสนองที่มีชีวิตชีวา (Micro-Interactions & Polish)**
   - มี Hover / Active States ที่นุ่มนวล (Smooth Transitions, Scale Effect เบาๆ)
   - มี Loading States / Spinners ระหว่างรอการประมวลผล ไม่ปล่อยให้หน้าจอค้าง
   - ข้อความแจ้งเตือนที่สุภาพ ชัดเจน เข้าใจง่าย และให้คำแนะนำที่แก้ไขปัญหาได้ทันที

3. **รองรับทุกขนาดหน้าจอ (Fully Responsive & Mobile-First Touch)**
   - จัด Layout ให้ยืดหยุ่นด้วย Flexbox/Grid ที่แสดงผลสวยงามทั้งบนมือถือ, แท็บเล็ต และคอมพิวเตอร์
