<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</nav>

<div id="loading-screen">
    <div class="loading-bar"></div>
</div>






<style>
 /* Loading Screen */
#loading-screen {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #3498db, #2980b9); /* Gradasi warna */
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: flex-end; /* Posisi loading bar di bawah */
    overflow: hidden; /* Sembunyikan bagian yang keluar */
}

/* Loading Bar Animasi */
.loading-bar {
    width: 100%;
    height: 0%;
    background: #f4f4f4; /* Background untuk area loading */
    animation: riseUp 1.5s ease-in-out forwards;
}

/* Animasi dari bawah ke atas */
@keyframes riseUp {
    0% {
        height: 0%; /* Awal: hanya terlihat garis kecil di bawah */
    }
    100% {
        height: 100%; /* Akhir: memenuhi layar */
    }
}

/* Hilangkan loading screen */
#loading-screen.hidden {
    animation: fadeOut 0.5s ease-out forwards;
}

/* Animasi untuk menghilangkan loading */
@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        visibility: hidden;
    }
}

</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loadingScreen = document.getElementById("loading-screen");

        // Sembunyikan loading setelah halaman selesai dimuat
        window.addEventListener("load", function () {
            setTimeout(() => {
                loadingScreen.classList.add("hidden");
            }, 100); // Durasi animasi 1.5 detik
        });

        // Opsional: Fungsi untuk memunculkan kembali loading secara manual
        window.showLoading = function () {
            loadingScreen.classList.remove("hidden");
        };

        window.hideLoading = function () {
            loadingScreen.classList.add("hidden");
        };
    });
</script>

