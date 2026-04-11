document.addEventListener('DOMContentLoaded', () => {
    const postsTbody = document.getElementById('posts-tbody');
    if (postsTbody) {
        loadPosts();
    }

    initializeAvatars();
});

function loadPosts() {
    fetch('../ajax/admin/api-get-posts.php')
        .then(res => res.json())
        .then(data => {
            if (data.success && data.posts) {
                renderPosts(data.posts);
            } else {
                renderPostsEmpty();
            }
        })
        .catch(err => {
            console.error(err);
            renderPostsError();
        });
}
function renderPosts(posts) {
    const tbody = document.getElementById('posts-tbody');
    if (!tbody) return;

    if (posts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun post pubblicato al momento.</td></tr>';
        return;
    }

    tbody.innerHTML = posts.map(post => `
        <tr id="post-row-${post.id}">
            <td class="ps-4 py-3">
                <div class="fw-bold text-dark">${post.titolo}</div>
                <div class="text-muted small">ID: #${post.id}</div>
            </td>
            <td class="py-3">
                <div class="d-flex align-items-center">
                    <em class="bi bi-person-circle me-2 text-secondary"></em>
                    <div>
                        <div class="small fw-semibold">${post.creatore_nome} ${post.creatore_cognome}</div>
                        <div class="text-muted extra-small" style="font-size: 0.75rem;">${post.creatore_email}</div>
                    </div>
                </div>
            </td>
            <td class="pe-4 py-3 text-end">
                <div class="d-flex justify-content-end gap-2 text-nowrap">
                    <a href="admin_edit_post.php?id=${post.id}" class="btn btn-warning btn-sm fw-semibold text-dark">
                        <em class="bi bi-pencil me-1"></em>Modifica
                    </a>
                    <button type="button" class="btn btn-danger btn-sm fw-semibold" onclick="deletePost(${post.id}, '${post.titolo.replace(/'/g, "\\'")}')">
                        <em class="bi bi-trash me-1"></em>Elimina
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPostsEmpty() {
    const tbody = document.getElementById('posts-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-muted">Nessun post pubblicato al momento.</td></tr>';
    }
}

function renderPostsError() {
    const tbody = document.getElementById('posts-tbody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-5 text-danger">Errore nel caricamento dei post.</td></tr>';
    }
}

function hashColor(str) {
    const palette = [
        '#2e7d32', '#1565c0', '#6a1b9a', '#c62828', 
        '#f57f17', '#00695c', '#283593', '#4e342e'
    ];
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return palette[Math.abs(hash) % palette.length];
}

function initializeAvatars() {
    const avatars = document.querySelectorAll('.admin-user-avatar');
    avatars.forEach(avatar => {
        const userName = avatar.getAttribute('data-user');
        if (userName) {
            avatar.style.backgroundColor = hashColor(userName);
            if (avatar.textContent.trim() === "") {
                avatar.textContent = getInitials(userName);
            }
        }
    });
}

function getInitials(fullName) {
    return fullName.split(' ').filter(Boolean).slice(0, 2).map(word => word[0].toUpperCase()).join('');
}

function deletePost(postId, title) {
    if (confirm(`Vuoi procedere all'eliminazione del post: "${title}"?`)) {
        const formData = new FormData();
        formData.append('id', postId);

        fetch('../ajax/admin/api-admin-delete-post.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`post-row-${postId}`);
                if (row) {
                    row.classList.add('fade-out');
                    setTimeout(() => row.remove(), 400);
                }
                alert(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Errore di connessione.');
        });
    }
}
