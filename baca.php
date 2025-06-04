<?php
include 'koneksi.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validasi dan ambil ID buku dari URL
$id_buku = null;
if (isset($_GET['id_buku']) && !empty($_GET['id_buku'])) {
    $id_buku = mysqli_real_escape_string($conn, $_GET['id_buku']);
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_buku = mysqli_real_escape_string($conn, $_GET['id']);
} else {
    die("ID buku tidak valid");
}

// Query untuk mengambil data buku
$query = "SELECT * FROM buku WHERE id_buku = '$id_buku'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Buku tidak ditemukan. ID yang dicari: " . htmlspecialchars($id_buku));
}

$row = mysqli_fetch_assoc($result);

// Cek dan catat history untuk user yang login
$history_recorded = false;
$history_message = "";

if (isset($_SESSION['id_user'])) {
    $id_user = mysqli_real_escape_string($conn, $_SESSION['id_user']);
    
    // Cek apakah sudah ada history untuk user dan buku ini
    $cek_query = "SELECT * FROM history WHERE id_user='$id_user' AND id_buku='$id_buku'";
    $cek_result = mysqli_query($conn, $cek_query);
    
    if (!$cek_result) {
        $history_message = "Error checking history: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($cek_result) == 0) {
            // Insert history baru
            $insert_query = "INSERT INTO history (id_user, id_buku, tanggal_baca) VALUES ('$id_user', '$id_buku', NOW())";
            $insert_result = mysqli_query($conn, $insert_query);
            
            if ($insert_result) {
                $history_recorded = true;
                $history_message = "History baru berhasil ditambahkan";
            } else {
                $history_message = "Error inserting history: " . mysqli_error($conn);
            }
        } else {
            // Update history yang sudah ada
            $update_query = "UPDATE history SET tanggal_baca=NOW() WHERE id_user='$id_user' AND id_buku='$id_buku'";
            $update_result = mysqli_query($conn, $update_query);
            
            if ($update_result) {
                $history_recorded = true;
                $history_message = "History berhasil diupdate";
            } else {
                $history_message = "Error updating history: " . mysqli_error($conn);
            }
        }
    }
} else {
    // Untuk testing, gunakan user ID default jika tidak ada session
    $default_user_id = 12311; // Sesuaikan dengan user ID yang digunakan di history.php
    
    $cek_query = "SELECT * FROM history WHERE id_user='$default_user_id' AND id_buku='$id_buku'";
    $cek_result = mysqli_query($conn, $cek_query);
    
    if (!$cek_result) {
        $history_message = "Error checking history (default user): " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($cek_result) == 0) {
            $insert_query = "INSERT INTO history (id_user, id_buku, tanggal_baca) VALUES ('$default_user_id', '$id_buku', NOW())";
            $insert_result = mysqli_query($conn, $insert_query);
            
            if ($insert_result) {
                $history_recorded = true;
                $history_message = "History berhasil ditambahkan untuk user default ($default_user_id)";
            } else {
                $history_message = "Error inserting history (default user): " . mysqli_error($conn);
            }
        } else {
            $update_query = "UPDATE history SET tanggal_baca=NOW() WHERE id_user='$default_user_id' AND id_buku='$id_buku'";
            $update_result = mysqli_query($conn, $update_query);
            
            if ($update_result) {
                $history_recorded = true;
                $history_message = "History berhasil diupdate untuk user default ($default_user_id)";
            } else {
                $history_message = "Error updating history (default user): " . mysqli_error($conn);
            }
        }
    }
}

// Tentukan path PDF yang benar
$pdf_path = '';
if (!empty($row['file_buku'])) {
    $possible_paths = [
        $row['file_buku'],
        'file/' . $row['file_buku'],
        'uploads/' . basename($row['file_buku'])
    ];
    
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $pdf_path = $path;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Buku - <?= htmlspecialchars($row['judul']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .controls {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #5a6fd8;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-history {
            background: #28a745;
        }

        .btn-history:hover {
            background: #218838;
        }

        .pdf-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        .pdf-viewer {
            width: 100%;
            height: 80vh;
            min-height: 600px;
            border: none;
            display: block;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }

        .history-info {
            background: #e2e8f0;
            border: 1px solid #cbd5e0;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .container {
                padding: 10px;
            }
            
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .pdf-viewer {
                height: 70vh;
            }
        }

        .pdf-container.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            border-radius: 0;
        }

        .pdf-container.fullscreen .pdf-viewer {
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1><?= htmlspecialchars($row['judul']); ?></h1>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($row['penulis']); ?></p>
        </div>
    </div>

    <div class="container">
        <!-- History Status -->
        <?php if ($history_recorded): ?>
            <div class="success-message">
                ✅ <?= htmlspecialchars($history_message) ?>
            </div>
        <?php elseif (!empty($history_message)): ?>
            <div class="history-info">
                ℹ️ <?= htmlspecialchars($history_message) ?>
            </div>
        <?php endif; ?>

        <div class="controls">
            <div>
                <a href="homepage.php" class="btn btn-secondary">← Kembali ke Beranda</a>
                <a href="history.php" class="btn btn-history">📚 Lihat History</a>
            </div>
            <div>
                <?php if (!empty($pdf_path) && file_exists($pdf_path)): ?>
                    <button onclick="toggleFullscreen()" class="btn" id="fullscreenBtn">📖 Mode Fullscreen</button>
                    <button onclick="downloadPDF()" class="btn">📥 Download</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($pdf_path) && file_exists($pdf_path)): ?>
            <div class="pdf-container" id="pdfContainer">
                <iframe 
                    src="<?= htmlspecialchars($pdf_path); ?>#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH" 
                    class="pdf-viewer" 
                    id="pdfViewer"
                    title="PDF Viewer - <?= htmlspecialchars($row['judul']); ?>">
                </iframe>
            </div>
        <?php elseif (!empty($row['file_buku'])): ?>
            <div class="error-message">
                <h3>❌ File tidak ditemukan</h3>
                <p><strong>Path yang dicari:</strong> <?= htmlspecialchars($row['file_buku']) ?></p>
                <p>File PDF tidak ditemukan di server. Silakan hubungi administrator.</p>
                <a href="homepage.php" class="btn" style="margin-top: 15px;">Kembali ke Beranda</a>
            </div>
        <?php else: ?>
            <div class="error-message">
                <h3>📚 File buku tidak tersedia</h3>
                <p>File PDF untuk buku ini belum diupload.</p>
                <a href="homepage.php" class="btn" style="margin-top: 15px;">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let isFullscreen = false;

        function toggleFullscreen() {
            const container = document.getElementById('pdfContainer');
            const btn = document.getElementById('fullscreenBtn');
            
            if (!isFullscreen) {
                container.classList.add('fullscreen');
                btn.innerHTML = '🚪 Keluar Fullscreen';
                document.body.style.overflow = 'hidden';
                isFullscreen = true;
            } else {
                container.classList.remove('fullscreen');
                btn.innerHTML = '📖 Mode Fullscreen';
                document.body.style.overflow = 'auto';
                isFullscreen = false;
            }
        }

        function downloadPDF() {
            <?php if (!empty($pdf_path) && file_exists($pdf_path)): ?>
                const link = document.createElement('a');
                link.href = '<?= htmlspecialchars($pdf_path); ?>';
                link.download = '<?= htmlspecialchars($row['judul']); ?>.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            <?php else: ?>
                alert('File tidak tersedia untuk didownload');
            <?php endif; ?>
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F11' || (e.key === 'f' && e.ctrlKey)) {
                e.preventDefault();
                toggleFullscreen();
            }
            if (e.key === 'Escape' && isFullscreen) {
                toggleFullscreen();
            }
        });

        // Auto-redirect to history after 5 seconds (optional)
        <?php if ($history_recorded): ?>
        setTimeout(function() {
            if (confirm('History berhasil dicatat! Apakah Anda ingin melihat halaman history?')) {
                window.open('history.php', '_blank');
            }
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>