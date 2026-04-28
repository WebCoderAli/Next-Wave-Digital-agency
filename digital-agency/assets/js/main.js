/* =========================================================
   DIGITAL AGENCY - MAIN JAVASCRIPT
   Author: Uni Project
========================================================= */

"use strict";

document.addEventListener("DOMContentLoaded", function () {
  /* =====================================================
       AUTO HIDE ALERTS
    ===================================================== */
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 500);
    }, 3500);
  });

  /* =====================================================
       CONFIRM DELETE (GLOBAL)
    ===================================================== */
  const deleteButtons = document.querySelectorAll("[data-confirm]");
  deleteButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const msg = this.getAttribute("data-confirm") || "Are you sure?";
      if (!confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  /* =====================================================
       IMAGE PREVIEW (UPLOAD)
    ===================================================== */
  const imageInputs = document.querySelectorAll("input[type='file']");
  imageInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const previewId = this.getAttribute("data-preview");
      if (!previewId) return;

      const preview = document.getElementById(previewId);
      if (!preview || !this.files.length) return;

      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(this.files[0]);
    });
  });

  /* =====================================================
       SMOOTH SCROLL
    ===================================================== */
  document.querySelectorAll("a[href^='#']").forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href"))?.scrollIntoView({
        behavior: "smooth",
      });
    });
  });

  /* =====================================================
       DISABLE MULTIPLE SUBMITS
    ===================================================== */
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function () {
      const btn = this.querySelector(
        "button[type='submit'], button:not([type])",
      );
      if (btn) {
        btn.disabled = true;
        btn.innerText = "Processing...";
      }
    });
  });

  /* =====================================================
       TOOLTIP INIT (BOOTSTRAP)
    ===================================================== */
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
  );
  tooltipTriggerList.forEach((el) => {
    new bootstrap.Tooltip(el);
  });

  /* =====================================================
       DASHBOARD CARD ANIMATION
    ===================================================== */
  const cards = document.querySelectorAll(".card");
  cards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.style.transform = "translateY(-6px)";
    });
    card.addEventListener("mouseleave", () => {
      card.style.transform = "translateY(0)";
    });
  });
});

/* =========================================================
   GLOBAL UTILITY FUNCTIONS
========================================================= */

// Format date (optional utility)
function formatDate(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}
