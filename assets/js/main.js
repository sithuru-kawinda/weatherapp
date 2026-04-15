// Contact form with better error handling
document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const name = document.getElementById('contactName').value;
    const email = document.getElementById('contactEmail').value;
    const message = document.getElementById('contactMessage').value;
    const statusDiv = document.getElementById('contactStatus');
    
    // Validation
    if (!name || !email || !message) {
        statusDiv.style.display = 'block';
        statusDiv.style.background = '#3a1a1a';
        statusDiv.style.color = '#f44336';
        statusDiv.innerHTML = '✗ Please fill all fields';
        setTimeout(() => statusDiv.style.display = 'none', 3000);
        return;
    }
    
    statusDiv.style.display = 'block';
    statusDiv.style.background = '#1a1a1a';
    statusDiv.style.color = '#c5a059';
    statusDiv.innerHTML = 'Sending message...';
    
    try {
        const res = await fetch('send_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, email: email, message: message })
        });
        
        const data = await res.json();
        
        if (data.status === 'success') {
            statusDiv.style.background = '#1a3a1a';
            statusDiv.style.color = '#4CAF50';
            statusDiv.innerHTML = '✓ ' + data.message;
            document.getElementById('contactForm').reset();
        } else {
            statusDiv.style.background = '#3a1a1a';
            statusDiv.style.color = '#f44336';
            statusDiv.innerHTML = '✗ ' + data.message;
        }
    } catch (err) {
        console.error('Contact form error:', err);
        statusDiv.style.background = '#3a1a1a';
        statusDiv.style.color = '#f44336';
        statusDiv.innerHTML = '✗ Connection error. Please try again.';
    }
    
    setTimeout(() => {
        statusDiv.style.display = 'none';
    }, 5000);
});