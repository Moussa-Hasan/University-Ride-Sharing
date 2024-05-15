<?php
session_start();

// Check if the necessary session variables are not set
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../../../login.php");
    exit();
}

$student_id = $_SESSION["user_id"];
$student_name = $_SESSION['name'];
$student_role = $_SESSION['user_role'];

if (isset($_GET['id'])) {
    $driver_id_get = $_GET['id'];
    $_SESSION['driver_id'] = $driver_id_get;
} elseif (isset($_SESSION['driver_id'])) {
    $driver_id_get = $_SESSION['driver_id']; // Retrieve from session
}

require_once '../../../includes/connection.php';

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Function to fetch chat messages
function getChatMessages($driver_id, $student_id)
{
    global $con;
    $stmt = $con->prepare("SELECT * FROM comment WHERE (driver_id = ? AND student_id = ?) OR (driver_id = ? AND student_id = ?) ORDER BY timestamp ASC");
    $stmt->bind_param("iiii", $driver_id, $student_id, $student_id, $driver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $messages;
}

// Function to add a comment
function addComment($driver_id, $student_id, $comment)
{
    global $con;
    $insert_stmt = $con->prepare("INSERT INTO comment (driver_id, student_id, comment, role, timestamp) VALUES (?, ?, ?, 'student', NOW())");
    $insert_stmt->bind_param("iis", $driver_id, $student_id, $comment);

    if ($insert_stmt->execute()) {
        // Comment added successfully
        return true;
    } else {
        // Failed to add comment
        return false;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $student_id = $student_id;
    $driver_id = $driver_id_get;

    if (addComment($driver_id, $student_id, $comment)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Failed to add comment.";
    }
}

// Fetch chat messages
$driver_id = $driver_id_get;
$student_id = $student_id;
$chatMessages = getChatMessages($driver_id, $student_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../../../dist/output.css">
    <!-- flowbit js  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.css" rel="stylesheet" />
    <!-- daisy ui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.17/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="flex flex-col h-screen">
        <header class="bg-gray-200 p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Chat</h1>
            <a href="../student.php" class="btn btn-circle btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </header>
        <main class="flex-grow bg-gray-100 p-4 overflow-y-auto">
            <div class="flex flex-col space-y-2">
                <!-- Chat messages -->
                <?php foreach ($chatMessages as $message) : ?>
                    <?php if ($message['role'] == 'student') : ?>
                        <!-- Student's message on the right -->
                        <div class="chat chat-end">
                            <div class="chat-image avatar">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full">
                                    <img src="../../../images/passenger.png" alt="">
                                </div>
                            </div>
                            <div class="chat-header">
                                Student
                                <time class="text-xs opacity-50"><? echo $message['timestamp']; ?></time>
                            </div>
                            <div class="chat-bubble"><?= htmlspecialchars($message['comment']); ?></div>
                        </div>
                        
                    <?php elseif ($message['role'] == 'driver') : ?>
                        <!-- Driver's message on the left -->
                        <div class="chat chat-start">
                            <div class="chat-image avatar">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full">
                                    <img src="../../../images/driver.png" alt="">
                                </div>
                            </div>
                            <div class="chat-header">
                                Driver
                                <time class="text-xs opacity-50"><? echo $message['timestamp']; ?></time>
                            </div>
                            <div class="chat-bubble"><?= htmlspecialchars($message['comment']); ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </main>
        <footer class="bg-gray-200 p-4">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="flex items-center">
                <input type="text" name="comment" placeholder="Type your message..." class="flex-1 block w-full py-2 px-4 font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
                <button type="submit" class="btn glass flex-shrink-0 ml-4 text-white bg-slate-700 hover:bg-slate-800 py-2 px-4 font-semibold rounded-lg">
                    Send
                </button>
            </form>
        </footer>
    </div>

    <!-- daisy ui -->
    <script src="https://cdn.tailwindcss.com"></script>

</body>

</html>