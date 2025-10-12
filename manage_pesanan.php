<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$success = "";
$error = "";

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM pesanan WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $success = "Pesanan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pesanan: " . mysqli_error($conn);
    }
}

// Handle UPDATE STATUS
if (isset($_POST['update_status'])) {
    $id = (int)$_POST['pesanan_id'];
    $status = escape_string($_POST['status']);
    
    $query = "UPDATE pesanan SET status = '$status' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $success = "Status pesanan berhasil diupdate!";
    } else {
        $error = "Gagal update status: " . mysqli_error($conn);
    }
}

// Handle CREATE & UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update_status'])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $paket_id = (int)$_POST['paket_id'];
    $nama_pemesan = escape_string($_POST['nama_pemesan']);
    $email = escape_string($_POST['email']);
    $phone = escape_string($_POST['phone']);
    $alamat = escape_string($_POST['alamat']);
    $tanggal_mulai = escape_string($_POST['tanggal_mulai']);
    $jumlah_hari = (int)$_POST['jumlah_hari'];
    $total_harga = (float)$_POST['total_harga'];
    $status = escape_string($_POST['status']);
    $catatan = escape_string($_POST['catatan']);
    
    if ($id > 0) {
        // UPDATE
        $query = "UPDATE pesanan SET 
                  paket_id = $paket_id,
                  nama_pemesan = '$nama_pemesan',
                  email = '$email',
                  phone = '$phone',
                  alamat = '$alamat',
                  tanggal_mulai = '$tanggal_mulai',
                  jumlah_hari = $jumlah_hari,
                  total_harga = $total_harga,
                  status = '$status',
                  catatan = '$catatan'
                  WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            $success = "Pesanan berhasil diupdate!";
        } else {
            $error = "Gagal update pesanan: " . mysqli_error($conn);
        }
    } else {
        // CREATE
        $query = "INSERT INTO pesanan (paket_id, nama_pemesan, email, phone, alamat, tanggal_mulai, jumlah_hari, total_harga, status, catatan)
                  VALUES ($paket_id, '$nama_pemesan', '$email', '$phone', '$alamat', '$tanggal_mulai', $jumlah_hari, $total_harga, '$status', '$catatan')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Pesanan berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah pesanan: " . mysqli_error($conn);
        }
    }
}

// Get data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM pesanan WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}

// Get all pesanan dengan join paket
$pesanan_query = "SELECT p.*, pc.nama_paket, pc.kategori 
                  FROM pesanan p 
                  LEFT JOIN paket_catering pc ON p.paket_id = pc.id 
                  ORDER BY p.created_at DESC";
$pesanan_list = mysqli_query($conn, $pesanan_query);

// Get paket list untuk dropdown
$paket_options = mysqli_query($conn, "SELECT id, nama_paket, harga FROM paket_catering WHERE status = 'active'");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Nutribox</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <style>
        .crud-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
        }
        .crud-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        th {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }
        td {
            padding: 0.8rem;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #cfe2ff; color: #084298; }
        .badge-processing { background: #e7f1ff; color: #055160; }
        .badge-delivered { background: #d1e7dd; color: #0f5132; }
        .badge-cancelled { background: #f8d7da; color: #842029; }
        .status-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .status-form select {
            padding: 0.3rem 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Nutribox</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage_paket.php">Paket</a></li>
                    <li><a href="manage_pesanan.php">Pesanan</a></li>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="crud-container">
        <div class="crud-header">
            <h2>Manajemen Pesanan</h2>
            <span>Admin: <?php echo htmlspecialchars($username); ?></span>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- FORM CREATE/UPDATE -->
        <div class="form-container">
            <h3><?php echo $edit_data ? 'Edit Pesanan' : 'Tambah Pesanan Baru'; ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Paket*</label>
                        <select name="paket_id" required>
                            <option value="">Pilih Paket</option>
                            <?php 
                            mysqli_data_seek($paket_options, 0);
                            while ($paket = mysqli_fetch_assoc($paket_options)): 
                            ?>
                                <option value="<?php echo $paket['id']; ?>" 
                                    <?php echo ($edit_data['paket_id'] ?? '') == $paket['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($paket['nama_paket']); ?> - Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Pemesan*</label>
                        <input type="text" name="nama_pemesan" value="<?php echo $edit_data['nama_pemesan'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $edit_data['email'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>No. HP*</label>
                        <input type="text" name="phone" value="<?php echo $edit_data['phone'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Mulai*</label>
                        <input type="date" name="tanggal_mulai" value="<?php echo $edit_data['tanggal_mulai'] ?? date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Jumlah Hari*</label>
                        <input type="number" name="jumlah_hari" value="<?php echo $edit_data['jumlah_hari'] ?? 7; ?>" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Total Harga (Rp)*</label>
                        <input type="number" name="total_harga" value="<?php echo $edit_data['total_harga'] ?? ''; ?>" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Status*</label>
                        <select name="status" required>
                            <option value="pending" <?php echo ($edit_data['status'] ?? 'pending') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo ($edit_data['status'] ?? '') == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="processing" <?php echo ($edit_data['status'] ?? '') == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="delivered" <?php echo ($edit_data['status'] ?? '') == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo ($edit_data['status'] ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Alamat Pengiriman*</label>
                    <textarea name="alamat" required><?php echo $edit_data['alamat'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan"><?php echo $edit_data['catatan'] ?? ''; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Pesanan' : 'Tambah Pesanan'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="manage_pesanan.php" class="btn btn-outline">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- TABLE LIST -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pemesan</th>
                        <th>Paket</th>
                        <th>Tanggal Mulai</th>
                        <th>Durasi</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Update Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pesanan_list)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama_pemesan']); ?></strong><br>
                            <small><?php echo htmlspecialchars($row['phone']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['nama_paket'] ?? 'N/A'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal_mulai'])); ?></td>
                        <td><?php echo $row['jumlah_hari']; ?> hari</td>
                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="status-form">
                                <input type="hidden" name="pesanan_id" value="<?php echo $row['id']; ?>">
                                <select name="status">
                                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $row['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="processing" <?php echo $row['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="delivered" <?php echo $row['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-small btn-primary">Update</button>
                            </form>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" 
                                   class="btn-small btn-delete" 
                                   onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>