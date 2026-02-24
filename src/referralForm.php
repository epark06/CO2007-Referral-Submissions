<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    function clean($value) {
        return htmlspecialchars($value ?? '');
    }
    $patient = clean($_POST['patient_name'] ?? '');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Referral Submitted</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container success">
            <h2>Referral Submitted ✓</h2>
            <p>
                The referral for <strong><?= $patient ?></strong> has been successfully
                submitted and sent to the triage team.
            </p>
            <a href="referralForm.php">
                <button>Submit Another Referral</button>
            </a>
        </div>
    </body>
    </html>
    <?php exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Referral Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div id="errorBanner" class="error-banner" style="display: none;">
        <span class="error-message">Please correct the highlighted errors before submitting.</span>
        <span class="close-banner" id="closeBanner">&times;</span>
    </div>
    
    <button id="themeToggle" class="theme-toggle" type="button" aria-pressed="false" aria-label="Toggle dark mode">🌙 Dark</button>

    <h2>Patient Referral Form</h2>

    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <form action="processReferral.php" method="POST" id="referralForm" novalidate>

        <h3>Referring Physician</h3>

        <div class="form-group">
            <label>
                Physician Name
                <span class="required-star"
                      data-tooltip="Enter the full name of the referring physician (minimum 3 characters).">*</span>
            </label>
            <input type="text" name="ref_physician" required>
            <div class="error" id="errorPhysician"></div>
        </div>

        <div class="form-group">
            <label>
                Clinic Name
                <span class="required-star"
                      data-tooltip="Enter the name of the clinic or medical practice (minimum 3 characters).">*</span>
            </label>
            <input type="text" name="ref_clinic" required>
            <div class="error" id="errorClinic"></div>
        </div>

        <hr>

        <h3>Contact Information</h3>

        <div class="contact-grid">
            <div class="form-group">
                <label>
                    Phone Number
                    <span class="required-star"
                          data-tooltip="Enter a valid UK phone number (mobile or landline).">*</span>
                </label>
                <input type="tel" name="ref_phone" placeholder="07123 456789" required>
                <div class="error" id="errorPhone"></div>
            </div>

            <div class="form-group">
                <label>
                    Email Address
                    <span class="required-star"
                          data-tooltip="Enter a valid professional email address (e.g. doctor@clinic.com).">*</span>
                </label>
                <input type="email" name="ref_email" placeholder="doctor@clinic.com" required>
                <div class="error" id="errorEmail"></div>
            </div>

            <div class="form-group">
                <label>Fax Number</label>
                <input type="tel" name="ref_fax" placeholder="0161 123 4567">
                <div class="error" id="errorFax"></div>
            </div>
        </div>

        <hr>

        <h3>Patient Details</h3>

        <div class="form-group">
            <label>
                Full Name
                <span class="required-star"
                      data-tooltip="Enter the patient’s full legal first and last name.">*</span>
            </label>
            <input type="text" name="patient_name" required>
            <div class="error" id="errorPatient"></div>
        </div>

        <div class="form-group">
            <label>
                Date of Birth
                <span class="required-star"
                      data-tooltip="Select the patient’s date of birth (must be before today).">*</span>
            </label>
            <input type="date" name="patient_dob" required>
            <div class="error" id="errorDob"></div>
        </div>

        <hr>

        <h3>Clinical Information</h3>

        <div class="form-group">
            <label>
                Urgency Level
                <span class="required-star"
                      data-tooltip="Choose how urgently the patient needs to be seen.">*</span>
            </label>
            <select name="urgency" required>
                <option value="">Select urgency</option>
                <option value="Routine">Routine</option>
                <option value="Urgent">Urgent (24–48 hours)</option>
                <option value="STAT">STAT</option>
            </select>
            <div class="error" id="errorUrgency"></div>
        </div>

        <div class="form-group">
            <label>
                Notes / Reason for Referral
                <span class="required-star"
                      data-tooltip="Provide a brief clinical reason for referral (minimum 10 characters, max 500).">*</span>
            </label>
            <textarea
                name="reason"
                maxlength="500"
                required
                placeholder="Brief clinical history (max 500 characters)"
            ></textarea>
            <small id="charCount">500 characters remaining</small>
            <div class="error" id="errorReason"></div>
        </div>

        <button type="submit">Submit Referral</button>
    </form>
</div>

<script src="script.js"></script>
</body>
</html>
