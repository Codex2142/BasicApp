<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Zakiah</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .hero-section {
            position: relative;
            background-image: url('{{ asset('images/LockSceeen.png') }}');
            background-size: cover;
            background-position: center;
            height: 60vh;
            color: white;
            display: flex;
            /* Center X */
            justify-content: center;
            /* Center X */
            align-items: center;
            /* Center Y */
            text-align: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Overlay gelap */
            z-index: 1;
        }

        .hero-section>div {
            position: relative;
            z-index: 2;
        }


        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 9999px;
        }

        footer {
            background-color: #212529;
            color: white;
            padding: 2rem 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="">Toko Zakiah</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div>
            <h1>Website Informatif Toko Zakiah</h1>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tenetur, quisquam ullam omnis, sunt sequi
                praesentium impedit dignissimos laboriosam quidem iste quibusdam quas porro, optio tempora! Facilis quia
                quibusdam quisquam sit?</p>
        </div>
    </div>

    <!-- Profil & Jadwal Buka -->
    <div class="container py-5">
        <div class="row gap-2 justify-content-center">
            <div class="col-md-5 bg-body-emphasis rounded shadow p-3">
                <h2>Profil Perusahaan</h2>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quasi eaque sed omnis ab quis aperiam
                    consequatur placeat magnam ipsum, mollitia voluptatum doloremque accusamus, consequuntur asperiores
                    eligendi necessitatibus possimus quam aliquid?</p>
            </div>
            <div class="col-md-5 bg-body-emphasis rounded shadow p-3">
                <h2>Jadwal Buka Toko</h2>
                <table>
                    <tr>
                        <td>Senin</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Selasa</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Rabu</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Kamis</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Jumat</td>
                        <td>: 17.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Sabtu</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                    <tr>
                        <td>Minggu</td>
                        <td>: 08.00 - 20.30</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Produk Section -->
    <div class="container pb-5 bg-body-emphasis rounded shadow p-3">
        <h2 class="text-center mb-4">Produk Kami</h2>

        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Cari Produk" aria-label="Cari Produk"
                aria-describedby="button-addon2">
            <button class="btn btn-danger" type="button" id="button-addon2">X</button>
        </div>
        <div id="product-container" class="row g-3">
            @foreach ($result as $item)
                <div class="col-6 col-md-3 col-lg-1-5 justify-center">
                    <div class="card product-card text-center p-2">
                        <img src="{{ $item->photo ? asset('storage/' . $item->photo) : 'https://placehold.co/70x70/png' }}"
                            alt="Product Image" class="mx-auto d-block"
                            style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
                        <div class="card-body p-2">
                            <p class="mb-1">{{ $item->name }}</p>
                            <p class="mb-0">Rp {{ number_format($item->price1, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <button id="prevPage" class="btn btn-outline-primary me-2">&laquo; Prev</button>
            <button id="nextPage" class="btn btn-outline-primary">Next &raquo;</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <div class="container">
            <p>&copy; 2025 Toko Zakiah. All rights reserved.</p>
        </div>
    </footer>

    <!-- Corner Button -->
    @auth
        <div
            style="background-color: #14532d; color: white; position: fixed; bottom: 20px; right: 20px; padding: 10px 15px; border-radius: 8px; cursor: pointer; transition: background-color 0.3s, color 0.3s;">
            <a href="/Beranda" style="color: inherit; text-decoration: none;">
                <i class="bi bi-house"></i>
            </a>
        </div>
    @endauth

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.querySelector('input[placeholder="Cari Produk"]');
            const cards = document.querySelectorAll('#product-container .col-6');

            input.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();

                cards.forEach(card => {
                    const name = card.querySelector('.card-body p').textContent.toLowerCase();
                    if (name.includes(keyword)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Tombol reset/X
            const resetButton = document.getElementById('button-addon2');
            resetButton.addEventListener('click', function() {
                input.value = '';
                cards.forEach(card => card.style.display = 'block');
            });
        });

        const cardsPerPage = window.innerWidth >= 992 ? 8 : 4;
        const allCards = Array.from(document.querySelectorAll('#product-container .col-6'));
        let currentPage = 0;

        function renderPage(page) {
            const start = page * cardsPerPage;
            const end = start + cardsPerPage;
            allCards.forEach((card, i) => {
                card.style.display = i >= start && i < end ? 'block' : 'none';
            });
        }

        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 0) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const maxPage = Math.floor(allCards.length / cardsPerPage);
            if (currentPage < maxPage) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        window.addEventListener('resize', () => location.reload()); // reload page on resize
        renderPage(currentPage);
    </script>
</body>

</html>
