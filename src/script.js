window.addEventListener('load', () => {
    let idleTimer;

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showInactivityModal, 180000); // 3 minutes
    }

    function showInactivityModal() {
        if (document.getElementById('idleModal')) return;

        const modalHtml = `
            <div id="idleModal" class="idle-modal-overlay">
                <div class="idle-modal-content">
                    <h2 style="color: var(--error); margin-top:0;">Are you still there?</h2>
                    <p>We noticed you haven't interacted with the form for a while.</p>
                    <p><strong>Are you in need of medical assistance?</strong></p>
                    <div style="margin-top: 20px;">
                        <button id="yesBtn" class="idle-btn-stay" style="margin-right:10px;">Yes</button>
                        <button id="noBtn" class="idle-btn-stay">No</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        document.getElementById('yesBtn').addEventListener('click', () => {
            alert("Please call emergency services immediately (999 or 111 in the UK).");
            closeIdleModal();
        });

        document.getElementById('noBtn').addEventListener('click', () => {
            closeIdleModal();
        });
    }

    function closeIdleModal() {
        const modal = document.getElementById('idleModal');
        if (modal) modal.remove();
        resetIdleTimer();
    }

    ['mousemove', 'mousedown', 'keydown', 'touchstart'].forEach(evt => {
        window.addEventListener(evt, resetIdleTimer);
    });

    resetIdleTimer();
});

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

    // UPDATED VALIDATION (AGE MUST BE 0–120)
    patient_dob: {
        el: form.patient_dob,
        validator: v => {
            if (v === "") return false;

            const dob = new Date(v);
            const today = new Date();

            if (dob >= today) return false;

            const age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            const dayDiff = today.getDate() - dob.getDate();

            const adjustedAge =
                monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)
                    ? age - 1
                    : age;

            return adjustedAge >= 0 && adjustedAge <= 120;
        },
        errorEl: document.getElementById("errorDob"),
        msg: "Enter a valid date of birth (age must be between 0 and 120).",
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

    if (progressBar) {
        progressBar.style.width = Math.round((validCount / total) * 100) + "%";
    }
    return validCount === total;
}

const textarea = form.reason;
const charCount = document.getElementById("charCount");

if (textarea && charCount) {
    textarea.addEventListener("input", () => {
        const remaining = 500 - textarea.value.length;
        charCount.textContent = `${remaining} characters remaining`;
        charCount.style.color = remaining < 50 ? "#d93025" : "#666";
    });
}

Object.values(fields).forEach(field => {
    if (!field.el) return;
    field.el.addEventListener("input", () => {
        field.touched = true;
        validateAllFields();
    });

    field.el.addEventListener("blur", () => {
        field.touched = true;
        validateAllFields();
    });
});

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

validateAllFields();

(function () {
    const toggle = document.getElementById('themeToggle');
    const stored = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initial = stored || (systemPrefersDark ? 'dark' : 'light');

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark-mode');
            if (toggle) {
                toggle.textContent = '☀️ Light';
                toggle.setAttribute('aria-pressed', 'true');
            }
        } else {
            document.body.classList.remove('dark-mode');
            if (toggle) {
                toggle.textContent = '🌙 Dark';
                toggle.setAttribute('aria-pressed', 'false');
            }
        }
    }

    applyTheme(initial);

    if (toggle) {
        toggle.addEventListener('click', () => {
            const newTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    if (!stored && window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            applyTheme(e.matches ? 'dark' : 'light');
        });
    }
})();

closeBanner.addEventListener("click", () => {
    errorBanner.style.display = "none";
});

Object.values(fields).forEach(field => {
    field.el.addEventListener("input", () => {
        field.touched = true;
        const isFormValid = validateAllFields();
        
        if (isFormValid) {
            errorBanner.style.display = "none";
        }
    });

    field.el.addEventListener("blur", () => {
        field.touched = true;
        validateAllFields();
    });
});

form.addEventListener("submit", e => {
    Object.values(fields).forEach(f => (f.touched = true));

    if (!validateAllFields()) {
        e.preventDefault();
        
        errorBanner.style.display = "flex";
        errorBanner.scrollIntoView({ behavior: "smooth", block: "start" });

        const firstError = document.querySelector(".error:not(:empty)");
        if (firstError) {
            console.log("Validation failed; check the banner.");
        }
    }
});
