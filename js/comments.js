function hashColor(str) {
    var palette = [
        '#2e7d32', '#1565c0', '#6a1b9a', '#c62828', 
        '#f57f17', '#00695c', '#283593', '#4e342e'
    ];
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return palette[Math.abs(hash) % palette.length];
}

function initializeAvatars() {
    var avatars = document.querySelectorAll('.comment-avatar');
    avatars.forEach(avatar => {
        var userName = avatar.getAttribute('data-user');
        var userInitials = avatar.getAttribute('data-initials');
        if (userName) {
            avatar.style.backgroundColor = hashColor(userName);
            if (avatar.textContent.trim() === "" && userInitials) {
                avatar.textContent = userInitials;
            }
        }
    });
}

function clearReply() {
    document.getElementById('reply-author-name').textContent = '';
    document.getElementById('reply-author-date').textContent = '';
    document.getElementById('reply-text-preview').textContent = '';
    document.getElementById('reply-to').value = '';
    document.getElementById('reply-preview-wrapper').classList.add('d-none');
}

function formatDateTime(dateStr) {
    var opts = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateStr).toLocaleDateString('it-IT', opts).replace(',', '');
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function loadComments() {
    var params = new URLSearchParams(window.location.search);
    var postId = params.get('post_id');
    if (!postId) return;

    fetch('../ajax/posts/api-get-comments.php?post_id=' + postId)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('post-title').textContent = data.post_title;
            document.getElementById('comments-count').textContent = data.comments.length + ' commenti';
            renderComments(data.comments);
        } else {
            document.getElementById('comments-list').innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
        }
    })
    .catch(err => {
        console.error("Errore fetch commenti:", err);
        document.getElementById('comments-list').innerHTML = '<div class="alert alert-danger">Errore di connessione.</div>';
    });
}

function renderComments(comments) {
    var list = document.getElementById('comments-list');
    list.innerHTML = '';

    if (comments.length === 0) {
        list.innerHTML = '<p class="text-secondary text-center my-4">Nessun commento presente. Scrivi il primo!</p>';
        return;
    }

    comments.forEach(comment => {
        var fullname = comment.creatore_nome + ' ' + comment.creatore_cognome;
        var userInitials = (comment.creatore_nome[0] + comment.creatore_cognome[0]).toUpperCase();
        var dateStr = formatDateTime(comment.data_scrittura);
        var safeText = escapeHtml(comment.testo);

        var replyHtml = '';
        if (comment.risposta_id && comment.risposta_testo) {
            var replyFullname = comment.risposta_autore_nome + ' ' + comment.risposta_autore_cognome;
            var replyDateStr = formatDateTime(comment.risposta_data);
            replyHtml =
                '<div class="comment-reply-quote mb-2">' +
                    '<span class="reply-author">' + escapeHtml(replyFullname) + '</span>' +
                    '<span class="text-muted ms-2 small">' + replyDateStr + '</span>' +
                    '<span class="d-block mt-1">' + escapeHtml(comment.risposta_testo) + '</span>' +
                '</div>';
        }

        var html =
            '<div class="card border-0 shadow-sm">' +
                '<div class="card-body p-4">' +
                    '<div class="d-flex align-items-start gap-3">' +
                        '<div class="comment-avatar" aria-hidden="true" data-user="' + escapeHtml(fullname) + '" data-initials="' + escapeHtml(userInitials) + '"></div>' +
                        '<div class="flex-grow-1">' +
                            '<div class="d-flex justify-content-between align-items-center mb-1">' +
                                '<span class="fw-semibold text-dark">' + escapeHtml(fullname) + '</span>' +
                                '<div class="d-flex align-items-center gap-2">' +
                                    '<span class="text-secondary small">' + dateStr + '</span>' +
                                    '<button class="reply-btn js-reply-btn" title="Rispondi" aria-label="Rispondi a ' + escapeHtml(fullname) + '">' +
                                        '<em class="bi bi-reply" aria-hidden="true"></em> Rispondi' +
                                    '</button>' +
                                '</div>' +
                            '</div>' +
                            replyHtml +
                            '<p class="mb-0 text-dark">' + safeText + '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

        list.insertAdjacentHTML('beforeend', html);
        
        var replyBtn = list.lastElementChild.querySelector('.js-reply-btn');
        if (replyBtn) {
            replyBtn.addEventListener('click', () => {
                document.getElementById('reply-author-name').textContent = fullname;
                document.getElementById('reply-author-date').textContent = dateStr;
                document.getElementById('reply-text-preview').textContent = safeText;
                document.getElementById('reply-to').value = comment.id || '';
                document.getElementById('reply-preview-wrapper').classList.remove('d-none');
                document.getElementById('comment-text').focus();
                document.getElementById('comment-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }
    });

    initializeAvatars();
}

function setButtonLoading(button, isLoading) {
    if (isLoading) {
        button.setAttribute('data-original-text', button.innerHTML);
        button.disabled = true;
        button.innerHTML = 'Invio...';
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text');
    }
}

function initFormSubmit() {
    const form = document.getElementById('comment-form');
    if (!form) return;
    
    form.addEventListener('submit', event => {
        event.preventDefault();
        
        const btn = document.getElementById('comment-submit-btn');
        setButtonLoading(btn, true);
        
        const formData = new FormData(form);
        const params = new URLSearchParams(window.location.search);
        formData.set('post_id', params.get('post_id'));
        
        fetch('../ajax/posts/api-add-comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = result.redirect;
            } else {
                alert('Errore: ' + result.message);
                setButtonLoading(btn, false);
            }
        })
        .catch(error => {
            console.error('Errore invio commento:', error);
            alert('Errore di connessione. Riprova.');
            setButtonLoading(btn, false);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadComments();
    initFormSubmit();
});
