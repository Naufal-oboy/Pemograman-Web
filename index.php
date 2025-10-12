<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

// Get active packages from database
$query = "SELECT * FROM paket_catering WHERE status = 'active' ORDER BY created_at DESC LIMIT 4";
$paket_result = mysqli_query($conn, $query);

// Get statistics for public view
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM paket_catering WHERE status = 'active') as total_paket,
        (SELECT COUNT(*) FROM pesanan WHERE status = 'delivered') as pesanan_selesai
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Calculate happy customers (estimasi)
$happy_customers = $stats['pesanan_selesai'] * 1.2; // Estimation

// Get recent testimonials
$testimoni_query = "SELECT * FROM testimoni WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3";
$testimoni_result = mysqli_query($conn, $testimoni_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nutribox - Catering Diet Sehat</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
  <style>
    /* Tombol Login di Header */
    .btn-login-header {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      padding: 0.7rem 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
    }
    
    .btn-login-header:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    
    /* CTA Section untuk Login */
    .cta-login-section {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      text-align: center;
      padding: 3rem 2rem;
      margin: 4rem 0;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .cta-login-section h2 {
      font-family: 'Poppins', sans-serif;
      font-size: 2rem;
      margin-bottom: 1rem;
    }
    
    .cta-login-section p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
      opacity: 0.95;
    }
    
    .cta-login-section .btn {
      background: white;
      color: #28a745;
      font-size: 1.1rem;
      padding: 1rem 2.5rem;
    }
    
    .cta-login-section .btn:hover {
      background: #f8f9fa;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    /* Testimonial Section */
    .testimonial-section {
      background: #f8f9fa;
      padding: 3rem 2rem;
      margin: 3rem 0;
      border-radius: 20px;
    }
    
    .testimonial-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .testimonial-card {
      background: white;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .testimonial-rating {
      color: #ffc107;
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }
    
    .testimonial-text {
      font-style: italic;
      color: #666;
      margin-bottom: 1rem;
      line-height: 1.6;
    }
    
    .testimonial-author {
      font-weight: 600;
      color: #333;
    }
  </style>
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="container">
      <h1>Nutribox</h1>
      <nav aria-label="Navigasi utama">
        <ul>
          <li><a href="#paket-catering">Paket Catering</a></li>
          <li><a href="#testimonial">Testimoni</a></li>
          <li><a href="#promo">Promo</a></li>
          <li><a href="#cara-pesan">Cara Pesan</a></li>
          <li><a href="login.php" class="btn-login-header">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="hero-content">
      <h2>Deliciously Healthy<br> Perfectly Yours</h2>
    </div>
  </section>

  <!-- MAIN CONTENT -->
  <main class="container">
    <!-- Motivational Quote Section -->
    <section class="quote-section">
      <div class="quote-text" id="quote-text">
        <div class="loading"></div>
      </div>
      <div class="quote-author" id="quote-author"></div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
      <h2>Dipercaya oleh Ribuan Pelanggan</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['total_paket']; ?>+</div>
          <div class="stat-label">Paket Tersedia</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo number_format($happy_customers, 0, ',', '.'); ?>+</div>
          <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['pesanan_selesai']; ?>+</div>
          <div class="stat-label">Pesanan Selesai</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">4.8</div>
          <div class="stat-label">Rating ⭐</div>
        </div>
      </div>
    </section>

    <section id="paket-catering" aria-labelledby="paket-catering-title">
      <div class="paket-heading">
        <h2 id="paket-catering-title">Paket Catering Nutribox</h2>
        <h3>Temukan Tujuan Diet Anda</h3>
      </div>

      <div class="grid-4">
        <?php 
        if (mysqli_num_rows($paket_result) > 0):
          while ($paket = mysqli_fetch_assoc($paket_result)): 
        ?>
        <article class="card card-featured" data-package="<?php echo $paket['kategori']; ?>">
          <img src="<?php echo htmlspecialchars($paket['gambar']); ?>" alt="<?php echo htmlspecialchars($paket['nama_paket']); ?>">
          <div class="card-body">
            <h4><?php echo htmlspecialchars($paket['nama_paket']); ?></h4>
            <p><?php echo htmlspecialchars($paket['deskripsi']); ?></p>
            
            <div style="margin: 1rem 0; padding: 0.8rem; background: #f8f9fa; border-radius: 8px; font-size: 0.95rem;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
                <span><strong>Harga:</strong></span>
                <span style="color: #28a745; font-weight: 600;">Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span><strong>Durasi:</strong></span>
                <span><?php echo $paket['durasi']; ?> hari</span>
              </div>
            </div>
            
            <button class="btn btn-outline" 
                    data-info="<?php echo $paket['kategori']; ?>"
                    data-title="<?php echo htmlspecialchars($paket['nama_paket']); ?>"
                    data-desc="<?php echo htmlspecialchars($paket['deskripsi']); ?>"
                    data-price="<?php echo number_format($paket['harga'], 0, ',', '.'); ?>"
                    data-duration="<?php echo $paket['durasi']; ?>">
              Info Selengkapnya
            </button>
          </div>
        </article>
        <?php endwhile; ?>
        <?php else: ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 2rem;">
          <p style="color: #666;">Paket catering akan segera tersedia.</p>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <?php if (mysqli_num_rows($testimoni_result) > 0): ?>
    <section id="testimonial" class="testimonial-section">
      <h2 style="text-align: center; font-family: 'Poppins', sans-serif; color: #333; margin-bottom: 1rem;">
        Apa Kata Mereka?
      </h2>
      <p style="text-align: center; color: #666; margin-bottom: 2rem;">
        Testimoni dari pelanggan yang puas dengan Nutribox
      </p>
      
      <div class="testimonial-grid">
        <?php while ($testi = mysqli_fetch_assoc($testimoni_result)): ?>
        <div class="testimonial-card">
          <div class="testimonial-rating">
            <?php 
            $rating = (int)$testi['rating'];
            echo str_repeat('⭐', $rating); 
            ?>
          </div>
          <div class="testimonial-text">
            "<?php echo htmlspecialchars($testi['komentar']); ?>"
          </div>
          <div class="testimonial-author">
            — <?php echo htmlspecialchars($testi['nama']); ?>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- CTA LOGIN SECTION -->
    <section class="cta-login-section">
      <h2>Siap Memulai Hidup Sehat?</h2>
      <p>Login sekarang untuk akses fitur lengkap, filter paket khusus, dan penawaran eksklusif member!</p>
      <a href="login.php" class="btn">Login / Daftar Sekarang</a>
    </section>

    <!-- PROMO -->
    <section id="promo" aria-labelledby="promo-title">
      <h2 id="promo-title">Promo Nutribox</h2>
      <p>Dapatkan promo menarik setiap bulan: diskon spesial, bonus menu tambahan, hingga paket bundling hemat.</p>
      <button class="btn btn-primary" id="btn-promo">Lihat Promo</button>
    </section>

    <!-- CARA PESAN -->
    <section id="cara-pesan" aria-labelledby="cara-pesan-title">
      <h2 id="cara-pesan-title">Cara Pesan di Nutribox</h2>
      <ol>
        <li>Pilih paket catering sesuai kebutuhan Anda.</li>
        <li>Hubungi Nutribox via WhatsApp atau isi form di website.</li>
        <li>Lakukan pembayaran sesuai instruksi.</li>
        <li>Nutribox akan menyiapkan dan mengantar catering sehat tepat waktu.</li>
      </ol>
      <button class="btn btn-primary" id="btn-pesan">Pesan Sekarang</button>
    </section>
  </main>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2025 Nutribox | <a href="login.php">Login Member Area</a></p>
  </footer>

  <!-- Notification -->
  <div class="notification" id="notification"></div>

  <script src="script.js"></script>
  <script>
    // Update modal untuk data dari database
    document.addEventListener("click", (e) => {
      if (e.target && e.target.hasAttribute("data-info")) {
        const title = e.target.getAttribute("data-title");
        const desc = e.target.getAttribute("data-desc");
        const price = e.target.getAttribute("data-price");
        const duration = e.target.getAttribute("data-duration");
        
        const modal = document.querySelector('.modal');
        document.getElementById("modal-title").textContent = title;
        document.getElementById("modal-desc").innerHTML = `
          ${desc}<br><br>
          <strong>💰 Harga:</strong> Rp ${price}<br>
          <strong>📅 Durasi:</strong> ${duration} hari<br><br>
          <em>Login untuk melihat detail lengkap dan memesan paket ini.</em>
        `;
        document.getElementById("modal-cta").textContent = "Login untuk Pesan";
        
        modal.classList.add("show");
        document.body.style.overflow = "hidden";
      }
    });

    // Modal CTA redirect to login
    document.addEventListener("click", (e) => {
      if (e.target && e.target.id === "modal-cta") {
        window.location.href = 'login.php';
      }
    });
  </script>
</body>
</html>