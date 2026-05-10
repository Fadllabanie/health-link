<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health Links — منصة الربط الصحي</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,600,700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Cairo',sans-serif;background:linear-gradient(135deg,#eef2ff 0%,#f0fdfa 100%);color:#1f2937;min-height:100vh;display:flex;flex-direction:column}
        header{padding:1.25rem 2rem;display:flex;justify-content:space-between;align-items:center;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.05)}
        .logo{font-weight:700;font-size:1.4rem;color:#0d9488}
        .nav-links{display:flex;gap:.75rem}
        .btn{padding:.55rem 1.25rem;border-radius:.5rem;text-decoration:none;font-weight:600;font-size:.95rem;transition:all .2s;display:inline-block}
        .btn-ghost{color:#0d9488}
        .btn-ghost:hover{background:#f0fdfa}
        .btn-primary{background:#0d9488;color:#fff}
        .btn-primary:hover{background:#0f766e}
        main{flex:1;display:flex;align-items:center;justify-content:center;padding:3rem 1.5rem;text-align:center}
        .hero{max-width:780px}
        .hero h1{font-size:clamp(2rem,5vw,3.25rem);font-weight:700;line-height:1.3;margin-bottom:1.25rem;color:#0f172a}
        .hero h1 span{color:#0d9488}
        .hero p{font-size:1.15rem;color:#475569;margin-bottom:2rem;line-height:1.8}
        .cta{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap}
        .btn-lg{padding:.85rem 2rem;font-size:1.05rem}
        .features{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;margin-top:3.5rem;text-align:right}
        .feature{background:#fff;padding:1.5rem;border-radius:.75rem;box-shadow:0 1px 3px rgba(0,0,0,.05);border:1px solid #e5e7eb}
        .feature h3{font-size:1.1rem;color:#0f172a;margin-bottom:.5rem}
        .feature p{font-size:.95rem;color:#64748b;margin:0}
        footer{padding:1.5rem;text-align:center;color:#94a3b8;font-size:.9rem}
    </style>
</head>
<body>
    <header>
        <div class="logo">Health Links</div>
        <nav class="nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">لوحة التحكم</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">تسجيل الدخول</a>
             
            @endauth
        </nav>
    </header>

    <main>
        <div class="hero">
            <h1>منصة <span>Health Links</span> للربط الصحي</h1>
            <p>نظام متكامل لإدارة المستشفيات والعيادات والصيدليات — سجلات طبية، وصفات إلكترونية، ومتابعة المرضى عبر رمز QR موحّد.</p>
            <div class="cta">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">الذهاب إلى لوحة التحكم</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">ابدأ الآن</a>
                @endauth
            </div>

            <div class="features">
                <div class="feature">
                    <h3>السجلات الطبية</h3>
                    <p>توثيق آمن للزيارات والتشخيصات والمرفقات الطبية.</p>
                </div>
                <div class="feature">
                    <h3>الوصفات الإلكترونية</h3>
                    <p>إصدار الوصفات وصرفها عبر شبكة الصيدليات.</p>
                </div>
                <div class="feature">
                    <h3>رمز المريض QR</h3>
                    <p>وصول فوري للسجل الطبي عبر رمز موحّد.</p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        © {{ date('Y') }} Health Links — جميع الحقوق محفوظة
    </footer>
</body>
</html>
