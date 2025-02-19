
<!-- Bootstrap Header -->
    <div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font">Robert WebSite</h3>
    <a href="logout.php" class="btn btn-light bn-sm">Log Out</a>

</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid flex-lg-column align-items-stretch">
    <h4 class="mt-2 text-light">Admin Panel</h4>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <button class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
               <span>Bookings</span>
               <i class ="bi bi-caret-down-fill"></i>
            </button>
            <div class="collapse" id="bookingLinks">
                <ul class="nav nav-pills flex-column ms-3">
                    <li class="nav-item border white">
                        <a class="nav-link text-white" href="new_bookings.php">New Bookings</a>
                    </li>
                    <li class="nav-item border white">
                        <a class="nav-link text-white" href="booking_requests.php">Booking Requests</a>
                    </li>
                    <li class="nav-item border white">
                        <a class="nav-link text-white" href="booking_confirmed.php">Confirmed Bookings</a>
                    </li>
                    <li class="nav-item border white">
                        <a class="nav-link text-white" href="booking_cancelled.php">Cancelled Bookings</a>
                    </li>
                </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="users.php">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="user_queries.php">User Queries</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="rooms.php">Rooms</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="features_facilities.php">Features & Facilities</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-white" href="carousel.php">Carousel</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="settings.php">Settings</a>
        </li>
    </ul>
    </div>
    </div>
</nav>
</div>
