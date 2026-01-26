<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize data
    $physician = htmlspecialchars($_POST['ref_physician']);
    $patient   = htmlspecialchars($_POST['patient_name']);
    $urgency   = htmlspecialchars($_POST['urgency']);

    // Logic for database or email goes here...

    echo "<div style='text-align:center; font-family:sans-serif; margin-top:50px;'>";
    echo "<h1>Submission Received</h1>";
    echo "<p>Referral for <strong>$patient</strong> has been sent to the triage team.</p>";
    echo "<a href='referralForm.php'>Go Back</a>";
    echo "</div>";
}
?>