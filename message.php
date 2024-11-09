<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'connect.php';

// Get UserID from the current session username
$username = $_SESSION['username'];
$userIDQuery = "SELECT UserID FROM Users WHERE Username = '$username'";
$userIDResult = $conn->query($userIDQuery);

if ($userIDResult->num_rows > 0) {
    $userRow = $userIDResult->fetch_assoc();
    $userID = $userRow['UserID'];

    // Retrieve sent messages
    $sentMessagesQuery = "SELECT MessageID, ReceiverID, MessageText, SendDateTime FROM Messages WHERE SenderID = '$userID'";
    $sentMessagesResult = $conn->query($sentMessagesQuery);

    // Retrieve received messages
    $receivedMessagesQuery = "SELECT MessageID, SenderID, MessageText, SendDateTime FROM Messages WHERE ReceiverID = '$userID'";
    $receivedMessagesResult = $conn->query($receivedMessagesQuery);
}

// Handle message reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_message'])) {
    $replyMessage = $_POST['replyMessage'];
    $messageID = $_POST['messageID'];
    $receiverID = $_POST['receiverID'];

    // Insert the reply message into the database
    $insertReplyQuery = "INSERT INTO Messages (SenderID, ReceiverID, MessageText) VALUES ('$userID', '$receiverID', '$replyMessage')";

    if ($conn->query($insertReplyQuery) === TRUE) {
        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Messages</title>
</head>
<body>

    <div class="container">
        <h2>Sent Messages</h2>
        <?php
        if ($sentMessagesResult->num_rows > 0) {
            while ($row = $sentMessagesResult->fetch_assoc()) {
                echo "<p><strong>Message Sent: </strong>" . $row['SendDateTime'] . "</p>";
                echo "<p>" . $row['MessageText'] . "</p>";
                // Display reply form
                echo "<form method='post'>";
                echo "<input type='hidden' name='messageID' value='" . $row['MessageID'] . "'>";
                echo "<input type='hidden' name='receiverID' value='" . $row['ReceiverID'] . "'>";
                echo "<textarea name='replyMessage' placeholder='Reply to this message'></textarea>";
                echo "<button type='submit' name='reply_message'>Reply</button>";
                echo "</form>";
            }
        } else {
            echo "<p>No sent messages found.</p>";
        }
        ?>

        <h2>Received Messages</h2>
        <?php
        if ($receivedMessagesResult->num_rows > 0) {
            while ($row = $receivedMessagesResult->fetch_assoc()) {
                echo "<p><strong>Message Received: </strong>" . $row['SendDateTime'] . "</p>";
                echo "<p>" . $row['MessageText'] . "</p>";
                // Display reply form
                echo "<form method='post'>";
                echo "<input type='hidden' name='messageID' value='" . $row['MessageID'] . "'>";
                echo "<input type='hidden' name='receiverID' value='" . $row['SenderID'] . "'>";
                echo "<textarea name='replyMessage' placeholder='Reply to this message'></textarea>";
                echo "<button type='submit' name='reply_message'>Reply</button>";
                echo "</form>";
            }
        } else {
            echo "<p>No received messages found.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
    // Close database connection
    $conn->close();
?>
