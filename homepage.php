<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Digital - SMKN 8 Semarang</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background-color: #ffffff;
      line-height: 1.6;
    }

    /* Header Styles */
    .header {
      background-color: #eeeeee;
      padding: 20px 50px;
      border-radius: 40px;
      margin: 40px 50px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .logo {
      width: 80px;
      height: 80px;
      object-fit: cover;
    }

    .nav-menu {
      display: flex;
      gap: 30px;
      align-items: center;
    }

    .nav-item {
      font-weight: 600;
      color: #121010b2;
      font-size: 18px;
      cursor: pointer;
      transition: color 0.3s;
    }

    .nav-item:hover {
      color: #000;
    }

    .search-container {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 8px 15px;
      border-radius: 25px;
      width: 300px;
    }

    .search-input {
      border: none;
      outline: none;
      flex: 1;
      padding: 5px;
      font-size: 14px;
    }

    .search-icon {
      width: 20px;
      height: 20px;
      cursor: pointer;
    }

    .auth-buttons {
      display: flex;
      gap: 10px;
    }

    .btn {
      padding: 8px 16px;
      border-radius: 5px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      border: none;
      transition: all 0.3s;
    }

    .btn-login {
      background-color: #ffffffba;
      color: #000;
    }

    .btn-signup {
      background-color: #151510;
      color: #fff;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    /* Hero Section dengan foto perpustakaan */
    .hero-section {
      margin: 0 50px 30px 50px;
      border-radius: 30px;
      overflow: hidden;
      position: relative;
      height: 400px;
      background: linear-gradient(135deg, #7cb3e6 0%, #a8d5ff 100%);
    }

    .hero-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.9;
    }

    .hero-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(124, 179, 230, 0.8) 0%, rgba(168, 213, 255, 0.6) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .hero-content {
      color: white;
      z-index: 2;
    }

    .hero-title {
      font-size: 48px;
      font-weight: 700;
      margin-bottom: 15px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
      font-size: 20px;
      font-weight: 400;
      opacity: 0.95;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    /* Main Content */
    .main-content {
      background-color: #7cb3e661;
      border-radius: 30px;
      margin: 0 50px;
      padding: 40px;
      min-height: 800px;
    }

    /* Popular Categories Section */
    .popular-categories {
      margin-bottom: 40px;
    }

    .popular-title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 30px;
      color: #000;
      text-align: center;
    }

    .categories-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .category-section {
      margin-top: 50px;
      margin-bottom: 50px;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .category-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 25px;
    }

    .category-title {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      position: relative;
    }

    

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .view-all-btn {
      background: #007bff;
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .view-all-btn:hover {
      background: #0056b3;
      transform: translateY(-2px);
    }

    .books-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 15px;
    }

    /* Link wrapper untuk buku card */
    .buku-link {
      text-decoration: none;
      color: inherit;
      display: block;
      position: relative;
    }

    .buku-card {
      flex: 0 0 auto;
      width: 180px;
      background: #fff;
      padding: 15px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
      position: relative;
    }

    .buku-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .buku-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .judul {
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 4px;
      color: #333;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      line-height: 1.3;
    }

    .penulis {
      font-size: 10px;
      color: #666;
      font-style: italic;
    }

    /* Badge untuk buku baru */
    .book-new-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: linear-gradient(45deg, #ff6b6b, #ee5a52);
      color: white;
      font-size: 8px;
      font-weight: bold;
      padding: 2px 5px;
      border-radius: 5px;
      z-index: 10;
    }

    /* Badge untuk buku yang baru diupdate */
    .book-updated-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background: linear-gradient(45deg, #4ecdc4, #44a08d);
      color: white;
      font-size: 8px;
      font-weight: bold;
      padding: 2px 5px;
      border-radius: 5px;
      z-index: 10;
    }

    /* Regular categories section */
    .kategori-section {
      margin-bottom: 50px;
    }

    .kategori-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #000;
      padding-bottom: 10px;
      border-bottom: 2px solid #ddd;
    }

    .buku-slider {
      display: flex;
      gap: 20px;
      overflow-x: auto;
      padding: 20px 0;
      scroll-behavior: smooth;
    }

    .buku-slider::-webkit-scrollbar {
      height: 8px;
    }

    .buku-slider::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .buku-slider::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    .buku-slider::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    .no-books {
      text-align: center;
      padding: 40px;
      color: #666;
      font-style: italic;
    }

    /* FOOTER STYLES - DENGAN LOGO IMAGE */
    .footer {
      background-color: #c9e4ff;
      padding: 40px 50px;
      margin-top: 50px;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 50px;
    }

    .footer-content {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 40px;
      margin-bottom: 20px;
    }

    /* Logo Section dengan Image */
    .footer-logo {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .footer-logo-img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      margin-bottom: 15px;
      border-radius: 12px;
      padding: 8px;
    }

    .company-info h3 {
      font-size: 18px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .company-info p {
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 5px;
    }

    /* Operating Hours Section */
    .footer-hours {
      text-align: left;
    }

    .footer-hours h4 {
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .hours-info {
      margin-bottom: 20px;
    }

    .hours-info p {
      font-size: 14px;
      margin-bottom: 8px;
      line-height: 1.4;
    }

    .contact-info h4 {
      font-size: 16px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .contact-info p {
      font-size: 14px;
      margin-bottom: 5px;
    }

    .contact-info a {
      color: black;
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    /* Categories Section */
    .footer-categories {
      text-align: left;
    }

    .footer-categories h4 {
      font-size: 16px;
      margin-bottom: 15px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .categories-list {
      list-style: none;
    }

    .categories-list li {
      margin-bottom: 10px;
    }

    .categories-list a,
    .categories-list span {
      color: black;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s ease;
      cursor: pointer;
    }

    .categories-list a:hover {
      color: #b8d4f0;
      text-decoration: underline;
    }

    .footer-bottom {
      text-align: center;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid rgba(255,255,255,0.2);
      color: rgba(255,255,255,0.8);
    }

    /* Update indicator untuk informasi */
    .update-info {
      font-size: 10px;
      color: #666;
      margin-top: 2px;
      font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header {
        flex-direction: column;
        gap: 20px;
        margin: 20px;
        padding: 20px;
      }

      .nav-menu {
        flex-direction: column;
        gap: 15px;
      }

      .search-container {
        width: 100%;
        max-width: 300px;
      }

      .hero-section {
        margin: 0 20px 20px 20px;
        height: 250px;
      }

      .hero-title {
        font-size: 32px;
      }

      .hero-subtitle {
        font-size: 16px;
      }

      .main-content {
        margin: 0 20px;
        padding: 20px;
      }

      .categories-container {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .books-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
      }

      .buku-slider .buku-card {
        width: 150px;
      }

      .footer-container {
        padding: 0 20px;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
        text-align: center;
      }

      .footer-logo {
        align-items: center;
      }

      .footer-hours,
      .footer-categories {
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <img class="logo" src="logo_e-snap-removebg-preview.png" alt="Logo SMKN 8 Semarang">
    
    <nav class="nav-menu">
      <div class="nav-item">CATEGORIES</div>
      <div class="nav-item">HISTORY</div>
    </nav>

    <div class="search-container">
      <input type="text" class="search-input" placeholder="Cari buku favoritmu..">
      <img class="search-icon" src="img/search-3.svg" alt="Search">
    </div>

    <div class="auth-buttons">
      <button class="btn btn-login">Login</button>
      <button class="btn btn-signup">Sign Up</button>
    </div>
  </header>

  <!-- Hero Section dengan foto perpustakaan -->
  <section class="hero-section">
    <!-- Ganti dengan path foto perpustakaan Anda -->
    <img src="foto_perpustakaan.png" alt="Perpustakaan SMKN 8 Semarang" class="hero-image" onerror="this.style.display='none'">
  </section>

  <!-- Main Content -->
  <main class="main-content">
        
        <!-- New Books Section - Updated dengan logic yang lebih pintar -->
        <div class="category-section">
          <div class="category-header">
            <h3 class="category-title">
              New
              
            </h3>
          </div>
          <div class="books-grid">
            <?php
            // Query untuk mengambil buku yang baru ditambahkan (dalam 7 hari terakhir) 
            // ATAU buku yang baru diupdate (dalam 3 hari terakhir)
            // Diurutkan berdasarkan aktivitas terbaru
            $newBooksQuery = $conn->query("
                SELECT 
                    id_buku, 
                    judul, 
                    penulis, 
                    cover_buku, 
                    created_at,
                    updated_at,
                    CASE 
                        WHEN DATEDIFF(NOW(), created_at) <= 7 THEN 'new'
                        WHEN DATEDIFF(NOW(), updated_at) <= 3 THEN 'updated'
                        ELSE 'old'
                    END as status,
                    GREATEST(created_at, COALESCE(updated_at, created_at)) as latest_activity
                FROM buku 
                WHERE 
                    DATEDIFF(NOW(), created_at) <= 7 
                    OR DATEDIFF(NOW(), COALESCE(updated_at, created_at)) <= 3
                ORDER BY latest_activity DESC 
                LIMIT 5
            ");
            
            if ($newBooksQuery && $newBooksQuery->num_rows > 0):
              while ($buku = $newBooksQuery->fetch_assoc()):
                // Menentukan badge yang akan ditampilkan
                $badgeClass = '';
                $badgeText = '';
                if ($buku['status'] == 'new') {

                    
                } elseif ($buku['status'] == 'updated') {
                    
                    $badgeText = 'UPDATED';
                }
                
                // Format tanggal untuk informasi
                $latestDate = new DateTime($buku['latest_activity']);
                $now = new DateTime();
                $diff = $now->diff($latestDate);
                
                if ($diff->days == 0) {
                    $timeInfo = 'Hari ini';
                } elseif ($diff->days == 1) {
                    $timeInfo = '1 hari lalu';
                } else {
                    $timeInfo = $diff->days . ' hari lalu';
                }
            ?>
                <a href="baca.php?id=<?php echo $buku['id_buku']; ?>" class="buku-link">
                  <div class="buku-card">
                    <?php if ($badgeText): ?>
                        <span class="<?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                    <?php endif; ?>
                    <img src="<?php echo htmlspecialchars($buku['cover_buku']); ?>" 
                         alt="Cover <?php echo htmlspecialchars($buku['judul']); ?>"
                         onerror="this.src='img/no-image.jpg'">
                    <div class="judul"><?php echo htmlspecialchars($buku['judul']); ?></div>
                    <div class="penulis"><?php echo htmlspecialchars($buku['penulis']); ?></div>
                    <div class="update-info"><?php echo $timeInfo; ?></div>
                  </div>
                </a>
            <?php 
              endwhile;
            else:
            ?>
              <div class="no-books">Belum ada buku terbaru</div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Education Category Section -->
        <div class="category-section">
          <div class="category-header">
            <h3 class="category-title">Education</h3>
          </div>
          <div class="books-grid">
            <?php
            // Query untuk mengambil 5 buku dari kategori education
            $educationQuery = $conn->query("SELECT b.id_buku, b.judul, b.penulis, b.cover_buku 
                                          FROM buku b 
                                          JOIN kategori k ON b.id_kategori = k.id_kategori 
                                          WHERE k.nama_kategori = 'education' 
                                          ORDER BY b.id_buku DESC 
                                          LIMIT 5");
            
            if ($educationQuery && $educationQuery->num_rows > 0):
              while ($buku = $educationQuery->fetch_assoc()):
            ?>
                <a href="baca.php?id=<?php echo $buku['id_buku']; ?>" class="buku-link">
                  <div class="buku-card">
                    <img src="<?php echo htmlspecialchars($buku['cover_buku']); ?>" 
                         alt="Cover <?php echo htmlspecialchars($buku['judul']); ?>"
                         onerror="this.src='img/no-image.jpg'">
                    <div class="judul"><?php echo htmlspecialchars($buku['judul']); ?></div>
                    <div class="penulis"><?php echo htmlspecialchars($buku['penulis']); ?></div>
                  </div>
                </a>
            <?php 
              endwhile;
            else:
            ?>
              <div class="no-books">Belum ada buku Education</div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Kids Category Section -->
       

  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-content">
        <!-- Logo dan Info Perusahaan -->
        <div class="footer-logo">
          <!-- Ganti dengan path logo Anda -->
          <img src="logo_e-snap-removebg-preview.png" alt="Logo E-SNAP" class="footer-logo-img" onerror="this.style.display='none'">
          <div class="company-info">
            <p>Jalan Pandanaran 2 No.12,</p>
            <p>Mugassari, Semarang Sel.,</p>
            <p>Kota Semarang, Jawa Tengah</p>
            <p>50249</p>
          </div>
        </div>

        <!-- Jam Operasional dan Kontak -->
        <div class="footer-hours">
          <h4>Jam Operasional</h4>
          <div class="hours-info">
            <p>Senin - Kamis : 07:00 - 16:00</p>
            <p>Jumat : 07:00-14:00</p>
            <p>Sabtu - Minggu : LIBUR</p>
          </div>
          
          <div class="contact-info">
            <h4>Kontak</h4>
            <p>Telepon: <a href="tel:024-8312190">(024) 8312190</a></p>
            <p>E-mail: <a href="mailto:smkn8semarang@gmail.com">smkn8semarang@gmail.com</a></p>
          </div>
        </div>

        <!-- Kategori -->
        <div class="footer-categories">
          <h4>Categories</h4>
          <ul class="categories-list">
            <li><span>EDUCATION</span></li>
            <li><span>KIDS</span></li>
            <li><span>FICTION</span></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© All Rights Reserved By Kelompok 3</p>
      </div>
    </div>
  </footer>

</body>
</html>