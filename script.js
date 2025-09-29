// ============= SMOOTH SCROLL UNTUK NAVIGASI =============
document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start"
      });
    }
  });
});

// ============= HIGHLIGHT MENU AKTIF SAAT SCROLL =============
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll("nav ul li a");

window.addEventListener("scroll", () => {
  let current = "";
  sections.forEach(section => {
    const sectionTop = section.offsetTop - 100;
    if (scrollY >= sectionTop) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach(link => {
    link.classList.remove("active");
    if (link.getAttribute("href") === `#${current}`) {
      link.classList.add("active");
    }
  });
});

// ============= ENHANCED MODAL DENGAN DOM MANIPULATION =============

// Buat elemen modal dinamis dengan DOM Manipulation
const modal = document.createElement("div");
modal.classList.add("modal");
modal.innerHTML = `
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3 id="modal-title"></h3>
    <p id="modal-desc"></p>
    <button class="btn btn-primary" id="modal-cta">Hubungi Kami</button>
  </div>
`;
document.body.appendChild(modal);

// Data paket detail untuk DOM content manipulation
const packageDetails = {
  medical: {
    title: "Medical Package",
    desc: "Paket khusus untuk kondisi medis dengan menu yang disesuaikan oleh ahli gizi. Tersedia konsultasi gratis dengan nutritionist bersertifikat. Menu rendah gula, rendah garam, dan tinggi serat.",
    cta: "Konsultasi Gratis"
  },
  weight: {
    title: "Weight Management",
    desc: "Program diet terpandu dengan tracking progress harian. Includes meal plan personal, olahraga ringan, dan monitoring berat badan. Garansi hasil atau uang kembali!",
    cta: "Mulai Program"
  },
  healthy: {
    title: "Healthy Personal",
    desc: "Menu seimbang 4 sehat 5 sempurna dengan variasi yang beragam. Fresh ingredients, no MSG, no pengawet. Pilihan vegetarian dan non-vegetarian tersedia.",
    cta: "Pilih Paket"
  },
  kids: {
    title: "Baby and Kids Meal",
    desc: "Menu khusus anak dengan nutrisi lengkap untuk pertumbuhan optimal. Fun presentation, rasa yang disukai anak, dan 100% organic ingredients. Parent approved!",
    cta: "Lihat Menu Anak"
  }
};

// Event listener untuk tombol info dengan DOM manipulation
document.querySelectorAll("[data-info]").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const packageType = e.target.getAttribute("data-info");
    const detail = packageDetails[packageType];
    
    // Update modal content dengan DOM manipulation
    const modalTitle = document.getElementById("modal-title");
    const modalDesc = document.getElementById("modal-desc");
    const modalCta = document.getElementById("modal-cta");
    
    modalTitle.textContent = detail.title;
    modalDesc.textContent = detail.desc;
    modalCta.textContent = detail.cta;
    
    // Show modal dengan animasi
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
  });
});

// Event listeners untuk close modal
modal.querySelector(".close").addEventListener("click", closeModal);
modal.addEventListener("click", (e) => {
  if (e.target === modal) closeModal();
});

// Keyboard event listener untuk ESC key
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("show")) {
    closeModal();
  }
});

function closeModal() {
  modal.classList.remove("show");
  document.body.style.overflow = "auto";
}

// ============= FETCH API - MOTIVATIONAL QUOTES =============
async function loadMotivationalQuote() {
  const quoteText = document.getElementById("quote-text");
  const quoteAuthor = document.getElementById("quote-author");
  
  try {
    // Fetch data dari public API
    const response = await fetch("https://api.quotable.io/random?tags=motivational,health,life");
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    // DOM manipulation untuk update quote dengan animasi
    setTimeout(() => {
      quoteText.textContent = data.content;
      quoteAuthor.textContent = `— ${data.author}`;
    }, 1000);
    
  } catch (error) {
    console.error("Failed to fetch quote:", error);
    // Fallback quote jika API gagal
    setTimeout(() => {
      quoteText.textContent = "Kesehatan adalah kekayaan yang sesungguhnya.";
      quoteAuthor.textContent = "— Pepatah Bijak";
    }, 1000);
  }
}

// ============= NOTIFICATION SYSTEM DENGAN DOM MANIPULATION =============
function showNotification(message) {
  const notification = document.getElementById("notification");
  notification.textContent = message;
  notification.classList.add("show");
  
  // Auto hide notification setelah 3 detik
  setTimeout(() => {
    notification.classList.remove("show");
  }, 3000);
}

// ============= INTERACTIVE BUTTON EVENTS =============
document.getElementById("btn-promo").addEventListener("click", () => {
  showNotification("Promo terbaru akan segera hadir! 🎉");
});

document.getElementById("btn-pesan").addEventListener("click", () => {
  showNotification("Mengarahkan ke WhatsApp...");
  setTimeout(() => {
    window.open("https://wa.me/628123456789?text=Halo%20Nutribox,%20saya%20ingin%20memesan%20paket%20catering", "_blank");
  }, 1000);
});

// Modal CTA button event dengan event delegation
document.addEventListener("click", (e) => {
  if (e.target && e.target.id === "modal-cta") {
    showNotification("Menghubungi tim Nutribox...");
    closeModal();
  }
});

// ============= CARD HOVER EFFECTS DENGAN DOM MANIPULATION =============
document.querySelectorAll(".card").forEach(card => {
  card.addEventListener("mouseenter", () => {
    // DOM style manipulation untuk hover effect
    card.style.transform = "translateY(-8px) scale(1.02)";
    card.style.boxShadow = "0 8px 25px rgba(0,0,0,0.15)";
  });
  
  card.addEventListener("mouseleave", () => {
    // Reset style ke normal
    card.style.transform = "translateY(0) scale(1)";
    card.style.boxShadow = "0 4px 12px rgba(0,0,0,0.1)";
  });
});

// ============= SCROLL ANIMATIONS DENGAN DOM MANIPULATION =============
function animateOnScroll() {
  const elements = document.querySelectorAll(".card, .quote-section");
  
  elements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;
    
    if (elementTop < window.innerHeight - elementVisible) {
      // DOM style manipulation untuk animasi scroll
      element.style.opacity = "1";
      element.style.transform = "translateY(0)";
    }
  });
}

// Initialize scroll animations dengan DOM manipulation
function initScrollAnimations() {
  const elementsToAnimate = document.querySelectorAll(".card, .quote-section");
  elementsToAnimate.forEach(element => {
    element.style.opacity = "0";
    element.style.transform = "translateY(30px)";
    element.style.transition = "all 0.6s ease";
  });
}

// Event listener untuk scroll
window.addEventListener("scroll", animateOnScroll);

// ============= DYNAMIC CONTENT LOADING =============
async function loadDynamicContent() {
  try {
    // Simulasi fetch data tambahan (bisa diganti dengan API lain)
    const healthTips = [
      "Minum air putih minimal 8 gelas sehari",
      "Konsumsi sayuran berwarna-warni untuk nutrisi lengkap",
      "Olahraga ringan 30 menit setiap hari",
      "Tidur berkualitas 7-8 jam per malam"
    ];
    
    // Pilih tip acak
    const randomTip = healthTips[Math.floor(Math.random() * healthTips.length)];
    
    // Buat element tip dinamis dengan DOM manipulation
    const tipElement = document.createElement("div");
    tipElement.className = "health-tip";
    tipElement.innerHTML = `
      <p><strong>💡 Health Tip:</strong> ${randomTip}</p>
    `;
    
    // Tambahkan style dinamis
    tipElement.style.cssText = `
      background: linear-gradient(45deg, #ffecd2 0%, #fcb69f 100%);
      padding: 1rem;
      border-radius: 10px;
      margin: 1rem 0;
      text-align: center;
      animation: slideInFromLeft 0.5s ease-out;
    `;
    
    // Insert ke dalam main container
    const mainContainer = document.querySelector("main .container");
    const firstSection = mainContainer.querySelector("section");
    mainContainer.insertBefore(tipElement, firstSection);
    
  } catch (error) {
    console.error("Error loading dynamic content:", error);
  }
}

// ============= FORM VALIDATION (jika ada form di masa depan) =============
function validateForm(formData) {
  const errors = [];
  
  // Contoh validasi email
  if (formData.email && !formData.email.includes("@")) {
    errors.push("Email tidak valid");
  }
  
  // Contoh validasi nama
  if (formData.name && formData.name.length < 2) {
    errors.push("Nama minimal 2 karakter");
  }
  
  // Contoh validasi nomor telepon
  if (formData.phone && formData.phone.length < 10) {
    errors.push("Nomor telepon tidak valid");
  }
  
  return errors;
}

// ============= INTERACTIVE SEARCH FEATURE =============
function createSearchFeature() {
  // Buat search input dengan DOM manipulation
  const searchContainer = document.createElement("div");
  searchContainer.className = "search-container";
  searchContainer.innerHTML = `
    <input type="text" id="search-packages" placeholder="🔍 Cari paket catering..." />
  `;
  
  // Style untuk search container
  searchContainer.style.cssText = `
    text-align: center;
    margin: 2rem 0;
  `;
  
  // Style untuk search input
  const searchInput = searchContainer.querySelector("#search-packages");
  searchInput.style.cssText = `
    width: 300px;
    max-width: 90%;
    padding: 0.8rem 1rem;
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 1rem;
    transition: all 0.3s ease;
  `;
  
  // Insert search ke dalam paket heading
  const paketHeading = document.querySelector(".paket-heading");
  paketHeading.appendChild(searchContainer);
  
  // Event listener untuk search functionality
  searchInput.addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll(".card-featured");
    
    cards.forEach(card => {
      const title = card.querySelector("h4").textContent.toLowerCase();
      const desc = card.querySelector("p").textContent.toLowerCase();
      
      if (title.includes(searchTerm) || desc.includes(searchTerm)) {
        card.style.display = "flex";
        card.style.opacity = "1";
      } else {
        card.style.display = searchTerm === "" ? "flex" : "none";
        card.style.opacity = searchTerm === "" ? "1" : "0";
      }
    });
  });
  
  // Focus dan blur events
  searchInput.addEventListener("focus", () => {
    searchInput.style.borderColor = "#ff6600";
    searchInput.style.boxShadow = "0 0 0 3px rgba(255, 102, 0, 0.1)";
  });
  
  searchInput.addEventListener("blur", () => {
    searchInput.style.borderColor = "#ddd";
    searchInput.style.boxShadow = "none";
  });
}

// ============= COUNTER ANIMATION =============
function animateCounter(element, target, duration = 2000) {
  let start = 0;
  const increment = target / (duration / 16);
  
  const updateCounter = () => {
    start += increment;
    if (start < target) {
      element.textContent = Math.floor(start);
      requestAnimationFrame(updateCounter);
    } else {
      element.textContent = target;
    }
  };
  
  updateCounter();
}

// ============= STATISTICS SECTION =============
function createStatsSection() {
  const statsSection = document.createElement("section");
  statsSection.className = "stats-section";
  statsSection.innerHTML = `
    <h2>Nutribox Dalam Angka</h2>
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-number" data-count="5000">0</div>
        <div class="stat-label">Pelanggan Puas</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="15">0</div>
        <div class="stat-label">Menu Sehat</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="50">0</div>
        <div class="stat-label">Kota Terjangkau</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="99">0</div>
        <div class="stat-label">% Kepuasan</div>
      </div>
    </div>
  `;
  
  // Style untuk stats section
  statsSection.style.cssText = `
    background: #f8f9fa;
    padding: 3rem 2rem;
    margin: 3rem 0;
    border-radius: 15px;
    text-align: center;
  `;
  
  // Insert stats section sebelum promo
  const promoSection = document.getElementById("promo");
  promoSection.parentNode.insertBefore(statsSection, promoSection);
  
  // Style untuk stats grid
  const statsGrid = statsSection.querySelector(".stats-grid");
  statsGrid.style.cssText = `
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
  `;
  
  // Style untuk stat items
  const statItems = statsSection.querySelectorAll(".stat-item");
  statItems.forEach(item => {
    item.style.cssText = `
      padding: 1rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    `;
    
    const statNumber = item.querySelector(".stat-number");
    statNumber.style.cssText = `
      font-size: 2.5rem;
      font-weight: bold;
      color: #28a745;
      margin-bottom: 0.5rem;
    `;
    
    const statLabel = item.querySelector(".stat-label");
    statLabel.style.cssText = `
      font-size: 1rem;
      color: #666;
      font-weight: 600;
    `;
    
    // Hover effect
    item.addEventListener("mouseenter", () => {
      item.style.transform = "translateY(-5px)";
    });
    
    item.addEventListener("mouseleave", () => {
      item.style.transform = "translateY(0)";
    });
  });
  
  // Intersection Observer untuk animasi counter
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const statNumbers = entry.target.querySelectorAll(".stat-number");
        statNumbers.forEach(num => {
          const target = parseInt(num.getAttribute("data-count"));
          animateCounter(num, target);
        });
        observer.unobserve(entry.target);
      }
    });
  });
  
  observer.observe(statsSection);
}

// ============= LOCAL STORAGE UNTUK PREFERENCES =============
function saveUserPreference(key, value) {
  try {
    const preferences = JSON.parse(localStorage.getItem("nutriboxPrefs") || "{}");
    preferences[key] = value;
    localStorage.setItem("nutriboxPrefs", JSON.stringify(preferences));
  } catch (error) {
    console.log("LocalStorage tidak tersedia, menggunakan memori sementara");
    // Fallback ke variabel global jika localStorage tidak tersedia
    window.nutriboxPrefs = window.nutriboxPrefs || {};
    window.nutriboxPrefs[key] = value;
  }
}

function getUserPreference(key, defaultValue = null) {
  try {
    const preferences = JSON.parse(localStorage.getItem("nutriboxPrefs") || "{}");
    return preferences[key] || defaultValue;
  } catch (error) {
    console.log("LocalStorage tidak tersedia, menggunakan memori sementara");
    return (window.nutriboxPrefs && window.nutriboxPrefs[key]) || defaultValue;
  }
}

// ============= THEME SWITCHER =============
function createThemeSwitcher() {
  const themeToggle = document.createElement("button");
  themeToggle.innerHTML = "🌙";
  themeToggle.className = "theme-toggle";
  themeToggle.setAttribute("aria-label", "Toggle dark mode");
  
  // Style untuk theme toggle
  themeToggle.style.cssText = `
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    background: #28a745;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    z-index: 1000;
  `;
  
  document.body.appendChild(themeToggle);
  
  // Load saved theme
  const savedTheme = getUserPreference("theme", "light");
  if (savedTheme === "dark") {
    toggleDarkMode(true);
    themeToggle.innerHTML = "☀️";
  }
  
  themeToggle.addEventListener("click", () => {
    const isDark = document.body.classList.contains("dark-mode");
    toggleDarkMode(!isDark);
    themeToggle.innerHTML = !isDark ? "☀️" : "🌙";
    saveUserPreference("theme", !isDark ? "dark" : "light");
  });
  
  // Hover effect
  themeToggle.addEventListener("mouseenter", () => {
    themeToggle.style.transform = "scale(1.1)";
  });
  
  themeToggle.addEventListener("mouseleave", () => {
    themeToggle.style.transform = "scale(1)";
  });
}

function toggleDarkMode(isDark) {
  if (isDark) {
    document.body.classList.add("dark-mode");
    // Add dark mode styles dynamically
    const darkModeStyles = `
      .dark-mode {
        background-color: #1a1a1a;
        color: #e0e0e0;
      }
      .dark-mode header {
        background-color: #2d2d2d;
        border-bottom-color: #404040;
      }
      .dark-mode .card {
        background-color: #2d2d2d;
        color: #e0e0e0;
      }
      .dark-mode #promo,
      .dark-mode #cara-pesan {
        background-color: #2d2d2d;
      }
    `;
    
    let styleSheet = document.getElementById("dark-mode-styles");
    if (!styleSheet) {
      styleSheet = document.createElement("style");
      styleSheet.id = "dark-mode-styles";
      document.head.appendChild(styleSheet);
    }
    styleSheet.textContent = darkModeStyles;
  } else {
    document.body.classList.remove("dark-mode");
    const styleSheet = document.getElementById("dark-mode-styles");
    if (styleSheet) {
      styleSheet.remove();
    }
  }
}

// ============= INITIALIZATION & EVENT LISTENERS =============
document.addEventListener("DOMContentLoaded", () => {
  // Initialize semua fitur
  loadMotivationalQuote();
  initScrollAnimations();
  animateOnScroll();
  createSearchFeature();
  createStatsSection();
  loadDynamicContent();
  createThemeSwitcher();
  
  // Welcome message dengan delay
  setTimeout(() => {
    showNotification("Selamat datang di Nutribox! 🥗");
  }, 1500);
  
  // Preload gambar untuk performa lebih baik
  const imageUrls = [
    "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=200&fit=crop",
    "https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&h=200&fit=crop",
    "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=200&fit=crop",
    "https://images.unsplash.com/photo-1571741140674-c0ce2420e100?w=400&h=200&fit=crop"
  ];
  
  imageUrls.forEach(url => {
    const img = new Image();
    img.src = url;
  });
});

// ============= ERROR HANDLING =============
window.addEventListener("error", (e) => {
  console.error("JavaScript Error:", e.error);
  showNotification("Terjadi kesalahan kecil, tapi website tetap berfungsi!");
});

// ============= PERFORMANCE MONITORING =============
window.addEventListener("load", () => {
  // Monitor performa loading
  const loadTime = performance.now();
  console.log(`Website loaded in ${loadTime.toFixed(2)}ms`);
  
  if (loadTime > 3000) {
    console.warn("Loading time is slower than expected");
  }
});

// Export functions untuk testing (jika diperlukan)
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    showNotification,
    validateForm,
    saveUserPreference,
    getUserPreference
  };
}