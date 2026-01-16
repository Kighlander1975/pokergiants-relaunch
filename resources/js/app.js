import "./bootstrap";
import "../css/app.css";
import "../css/components/navbar.css";
import "../css/pages/home.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "cropperjs/dist/cropper.css";
import Cropper from "cropperjs";

window.Cropper = Cropper;

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Import admin modules
import "./admin/headline-editor";

// Wrap .one-card elements in a separate container to force new line
document.addEventListener("DOMContentLoaded", function () {
    const oneCards = document.querySelectorAll(".one-card");
    oneCards.forEach((card) => {
        const wrapper = document.createElement("div");
        wrapper.className = "one-card-wrapper";
        card.parentNode.insertBefore(wrapper, card);
        wrapper.appendChild(card);
    });
});
// Bootstrap Toast System for Flash Messages
document.addEventListener("DOMContentLoaded", function () {
    // Success Message
    if (window.Laravel && window.Laravel.success) {
        showToast(window.Laravel.success, "success");
    }
    // Error Message
    if (window.Laravel && window.Laravel.error) {
        showToast(window.Laravel.error, "danger");
    }
});

function showToast(message, type = "info") {
    const toastContainer = document.querySelector(".toast-container");
    if (!toastContainer) return;

    const toastId = "toast-" + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML("beforeend", toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000,
    });
    toast.show();

    // Remove from DOM after hide
    toastElement.addEventListener("hidden.bs.toast", () => {
        toastElement.remove();
    });
}

// Count-up animation for dashboard statistics
document.addEventListener("DOMContentLoaded", function () {
    const countElements = document.querySelectorAll(".count-up");

    countElements.forEach((element) => {
        const targetValue = parseInt(element.textContent.replace(/\D/g, ""));
        let currentValue = 0;
        const duration = 500; // 0.5 seconds
        const increment = targetValue / (duration / 16); // 60 FPS

        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }
            element.textContent = Math.floor(currentValue).toLocaleString();
        }, 16);
    });
});
