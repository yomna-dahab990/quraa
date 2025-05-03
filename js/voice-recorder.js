class VoiceRecorder {
    constructor() {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.isRecording = false;
        this.recordingTime = 0;
        this.timerInterval = null;
    }

    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream);
            this.audioChunks = [];
            
            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = () => {
                const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                this.onRecordingComplete(audioUrl);
            };

            this.mediaRecorder.start();
            this.isRecording = true;
            this.startTimer();
            
            return true;
        } catch (error) {
            console.error('Error accessing microphone:', error);
            return false;
        }
    }

    stopRecording() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.isRecording = false;
            this.stopTimer();
            
            // Stop all tracks
            this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
    }

    startTimer() {
        this.recordingTime = 0;
        this.timerInterval = setInterval(() => {
            this.recordingTime++;
            this.updateTimerDisplay();
        }, 1000);
    }

    stopTimer() {
        clearInterval(this.timerInterval);
        this.recordingTime = 0;
        this.updateTimerDisplay();
    }

    updateTimerDisplay() {
        const timerElement = document.getElementById('recording-timer');
        if (timerElement) {
            const minutes = Math.floor(this.recordingTime / 60);
            const seconds = this.recordingTime % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
    }

    onRecordingComplete(audioUrl) {
        // This will be implemented in the main page
        console.log('Recording completed:', audioUrl);
    }
}

// Speech recognition functionality
class SpeechRecognizer {
    constructor() {
        this.recognition = null;
        this.isRecognizing = false;
    }

    initialize() {
        if ('webkitSpeechRecognition' in window) {
            this.recognition = new webkitSpeechRecognition();
            this.recognition.continuous = true;
            this.recognition.interimResults = true;
            this.recognition.lang = 'ar-SA';

            this.recognition.onresult = (event) => {
                const transcript = Array.from(event.results)
                    .map(result => result[0].transcript)
                    .join('');
                this.onSpeechResult(transcript);
            };

            this.recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
            };

            return true;
        }
        return false;
    }

    start() {
        if (this.recognition && !this.isRecognizing) {
            this.recognition.start();
            this.isRecognizing = true;
        }
    }

    stop() {
        if (this.recognition && this.isRecognizing) {
            this.recognition.stop();
            this.isRecognizing = false;
        }
    }

    onSpeechResult(transcript) {
        // This will be implemented in the main page
        console.log('Speech recognized:', transcript);
    }
}

// Export the classes
window.VoiceRecorder = VoiceRecorder;
window.SpeechRecognizer = SpeechRecognizer; 