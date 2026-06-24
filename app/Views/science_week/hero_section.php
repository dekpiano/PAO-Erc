<?php
/**
 * Hero Section for Science Week Portal
 * Aligns with the bright playful theme and logo.
 */
?>
<div class="flex flex-col lg:flex-row items-center justify-between gap-12 py-12 lg:py-24">
    <div class="lg:w-3/5 space-y-6 text-center lg:text-left scroll-reveal-left" data-delay="0">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-sm font-semibold tracking-wider uppercase">
            <i data-lucide="sparkles" class="w-4 h-4 text-cyan-400 animate-pulse"></i> Science &amp; Technology Week 2026
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-none text-white">
            สัปดาห์วิทยาศาสตร์ <br/>
            <span class="bg-gradient-to-r from-cyan-400 via-yellow-300 via-emerald-400 via-purple-400 to-rose-500 bg-clip-text text-transparent leading-normal">
                สนุกคิด ติดปีกจินตนาการ
            </span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl leading-relaxed font-medium">
            เปิดโลกแห่งการเรียนรู้ยุคใหม่ผ่านแนวคิด <strong>STEAM Education</strong> ผสมผสานวิทยาศาสตร์ เทคโนโลยี วิศวกรรมศาสตร์ ศิลปะ และคณิตศาสตร์ เพื่อปลุกจินตนาการและความสร้างสรรค์อย่างไร้ขีดจำกัด
        </p>
        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
            <a href="<?php echo base_url('science-week/register'); ?>" class="px-8 py-4 rounded-xl text-white font-bold neon-btn-primary flex items-center gap-3">
                <i data-lucide="clipboard-edit" class="w-5 h-5"></i> สมัครแข่งขันออนไลน์
            </a>
            <a href="#activities" class="px-6 py-4 rounded-xl bg-slate-800/80 border border-slate-700 text-slate-300 font-semibold hover:bg-slate-700/80 hover:text-white transition-all">
                ดูรายละเอียดกิจกรรม
            </a>
        </div>
    </div>
    <div class="lg:w-2/5 flex justify-center scroll-reveal-scale" data-delay="200">
        <div class="relative group floating-logo">
            <div class="absolute -inset-1.5 bg-gradient-to-r from-cyan-400 via-yellow-400 via-emerald-400 via-purple-500 to-rose-500 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
            <img src="<?php echo base_url('uploads/science_week/logo/S__49446940.jpg'); ?>" alt="STEAM Logo" class="relative w-80 h-80 rounded-full border-4 border-slate-900 object-cover shadow-2xl">
        </div>
    </div>
</div>
