// CASCADE: Always enforce disabling if session is pending acceptance
function enforceSessionPendingDisable() {
    if (window.sessionPendingAcceptance) {
        setTimeout(() => {
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.querySelector('.send-button');
            const attachmentIcon = document.querySelector('.attachment-icon');
            if (messageInput) {
                messageInput.disabled = true;
                messageInput.placeholder = 'Session pending acceptance';
                messageInput.style.background = '#f3f3f3';
                messageInput.style.color = '#b0b0b0';
                messageInput.style.cursor = 'not-allowed';
            }
            if (sendButton) {
                sendButton.disabled = true;
                sendButton.style.opacity = '0.5';
                sendButton.style.cursor = 'not-allowed';
            }
            if (attachmentIcon) {
                attachmentIcon.style.opacity = '0.5';
                attachmentIcon.style.pointerEvents = 'none';
                attachmentIcon.title = 'Session pending acceptance - Cannot send files';
            }
        }, 100);
    }
}
