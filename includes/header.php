<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="index.php">Home</a></li>
                <li onclick="about_modal.showModal()"><a>About</a></li>
                <li onclick="contact_modal.showModal()"><a>Contact</a></li>
                <li>
                    <a>User Access</a>
                    <ul class="p-2">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="registration_student.php">Student registration</a></li>
                        <li><a href="registration_driver.php">Driver registration</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <a class="btn btn-ghost text-xl" href="index.php">University Ride Share</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a href="index.php">Home</a></li>
            <li onclick="about_modal.showModal()"><a>About</a></li>
            <li onclick="contact_modal.showModal()"><a>Contact</a></li>
            <li>
                <details>
                    <summary>User Access</summary>
                    <ul class="p-2">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="registration_student.php">Student registration</a></li>
                        <li><a href="registration_driver.php">Driver registration</a></li>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
    <div class="navbar-end">
        <button class="btn btn-ghost btn-circle hover:bg-transparent">
            <img src="images/logo.png" class="" alt="">
        </button>
    </div>
</div>