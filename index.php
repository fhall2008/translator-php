<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Speech to German Translator</title>
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
            min-height: 50px;
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
        #indicator {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-left: 10px;
            background-color: #55ff55;
            vertical-align: middle;
        }
        button {
            background-color: #55ff55;
            color: black;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #33cc33;
        }
    </style>
</head>
<body>
    <h1>🎤 Live Speech to German Translator</h1>
    <button id="startBtn">Start / Stop Listening</button>
    <div style="margin-top: 20px;">
        Listening: <div id="indicator"></div>
    </div>
    <div id="original">Original text will appear here</div>
    <div id="translation">German translation will appear here</div>

    <script>
        let recognition;
        let listening = false;
        let indicator = document.getElementById('indicator');
        let originalDiv = document.getElementById('original');
        let translationDiv = document.getElementById('translation');

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
        } else if ('SpeechRecognition' in window) {
            recognition = new SpeechRecognition();
        } else {
            alert("Your browser does not support Speech Recognition API.");
        }

        recognition.continuous = true;
        recognition.interimResults = true;  // ENABLE interim results
        recognition.lang = 'en-US';

        recognition.onresult = function(event) {
            let interimTranscript = '';
            let finalTranscript = '';
            for (let i = event.resultIndex; i < event.results.length; i++) {
                let transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript + ' ';
                    // Send final transcript to PHP for translation
                    fetch('translate.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'text=' + encodeURIComponent(transcript)
                    })
                    .then(response => response.text())
                    .then(data => {
                        translationDiv.textContent = data;
                    });
                } else {
                    interimTranscript += transcript + ' ';
                }
            }
            originalDiv.textContent = interimTranscript || finalTranscript;
            indicator.dataset.active = 'true';
        };

        recognition.onerror = function(event) {
            console.error(event.error);
            indicator.dataset.active = 'false';
        };

        recognition.onend = function() {
            if(listening) recognition.start(); // auto-restart
            indicator.dataset.active = 'false';
        };

        document.getElementById('startBtn').onclick = () => {
            if (!listening) {
                recognition.start();
                listening = true;
            } else {
                recognition.stop();
                listening = false;
            }
        };

        // Pulse animation
        let opacity = 1;
        let fadeOut = true;
        setInterval(() => {
            let active = indicator.dataset.active === 'true';
            let color = active ? '85,255,85' : '255,85,85'; // green or red
            if(fadeOut) { opacity -= 0.05; if(opacity <= 0.3) fadeOut = false; }
            else { opacity += 0.05; if(opacity >= 1.0) fadeOut = true; }
            indicator.style.backgroundColor = `rgba(${color},${opacity})`;
        }, 100);
    </script>
</body>
</html>
