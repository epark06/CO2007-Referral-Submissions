<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Referral Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Patient Referral Form</h2>
    <form action="processReferral.php" method="POST">
        
        <h3>Referring Physician</h3>
        <div class="form-group">
            <label>Physician Name*</label>
            <input type="text" name="ref_physician" placeholder="Dr. Smith" required>
        </div>
        <div class="form-group">
            <label>Clinic Name*</label>
            <input type="text" name="ref_clinic" required>
        </div>

        <hr>

        <h3>Patient Details</h3>
        <div class="form-group">
            <label>Full Name*</label>
            <input type="text" name="patient_name" required>
        </div>
        <div class="form-group">
            <label>Date of Birth*</label>
            <input type="date" name="patient_dob" required>
        </div>

        <hr>

        <h3>Clinical Information</h3>
        <div class="form-group">
            <label>Urgency Level</label>
            <select name="urgency">
                <option value="routine">Routine</option>
                <option value="urgent">Urgent (24-48 hours)</option>
                <option value="stat">STAT (Immediate)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Notes / Reason for Referral</label>
            <textarea name="reason" rows="4" placeholder="Brief clinical history..."></textarea>
        </div>

        <button type="submit">Send Referral</button>
    </form>
</div>

</body>
</html>