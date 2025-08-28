<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Speech to German Translator</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            text-align: center;
            padding: 50px;
        }
        #original, #translation {
            margin: 20px auto;
            padding: 20px;
            border-radius: 12px;
            width: 70%;
        }
        #original {
            background-color: #2e2e2e;
            color: #aad8ff;
        }
        #translation {
            background-color: #2e2e2e;
            color: #ffcc66;
            font-weight: bold;
        }
        button {
            background-color: #55ff55;
            color: black;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #33cc33;
        }
    </style>
</head>
<body>
    <h1>🎤 Speech to German Translator</h1>
    <button id="startBtn">Start Recording</button>
    <div id="original">Original text will appear here</div>
    <div id="translation">German translation will appear here</div>

    <script>
        const startBtn = document.getElementById('startBtn');
        const originalDiv = document.getElementById('original');
        const translationDiv = document.getElementById('translation');

        let recognition;
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
        } else if ('SpeechRecognition' in window) {
            recognition = new SpeechRecognition();
        } else {
            alert("Your browser does not support Speech Recognition API.");
        }

        recognition.continuous = false;
        recognition.lang = 'en-US';
        recognition.interimResults = false;

        recognition.onresult = function(event) {
            let text = event.results[0][0].transcript;
            originalDiv.textContent = text;

            // Send text to PHP server for translation
            fetch('translate.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'text=' + encodeURIComponent(text)
            })
            .then(response => response.text())
            .then(data => {
                translationDiv.textContent = data;
            });
        }

        recognition.onerror = function(event) {
            console.error(event.error);
        }

        startBtn.onclick = () => {
            recognition.start();
        }
    </script>
</body>
</html>
