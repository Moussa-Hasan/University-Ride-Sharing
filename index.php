<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
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

    <!-- Hero Section -->
    <div class="hero min-h-screen" style="background-image: url('images/hero.jpg'); min-height: 93vh;">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">Reserve a Seat in a Driver's Car</h1>
                <p class="mb-5">Connect with drivers in your university to reserve a seat in their car and commute conveniently from campus to home.</p>
                <a href="registration_student.php"><button class="btn glass text-white hover:text-gray-800">Get Started</button></a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-200 rounded-lg p-6 shadow-lg hover:bg-gray-100 duration-300 transition cursor-pointer">
                    <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-700">Easy to Use</h2>
                    <p class="text-gray-700">Our platform makes it simple for students to reserve a seat and for drivers to offer rides from campus to home.</p>
                </div>
                <div class="bg-gray-200 rounded-lg p-6 shadow-lg hover:bg-gray-100 duration-300 transition cursor-pointer">
                    <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-700">Save Time and Money</h2>
                    <p class="text-gray-700">By reserving a seat in a driver's car, you can reduce your commuting time and share the cost of fuel, making it more affordable for everyone.</p>
                </div>
                <div class="bg-gray-200 rounded-lg p-6 shadow-lg hover:bg-gray-100 duration-300 transition cursor-pointer">
                    <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-700">Contribute to Sustainability</h2>
                    <p class="text-gray-700">Reducing the number of vehicles on the road helps lower carbon emissions and promotes a more sustainable campus environment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-gradient-to-r from-slate-700 via-slate-600 to-slate-500">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">Ready to Get Started As a Driver?</h2>
            <p class="text-lg text-white mb-8">Become a part of the University Ride community and start accepting reservations for passengers in your car today.</p>
            <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-4">
                <a href="registration_driver.php"><button class="btn glass text-white hover:text-gray-800">Sign Up as a Driver now</button></a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- flowbit js  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <!-- daisy ui -->
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>