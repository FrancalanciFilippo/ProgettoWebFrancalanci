
function hashColor(str) {
    const palette = [
        '#2e7d32', // verde scuro
        '#1565c0', // blu
        '#6a1b9a', // viola
        '#c62828', // rosso scuro
        '#f57f17', // arancio
        '#00695c', // verde acqua
        '#283593', // blu navy
        '#4e342e', // marrone
    ];
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return palette[Math.abs(hash) % palette.length];
}
function getInitials(fullName) {
    return fullName
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map(word => word[0].toUpperCase())
        .join('');
}

function setReply(author, date, text, replyId) {
    document.getElementById('reply-author-name').textContent = author;
    document.getElementById('reply-author-date').textContent = date;
    document.getElementById('reply-text-preview').textContent = text;
    document.getElementById('reply-to').value = replyId || author;
    document.getElementById('reply-preview-wrapper').classList.remove('d-none');
    document.getElementById('comment-text').focus();
    document.getElementById('comment-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function clearReply() {
    document.getElementById('reply-author-name').textContent = '';
    document.getElementById('reply-author-date').textContent = '';
    document.getElementById('reply-text-preview').textContent = '';
    document.getElementById('reply-to').value = '';
    document.getElementById('reply-preview-wrapper').classList.add('d-none');
    document.getElementById('comment-text').value = '';
}
