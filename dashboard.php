<?php
session_start();
require_once 'koneksi.php';

// Jika user belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data dari session
$username = $_SESSION['username'];
$full_name = $_SESSION['full_name'] ?? $username;
$login_time = $_SESSION['login_time'] ?? 'Unknown';

// Ambil query string untuk filter paket (opsional)
$filter = $_GET['filter'] ?? 'all';

// Build query berdasarkan filter
$query = "SELECT * FROM paket_catering WHERE status = 'active'";
if ($filter !== 'all') {
    $filter_safe = escape_string($filter);
    $query .= " AND kategori = '$filter_safe'";
}
$query .= " ORDER BY created_at DESC";

// Execute query
$paket_result = mysqli_query($conn, $query);

// Get statistics
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM paket_catering WHERE status = 'active') as total_paket,
        (SELECT COUNT(*) FROM pesanan WHERE status != 'cancelled') as total_pesanan,
        (SELECT COUNT(*) FROM pesanan WHERE status = 'delivered') as pesanan_selesai,
        (SELECT COUNT(DISTINCT user_id) FROM pesanan WHERE user_id IS NOT NULL) as total_customer
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Nutribox Catering Diet Sehat</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="container">
      <h1>Nutribox</h1>
      <nav aria-label="Navigasi utama">
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="manage_paket.php">Kelola Paket</a></li>
          <li><a href="manage_pesanan.php">Kelola Pesanan</a></li>
          <li><a href="#statistik">Statistik</a></li>
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
    <!-- User Info Section -->
    <div class="user-info">
      <div class="user-details">
        <div class="user-name">Selamat datang, <?php echo htmlspecialchars($full_name); ?>! 👋</div>
        <div class="login-time">Login: <?php echo htmlspecialchars($login_time); ?></div>
      </div>
      <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <!-- Motivational Quote Section -->
    <section class="quote-section">
      <div class="quote-text" id="quote-text">
        <div class="loading"></div>
      </div>
      <div class="quote-author" id="quote-author"></div>
    </section>

    <!-- Statistics Section -->
    <section id="statistik" class="stats-section">
      <h2>Statistik Nutribox</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['total_paket']; ?></div>
          <div class="stat-label">Paket Aktif</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['total_pesanan']; ?></div>
          <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['pesanan_selesai']; ?></div>
          <div class="stat-label">Pesanan Selesai</div>
        </div>
        <div class="stat-item">
          <div class="stat-number"><?php echo $stats['total_customer']; ?></div>
          <div class="stat-label">Total Customer</div>
        </div>
      </div>
    </section>

    <section id="paket-catering" aria-labelledby="paket-catering-title">
      <div class="paket-heading">
        <h2 id="paket-catering-title">Paket Catering Nutribox</h2>
        <h3>Temukan Tujuan Diet Anda</h3>
        
        <!-- Filter Buttons -->
        <div class="filter-buttons">
          <a href="?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">Semua</a>
          <a href="?filter=medical" class="filter-btn <?php echo $filter === 'medical' ? 'active' : ''; ?>">Medical</a>
          <a href="?filter=weight" class="filter-btn <?php echo $filter === 'weight' ? 'active' : ''; ?>">Weight</a>
          <a href="?filter=healthy" class="filter-btn <?php echo $filter === 'healthy' ? 'active' : ''; ?>">Healthy</a>
          <a href="?filter=kids" class="filter-btn <?php echo $filter === 'kids' ? 'active' : ''; ?>">Kids</a>
        </div>
      </div>

      <div class="grid-4">
        <?php 
        if (mysqli_num_rows($paket_result) > 0):
          while ($paket = mysqli_fetch_assoc($paket_result)): 
        ?>
        <article class="card card-featured" data-package="<?php echo $paket['kategori']; ?>" data-id="<?php echo $paket['id']; ?>">
          <img src="<?php echo htmlspecialchars($paket['gambar']); ?>" alt="<?php echo htmlspecialchars($paket['nama_paket']); ?>">
          <div class="card-body">
            <h4><?php echo htmlspecialchars($paket['nama_paket']); ?></h4>
            <p><?php echo htmlspecialchars($paket['deskripsi']); ?></p>
            
            <div style="margin: 1rem 0; padding: 0.8rem; background: #f8f9fa; border-radius: 8px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong style="color: #28a745;">Harga:</strong>
                <span style="color: #333; font-weight: 600;">Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></span>
              </div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <strong style="color: #17a2b8;">Durasi:</strong>
                <span><?php echo $paket['durasi']; ?> hari</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <strong style="color: #ffc107;">Kalori:</strong>
                <span><?php echo $paket['kalori']; ?> kcal/hari</span>
              </div>
            </div>
            
            <button class="btn btn-outline" 
                    data-info="<?php echo $paket['id']; ?>"
                    data-title="<?php echo htmlspecialchars($paket['nama_paket']); ?>"
                    data-desc="<?php echo htmlspecialchars($paket['deskripsi']); ?>"
                    data-price="<?php echo number_format($paket['harga'], 0, ',', '.'); ?>"
                    data-duration="<?php echo $paket['durasi']; ?>"
                    data-calorie="<?php echo $paket['kalori']; ?>">
              Info Selengkapnya
            </button>
          </div>
        </article>
        <?php 
          endwhile;
        else:
        ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
          <h3 style="color: #666;">Tidak ada paket untuk kategori ini</h3>
          <p style="color: #999;">Coba pilih kategori lain atau kembali ke "Semua"</p>
          <a href="?filter=all" class="btn btn-primary" style="margin-top: 1rem;">Lihat Semua Paket</a>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Quick Actions -->
    <section id="quick-actions" style="text-align: center; margin: 4rem 0;">
      <h2 style="font-family: 'Poppins', sans-serif; margin-bottom: 2rem;">Manajemen Cepat</h2>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="manage_paket.php" class="btn btn-primary" style="min-width: 200px;">
          📦 Kelola Paket
        </a>
        <a href="manage_pesanan.php" class="btn btn-primary" style="min-width: 200px;">
          🛒 Kelola Pesanan
        </a>
        <button class="btn btn-outline" id="btn-refresh" style="min-width: 200px;">
          🔄 Refresh Data
        </button>
      </div>
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
    <p>&copy; 2025 Nutribox | Admin Dashboard | <a href="index.php">Lihat Halaman Publik</a></p>
  </footer>

  <!-- Notification -->
  <div class="notification" id="notification"></div>

  <script src="script.js"></script>
  <script>
    // Update modal untuk menampilkan data dari database
    document.addEventListener("click", (e) => {
      if (e.target && e.target.hasAttribute("data-info")) {
        const title = e.target.getAttribute("data-title");
        const desc = e.target.getAttribute("data-desc");
        const price = e.target.getAttribute("data-price");
        const duration = e.target.getAttribute("data-duration");
        const calorie = e.target.getAttribute("data-calorie");
        
        const modal = document.querySelector('.modal');
        document.getElementById("modal-title").textContent = title;
        document.getElementById("modal-desc").innerHTML = `
          ${desc}<br><br>
          <strong>💰 Harga:</strong> Rp ${price}<br>
          <strong>📅 Durasi:</strong> ${duration} hari<br>
          <strong>🔥 Kalori:</strong> ${calorie} kcal/hari
        `;
        document.getElementById("modal-cta").textContent = "Hubungi Kami";
        
        modal.classList.add("show");
        document.body.style.overflow = "hidden";
      }
    });

    // Refresh button
    document.getElementById('btn-refresh')?.addEventListener('click', () => {
      showNotification('Memuat ulang data...');
      setTimeout(() => {
        location.reload();
      }, 500);
    });
  </script>
</body>
</html>