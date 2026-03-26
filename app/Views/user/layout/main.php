<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LongWela | User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg-color: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: #0f172a; color: var(--text-primary); padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .glass-card { background: var(--glass); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
