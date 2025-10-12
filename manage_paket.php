<?php
session_start();
require_once 'koneksi.php';

// Cek login
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
    $query = "DELETE FROM paket_catering WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $success = "Paket berhasil dihapus!";
    } else {
        $error = "Gagal menghapus paket: " . mysqli_error($conn);
    }
}

// Handle CREATE & UPDATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama_paket = escape_string($_POST['nama_paket']);
    $kategori = escape_string($_POST['kategori']);
    $deskripsi = escape_string($_POST['deskripsi']);
    $harga = (float)$_POST['harga'];
    $durasi = (int)$_POST['durasi'];
    $kalori = (int)$_POST['kalori'];
    $status = escape_string($_POST['status']);
    $gambar = escape_string($_POST['gambar']);
    
    if ($id > 0) {
        // UPDATE
        $query = "UPDATE paket_catering SET 
                  nama_paket = '$nama_paket',
                  kategori = '$kategori',
                  deskripsi = '$deskripsi',
                  harga = $harga,
                  durasi = $durasi,
                  kalori = $kalori,
                  status = '$status',
                  gambar = '$gambar'
                  WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            $success = "Paket berhasil diupdate!";
        } else {
            $error = "Gagal update paket: " . mysqli_error($conn);
        }
    } else {
        // CREATE
        $query = "INSERT INTO paket_catering (nama_paket, kategori, deskripsi, harga, durasi, kalori, status, gambar)
                  VALUES ('$nama_paket', '$kategori', '$deskripsi', $harga, $durasi, $kalori, '$status', '$gambar')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Paket berhasil ditambahkan!";
        } else {
            $error = "Gagal menambah paket: " . mysqli_error($conn);
        }
    }
}

// Get data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM paket_catering WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}

// Get all paket
$paket_list = mysqli_query($conn, "SELECT * FROM paket_catering ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Paket - Nutribox</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <style>
        .crud-container {
            max-width: 1200px;
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
            min-height: 100px;
            resize: vertical;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-small {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
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
        }
        .badge-medical { background: #e3f2fd; color: #1976d2; }
        .badge-weight { background: #fff3e0; color: #f57c00; }
        .badge-healthy { background: #e8f5e9; color: #388e3c; }
        .badge-kids { background: #fce4ec; color: #c2185b; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
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
            <h2>Manajemen Paket Catering</h2>
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
            <h3><?php echo $edit_data ? 'Edit Paket' : 'Tambah Paket Baru'; ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Paket*</label>
                        <input type="text" name="nama_paket" value="<?php echo $edit_data['nama_paket'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Kategori*</label>
                        <select name="kategori" required>
                            <option value="medical" <?php echo ($edit_data['kategori'] ?? '') == 'medical' ? 'selected' : ''; ?>>Medical</option>
                            <option value="weight" <?php echo ($edit_data['kategori'] ?? '') == 'weight' ? 'selected' : ''; ?>>Weight</option>
                            <option value="healthy" <?php echo ($edit_data['kategori'] ?? '') == 'healthy' ? 'selected' : ''; ?>>Healthy</option>
                            <option value="kids" <?php echo ($edit_data['kategori'] ?? '') == 'kids' ? 'selected' : ''; ?>>Kids</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga (Rp)*</label>
                        <input type="number" name="harga" value="<?php echo $edit_data['harga'] ?? ''; ?>" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Durasi (Hari)*</label>
                        <input type="number" name="durasi" value="<?php echo $edit_data['durasi'] ?? 7; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Kalori*</label>
                        <input type="number" name="kalori" value="<?php echo $edit_data['kalori'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Status*</label>
                        <select name="status" required>
                            <option value="active" <?php echo ($edit_data['status'] ?? 'active') == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($edit_data['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Deskripsi*</label>
                    <textarea name="deskripsi" required><?php echo $edit_data['deskripsi'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>URL Gambar</label>
                    <input type="text" name="gambar" value="<?php echo $edit_data['gambar'] ?? 'project/default.jpeg'; ?>">
                </div>
                
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Paket' : 'Tambah Paket'; ?>
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="manage_paket.php" class="btn btn-outline">Batal</a>
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
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Kalori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($paket_list)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_paket']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['kategori']; ?>">
                                <?php echo ucfirst($row['kategori']); ?>
                            </span>
                        </td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['durasi']; ?> hari</td>
                        <td><?php echo $row['kalori']; ?> kcal</td>
                        <td>
                            <span class="badge badge-<?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" 
                                   class="btn-small btn-delete" 
                                   onclick="return confirm('Yakin ingin menghapus paket ini?')">Hapus</a>
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