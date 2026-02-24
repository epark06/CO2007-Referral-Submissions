// Add these to the top of script.js with your other selections
const errorBanner = document.getElementById("errorBanner");
const closeBanner = document.getElementById("closeBanner");
const form = document.getElementById("referralForm");
const progressBar = document.getElementById("progressBar");

const fields = {
    ref_physician: {
        el: form.ref_physician,
        validator: v => v.trim().length >= 3,
        errorEl: document.getElementById("errorPhysician"),
        msg: "Physician name must be at least 3 characters.",
        touched: false
    },
    ref_clinic: {
        el: form.ref_clinic,
        validator: v => v.trim().length >= 3,
        errorEl: document.getElementById("errorClinic"),
        msg: "Clinic name must be at least 3 characters.",
        touched: false
    },
    ref_phone: {
        el: form.ref_phone,
        validator: v =>
            /^((\+44\s?|0)7\d{9}|(\+44\s?|0)1\d{9,10})$/.test(
                v.replace(/\s+/g, "")
            ),
        errorEl: document.getElementById("errorPhone"),
        msg: "Enter a valid UK phone number.",
        touched: false
    },
    ref_email: {
        el: form.ref_email,
        validator: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
        errorEl: document.getElementById("errorEmail"),
        msg: "Enter a valid email address.",
        touched: false
    },
    ref_fax: {
        el: form.ref_fax,
        validator: v =>
            v.trim() === "" || /^[0-9\s\-+()]{7,20}$/.test(v),
        errorEl: document.getElementById("errorFax"),
        msg: "Enter a valid fax number or leave blank.",
        touched: false
    },
    patient_name: {
        el: form.patient_name,
        validator: v =>
            v.trim().split(/\s+/).length >= 2 && v.trim().length >= 5,
        errorEl: document.getElementById("errorPatient"),
        msg: "Enter full first and last name.",
        touched: false
    },
    patient_dob: {
        el: form.patient_dob,
        validator: v => v !== "" && new Date(v) < new Date(),
        errorEl: document.getElementById("errorDob"),
        msg: "Date of birth must be in the past.",
        touched: false
    },
    urgency: {
        el: form.urgency,
        validator: v => v !== "",
        errorEl: document.getElementById("errorUrgency"),
        msg: "Please select an urgency level.",
        touched: false
    },
    reason: {
        el: form.reason,
        validator: v => v.trim().length >= 10,
        errorEl: document.getElementById("errorReason"),
        msg: "Reason must be at least 10 characters.",
        touched: false
    }
};

function validateField(field) {
    const valid = field.validator(field.el.value);

    if (!valid && field.touched) {
        field.errorEl.textContent = field.msg;
    } else {
        field.errorEl.textContent = "";
    }

    return valid;
}

function validateAllFields() {
    let validCount = 0;
    const total = Object.keys(fields).length;

    Object.values(fields).forEach(field => {
        if (validateField(field)) validCount++;
    });

    progressBar.style.width = Math.round((validCount / total) * 100) + "%";
    return validCount === total;
}

// Character counter
const textarea = form.reason;
const charCount = document.getElementById("charCount");

textarea.addEventListener("input", () => {
    const remaining = 500 - textarea.value.length;
    charCount.textContent = `${remaining} characters remaining`;
    charCount.style.color = remaining < 50 ? "#d93025" : "#666";
});

// Live validation
Object.values(fields).forEach(field => {
    field.el.addEventListener("input", () => {
        field.touched = true;
        validateAllFields();
    });

    field.el.addEventListener("blur", () => {
        field.touched = true;
        validateAllFields();
    });
});

// Prevent submit unless valid
form.addEventListener("submit", e => {
    Object.values(fields).forEach(f => (f.touched = true));

    if (!validateAllFields()) {
        e.preventDefault();
        const firstError = document.querySelector(".error:not(:empty)");
        if (firstError) {
            firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
});

// Logic to dismiss the banner manually
closeBanner.addEventListener("click", () => {
    errorBanner.style.display = "none";
});

// Update the "Live validation" block to auto-hide the banner
Object.values(fields).forEach(field => {
    field.el.addEventListener("input", () => {
        field.touched = true;
        const isFormValid = validateAllFields();
        
        // Auto-hide banner if the user fixes everything
        if (isFormValid) {
            errorBanner.style.display = "none";
        }
    });

    field.el.addEventListener("blur", () => {
        field.touched = true;
        validateAllFields();
    });
});

// Update the submit handler to show the banner
form.addEventListener("submit", e => {
    Object.values(fields).forEach(f => (f.touched = true));

    if (!validateAllFields()) {
        e.preventDefault();
        
        // Show the banner at the top
        errorBanner.style.display = "flex";
        errorBanner.scrollIntoView({ behavior: "smooth", block: "start" });

        const firstError = document.querySelector(".error:not(:empty)");
        if (firstError) {
            // Optional: You can keep the sub-field scroll or stick to the top banner
            console.log("Validation failed; check the banner.");
        }
    }
});

// Initialise
validateAllFields();
