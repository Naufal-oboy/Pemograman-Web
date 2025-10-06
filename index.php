<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}
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

    <section id="paket-catering" aria-labelledby="paket-catering-title">
      <div class="paket-heading">
        <h2 id="paket-catering-title">Paket Catering Nutribox</h2>
        <h3>Temukan Tujuan Diet Anda</h3>
      </div>

      <div class="grid-4">
        <article class="card card-featured" data-package="medical">
          <img src="project/medical.jpeg" alt="Medical Package">
          <div class="card-body">
            <h4>Medical Package</h4>
            <p>Paket catering sehat untuk kebutuhan medis/pantangan seperti diabetes, jantung, stroke, ginjal, kolesterol, isolasi mandiri, dan kebutuhan khusus seperti ibu hamil dan pemulihan pasca operasi.</p>
            <button class="btn btn-outline" data-info="medical">Info Selengkapnya</button>
          </div>
        </article>

        <article class="card card-featured" data-package="weight">
          <img src="project/weight.jpeg" alt="Weight Management">
          <div class="card-body">
            <h4>Weight Management</h4>
            <p>Paket catering diet untuk bantu turunkan atau menambah berat badan. Menu rendah kalori, rendah garam, tinggi protein. Garansi turun hingga 3 kg dalam 2 minggu*</p>
            <button class="btn btn-outline" data-info="weight">Info Selengkapnya</button>
          </div>
        </article>

        <article class="card card-featured" data-package="healthy">
          <img src="project/healthy.jpeg" alt="Healthy Personal">
          <div class="card-body">
            <h4>Healthy Personal</h4>
            <p>Paket catering makanan sehat untuk pemenuhan kebutuhan gizi sehari-hari dengan harga terjangkau. Tersedia paket mulai 7 hari hingga 28 hari.</p>
            <button class="btn btn-outline" data-info="healthy">Info Selengkapnya</button>
          </div>
        </article>

        <article class="card card-featured" data-package="kids">
          <img src="project/kids.jpeg" alt="Baby and Kids Meal">
          <div class="card-body">
            <h4>Baby and Kids Meal</h4>
            <p>Asupan gizi dan nutrisi terbaik untuk mendukung tumbuh kembang anak Anda. Biasakan makan sehat sejak dini. Tersedia untuk usia 1-9 tahun.</p>
            <button class="btn btn-outline" data-info="kids">Info Selengkapnya</button>
          </div>
        </article>
      </div>
    </section>

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
</body>
</html>