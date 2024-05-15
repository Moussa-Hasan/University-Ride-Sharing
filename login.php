<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="dist/output.css">
    <!-- flowbit js  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.css" rel="stylesheet" />
    <!-- daisy ui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.17/dist/full.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- Navigation bar -->
    <?php include 'includes/header.php'; ?>

    <!-- About modal -->
    <?php include 'includes/about_modal.php'; ?>

    <!-- Contact modal -->
    <?php include 'includes/contact_modal.php'; ?>

    <?php
    // Check if an error parameter is present in the URL
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        // Display the error notification with a close button
        echo '<div class="flex justify-between items-center bg-red-500 text-white px-4 py-2 mb-4">
              <span>Login failed. Please check your credentials</span>
              <a href="login.php" class="text-white ml-2">&times;</a>
          </div>';
    }
    ?>

    <section class="">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen">
            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Login
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="actions/login_action.php" method="POST">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="name@gmail.com" value="<?php echo isset($_SESSION['login_form_values']['email']) ? $_SESSION['login_form_values']['email'] : ''; ?>">
                            <?php if (isset($_SESSION['login_errors']['email'])) : ?>
                                <span class="text-red-500 text-sm"><?php echo $_SESSION['login_errors']['email']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" value="<?php echo isset($_SESSION['login_form_values']['password']) ? $_SESSION['login_form_values']['password'] : ''; ?>">
                            <?php if (isset($_SESSION['login_errors']['password'])) : ?>
                                <span class="text-red-500 text-sm"><?php echo $_SESSION['login_errors']['password']; ?></span>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
                        <p class="text-sm font-light text-gray-500">
                            Don’t have an account yet? <a href="registration_student.php" class="font-medium text-blue-600 hover:underline">Sign up</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Clear the login errors from the session
    unset($_SESSION['login_errors']);
    ?>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- flowbit js  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <!-- daisy ui -->
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>