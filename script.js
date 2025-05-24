document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm-password");
    const strengthBars = document.querySelectorAll(".strength-bar");
    const strengthText = document.querySelector(".strength-text");
    const matchMessage = document.getElementById("password-match");

    passwordInput.addEventListener("input", function () {
        const value = passwordInput.value;
        let strength = 0;

        // Basic strength criteria
        if (value.length >= 8) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[^A-Za-z0-9]/.test(value)) strength++;

        // Reset bars
        strengthBars.forEach(bar => bar.className = "strength-bar");

        // Update based on strength
        if (value.length === 0) {
            strengthText.textContent = "";
            return;
        }

        if (strength <= 1) {
            strengthBars[0].classList.add("weak");
            strengthText.textContent = "Weak";
        } else if (strength === 2 || strength === 3) {
            strengthBars[0].classList.add("medium");
            strengthBars[1].classList.add("medium");
            strengthText.textContent = "Medium";
        } else {
            strengthBars[0].classList.add("strong");
            strengthBars[1].classList.add("strong");
            strengthBars[2].classList.add("strong");
            strengthText.textContent = "Strong";
        }
    });

    confirmInput.addEventListener("input", function () {
        if (confirmInput.value === passwordInput.value) {
            matchMessage.textContent = "Passwords match";
            matchMessage.className = "validation-message match";
        } else {
            matchMessage.textContent = "Passwords do not match";
            matchMessage.className = "validation-message no-match";
        }
    });
});
