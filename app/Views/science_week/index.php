<?= $this->extend('science_week/layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.35) 0%, rgba(118, 75, 162, 0.35) 25%, rgba(240, 147, 251, 0.2) 50%, rgba(245, 87, 108, 0.25) 75%, rgba(253, 160, 133, 0.25) 100%);
        background-size: 400% 400%;
        animation: heroGradientShift 12s ease infinite;
    }
    @keyframes heroGradientShift {
        0% { background-position: 0% 50%; }
        25% { background-position: 50% 100%; }
        50% { background-position: 100% 50%; }
        75% { background-position: 50% 0%; }
        100% { background-position: 0% 50%; }
    }

    /* Animated mesh overlay */
    .hero-mesh {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 40%),
            radial-gradient(ellipse at 50% 80%, rgba(0,0,0,0.2) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Floating shapes */
    .hero-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.12;
        filter: blur(1px);
        animation: shapeFloat 20s ease-in-out infinite;
        pointer-events: none;
    }
    .hero-shape-1 { width: 300px; height: 300px; top: -50px; left: -80px; background: #fff; animation-delay: 0s; }
    .hero-shape-2 { width: 200px; height: 200px; bottom: -40px; right: -60px; background: #fbbf24; animation-delay: -5s; animation-duration: 25s; }
    .hero-shape-3 { width: 150px; height: 150px; top: 40%; right: 10%; background: #34d399; animation-delay: -10s; animation-duration: 18s; }
    .hero-shape-4 { width: 100px; height: 100px; bottom: 20%; left: 15%; background: #60a5fa; animation-delay: -3s; animation-duration: 22s; }
    .hero-shape-5 { width: 80px; height: 80px; top: 20%; left: 40%; background: #f472b6; animation-delay: -7s; animation-duration: 16s; }
    @keyframes shapeFloat {
        0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
        25% { transform: translate(30px, -40px) rotate(90deg) scale(1.1); }
        50% { transform: translate(-20px, 20px) rotate(180deg) scale(0.95); }
        75% { transform: translate(40px, 10px) rotate(270deg) scale(1.05); }
    }

    /* Hero content animations */
    .hero-badge {
        animation: heroBadgeIn 0.8s cubic-bezier(0.16,1,0.3,1) 0.2s both;
    }
    .hero-title {
        animation: heroTitleIn 1s cubic-bezier(0.16,1,0.3,1) 0.4s both;
    }
    .hero-subtitle {
        animation: heroSubtitleIn 1s cubic-bezier(0.16,1,0.3,1) 0.7s both;
    }
    .hero-cta {
        animation: heroCTAIn 1s cubic-bezier(0.16,1,0.3,1) 1s both;
    }
    .hero-logo-wrap {
        animation: heroLogoIn 1.2s cubic-bezier(0.16,1,0.3,1) 0.5s both;
    }
    .hero-stats {
        animation: heroStatsIn 1s cubic-bezier(0.16,1,0.3,1) 1.2s both;
    }
    @keyframes heroBadgeIn {
        from { opacity: 0; transform: translateY(-30px) scale(0.8); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes heroTitleIn {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes heroSubtitleIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes heroCTAIn {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes heroLogoIn {
        from { opacity: 0; transform: scale(0.5) rotate(-15deg); }
        to { opacity: 1; transform: scale(1) rotate(0deg); }
    }
    @keyframes heroStatsIn {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Morphing blob behind logo */
    .morph-blob {
        position: absolute;
        width: 110%;
        height: 110%;
        top: -5%;
        left: -5%;
        background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03));
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        animation: morphBlob 8s ease-in-out infinite;
        filter: blur(2px);
    }
    @keyframes morphBlob {
        0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        50% { border-radius: 50% 60% 40% 50% / 30% 50% 70% 60%; }
        75% { border-radius: 40% 30% 60% 50% / 60% 40% 50% 30%; }
    }

    /* Floating logo */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    .floating-logo { animation: float 5s ease-in-out infinite; }

    /* Glow ring around logo */
    .logo-glow-ring {
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.25);
        animation: glowRingPulse 3s ease-in-out infinite;
    }
    @keyframes glowRingPulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.08); opacity: 0.6; }
    }

    /* Animated gradient text shimmer */
    .gradient-text-animate {
        background: linear-gradient(90deg, #fbbf24, #34d399, #60a5fa, #a78bfa, #f472b6, #fbbf24);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientTextShimmer 4s linear infinite;
    }
    @keyframes gradientTextShimmer {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* Hero CTA button pulse */
    .hero-btn-primary {
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.25);
        color: #fff;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .hero-btn-primary::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .hero-btn-primary:hover {
        background: rgba(255,255,255,0.25);
        border-color: #fff;
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }
    .hero-btn-primary:hover::after { opacity: 1; }

    .hero-btn-outline {
        background: transparent;
        border: 2px solid rgba(255,255,255,0.35);
        color: #fff;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hero-btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: #fff;
        transform: translateY(-2px);
    }

    /* ===== HERO MEGA REGISTER CTA ===== */
    .hero-register-wrap {
        animation: heroCTAIn 1s cubic-bezier(0.16,1,0.3,1) 0.85s both;
    }
    .hero-register-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1.1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 900;
        color: #fff;
        background: linear-gradient(135deg, #7c3aed 0%, #db2777 50%, #f43f5e 100%);
        background-size: 200% 200%;
        animation: heroRegGradient 3s ease infinite;
        border: none;
        border-radius: 1.25rem;
        cursor: pointer;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow:
            0 4px 15px rgba(124,58,237,0.4),
            0 0 40px rgba(219,39,119,0.2),
            inset 0 1px 0 rgba(255,255,255,0.2);
        text-decoration: none;
        letter-spacing: 0.02em;
        z-index: 1;
    }
    .hero-register-btn:hover {
        transform: translateY(-4px) scale(1.04);
        box-shadow:
            0 8px 30px rgba(124,58,237,0.5),
            0 0 60px rgba(219,39,119,0.3),
            inset 0 1px 0 rgba(255,255,255,0.3);
    }
    .hero-register-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 70%; height: 100%;
        background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
        animation: heroRegShimmer 2.5s ease-in-out infinite;
        z-index: 2;
    }
    @keyframes heroRegGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes heroRegShimmer {
        0% { left: -100%; }
        100% { left: 200%; }
    }
    /* Glow ring behind the button */
    .hero-register-glow {
        position: absolute;
        inset: -12px;
        border-radius: 2rem;
        background: radial-gradient(ellipse at center, rgba(219,39,119,0.25) 0%, transparent 70%);
        animation: heroRegGlowPulse 2s ease-in-out infinite alternate;
        pointer-events: none;
        z-index: 0;
    }
    @keyframes heroRegGlowPulse {
        0% { opacity: 0.3; transform: scale(0.92); }
        100% { opacity: 1; transform: scale(1.08); }
    }
    /* Sparkle dots */
    .hero-reg-sparkle {
        position: absolute;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: #fff;
        pointer-events: none;
        animation: heroSparkle 3s ease-in-out infinite;
    }
    .hero-reg-sparkle:nth-child(1) { top: -6px; left: 20%; animation-delay: 0s; }
    .hero-reg-sparkle:nth-child(2) { top: 50%; right: -6px; animation-delay: 0.6s; width: 3px; height: 3px; }
    .hero-reg-sparkle:nth-child(3) { bottom: -5px; left: 60%; animation-delay: 1.2s; width: 3px; height: 3px; }
    .hero-reg-sparkle:nth-child(4) { top: 30%; left: -5px; animation-delay: 1.8s; width: 2px; height: 2px; }
    @keyframes heroSparkle {
        0%, 100% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1.5); }
    }
    /* Divider line between register and other buttons */
    .hero-cta-divider {
        width: 1px;
        height: 44px;
        background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.4), transparent);
    }

    /* Floating STEAM icons in hero */
    .steam-float-icon {
        position: absolute;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        opacity: 0.25;
        pointer-events: none;
        animation: steamIconFloat 12s ease-in-out infinite;
    }
    .steam-float-icon:nth-child(1) { top: 12%; left: 8%; background: rgba(168,85,247,0.4); animation-delay: 0s; }
    .steam-float-icon:nth-child(2) { top: 65%; left: 5%; background: rgba(2,132,199,0.4); animation-delay: -3s; }
    .steam-float-icon:nth-child(3) { top: 25%; right: 6%; background: rgba(16,185,129,0.4); animation-delay: -6s; }
    .steam-float-icon:nth-child(4) { bottom: 15%; right: 12%; background: rgba(245,158,11,0.4); animation-delay: -2s; }
    .steam-float-icon:nth-child(5) { bottom: 30%; left: 20%; background: rgba(236,72,153,0.4); animation-delay: -8s; }
    @keyframes steamIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(8deg); }
        66% { transform: translateY(10px) rotate(-5deg); }
    }

    /* Stats counter section */
    .stat-card {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        text-align: center;
        color: #fff;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-4px);
    }

    /* Scroll indicator bounce */
    .scroll-indicator {
        animation: scrollBounce 2s ease-in-out infinite;
    }
    @keyframes scrollBounce {
        0%, 100% { transform: translateY(0); opacity: 1; }
        50% { transform: translateY(10px); opacity: 0.5; }
    }

    /* ===== MAIN CONTENT AREA ===== */
    .content-area {
        background: transparent;
        position: relative;
        overflow: hidden;
    }

    /* Particle canvas */
    #particles-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
    }

    /* Glass cards */
    .glass-sci-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(99, 102, 241, 0.12);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        color: #1e293b;
    }
    .glass-sci-card:hover {
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 20px 50px -10px rgba(99, 102, 241, 0.15);
        transform: translateY(-8px);
        border-color: var(--hover-border, rgba(99, 102, 241, 0.5)) !important;
    }
    .glass-sci-card h3 { color: #0f172a; }
    .glass-sci-card p { color: #475569; transition: color 0.3s ease; }
    .glass-sci-card:hover p { color: #1e293b; }

    /* Section heading styles */
    .section-heading {
        color: transparent;
        background-clip: text;
        -webkit-background-clip: text;
    }
    .section-heading-cyan {
        background-image: linear-gradient(135deg, #0284c7, #06b6d4);
    }
    .section-heading-purple {
        background-image: linear-gradient(135deg, #7c3aed, #a855f7);
    }

    /* Rainbow divider line */
    .rainbow-divider {
        height: 4px;
        background: linear-gradient(90deg, var(--steam-tech), var(--steam-eng), var(--steam-arts), var(--steam-science), var(--steam-math));
        background-size: 200% auto;
        border-radius: 4px;
        animation: rainbowSlide 4s linear infinite;
    }
    @keyframes rainbowSlide {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    /* === REGISTER CARD MEGA EFFECTS === */
    .register-mega-card {
        position: relative;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4c1d95 60%, #581c87 100%);
        border: none;
        isolation: isolate;
    }
    /* Animated rainbow border */
    .register-mega-card::before {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 1.6rem;
        background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899, #f43f5e, #f97316, #eab308, #22d3ee, #6366f1);
        background-size: 300% 100%;
        animation: borderRainbow 3s linear infinite;
        z-index: -2;
    }
    /* Inner dark fill */
    .register-mega-card::after {
        content: '';
        position: absolute;
        inset: 2px;
        border-radius: 1.5rem;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4c1d95 60%, #581c87 100%);
        z-index: -1;
    }
    @keyframes borderRainbow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }
    /* Shimmer sweep */
    .register-shimmer {
        position: absolute;
        inset: 0;
        border-radius: 1.5rem;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
    }
    .register-shimmer::after {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 60%; height: 100%;
        background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.08) 50%, transparent 70%);
        animation: shimmerSweep 2.5s ease-in-out infinite;
    }
    @keyframes shimmerSweep {
        0% { left: -100%; }
        100% { left: 200%; }
    }
    /* Pulsing glow ring behind card */
    .register-glow-ring {
        position: absolute;
        inset: -20px;
        border-radius: 2.5rem;
        background: radial-gradient(ellipse at center, rgba(139,92,246,0.25) 0%, transparent 70%);
        animation: glowPulse 2s ease-in-out infinite alternate;
        pointer-events: none;
        z-index: -3;
    }
    @keyframes glowPulse {
        0% { opacity: 0.4; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1.05); }
    }
    /* Floating particles */
    .register-particles {
        position: absolute; inset: 0;
        overflow: hidden; border-radius: 1.5rem;
        pointer-events: none; z-index: 0;
    }
    .register-particles span {
        position: absolute;
        width: 3px; height: 3px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        animation: particleFloat 4s ease-in-out infinite;
    }
    .register-particles span:nth-child(1) { top: 20%; left: 15%; animation-delay: 0s; animation-duration: 3.5s; }
    .register-particles span:nth-child(2) { top: 60%; left: 75%; animation-delay: 0.8s; animation-duration: 4.2s; }
    .register-particles span:nth-child(3) { top: 80%; left: 35%; animation-delay: 1.5s; animation-duration: 3.8s; }
    .register-particles span:nth-child(4) { top: 10%; left: 85%; animation-delay: 2s; animation-duration: 4.5s; width: 2px; height: 2px; }
    .register-particles span:nth-child(5) { top: 45%; left: 50%; animation-delay: 0.3s; animation-duration: 5s; width: 4px; height: 4px; background: rgba(167,139,250,0.6); }
    @keyframes particleFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: 0.4; }
        50% { transform: translateY(-18px) scale(1.5); opacity: 1; }
    }
    /* Badge pulse */
    .badge-live {
        animation: badgePulse 1.5s ease-in-out infinite;
    }
    @keyframes badgePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(244,63,94,0.6); }
        50% { box-shadow: 0 0 12px 4px rgba(244,63,94,0.3); }
    }
    /* CTA button */
    .register-cta-btn {
        background: linear-gradient(135deg, #7c3aed, #db2777);
        transition: all 0.3s ease;
    }
    .register-cta-btn:hover {
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        transform: translateY(-1px);
        box-shadow: 0 8px 25px -5px rgba(124,58,237,0.5);
    }

    /* Glow border on hover */
    .glow-border {
        position: relative;
        overflow: hidden;
    }
    .glow-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(99,102,241,0.06), transparent);
        transition: left 0.6s ease;
        z-index: 0;
    }
    .glow-border:hover::before {
        left: 100%;
    }

    /* Icon container animation */
    .icon-container {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-sci-card:hover .icon-container {
        transform: scale(1.15) rotate(5deg);
    }

    /* Activity badge */
    .activity-badge {
        transition: all 0.3s ease;
    }
    .glass-sci-card:hover .activity-badge {
        transform: scale(1.05);
    }

    /* ===== SCROLL ANIMATIONS ===== */
    .scroll-reveal {
        opacity: 0;
        transform: translateY(15px);
        transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
    .scroll-reveal-left {
        opacity: 0;
        transform: translateX(-15px);
        transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .scroll-reveal-left.revealed { opacity: 1; transform: translateX(0); }
    .scroll-reveal-right {
        opacity: 0;
        transform: translateX(15px);
        transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .scroll-reveal-right.revealed { opacity: 1; transform: translateX(0); }
    .scroll-reveal-scale {
        opacity: 0;
        transform: scale(0.97);
        transition: opacity 0.6s cubic-bezier(0.16,1,0.3,1), transform 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .scroll-reveal-scale.revealed { opacity: 1; transform: scale(1); }

    /* Scroll progress bar */
    #scroll-progress {
        position: fixed;
        top: 80px;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c, #fda085);
        background-size: 200% auto;
        animation: rainbowSlide 3s linear infinite;
        z-index: 100;
        width: 0%;
        transition: width 0.05s linear;
    }

    /* Parallax orbs */
    .parallax-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform;
        transition: transform 0.1s linear;
    }
    .parallax-orb-1 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(102,126,234,0.12) 0%, transparent 70%); top: 60px; left: -100px; z-index: 1; }
    .parallax-orb-2 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(168,85,247,0.10) 0%, transparent 70%); top: 500px; right: -150px; z-index: 1; }
    .parallax-orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, rgba(52,211,153,0.10) 0%, transparent 70%); top: 1100px; left: 15%; z-index: 1; }
    .parallax-orb-4 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(244,114,182,0.10) 0%, transparent 70%); top: 1600px; right: 10%; z-index: 1; }

    /* Timeline */
    .timeline-dot {
        box-shadow: 0 0 12px 3px rgba(99,102,241, 0.3);
        transition: all 0.3s ease;
    }
    .timeline-dot:hover {
        transform: scale(1.3);
    }

    /* Countdown flip effect */
    .countdown-cell {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .countdown-cell::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0.2;
    }
    .countdown-cell:hover {
        transform: scale(1.08);
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="hero">
    <!-- Mesh overlay -->
    <div class="hero-mesh"></div>

    <!-- Animated shapes -->
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="hero-shape hero-shape-3"></div>
    <div class="hero-shape hero-shape-4"></div>
    <div class="hero-shape hero-shape-5"></div>

    <!-- Floating STEAM icons -->
    <div class="steam-float-icon"><i data-lucide="flask-conical" class="w-6 h-6"></i></div>
    <div class="steam-float-icon"><i data-lucide="cpu" class="w-6 h-6"></i></div>
    <div class="steam-float-icon"><i data-lucide="settings" class="w-6 h-6"></i></div>
    <div class="steam-float-icon"><i data-lucide="palette" class="w-6 h-6"></i></div>
    <div class="steam-float-icon"><i data-lucide="sigma" class="w-6 h-6"></i></div>

    <!-- Hero content — Centered Layout -->
    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex flex-col items-center text-center py-8 sm:py-12 space-y-7">

            <!-- Logo + Badge Row -->
            <div class="hero-badge flex flex-col items-center gap-4">
                <div class="relative floating-logo">
                    <div class="morph-blob"></div>
                    <div class="logo-glow-ring"></div>
                    <img src="<?= base_url('uploads/science_week/logo/S__49446940.jpg') ?>" 
                         alt="STEAM Science Week Logo" 
                         class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white/40 object-cover shadow-2xl z-10">
                </div>
                <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/15 backdrop-blur-md border border-white/30 text-white text-sm font-bold tracking-wider uppercase shadow-lg">
                    <i data-lucide="sparkles" class="w-4 h-4 animate-pulse"></i> STEAM Science Week 2026
                </span>
            </div>

            <!-- Title -->
            <h1 class="hero-title text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-[1.1] text-white drop-shadow-lg">
                สัปดาห์วิทยาศาสตร์ <br>
                <span class="gradient-text-animate">
                    สนุกคิด ติดปีกจินตนาการ
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="hero-subtitle text-white/80 text-base sm:text-lg max-w-2xl leading-relaxed font-medium drop-shadow">
                เปิดโลกแห่งการเรียนรู้ยุคใหม่ผ่านแนวคิด <strong class="text-white">STEAM Education</strong> ผสมผสานวิทยาศาสตร์ เทคโนโลยี วิศวกรรมศาสตร์ ศิลปะ และคณิตศาสตร์
            </p>

            <!-- Feature Highlights Row -->
            <div class="hero-subtitle flex flex-wrap items-center justify-center gap-3 sm:gap-5 text-xs sm:text-sm font-bold text-white/90">
                <span class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-yellow-500/15 border border-yellow-400/30 shadow-sm backdrop-blur-sm">
                    <i data-lucide="trophy" class="w-4 h-4 text-yellow-400"></i> ชิงถ้วยรางวัล
                </span>
                <span class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/15 border border-emerald-400/30 shadow-sm backdrop-blur-sm">
                    <i data-lucide="award" class="w-4 h-4 text-emerald-400"></i> เกียรติบัตร
                </span>
                <span class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-cyan-500/15 border border-cyan-400/30 shadow-sm backdrop-blur-sm">
                    <i data-lucide="graduation-cap" class="w-4 h-4 text-cyan-400"></i> ทุนการศึกษา
                </span>
            </div>

            <!-- Countdown -->
            <div class="hero-countdown flex flex-wrap items-center justify-center gap-3 py-2">
                <span class="text-xs font-black uppercase tracking-wider text-pink-300 flex items-center gap-1.5 bg-pink-500/10 border border-pink-500/30 px-3.5 py-1.5 rounded-full shadow-sm">
                    <span class="animate-bounce">🚀</span> เริ่มงานในอีก:
                </span>
                <div class="flex gap-2 text-center text-white text-xs font-black">
                    <div class="relative overflow-hidden px-3.5 py-2 rounded-2xl bg-gradient-to-b from-indigo-500/25 to-indigo-600/10 border border-indigo-400/40 shadow-[0_0_15px_rgba(99,102,241,0.2)] min-w-[58px]">
                        <span id="countdown-days" class="font-mono text-base text-cyan-300 tabular-nums">00</span>
                        <span class="text-[9px] text-slate-300 block mt-0.5 font-bold">วัน</span>
                    </div>
                    <div class="relative overflow-hidden px-3.5 py-2 rounded-2xl bg-gradient-to-b from-purple-500/25 to-purple-600/10 border border-purple-400/40 shadow-[0_0_15px_rgba(168,85,247,0.2)] min-w-[58px]">
                        <span id="countdown-hours" class="font-mono text-base text-purple-300 tabular-nums">00</span>
                        <span class="text-[9px] text-slate-300 block mt-0.5 font-bold">ชม.</span>
                    </div>
                    <div class="relative overflow-hidden px-3.5 py-2 rounded-2xl bg-gradient-to-b from-pink-500/25 to-pink-600/10 border border-pink-400/40 shadow-[0_0_15px_rgba(236,72,153,0.2)] min-w-[58px]">
                        <span id="countdown-minutes" class="font-mono text-base text-pink-300 tabular-nums">00</span>
                        <span class="text-[9px] text-slate-300 block mt-0.5 font-bold">นาที</span>
                    </div>
                    <div class="relative overflow-hidden px-3.5 py-2 rounded-2xl bg-gradient-to-b from-rose-500/35 to-rose-600/20 border border-rose-400/50 shadow-[0_0_20px_rgba(244,63,94,0.35)] min-w-[58px]">
                        <span id="countdown-seconds" class="font-mono text-base text-rose-300 tabular-nums animate-pulse">00</span>
                        <span class="text-[9px] text-slate-300 block mt-0.5 font-bold">วิ</span>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="hero-cta flex flex-wrap items-center justify-center gap-4 sm:gap-5 pt-2">
                <!-- Mega Register Button -->
                <div class="hero-register-wrap relative">
                    <div class="hero-register-glow"></div>
                    <a href="<?= base_url('science-week/register') ?>" class="hero-register-btn">
                        <span class="hero-reg-sparkle"></span>
                        <span class="hero-reg-sparkle"></span>
                        <span class="hero-reg-sparkle"></span>
                        <span class="hero-reg-sparkle"></span>
                        <i data-lucide="clipboard-edit" class="w-6 h-6 drop-shadow-lg relative z-10"></i>
                        <span class="relative z-10">สมัครแข่งขันเลย!</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 relative z-10"></i>
                    </a>
                </div>

                <div class="hero-cta-divider hidden sm:block"></div>

                <a href="#action-menu" class="hero-btn-primary px-7 py-4 rounded-2xl font-bold text-sm flex items-center gap-2.5 shadow-xl">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i> เมนูบริการทั้งหมด
                </a>
                <a href="#activities" class="hero-btn-outline px-5 py-4 rounded-2xl font-semibold text-sm flex items-center gap-2">
                    <i data-lucide="arrow-down" class="w-4 h-4 scroll-indicator"></i> ดูกิจกรรม
                </a>
            </div>

            <!-- Stats row -->
            <div class="hero-stats grid grid-cols-2 sm:grid-cols-4 gap-4 w-full max-w-3xl mx-auto mt-4">
                <div class="stat-card">
                    <div class="text-2xl sm:text-3xl font-black" data-count="<?= esc($stat_steam ?? 5) ?>">0</div>
                    <div class="text-xs font-bold opacity-80 mt-1 uppercase tracking-wider">สาขา STEAM</div>
                </div>
                <div class="stat-card">
                    <div class="text-2xl sm:text-3xl font-black" data-count="<?= esc($stat_comp ?? 0) ?>">0</div>
                    <div class="text-xs font-bold opacity-80 mt-1 uppercase tracking-wider">ประเภทแข่งขัน</div>
                </div>
                <div class="stat-card">
                    <div class="text-2xl sm:text-3xl font-black" data-count="<?= esc($stat_team ?? 0) ?>">0</div>
                    <div class="text-xs font-bold opacity-80 mt-1 uppercase tracking-wider">ทีมลงทะเบียน</div>
                </div>
                <div class="stat-card">
                    <div class="text-2xl sm:text-3xl font-black" data-count="<?= esc($stat_student ?? 0) ?>">0</div>
                    <div class="text-xs font-bold opacity-80 mt-1 uppercase tracking-wider">นักเรียนเข้าร่วม</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll down indicator -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 scroll-indicator">
        <div class="w-8 h-12 rounded-full border-2 border-white/40 flex items-start justify-center p-2">
            <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce"></div>
        </div>
    </div>
</section>

<!-- ==================== MAIN CONTENT ==================== -->
<div class="content-area pt-0 pb-20 relative">
    <canvas id="particles-canvas"></canvas>

    <!-- Scroll Progress Bar -->
    <div id="scroll-progress"></div>

    <!-- Parallax Orbs -->
    <div class="parallax-orb parallax-orb-1" data-speed="0.12"></div>
    <div class="parallax-orb parallax-orb-2" data-speed="0.20"></div>
    <div class="parallax-orb parallax-orb-3" data-speed="0.16"></div>
    <div class="parallax-orb parallax-orb-4" data-speed="0.24"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- ===== ANNOUNCEMENTS BOARD ===== -->
        <div id="announcements-board" class="mt-8 mb-16 scroll-reveal" data-delay="50">
            <div class="glass-sci-card rounded-[2rem] p-6 sm:p-10 border-2 border-indigo-500/20 shadow-2xl relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl translate-y-1/4 -translate-x-1/4 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 border-b border-indigo-500/10 pb-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                                    <i data-lucide="megaphone" class="w-6 h-6"></i>
                                </div>
                                ประกาศ / ข่าวสาร
                            </h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-semibold mt-2">เอกสารประกาศและข่าวสารสำคัญงานสัปดาห์วิทยาศาสตร์ ปีการศึกษา <?= $active_year ?></p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-indigo-500 dark:text-indigo-400 text-xs font-bold shadow-sm">
                            ทั้งหมด <?= isset($announcements) ? count($announcements) : 0 ?> รายการ
                        </span>
                    </div>

                    <?php if (empty($announcements)): ?>
                        <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                            <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800/50 flex items-center justify-center mb-4 border border-slate-200 dark:border-slate-700">
                                <i data-lucide="inbox" class="w-10 h-10 opacity-50"></i>
                            </div>
                            <p class="text-base font-bold text-slate-600 dark:text-slate-300">ยังไม่มีประกาศ</p>
                            <p class="text-sm">ขณะนี้ยังไม่มีไฟล์เอกสารประกาศในระบบ</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($announcements as $ann): ?>
                                <?php 
                                    $ext = pathinfo($ann['ann_file'], PATHINFO_EXTENSION);
                                    $icon = 'file';
                                    $colorClass = 'from-slate-400 to-slate-500 shadow-slate-500/30';
                                    if (in_array(strtolower($ext), ['pdf'])) {
                                        $icon = 'file-text';
                                        $colorClass = 'from-rose-400 to-pink-500 shadow-rose-500/30';
                                    } elseif (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                                        $icon = 'image';
                                        $colorClass = 'from-blue-400 to-cyan-500 shadow-blue-500/30';
                                    }
                                ?>
                                <a href="<?= base_url($ann['ann_file']) ?>" target="_blank" class="group flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800 hover:border-indigo-400 hover:bg-white dark:hover:bg-slate-800 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br <?= $colorClass ?> text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-indigo-500 transition-colors">
                                            <?= esc($ann['ann_title']) ?>
                                        </h4>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2">
                                            <span class="text-xs font-semibold text-slate-500 flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= date('d/m/Y', strtotime($ann['ann_created_at'])) ?>
                                            </span>
                                            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:border-indigo-200 dark:group-hover:border-indigo-700/50 transition-colors">
                                                <?= $ext ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors shrink-0">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== SERVICES / ACTION MENU ===== -->
        <div id="action-menu" class="mt-16 mb-12 scroll-reveal" data-delay="100">
            <div class="text-center space-y-4 mb-8">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-indigo-300 text-xs font-black uppercase tracking-widest">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5 animate-pulse"></i> Services Portal
                </span>
                <h2 class="text-3xl sm:text-4xl font-black section-heading section-heading-cyan">เมนูบริการและระบบออนไลน์</h2>
                <p class="text-slate-400 max-w-xl mx-auto text-sm font-semibold">เข้าถึงระบบการสมัคร ค้นหารายชื่อผู้มีสิทธิ์และพิมพ์บัตรประจำตัวทีม และทำแบบประเมินความพึงพอใจ</p>
                <div class="rainbow-divider max-w-20 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 max-w-7xl mx-auto">
                <!-- 1. Register (Ultra-Premium Eye-Catching Card) -->
                <div class="relative group">
                    <div class="register-glow-ring"></div>
                    <a href="<?= base_url('science-week/register') ?>" class="register-mega-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between hover:scale-105 transition-all duration-300 text-left shadow-2xl shadow-purple-950/50 hover:shadow-purple-500/30 block min-h-[260px]">
                        <!-- Shimmer sweep -->
                        <div class="register-shimmer"></div>
                        <!-- Floating particles -->
                        <div class="register-particles"><span></span><span></span><span></span><span></span><span></span></div>
                        <!-- Glow orbs -->
                        <div class="absolute -right-8 -top-8 w-28 h-28 bg-purple-500/15 rounded-full blur-2xl group-hover:scale-150 group-hover:bg-purple-400/20 transition-all duration-700"></div>
                        <div class="absolute -left-6 -bottom-6 w-20 h-20 bg-indigo-500/15 rounded-full blur-2xl group-hover:scale-125 transition-all duration-700"></div>
                        
                        <!-- OPEN NOW Badge -->
                        <span class="badge-live absolute top-4 right-4 px-3 py-1.5 rounded-full text-[10px] font-black tracking-widest bg-gradient-to-r from-rose-500 to-pink-500 text-white border border-rose-400/40 shadow-lg flex items-center gap-1.5 z-20">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            OPEN NOW
                        </span>
                        
                        <div class="space-y-4 relative z-10">
                            <div class="w-16 h-16 rounded-2xl bg-white/15 border-2 border-white/25 flex items-center justify-center text-white shadow-lg backdrop-blur-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i data-lucide="clipboard-edit" class="w-8 h-8 drop-shadow-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-md">สมัครแข่งขัน</h3>
                                <p class="text-indigo-200/90 text-xs leading-relaxed font-semibold mt-2">
                                    ลงทะเบียนข้อมูลโรงเรียน ทีม<br>และผู้เข้าแข่งขันเพื่อจองสิทธิ์เข้าร่วม
                                </p>
                            </div>
                        </div>
                        <div class="mt-5 relative z-10">
                            <span class="register-cta-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-lg">
                                <i data-lucide="rocket" class="w-4 h-4"></i> ดำเนินการสมัครเลย
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1.5"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- 2. Check Status -->
                <a href="<?= base_url('science-week/check-status') ?>" class="glass-sci-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between glow-border border-2 border-indigo-500/30 hover:border-indigo-500 hover:scale-105 transition-all text-left shadow-lg">
                    <div class="space-y-4">
                        <div class="icon-container w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/25 to-cyan-500/25 border-2 border-indigo-500/40 flex items-center justify-center text-indigo-455 dark:text-cyan-400 shadow-md">
                            <i data-lucide="search" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">ตรวจสอบสถานะ</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed font-semibold">
                            ค้นหาข้อมูลใบสมัครเพื่อเช็คสถานะการอนุมัติและรายละเอียดข้อมูลทีม
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-black text-indigo-650 dark:text-cyan-400 group">
                        <span>เช็คสถานะการสมัคร</span> <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
                    </div>
                </a>

                <!-- 3. Approved List -->
                <a href="<?= base_url('science-week/approved-list') ?>" class="glass-sci-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between glow-border border-2 border-emerald-500/30 hover:border-emerald-500 hover:scale-105 transition-all text-left shadow-lg">
                    <div class="space-y-4">
                        <div class="icon-container w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/25 to-teal-500/25 border-2 border-emerald-500/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-md">
                            <i data-lucide="users" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">รายชื่อผู้มีสิทธิ์แข่ง</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed font-semibold">
                            ตรวจสอบรายชื่อทีมที่ได้รับการอนุมัติ และพิมพ์บัตรประจำตัวทีมเข้าแข่งขัน
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-black text-emerald-600 dark:text-emerald-400 group">
                        <span>ดูรายชื่อผู้มีสิทธิ์</span> <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
                    </div>
                </a>

                <!-- 4. Results -->
                <a href="<?= base_url('science-week/results') ?>" class="glass-sci-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between glow-border border-2 border-amber-500/35 hover:border-amber-500 hover:scale-105 transition-all text-left shadow-lg">
                    <div class="space-y-4">
                        <div class="icon-container w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-500/25 to-amber-500/25 border-2 border-yellow-500/40 flex items-center justify-center text-yellow-600 dark:text-yellow-400 shadow-md">
                            <i data-lucide="award" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">ประกาศผลรางวัล</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed font-semibold">
                            สรุปและประกาศผลรางวัลชนะเลิศตามลำดับกิจกรรมแข่งขันงานวิทยาศาสตร์
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-black text-amber-600 dark:text-amber-400 group">
                        <span>เช็คผลรางวัล</span> <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
                    </div>
                </a>

                <!-- 5. Evaluation -->
                <a href="<?= base_url('science-week/evaluation') ?>" class="glass-sci-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between glow-border border-2 border-rose-500/30 hover:border-rose-500 hover:scale-105 transition-all text-left shadow-lg">
                    <div class="space-y-4">
                        <div class="icon-container w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500/25 to-pink-500/25 border-2 border-rose-500/40 flex items-center justify-center text-rose-600 dark:text-rose-455 shadow-md">
                            <i data-lucide="clipboard-list" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">ทำแบบประเมิน</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed font-semibold">
                            ร่วมทำแบบประเมินความพึงพอใจการจัดกิจกรรม เพื่อดาวน์โหลดเกียรติบัตร
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-black text-rose-650 dark:text-rose-400 group">
                        <span>ทำแบบประเมิน</span> <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- ===== ACTIVITIES HIGHLIGHTS ===== -->
        <div id="activities" class="py-16 space-y-14">
            <div class="text-center space-y-4 scroll-reveal" data-delay="0">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-50 border border-cyan-200 text-cyan-700 text-xs font-black uppercase tracking-widest">
                    <i data-lucide="zap" class="w-3.5 h-3.5"></i> Highlights
                </span>
                <h2 class="text-3xl sm:text-5xl font-black section-heading section-heading-cyan">ไฮไลท์กิจกรรมยอดฮิต</h2>
                <p class="text-slate-500 max-w-xl mx-auto font-medium">ร่วมท้าทายจินตนาการและชิงถ้วยรางวัล เกียรติบัตร และทุนการศึกษาในประเภทต่าง ๆ ตามแนวทาง STEAM</p>
                <div class="rainbow-divider max-w-24 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php if (!empty($popular_competitions)): ?>
                    <?php $delay = 100; foreach ($popular_competitions as $comp): 
                        $compColor = $comp['comp_color'] ?? '#6366f1';
                        $compIcon = $comp['comp_icon'] ?? 'award';
                        $compLevel = $comp['comp_level'] ?? 'ทุกระดับชั้น';
                    ?>
                    <?php
                    $isPortrait = false;
                    if (!empty($comp['comp_banner']) && file_exists(FCPATH . $comp['comp_banner'])) {
                        list($w, $h) = @getimagesize(FCPATH . $comp['comp_banner']);
                        if ($h > $w) {
                            $isPortrait = true;
                        }
                    }
                    ?>
                        <a href="<?= base_url('science-week/register/form?type=' . urlencode($comp['comp_name'])) ?>" class="glass-sci-card rounded-3xl p-5 flex flex-col justify-between glow-border border-2 border-slate-200/10 hover:scale-105 transition-all text-left shadow-lg scroll-reveal group" data-delay="<?= $delay ?>" style="--hover-border: <?= esc($compColor) ?>;">
                            <div class="space-y-4 w-full">
                                <?php if (!empty($comp['comp_banner']) && $isPortrait): ?>
                                    <div class="flex flex-col sm:flex-row gap-4 items-start mb-2">
                                        <div class="w-full sm:w-[100px] sm:h-[150px] h-40 shrink-0 rounded-2xl overflow-hidden border border-slate-200/20 dark:border-slate-800 shadow-inner bg-slate-950">
                                            <img src="<?= base_url($comp['comp_banner']) ?>" alt="<?= esc($comp['comp_name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        </div>
                                        <div class="flex-1 space-y-3">
                                            <div class="icon-container w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md transition-transform group-hover:scale-110" style="background: <?= esc($compColor) ?>; box-shadow: 0 0 15px <?= esc($compColor) ?>40">
                                                <i data-lucide="<?= esc($compIcon) ?>" class="w-6 h-6"></i>
                                            </div>
                                            <h3 class="text-lg font-black text-slate-800 dark:text-white"><?= esc($comp['comp_name']) ?></h3>
                                            <p class="text-slate-650 dark:text-slate-300 text-xs leading-relaxed line-clamp-3 font-semibold">
                                                <?= esc(strip_tags($comp['comp_description'] ?? 'ร่วมแข่งขันชิงถ้วยรางวัล เกียรติบัตร และทุนการศึกษา!')) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if (!empty($comp['comp_banner'])): ?>
                                        <div class="relative w-full h-36 rounded-2xl overflow-hidden mb-3 border border-slate-200/20 dark:border-slate-800 shadow-inner bg-slate-950">
                                            <img src="<?= base_url($comp['comp_banner']) ?>" alt="<?= esc($comp['comp_name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        </div>
                                    <?php endif; ?>
                                    <div class="space-y-3">
                                        <div class="icon-container w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-md transition-transform group-hover:scale-110" style="background: <?= esc($compColor) ?>; box-shadow: 0 0 15px <?= esc($compColor) ?>40">
                                            <i data-lucide="<?= esc($compIcon) ?>" class="w-6 h-6"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-800 dark:text-white"><?= esc($comp['comp_name']) ?></h3>
                                        <p class="text-slate-655 dark:text-slate-300 text-xs leading-relaxed line-clamp-3 font-semibold">
                                            <?= esc(strip_tags($comp['comp_description'] ?? 'ร่วมแข่งขันชิงถ้วยรางวัล เกียรติบัตร และทุนการศึกษา!')) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-6 flex flex-col gap-3 w-full">
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-semibold">
                                    <span>ระดับ: <?= esc($compLevel) ?></span>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-200/20 dark:border-slate-800/40 pt-3 text-[11px] text-slate-500 dark:text-slate-450 font-bold">
                                    <span>สมัครแล้ว:</span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-black font-mono"><?= esc($comp['reg_count']) ?> ทีม</span>
                                </div>
                                <div class="pt-1 flex items-center gap-1.5 text-xs font-black transition-colors" style="color: <?= esc($compColor) ?>;">
                                    <span>สมัครเข้าร่วมแข่งขัน</span> <i data-lucide="arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1"></i>
                                </div>
                            </div>
                        </a>
                    <?php $delay += 100; endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center text-slate-400 py-8 font-medium">ไม่มีข้อมูลหัวข้อการแข่งขัน</div>
                <?php endif; ?>
            </div>

            <!-- View All Competitions Button -->
            <div class="text-center pt-8 scroll-reveal" data-delay="100">
                <a href="<?= base_url('science-week/register') ?>" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-indigo-500 hover:from-cyan-600 hover:to-indigo-600 text-white font-bold text-base transition-all duration-300 shadow-lg shadow-indigo-500/20 hover:scale-105">
                     <i data-lucide="award" class="w-5 h-5"></i> ดูการแข่งขันทั้งหมด
                </a>
            </div>
        </div>

        <!-- ===== SCHEDULE / TIMELINE ===== -->
        <div class="py-16 space-y-14">
            <div class="text-center space-y-4 scroll-reveal" data-delay="0">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-50 border border-purple-200 text-purple-700 text-xs font-black uppercase tracking-widest">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> Schedule
                </span>
                <h2 class="text-3xl sm:text-5xl font-black section-heading section-heading-purple">กำหนดการกิจกรรม</h2>
                <p class="text-slate-500 max-w-xl mx-auto font-medium">ตารางแผนกำหนดการจัดงานสัปดาห์วิทยาศาสตร์และวันแข่งขัน</p>
                <div class="rainbow-divider max-w-24 mx-auto"></div>
            </div>

            <div class="max-w-3xl mx-auto relative border-l-2 border-indigo-500/20 pl-8 space-y-10">
                <?php if (empty($schedules)): ?>
                    <div class="text-slate-400 text-center py-8 font-medium">ยังไม่มีกำหนดการจัดกิจกรรม</div>
                <?php else: ?>
                    <?php $delay = 0; foreach ($schedules as $sch): 
                        $colorName = $sch['sch_color'] ?: 'cyan';
                        $colorHex = '#22d3ee';
                        if ($colorName === 'emerald') $colorHex = '#34d399';
                        elseif ($colorName === 'purple') $colorHex = '#c084fc';
                        elseif ($colorName === 'yellow') $colorHex = '#facc15';
                        elseif ($colorName === 'rose') $colorHex = '#f43f5e';
                        elseif ($colorName === 'indigo') $colorHex = '#818cf8';
                    ?>
                        <div class="relative scroll-reveal" data-delay="<?= $delay ?>">
                            <div class="absolute -left-[41px] top-1.5 w-6 h-6 rounded-full border-4 border-slate-900 shadow-md animate-pulse timeline-dot" style="background-color: <?= $colorHex ?>; box-shadow: 0 0 12px <?= $colorHex ?>;"></div>
                            <div class="glass-sci-card rounded-2xl p-6 space-y-3 shadow-md border border-slate-200/10 hover:border-indigo-500/30 transition-all">
                                <span class="font-mono text-sm font-extrabold tracking-wider" style="color: <?= $colorHex ?>;"><?= esc($sch['sch_date']) ?></span>
                                <h4 class="text-xl font-black text-slate-800 dark:text-white"><?= esc($sch['sch_title']) ?></h4>
                                <p class="text-slate-600 dark:text-slate-350 text-sm leading-relaxed font-semibold"><?= esc($sch['sch_description'] ?: '') ?></p>
                            </div>
                        </div>
                    <?php $delay += 100; endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
    // ===== PARTICLE BACKGROUND =====
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        const colors = [
            'rgba(102,126,234,0.4)', 'rgba(118,75,162,0.4)', 'rgba(240,147,251,0.3)',
            'rgba(245,87,108,0.3)', 'rgba(253,160,133,0.3)', 'rgba(52,211,153,0.3)',
            'rgba(251,191,36,0.3)'
        ];
        function resize() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2.5 + 0.5;
                this.speedX = Math.random() * 0.4 - 0.2;
                this.speedY = Math.random() * 0.4 - 0.2;
                this.opacity = Math.random() * 0.6 + 0.2;
                this.color = colors[Math.floor(Math.random() * colors.length)];
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        for (let i = 0; i < 80; i++) particles.push(new Particle());

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animateParticles);
        }
        animateParticles();
    }

    // ===== STAT COUNTER ANIMATION =====
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        let count = 0;
        const duration = 2000;
        const step = Math.max(1, Math.floor(target / (duration / 30)));
        const suffix = target >= 100 ? '+' : '+';
        function tick() {
            count = Math.min(count + step, target);
            el.textContent = count + suffix;
            if (count < target) requestAnimationFrame(tick);
        }
        // Start counting when hero loads
        setTimeout(tick, 1500);
    });

    // ===== COUNTDOWN TIMER =====
    const targetDate = new Date('<?= esc($countdown_date) ?>').getTime();
    function updateCountdown() {
        const now = new Date().getTime();
        const diff = targetDate - now;
        if (diff < 0) {
            ['days','hours','minutes','seconds'].forEach(u => {
                const el = document.getElementById('countdown-' + u);
                if (el) el.innerText = '00';
            });
            return;
        }
        const d = Math.floor(diff / (1000*60*60*24));
        const h = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
        const m = Math.floor((diff % (1000*60*60)) / (1000*60));
        const s = Math.floor((diff % (1000*60)) / 1000);
        document.getElementById('countdown-days').innerText = String(d).padStart(2,'0');
        document.getElementById('countdown-hours').innerText = String(h).padStart(2,'0');
        document.getElementById('countdown-minutes').innerText = String(m).padStart(2,'0');
        document.getElementById('countdown-seconds').innerText = String(s).padStart(2,'0');
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // ===== SCROLL-REVEAL =====
    const revealElements = document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = parseInt(entry.target.dataset.delay || '0');
                setTimeout(() => entry.target.classList.add('revealed'), delay);
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    revealElements.forEach(el => revealObserver.observe(el));

    // ===== SCROLL-DRIVEN EFFECTS =====
    const scrollProgress = document.getElementById('scroll-progress');
    const parallaxOrbs = document.querySelectorAll('.parallax-orb');
    const rocket = document.getElementById('scroll-rocket');
    const flame = document.getElementById('rocket-flame');
    let lastScrollY = window.scrollY;
    let scrollTimeout;
    let ticking = false;

    function onScroll() {
        const scrolled = window.scrollY;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (scrollHeight <= 0) return;
        const scrollPercent = scrolled / scrollHeight;

        if (scrollProgress) scrollProgress.style.width = `${scrollPercent * 100}%`;

        parallaxOrbs.forEach(orb => {
            const speed = parseFloat(orb.dataset.speed || 0.15);
            orb.style.transform = `translate3d(0, ${scrolled * speed}px, 0)`;
        });

        if (rocket && flame) {
            if (scrolled > 150) {
                rocket.style.opacity = '1';
                rocket.style.visibility = 'visible';
            } else {
                rocket.style.opacity = '0';
                rocket.style.visibility = 'hidden';
            }
            const startTop = 15, endTop = 80;
            rocket.style.top = `${startTop + (scrollPercent * (endTop - startTop))}vh`;
            const sway = Math.sin(scrollPercent * Math.PI * 6) * 45;
            rocket.style.right = `${30 + sway}px`;
            const tilt = Math.cos(scrollPercent * Math.PI * 6) * 25;
            if (scrolled > lastScrollY) {
                rocket.style.transform = `translate3d(0,0,0) rotate(${180 - tilt}deg)`;
            } else {
                rocket.style.transform = `translate3d(0,0,0) rotate(${tilt}deg)`;
            }
            flame.style.opacity = '1';
            flame.style.height = '24px';
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => { flame.style.opacity = '0'; flame.style.height = '6px'; }, 150);
        }
        lastScrollY = scrolled;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
    }, { passive: true });

    // Initial reveal
    setTimeout(() => {
        revealElements.forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                const delay = parseInt(el.dataset.delay || '0');
                setTimeout(() => el.classList.add('revealed'), delay);
                revealObserver.unobserve(el);
            }
        });
    }, 100);
</script>

<!-- Scroll Rocket Indicator -->
<div id="scroll-rocket" class="fixed flex flex-col items-center pointer-events-none transition-all duration-300 ease-out" style="right: 30px; top: 15%; z-index: 99; opacity: 0; visibility: hidden; transform: translate3d(0,0,0) rotate(0deg);">
    <div class="w-14 h-14 rounded-full bg-slate-950/90 border border-indigo-500/30 flex items-center justify-center shadow-[0_0_25px_rgba(99,102,241,0.5)] backdrop-blur-md p-1.5">
        <svg class="w-full h-full drop-shadow-[0_0_5px_rgba(34,211,238,0.6)]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 14C7 15 5 18 5 20C7.5 20 8.5 18 9 16.5" fill="#ef4444" stroke="#991b1b" stroke-width="0.5" />
            <path d="M15 14C17 15 19 18 19 20C16.5 20 15.5 18 15 16.5" fill="#ef4444" stroke="#991b1b" stroke-width="0.5" />
            <path d="M12 2C12 2 8 6 8 13C8 16.5 10 18.5 12 20.5C14 18.5 16 16.5 16 13C16 6 12 2 12 2Z" fill="url(#rocketGrad)" stroke="#312e81" stroke-width="0.5" />
            <circle cx="12" cy="10" r="2.5" fill="#e2e8f0" stroke="#1e293b" stroke-width="0.75" />
            <circle cx="11.2" cy="9.2" r="0.8" fill="#ffffff" />
            <path d="M12 2C12 2 10.5 4.5 10.5 6.5C10.5 7.5 12 8.5 12 8.5C12 8.5 13.5 7.5 13.5 6.5C13.5 4.5 12 2 12 2Z" fill="#ef4444" />
            <defs>
                <linearGradient id="rocketGrad" x1="12" y1="2" x2="12" y2="20.5" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#22d3ee" />
                    <stop offset="60%" stop-color="#6366f1" />
                    <stop offset="100%" stop-color="#4f46e5" />
                </linearGradient>
            </defs>
        </svg>
    </div>
    <div id="rocket-flame" class="w-3 h-6 bg-gradient-to-b from-amber-400 via-orange-500 to-rose-600 rounded-full blur-[1px] opacity-0 transition-all duration-150" style="transform: translateY(-5px);"></div>
</div>

<?= $this->endSection() ?>
