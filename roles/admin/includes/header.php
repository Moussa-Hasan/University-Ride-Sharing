<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="dashboard.php">Homepage</a></li>
                <li>
                    <a>Users</a>
                    <ul class="p-2">
                        <li><a href="students_managment.php">Students</a></li>
                        <li><a href="drivers_managment.php">Drivers</a></li>
                    </ul>
                </li>
                <li><a href="reservations_managment.php">Reservations</a></li>
                <li><a href="rides_managment.php">Rides</a></li>
                <li><a href="comments_managment.php">Comments</a></li>
                <li><a href="setting_managment.php">Settings</a></li>
            </ul>
        </div>
        <a class="btn btn-ghost text-xl" href="dashboard.php">Admin Dashboard</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="dashboard.php">Homepage</a></li>
            <li>
                <details>
                    <summary>Users</summary>
                    <ul class="p-2">
                        <li><a href="students_managment.php">Students</a></li>
                        <li><a href="drivers_managment.php">Drivers</a></li>
                    </ul>
                </details>
            </li>
            <li><a href="reservations_managment.php">Reservations</a></li>
            <li><a href="rides_managment.php">Rides</a></li>
            <li><a href="comments_managment.php">Comments</a></li>
            <li><a href="setting_managment.php">Settings</a></li>
        </ul>
    </div>
    <div class="navbar-end">
        <button class="btn btn-ghost btn-circle" onclick="logout_modal.showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" />
            </svg>
        </button>
    </div>

</div>