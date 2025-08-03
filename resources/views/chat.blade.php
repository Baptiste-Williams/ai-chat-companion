<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keeping It Real AI (Kir-AI)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dark-mode {
    background-color: #121212 !important;
    color: #f1f1f1 !important;
}
.dark-mode .iphone-frame {
    background: #1e1e1e;
    border-color: #555;
}
.dark-mode .top-bar,
.dark-mode .bottom-bar,
.dark-mode .camera-notch {
    background: #222;
}
.dark-mode .app-title,
.dark-mode .contacts-label,
.dark-mode .persona-selector,
.dark-mode .message-input {
    background: #2c2c2c;
    color: #f1f1f1;
}
.dark-mode .message.user {
    background: #0a84ff;
}
.dark-mode .message.ai {
    background: #3a3a3c;
}
.dark-mode input[type="text"],
.dark-mode select {
    background-color: #333;
    color: #f1f1f1;
    border-color: #555;
}

        body {
            background: #e0e0e0;
        }
        .iphone-frame {
            width: 375px;
            height: 667px;
            margin: auto;
            border: 16px solid #333;
            border-radius: 40px;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            position: relative;
        }
        .camera-notch {
            position: relative;
            height: 30px;
            background: #333;
            border-radius: 0 0 20px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .camera-notch::before {
            content: '';
            width: 12px;
            height: 12px;
            background: #111;
            border-radius: 50%;
            position: absolute;
            left: 20px;
        }
        .camera-notch::after {
            content: '';
            width: 60px;
            height: 6px;
            background: #111;
            border-radius: 3px;
            position: absolute;
            right: 20px;
        }
        .top-bar {
            background: #007aff;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        .app-title {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            background-color: #f8f8f8;
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }
        .contacts-label {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            padding: 4px;
            background: #f8f8f8;
            border-bottom: 1px solid #ccc;
        }
        .persona-selector {
            display: flex;
            justify-content: space-around;
            padding: 8px;
            background: white;
            border-bottom: 1px solid #ccc;
        }
        .persona-btn {
            text-align: center;
            cursor: pointer;
        }
        .persona-btn img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 4px;
            border: 2px solid transparent;
            transition: transform 0.2s ease;
        }
        .persona-btn:hover img {
            transform: scale(1.1);
        }
        .persona-btn.active img {
            border-color: #007aff;
        }
        .chat-thread {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .message {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .message.user {
            align-self: flex-end;
            background: #007aff;
            color: white;
        }
        .message.ai {
            align-self: flex-start;
            background: #e5e5ea;
            color: black;
        }
        .message-input {
            display: flex;
            padding: 10px;
            background: white;
            border-top: 1px solid #ccc;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .message-input input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }
        .message-input select {
            width: 110px;
        }
        .message-input .form-check {
            display: flex;
            align-items: center;
        }
        .message-input .form-check-label {
            margin-left: 4px;
            font-size: 12px;
        }
        .message-input button {
            background: #007aff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
        }
        .bottom-bar {
            height: 20px;
            background: #333;
            border-radius: 20px;
            margin: 5px auto;
            width: 100px;
        }
        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="iphone-frame">
        <div class="camera-notch"></div>
        <div class="top-bar">
            <button id="darkModeToggle" class="dark-toggle-btn">🌙</button>

            <span id="persona-name">{{ ucfirst(session('persona', 'coach')) }}</span>
        </div>

        <div class="app-title">
            Keeping It Real AI <span class="text-muted">(Kir-AI)</span>
        </div>

        <div class="contacts-label">Contacts</div>

        <!-- Persona Selector -->
        <form action="{{ route('chat.persona') }}" method="POST" id="persona-form">
            @csrf
            <input type="hidden" name="persona" id="selected-persona" value="{{ session('persona', 'coach') }}">
            <div class="persona-selector">
                @php $current = session('persona', 'coach'); @endphp
                @foreach(['coach', 'coder', 'poet', 'tupac', 'obama'] as $persona)
                    <div class="persona-btn {{ $current === $persona ? 'active' : '' }}"
                         onclick="selectPersona('{{ $persona }}', '{{ ucfirst($persona) }}')">
                        <img src="/images/personas/{{ $persona }}.png" alt="{{ ucfirst($persona) }}">
                        <small>{{ ucfirst($persona) }}</small>
                    </div>
                @endforeach
            </div>
        </form>

        <!-- Reset Button -->
        <form action="{{ route('chat.reset') }}" method="POST" class="d-flex justify-content-center p-2 bg-white border-bottom">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Reset Conversation</button>
        </form>

        <!-- Chat Messages -->
        <div class="chat-thread fade-in" id="chat-thread">
            @foreach($messages as $msg)
                <div class="message {{ $msg['sender'] === 'user' ? 'user' : 'ai' }}">
                    <p>{{ $msg['text'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Message Input with Length Toggle and Tone Dropdown -->
        <form method="POST" action="{{ route('chat.send') }}" class="message-input">
            @csrf
            <input type="hidden" name="persona" value="{{ session('persona', 'coach') }}">
            <input type="text" name="message" placeholder="iMessage..." required>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="length" id="lengthToggle" value="long"
                    {{ session('length') === 'long' ? 'checked' : '' }}>
                <label class="form-check-label" for="lengthToggle">Long</label>
            </div>

            <select name="tone" class="form-select form-select-sm">
                @foreach(['neutral', 'funny', 'technical', 'detailed'] as $option)
                    <option value="{{ $option }}" {{ session('tone') === $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Send</button>
        </form>

        <div class="bottom-bar"></div>
    </div>

    <script>
        function selectPersona(persona, label) {
            document.getElementById('selected-persona').value = persona;
            document.getElementById('persona-name').textContent = label;
            document.getElementById('persona-form').submit();
        }
    </script>

    <script>
    const toggleBtn = document.getElementById('darkModeToggle');
    const body = document.body;

    // Load saved preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }

    toggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
</script>

</body>
</html>
