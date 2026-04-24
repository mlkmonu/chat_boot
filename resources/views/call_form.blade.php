<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Voice Call</title>
    <style>
        :root {
            --bg-start: #f3ede4;
            --bg-end: #efe2d2;
            --panel: rgba(255, 251, 245, 0.88);
            --panel-line: rgba(103, 77, 45, 0.12);
            --text: #1f1a15;
            --muted: #6e6257;
            --accent: #b75b2c;
            --accent-deep: #8e3f18;
            --success-bg: #e8f6ec;
            --success-text: #22613a;
            --error-bg: #fff0ef;
            --error-text: #972f2f;
            --shadow: 0 28px 70px rgba(94, 63, 31, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, "Times New Roman", serif;
            background:
                radial-gradient(circle at top left, rgba(231, 189, 142, 0.45), transparent 30%),
                radial-gradient(circle at bottom right, rgba(181, 120, 75, 0.22), transparent 28%),
                linear-gradient(135deg, var(--bg-start), var(--bg-end));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            color: var(--text);
        }

        .container {
            background: var(--panel);
            padding: 34px;
            border-radius: 28px;
            box-shadow: var(--shadow);
            border: 1px solid var(--panel-line);
            width: 100%;
            max-width: 510px;
            backdrop-filter: blur(18px);
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 14px;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(183, 91, 44, 0.1);
            color: var(--accent-deep);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: clamp(34px, 4vw, 46px);
            line-height: 0.98;
            color: var(--text);
        }

        .subtitle {
            margin: 16px 0 30px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 15px;
        }

        .info-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .info-card {
            padding: 14px 12px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.62);
            border: 1px solid rgba(110, 98, 87, 0.12);
        }

        .info-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: var(--text);
        }

        .info-card span {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        form {
            padding: 22px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(110, 98, 87, 0.1);
        }

        input[type="text"] {
            width: 100%;
            padding: 16px 18px;
            margin: 8px 0 18px 0;
            border-radius: 16px;
            border: 1px solid rgba(110, 98, 87, 0.18);
            font-size: 16px;
            background: rgba(255, 253, 250, 0.96);
            color: var(--text);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input[type="text"]:focus {
            border-color: rgba(183, 91, 44, 0.5);
            box-shadow: 0 0 0 4px rgba(183, 91, 44, 0.08);
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--accent), var(--accent-deep));
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 16px 28px rgba(183, 91, 44, 0.24);
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(183, 91, 44, 0.3);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 16px;
            margin-bottom: 16px;
            font-weight: 500;
            line-height: 1.5;
        }

        .success {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid rgba(34, 97, 58, 0.12);
        }

        .error {
            background-color: var(--error-bg);
            color: var(--error-text);
            border: 1px solid rgba(151, 47, 47, 0.12);
        }

        label {
            font-weight: 600;
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 15px;
        }

        .hint {
            margin-top: 14px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        @media (max-width: 640px) {
            .container {
                padding: 22px;
                border-radius: 22px;
            }

            .info-strip {
                grid-template-columns: 1fr;
            }

            form {
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="eyebrow">Voice Assistant</div>
        <h1>Launch an AI-powered call</h1>
        <p class="subtitle">
            Enter a phone number and let your Twilio + Gemini voice assistant start a live conversation with a polished call flow.
        </p>

        <div class="info-strip">
            <div class="info-card">
                <strong>Instant Call</strong>
                <span>Trigger an outbound AI call in one step.</span>
            </div>
            <div class="info-card">
                <strong>Hindi Voice</strong>
                <span>Assistant speaks naturally during the call.</span>
            </div>
            <div class="info-card">
                <strong>Live Replies</strong>
                <span>User questions are answered dynamically.</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        <form action="/call" method="POST">
            @csrf
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="+911234567890" required>
            <button type="submit">Start AI Call</button>
            <p class="hint">
                Use international format with country code, for example `+91...`, so Twilio can place the call correctly.
            </p>
        </form>
    </div>
</body>
</html>
