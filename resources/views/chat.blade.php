<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Call Assistant</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f5e0cc;
            --card: #fff8f2;
            --primary: #d56d1e;
            --primary-dark: #af5116;
            --text: #1f1510;
            --muted: #6b5b4c;
            --border: #e8ddd2;
            --success: #d9eed8;
            --eyebrow-bg: #f4dcbe;
            --eyebrow-text: #a45d2d;
            --feature-bg: #fff8f5;
            --feature-border: #efe4db;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #f4e7d9 0%, #f1e3d6 100%);
            color: var(--text);
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            width: 420px;
            background: var(--card);
            border-radius: 32px;
            padding: 32px;
            box-shadow: 0 18px 48px rgba(82, 62, 41, 0.12);
        }

        .eyebrow {
            display: inline-block;
            background: var(--eyebrow-bg);
            color: var(--eyebrow-text);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 14px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            margin: 10px 0;
            line-height: 1.15;
            color: #1b1009;
        }

        p {
            font-size: 14px;
            color: var(--muted);
            max-width: 420px;
            line-height: 1.8;
        }

        .features {
            display: flex;
            gap: 12px;
            margin: 22px 0;
        }

        .feature-box {
            flex: 1;
            background: var(--feature-bg);
            border: 1px solid var(--feature-border);
            padding: 14px;
            border-radius: 16px;
            font-size: 13px;
            color: var(--muted);
        }

        .feature-box strong {
            display: block;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 13px;
        }

        .success {
            background: var(--success);
            color: #2b5b39;
            padding: 14px;
            border-radius: 16px;
            font-size: 13px;
            margin-bottom: 18px;
            border: 1px solid rgba(40, 125, 71, 0.14);
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #3f2d20;
        }

        input {
            width: 100%;
            height: 48px;
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 0 14px;
            margin: 10px 0 18px;
            font-size: 14px;
            background: #fff;
            color: #4d3f30;
        }

        button {
            width: 100%;
            height: 48px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

<div class="page">
    <div class="card">

        <div class="eyebrow">VOICE ASSISTANT</div>

        <h1>Launch an AI-powered call</h1>

        <p>
            Enter a phone number and let your AI assistant start a live conversation.
        </p>

        <div class="features">
            <div class="feature-box">
                <strong>Instant Call</strong><br>
                Trigger AI call instantly
            </div>
            <div class="feature-box">
                <strong>Hindi Voice</strong><br>
                Natural speaking
            </div>
            <div class="feature-box">
                <strong>Live Replies</strong><br>
                Dynamic answers
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="/call">
            @csrf

            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="+911234567890" required>

            <button type="submit">Start AI Call</button>
        </form>

    </div>
</div>

</body>
</html>