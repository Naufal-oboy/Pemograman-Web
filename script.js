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

// ============= MODAL DENGAN DOM MANIPULATION =============
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

// Data paket detail
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

// Event listener untuk tombol info
document.addEventListener("click", (e) => {
  if (e.target && e.target.hasAttribute("data-info")) {
    const packageType = e.target.getAttribute("data-info");
    const detail = packageDetails[packageType];
    
    if (detail) {
      document.getElementById("modal-title").textContent = detail.title;
      document.getElementById("modal-desc").textContent = detail.desc;
      document.getElementById("modal-cta").textContent = detail.cta;
      
      modal.classList.add("show");
      document.body.style.overflow = "hidden";
    }
  }
});

// Close modal
function closeModal() {
  modal.classList.remove("show");
  document.body.style.overflow = "auto";
}

modal.querySelector(".close").addEventListener("click", closeModal);
modal.addEventListener("click", (e) => {
  if (e.target === modal) closeModal();
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && modal.classList.contains("show")) {
    closeModal();
  }
});

// ============= MOTIVATIONAL QUOTES =============
async function loadMotivationalQuote() {
  const quoteText = document.getElementById("quote-text");
  const quoteAuthor = document.getElementById("quote-author");
  
  if (!quoteText || !quoteAuthor) return;
  
  try {
    const response = await fetch("https://api.quotable.io/random?tags=health,life");
    
    if (!response.ok) {
      throw new Error("API Error");
    }
    
    const data = await response.json();
    
    setTimeout(() => {
      quoteText.textContent = `"${data.content}"`;
      quoteAuthor.textContent = `— ${data.author}`;
    }, 500);
    
  } catch (error) {
    console.log("Using fallback quote");
    setTimeout(() => {
      quoteText.textContent = '"Kesehatan adalah kekayaan yang sesungguhnya."';
      quoteAuthor.textContent = "— Pepatah Bijak";
    }, 500);
  }
}

// ============= NOTIFICATION SYSTEM =============
function showNotification(message) {
  const notification = document.getElementById("notification");
  if (notification) {
    notification.textContent = message;
    notification.classList.add("show");
    
    setTimeout(() => {
      notification.classList.remove("show");
    }, 3000);
  }
}

// ============= BUTTON EVENTS =============
const btnPromo = document.getElementById("btn-promo");
if (btnPromo) {
  btnPromo.addEventListener("click", () => {
    showNotification("Promo terbaru akan segera hadir!");
  });
}

const btnPesan = document.getElementById("btn-pesan");
if (btnPesan) {
  btnPesan.addEventListener("click", () => {
    showNotification("Mengarahkan ke WhatsApp...");
    setTimeout(() => {
      window.open("https://wa.me/628123456789?text=Halo%20Nutribox,%20saya%20ingin%20memesan%20paket%20catering", "_blank");
    }, 1000);
  });
}

// Modal CTA button
document.addEventListener("click", (e) => {
  if (e.target && e.target.id === "modal-cta") {
    showNotification("Menghubungi tim Nutribox...");
    closeModal();
  }
});

// ============= CARD HOVER EFFECTS =============
document.querySelectorAll(".card").forEach(card => {
  card.addEventListener("mouseenter", () => {
    card.style.transform = "translateY(-8px) scale(1.02)";
    card.style.boxShadow = "0 8px 25px rgba(0,0,0,0.15)";
  });
  
  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) scale(1)";
    card.style.boxShadow = "0 4px 12px rgba(0,0,0,0.1)";
  });
});

// ============= SCROLL ANIMATIONS =============
function animateOnScroll() {
  const elements = document.querySelectorAll(".card, .quote-section");
  
  elements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementVisible = 150;
    
    if (elementTop < window.innerHeight - elementVisible) {
      element.style.opacity = "1";
      element.style.transform = "translateY(0)";
    }
  });
}

function initScrollAnimations() {
  const elementsToAnimate = document.querySelectorAll(".card, .quote-section");
  elementsToAnimate.forEach(element => {
    element.style.opacity = "0";
    element.style.transform = "translateY(30px)";
    element.style.transition = "all 0.6s ease";
  });
}

window.addEventListener("scroll", animateOnScroll);

// ============= DARK MODE TOGGLE =============
function createDarkModeToggle() {
  const toggle = document.createElement("button");
  toggle.className = "theme-toggle";
  toggle.innerHTML = "🌙";
  toggle.setAttribute("aria-label", "Toggle dark mode");
  
  document.body.appendChild(toggle);
  
  // Load saved theme
  const savedTheme = localStorage.getItem("nutriboxTheme") || "light";
  if (savedTheme === "dark") {
    document.body.classList.add("dark-mode");
    toggle.innerHTML = "☀️";
  }
  
  // Toggle event
  toggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    const isDark = document.body.classList.contains("dark-mode");
    toggle.innerHTML = isDark ? "☀️" : "🌙";
    localStorage.setItem("nutriboxTheme", isDark ? "dark" : "light");
  });
}

// ============= INITIALIZATION =============
document.addEventListener("DOMContentLoaded", () => {
  // Load quote
  loadMotivationalQuote();
  
  // Init animations
  initScrollAnimations();
  animateOnScroll();
  
  // Create dark mode toggle
  createDarkModeToggle();
  
  // Welcome message
  setTimeout(() => {
    showNotification("Selamat datang di Nutribox!");
  }, 1000);
});

// ============= ERROR HANDLING =============
window.addEventListener("error", (e) => {
  console.error("JavaScript Error:", e.error);
});