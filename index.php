<?php
session_start();
require 'config.php';

// Retrieve current content from the database
$sql = "SELECT * FROM users WHERE id = 1";
$result = $conn->query($sql);
$content = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LEEP'S STORE</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--Icons-->
    <script src="https://unpkg.com/feather-icons"></script>

    <!--CSS-->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!--Navbar start-->
    <nav class="navbar">
        <a href="#" class="navbar-logo">LEEP's <span>STORE</span></a>
        <div class="navbar-nav">
            <a href="#home">Beranda</a>
            <a href="#about">Tentang Kami</a>
            <a href="#catalog">Katalog</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="search-button"><i data-feather="search"></i></a>
            <a href="profile.php" id="user-profile"><i data-feather="user"></i></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>

        <!-- Form Pencarian Start -->
        <div class="search-form">
            <input type="search" id="search-box" placeholder="Cari Kebutuhan Anda...">
            <label for="search-box"><a><i data-feather="search"></i></a></label>
            <!-- suggestion search -->
            <div id="suggestion-box" class="suggestion-box"></div>
        </div>
        <!-- Form Pencarian End -->
    </nav>
    <!--Navbar end-->

    <!--Bagian Hero Start-->
    <section class="hero" id="home">
        <main class="content">
            <h1>Wujudkan <span>Pondasi</span> Anda!</h1>
            <br>
            <button class="beautiful-button" onclick="window.location.href='catalog.php'">Cek Sekarang!</button>
        </main>
    </section>
    <!--Bagian Hero End-->

    <!--Bagian Tentang Start-->
    <section class="about" id="about">
        <h2> Tentang <span>Kami</span></h2>
        <div class="row">
            <div class="about-img">
                <img src="css/image/about-us.png" alt="Tentang LEEP'S STORE">
            </div>
            <div class="content">
                <h3>PREVIEW</h3>
                <p>LEEP'S STORE merupakan toko konstruksi terpercaya di kota Solo yang menyediakan berbagai macam kebutuhan bangunan untuk proyek kecil maupun besar. Berlokasi di pusat kota, kami berkomitmen untuk memberikan produk berkualitas tinggi dan layanan terbaik kepada pelanggan kami.</p>
                <p>Terima kasih telah memilih LEEP'S STORE sebagai mitra Anda dalam proyek pembangunan. Kami siap melayani Anda dengan sepenuh hati.</p>
            </div>
        </div>
    </section>
    <!--Bagian Tentang End-->

    <!--Bagian Katalog Start-->
    <section id="catalog" class="catalog">
        <h2> Produk <span>Kami</span></h2>
        <p>Pilih Sesuai Kebutuhan Anda!</p>

        <div class="row">
            <div class="catalog-card">
                <img src="css/image/catalog1-use.png" alt="Material" class="catalog-card-image">
                <h3 class="catalog-card-title">- Material -</h3>
                <p class="catalog-card-desc1">Bahan-bahan yang digunakan dalam proses konstruksi, seperti beton, baja, kayu, dan pipa, yang diperlukan untuk pembangunan struktur atau infrastruktur.</p>
                <br>
                <button class="beautiful-button" onclick="window.location.href='catalog.php'">Lihat</button>
            </div>
            <div class="catalog-card">
                <img src="css/image/catalog2-use.png" alt="K3" class="catalog-card-image">
                <h3 class="catalog-card-title">- K3 -</h3>
                <p class="catalog-card-desc2">Serangkaian peralatan penunjang untuk memastikan keamanan dan kesehatan pekerja, seperti alat pelindung diri (APD), dan alat keselamatan lainnya.</p>
                <br>
                <button class="beautiful-button" onclick="window.location.href='catalog.php'">Lihat</button>
            </div>
            <div class="catalog-card">
                <img src="css/image/catalog3-use.png" alt="Alat Berat" class="catalog-card-image">
                <h3 class="catalog-card-title">- Alat Berat -</h3>
                <p class="catalog-card-desc3">Penyewaan mesin-mesin besar yang digunakan dalam proyek konstruksi untuk melakukan pekerjaan berat seperti penggalian, pemadatan tanah, dan pengangkatan material besar.</p>
                <br>
                <button class="beautiful-button" onclick="window.location.href='catalog.php'">Lihat</button>
            </div>
        </div>
    </section>
    <!--Bagian Katalog End-->

    <!--Bagian Kontak Start-->
    <section id="contact" class="contact">
        <h2><span>Temukan</span> Kami!</h2>

        <div class="row">
            <!--Google Maps-->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.192476378671!2d110.854497!3d-7.553980199999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a16e288bd39b5%3A0x7a56e3d8a35e3bf9!2sJl.%20Surya%20I%20No.26a%2C%20Jebres%2C%20Kec.%20Jebres%2C%20Kota%20Surakarta%2C%20Jawa%20Tengah%2057126!5e0!3m2!1sen!2sid!4v1717218672062!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

            <form>
                <div class="info-group">
                    <i data-feather="map-pin"></i>
                    <span> Jl. Surya I No.26a, Jebres, Kec. Jebres, Kota Surakarta, Jawa Tengah 57126</span>
                </div>
                <div class="info-group">
                    <i data-feather="mail"></i>
                    <span> leepstore@gmail.com</span>
                </div>
                <div class="info-group">
                    <i data-feather="phone"></i>
                    <span> 0812-9004-0388</span>
                </div>
                <button type="button" class="whatsapp-button" onclick="redirectToWhatsApp()">Hubungi Kami!</button>
            </form>
        </div>
    </section>
    <!--Bagian Kontak End-->

    <!--Bagian Footer Start-->
    <footer>
        <div class="socials">
            <a href="#"><i data-feather="instagram"></i></a>
            <a href="#"><i data-feather="facebook"></i></a>
            <a href="#"><i data-feather="twitter"></i></a>
        </div>

        <div class="links">
            <a href="#home">Beranda</a>
            <a href="#about">Tentang Kami</a>
            <a href="#catalog">Katalog</a>
            <a href="#contact">Kontak</a>
        </div>

        <div class="credit">
            <p>Dibuat oleh <a href="">rizkyalifichwanto_222212857</a> | &copy; 2024.</p>
        </div>
    </footer>
    <!--Bagian Footer End-->

    <!--Javascript Internal-->
    <script>
        // feather icons replace
        feather.replace();

        //Toggle class active hamburger menu
        const navbarNav = document.querySelector('.navbar-nav');
        //ketika hamburger menu di klik
        document.querySelector('#hamburger-menu').onclick = (e) => {
            navbarNav.classList.toggle('active');
            e.preventDefault();
        };

        //Toggle class active search form
        const searchForm = document.querySelector('.search-form');
        const searchBox = document.querySelector('#search-box');
        //ketika search menu di klik
        document.querySelector('#search-button').onclick = (e) => {
            searchForm.classList.toggle('active');
            searchBox.focus();
            e.preventDefault();
        };

        //klik diluar elemen
        const hm = document.querySelector('#hamburger-menu');
        const sb = document.querySelector('#search-button');

        document.addEventListener('click', function(e) {
            if (!hm.contains(e.target) && !navbarNav.contains(e.target)) {
                navbarNav.classList.remove('active');
            }
            if (!sb.contains(e.target) && !searchForm.contains(e.target)) {
                searchForm.classList.remove('active');
            }
        });

        // Redirect ke WhatsApp
        function redirectToWhatsApp() {
            window.location.href = 'https://wa.me/6281290040388';
        }

        //AJAX PENCARIAN
        document.addEventListener("DOMContentLoaded", function() {
            // Tambahkan event listener pada input pencarian
            const searchBox = document.getElementById("search-box");
            searchBox.addEventListener("input", function() {
                // Ambil nilai input pencarian
                var query = this.value.toLowerCase();
                if (query === "") {
                    // Kosongkan saran jika input kosong
                    document.getElementById("suggestion-box").innerHTML = "";
                    return;
                }
                // Baca data dari file JSON
                fetch('product.json')
                    .then(response => response.json())
                    .then(data => {
                        // Filter data berdasarkan input pengguna (dengan mengonversi ke huruf kecil)
                        var suggestions = data.filter(product => product.name.toLowerCase().includes(query));

                        // Tampilkan saran nama produk
                        var suggestionHTML = "";
                        suggestions.forEach(product => {
                            suggestionHTML += "<div class='suggestion-item'>" + product.name + "</div>";
                        });
                        document.getElementById("suggestion-box").innerHTML = suggestionHTML;
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Tambahkan event listener untuk blur dan focus pada kolom pencarian
            searchBox.addEventListener("blur", function() {
                if (this.value === "") {
                    this.placeholder = "Masukan keyword";
                }
            });

            searchBox.addEventListener("focus", function() {
                this.placeholder = "";
            });
        });
    </script>
</body>

</html>
