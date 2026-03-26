<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LongWela | Time Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-color: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --text-primary: #f8fafc;
        }

        body {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a 70%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            animation: fadeIn 0.8s ease-out;
        }

        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .clock {
            font-size: 4rem;
            font-weight: 700;
            margin: 20px 0;
            letter-spacing: 2px;
            background: linear-gradient(to right, #60a5fa, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .date-display {
            font-size: 1.1rem;
            opacity: 0.7;
            margin-bottom: 40px;
        }

        .buttons-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 40px;
        }

        .btn {
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: white;
        }

        .btn-in {
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-in:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
        }

        .btn-out {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-out:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
        }

        .history-section {
            text-align: left;
            margin-top: 40px;
            border-top: 1px solid var(--glass-border);
            padding-top: 30px;
        }

        .history-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .history-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            padding: 12px 20px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .type-badge {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 100px;
            font-weight: 600;
        }

        .badge-in { background: rgba(16, 185, 129, 0.1); color: #34d399; }
        .badge-out { background: rgba(239, 68, 68, 0.1); color: #f87171; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<div class="container">
