<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMINA | Cinematic Photography</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Navigation -->
    <nav id="navbar">
        <div class="logo">LUMINA<span>.</span></div>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#portfolio">Portfolio</a></li>
            <li><a href="#client-login">Client Access</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#" onclick="document.getElementById('admin-panel').style.display='block'"><i class="fas fa-lock"></i></a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <header id="hero">
        <div class="hero-content reveal">
            <h1>Capturing Moments<br>That Matter</h1>
            <p>Cinematic Wedding & Portrait Photography</p>
            <a href="#portfolio" class="btn">View Portfolio</a>
        </div>
    </header>

    <!-- About Section -->
    <section id="about">
        <div class="about-img reveal">
            <img src="https://images.unsplash.com/photo-1554048612-387768052bf7?q=80&w=800&auto=format&fit=crop" alt="Photographer">
        </div>
        <div class="about-text reveal">
            <h3>The Story Behind the Lens</h3>
            <p style="color:var(--text-secondary); margin-bottom: 1.5rem;">
                With over 10 years of experience, I specialize in capturing raw emotions and cinematic details. My approach is minimalist yet powerful, focusing on the natural light and authentic interactions between people.
            </p>
            <p style="color:var(--text-secondary);">
                Based in New York, available worldwide.
            </p>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services">
        <h2 class="section-title reveal">Our Services</h2>
        <p class="section-subtitle reveal">Tailored photography experiences for every occasion</p>
        
        <div class="services-grid">
            <div class="service-card reveal">
                <i class="fas fa-rings-wedding"></i>
                <h4>Weddings</h4>
                <p>Full day coverage capturing every tear and smile.</p>
            </div>
            <div class="service-card reveal">
                <i class="fas fa-heart"></i>
                <h4>Engagements</h4>
                <p>Romantic sessions in your favorite locations.</p>
            </div>
            <div class="service-card reveal">
                <i class="fas fa-book-open"></i>
                <h4>Albums</h4>
                <p>Hand-crafted luxury photo books.</p>
            </div>
            <div class="service-card reveal">
                <i class="fas fa-birthday-cake"></i>
                <h4>Birthday Shoots</h4>
                <p>Fun and vibrant celebrations for all ages.</p>
            </div>
        </div>
    </section>

    <!-- Portfolio Preview (Selected Works) -->
    <section id="portfolio" style="background: var(--card-bg);">
        <h2 class="section-title reveal">Selected Works</h2>
        <p class="section-subtitle reveal">A glimpse into our recent journeys</p>

        <div class="gallery-grid" id="portfolio-gallery">
            <div style="text-align:center; grid-column:1/-1; color:#999; padding:50px;">Loading images...</div>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="#portfolio" class="btn" style="background:transparent; border-color: white; color: white;">View Full Gallery</a>
        </div>
    </section>

    <!-- Recent Client Work (Horizontal Scroll) -->
    <section id="recent-work">
        <h2 class="section-title reveal">Recent Client Stories</h2>
        <div class="client-work-scroll reveal">
            <div class="client-card">
                <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=400" alt="Sarah & Mike">
                <div class="client-info"><h4>Sarah & Mike</h4><p>Tuscany, Italy</p></div>
            </div>
            <div class="client-card">
                <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=400" alt="Emma's Birthday">
                <div class="client-info"><h4>Emma's 21st</h4><p>New York City</p></div>
            </div>
            <div class="client-card">
                <img src="https://images.unsplash.com/photo-1623855274679-665aaa742603?w=400" alt="The Smiths">
                <div class="client-info"><h4>The Smiths</h4><p>Central Park</p></div>
            </div>
            <div class="client-card">
                <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?w=400" alt="Jessica & Tom">
                <div class="client-info"><h4>Jessica & Tom</h4><p>Paris, France</p></div>
            </div>
        </div>
    </section>

    <!-- Social Section -->
    <section id="social">
        <h2 class="section-title reveal">Follow on Instagram</h2>
        <p class="section-subtitle reveal">@lumina_studio</p>
        <div class="insta-grid reveal">
            <img src="https://picsum.photos/id/10/400/400" alt="Insta">
            <img src="https://picsum.photos/id/16/400/400" alt="Insta">
            <img src="https://picsum.photos/id/28/400/400" alt="Insta">
            <img src="https://picsum.photos/id/29/400/400" alt="Insta">
        </div>
    </section>

    <!-- Client Login Section -->
    <section id="client-login" style="background: var(--card-bg);">
        <h2 class="section-title reveal">Client Portal</h2>
        <div class="client-portal reveal">
            <p style="margin-bottom: 1.5rem; color: #999;">Enter your unique access code to view your private gallery.</p>
            <form onsubmit="handleClientLogin(event)">
                <input type="text" id="client-code" class="form-input" placeholder="Access Code (e.g. A1B2C3D4)" required>
                <button type="submit" class="btn" style="width:100%">Enter Gallery</button>
            </form>
        </div>
        
        <div id="client-gallery-view" style="display:none; margin-top: 3rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <h3 id="client-name-display" style="color:var(--accent)"></h3>
                <button onclick="location.reload()" class="btn" style="padding: 5px 15px; font-size: 0.8rem;">Logout</button>
            </div>
            <div class="gallery-grid" id="client-images-container"></div>
            <div style="text-align:center; margin-top:2rem;">
                <button class="btn" onclick="alert('Download feature coming soon...')">Download All</button>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact-container">
            <div class="contact-text reveal">
                <h2 class="section-title" style="text-align:left;">Let's Create Together</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                    Ready to book your session or have questions? Fill out the form or reach out directly.
                </p>
                
                <div class="contact-info">
                    <div><i class="fas fa-envelope"></i> hello@lumina.com</div>
                    <div><i class="fas fa-phone"></i> +1 (555) 123-4567</div>
                    <div><i class="fas fa-map-marker-alt"></i> 123 Studio Ave, NY</div>
                </div>
            </div>
            
            <form id="contactForm">
                <input type="text" id="contactName" class="form-input" placeholder="Your Name" required>
                <input type="email" id="contactEmail" class="form-input" placeholder="Your Email" required>
                <textarea id="contactMessage" class="form-input" rows="5" placeholder="Tell me about your event..." required></textarea>
                <button type="submit" class="btn">Send Message</button>
                <div id="contactStatus" style="margin-top:15px; display:none; padding:10px; border-radius:5px;"></div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="social-icons" style="margin-bottom: 1rem;">
            <a href="#" style="margin: 0 10px; font-size: 1.5rem;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" style="margin: 0 10px; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
            <a href="#" style="margin: 0 10px; font-size: 1.5rem;"><i class="fab fa-whatsapp"></i></a>
        </div>
        <p>&copy; 2024 Lumina Photography. All Rights Reserved.</p>
    </footer>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img id="lightbox-img" src="" alt="">
    </div>

    <!-- Admin Panel -->
    <div id="admin-panel" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:3000; overflow-y:auto; padding: 2rem;">
        <button onclick="document.getElementById('admin-panel').style.display='none'" style="float:right; color:white; font-size:2rem; background:none; border:none; cursor:pointer;">&times;</button>
        <div style="max-width:800px; margin: 0 auto; color:white;">
            <h2 style="color:var(--accent); border-bottom:1px solid #333; padding-bottom:1rem;">Admin Dashboard</h2>
            
            <div id="admin-login-form" style="margin-top:2rem;">
                <h3>Admin Login</h3>
                <form onsubmit="adminLogin(event)">
                    <input type="text" id="admin-user" class="form-input" placeholder="Username" required>
                    <input type="password" id="admin-pass" class="form-input" placeholder="Password" required>
                    <button class="btn">Login</button>
                </form>
            </div>

            <div id="admin-content" style="display:none;">
                <div style="background:#1a1a1a; padding:1.5rem; margin-bottom:2rem; border:1px solid #333; border-radius:5px;">
                    <h4>Create New Client</h4>
                    <form onsubmit="createClient(event)" style="display:flex; gap:10px; flex-wrap:wrap;">
                        <input type="text" id="new-client-name" class="form-input" placeholder="Client Name" required style="flex:1;">
                        <button class="btn">Generate Code</button>
                    </form>
                </div>

                <div style="background:#1a1a1a; padding:1.5rem; margin-bottom:2rem; border:1px solid #333; border-radius:5px;">
                    <h4>Upload Photos</h4>
                    <form id="uploadForm" enctype="multipart/form-data">
                        <select id="upload-client-select" class="form-input" style="margin-bottom:10px;">
                            <option value="">-- Select Client --</option>
                        </select>
                        <input type="file" name="image" id="uploadFile" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <button type="submit" class="btn" style="margin-top:10px; width:100%;" id="uploadBtn">UPLOAD IMAGE</button>
                    </form>
                    <div id="uploadMessage" style="margin-top:10px; display:none; text-align:center; padding:10px; border-radius:5px;"></div>
                </div>

                <h4>Active Clients</h4>
                <ul id="client-list-ul" style="margin-top:10px; color:#aaa; list-style:none; padding:0;"></ul>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>