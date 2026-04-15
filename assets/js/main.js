document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // SCROLL ANIMATIONS
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealElements.forEach(el => revealObserver.observe(el));

    // STICKY NAV
    const nav = document.getElementById('navbar');
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }
    
    loadGallery();
});

// LIGHTBOX
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('active');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}

// LOAD GALLERY
async function loadGallery() {
    try {
        const res = await fetch('get_images.php');
        const images = await res.json();
        const container = document.getElementById('portfolio-gallery');
        if (!container) return;
        
        container.innerHTML = '';
        
        if (images.length > 0) {
            images.slice(0, 6).forEach(img => {
                const div = document.createElement('div');
                div.className = 'gallery-item reveal';
                div.onclick = () => openLightbox(img);
                div.innerHTML = `<img src="${img}"><div class="gallery-overlay"><i class="fas fa-search"></i></div>`;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<div style="text-align:center; grid-column:1/-1; color:#999; padding:50px;">No images yet. Upload from admin panel.</div>';
        }
    } catch(err) {
        console.error(err);
    }
}

// ADMIN LOGIN
async function adminLogin(e) {
    e.preventDefault();
    const user = document.getElementById('admin-user').value;
    const pass = document.getElementById('admin-pass').value;
    
    if (user === 'admin' && pass === 'admin123') {
        document.getElementById('admin-login-form').style.display = 'none';
        document.getElementById('admin-content').style.display = 'block';
        loadAdminClients();
    } else {
        alert('Login Failed! Use admin / admin123');
    }
}

async function createClient(e) {
    e.preventDefault();
    const clientName = document.getElementById('new-client-name').value;
    if (!clientName) return;
    
    const code = Math.random().toString(36).substring(2, 10).toUpperCase();
    alert(`Client Created!\n\nName: ${clientName}\nAccess Code: ${code}`);
    document.getElementById('new-client-name').value = '';
}

async function loadAdminClients() {
    const list = document.getElementById('client-list-ul');
    list.innerHTML = '<li style="color:#999;">Create your first client above</li>';
}

// UPLOAD FUNCTION
async function adminUpload(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('uploadFile');
    const uploadBtn = document.getElementById('uploadBtn');
    const uploadMessage = document.getElementById('uploadMessage');
    
    if (!fileInput.files.length) {
        alert('Select an image file');
        return;
    }
    
    const formData = new FormData();
    formData.append('image', fileInput.files[0]);
    
    uploadBtn.disabled = true;
    uploadBtn.textContent = 'Uploading...';
    uploadMessage.style.display = 'block';
    uploadMessage.style.color = '#c5a059';
    uploadMessage.innerHTML = 'Uploading...';
    
    try {
        const res = await fetch('upload.php', { method: 'POST', body: formData });
        const data = await res.json();
        
        if (data.status === 'success') {
            uploadMessage.style.color = '#4CAF50';
            uploadMessage.innerHTML = '✓ Upload successful!';
            alert('Upload Successful!');
            fileInput.value = '';
            loadGallery();
            setTimeout(() => uploadMessage.style.display = 'none', 2000);
        } else {
            uploadMessage.style.color = '#f44336';
            uploadMessage.innerHTML = '✗ ' + data.message;
        }
    } catch(err) {
        uploadMessage.style.color = '#f44336';
        uploadMessage.innerHTML = '✗ Error';
    } finally {
        uploadBtn.disabled = false;
        uploadBtn.textContent = 'UPLOAD IMAGE';
    }
}

// CLIENT LOGIN
async function handleClientLogin(e) {
    e.preventDefault();
    const code = document.getElementById('client-code').value;
    if (code) {
        alert('Access Granted!');
        document.querySelector('.client-portal').style.display = 'none';
        document.getElementById('client-gallery-view').style.display = 'block';
        document.getElementById('client-name-display').innerText = 'Your Gallery';
        loadClientImages();
    }
}

async function loadClientImages() {
    const res = await fetch('get_images.php');
    const images = await res.json();
    const container = document.getElementById('client-images-container');
    container.innerHTML = '';
    images.forEach(img => {
        const div = document.createElement('div');
        div.className = 'gallery-item';
        div.onclick = () => openLightbox(img);
        div.innerHTML = `<img src="${img}"><div class="gallery-overlay"><i class="fas fa-search"></i></div>`;
        container.appendChild(div);
    });
}

// CONTACT FORM
document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const status = document.getElementById('contactStatus');
    status.style.display = 'block';
    status.innerHTML = 'Sending...';
    
    try {
        const res = await fetch('send_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: document.getElementById('contactName').value,
                email: document.getElementById('contactEmail').value,
                message: document.getElementById('contactMessage').value
            })
        });
        const data = await res.json();
        status.innerHTML = data.message;
        status.style.color = data.status === 'success' ? '#4CAF50' : '#f44336';
        if (data.status === 'success') this.reset();
    } catch(err) {
        status.innerHTML = 'Error sending message';
    }
    setTimeout(() => status.style.display = 'none', 3000);
});

// Make functions global
window.openLightbox = openLightbox;
window.closeLightbox = closeLightbox;
window.handleClientLogin = handleClientLogin;
window.adminLogin = adminLogin;
window.createClient = createClient;
window.adminUpload = adminUpload;