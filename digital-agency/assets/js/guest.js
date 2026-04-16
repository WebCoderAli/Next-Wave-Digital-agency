/* =====================================================
   INDEX PAGE JAVASCRIPT (LANDING PAGE)
   ===================================================== */

document.addEventListener("DOMContentLoaded", () => {
  /* ---------- HERO LOAD ANIMATION ---------- */
  const heroContent = document.querySelector(".hero-content");
  if (heroContent) {
    heroContent.style.opacity = "1";
  }

  /* ---------- SERVICE CARDS SCROLL REVEAL ---------- */
  const serviceCards = document.querySelectorAll(".service-card");

  const revealServices = () => {
    const windowHeight = window.innerHeight;

    serviceCards.forEach((card) => {
      const cardTop = card.getBoundingClientRect().top;

      if (cardTop < windowHeight - 120) {
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      }
    });
  };

  window.addEventListener("scroll", revealServices);
  revealServices();

  /* ---------- WHY US COUNTER EFFECT ---------- */
  const counters = document.querySelectorAll(".why-card h3");

  const runCounter = (counter) => {
    const target = parseInt(counter.innerText);
    let count = 0;
    const speed = target / 60;

    const update = () => {
      count += speed;
      if (count < target) {
        counter.innerText = Math.floor(count) + "+";
        requestAnimationFrame(update);
      } else {
        counter.innerText = target + "+";
      }
    };
    update();
  };

  let counterStarted = false;

  window.addEventListener("scroll", () => {
    const section = document.querySelector(".why-us-section");
    if (!section || counterStarted) return;

    const top = section.getBoundingClientRect().top;
    if (top < window.innerHeight - 100) {
      counters.forEach((counter) => runCounter(counter));
      counterStarted = true;
    }
  });

  /* ---------- PROCESS STEPS HOVER FEEDBACK ---------- */
  const steps = document.querySelectorAll(".process-step");

  steps.forEach((step) => {
    step.addEventListener("mouseenter", () => {
      step.style.transform = "translateY(-8px)";
    });

    step.addEventListener("mouseleave", () => {
      step.style.transform = "translateY(0)";
    });
  });
});

/* =====================================================
   ABOUT PAGE JAVASCRIPT
   ===================================================== */

document.addEventListener("DOMContentLoaded", () => {
  /* ---------- SCROLL REVEAL FOR MISSION & VISION ---------- */
  const mvCards = document.querySelectorAll(".mv-card");

  const revealMvCards = () => {
    const windowHeight = window.innerHeight;

    mvCards.forEach((card) => {
      const top = card.getBoundingClientRect().top;

      if (top < windowHeight - 120) {
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      }
    });
  };

  // Initial state
  mvCards.forEach((card) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(40px)";
    card.style.transition = "all 0.6s ease";
  });

  window.addEventListener("scroll", revealMvCards);
  revealMvCards();

  /* ---------- TEAM CARDS REVEAL ---------- */
  const teamCards = document.querySelectorAll(".about-team .team-card");

  const revealTeam = () => {
    const windowHeight = window.innerHeight;

    teamCards.forEach((card) => {
      const top = card.getBoundingClientRect().top;

      if (top < windowHeight - 100) {
        card.style.opacity = "1";
        card.style.transform = "scale(1)";
      }
    });
  };

  // Initial state
  teamCards.forEach((card) => {
    card.style.opacity = "0";
    card.style.transform = "scale(0.9)";
    card.style.transition = "all 0.5s ease";
  });

  window.addEventListener("scroll", revealTeam);
  revealTeam();
});

/* =====================================================
   SERVICES PAGE JAVASCRIPT
   ===================================================== */

document.addEventListener("DOMContentLoaded", () => {
  /* ---------- SERVICE ROW SCROLL REVEAL ---------- */
  const serviceRows = document.querySelectorAll(".service-row");

  const revealServices = () => {
    const windowHeight = window.innerHeight;

    serviceRows.forEach((row) => {
      const rowTop = row.getBoundingClientRect().top;

      if (rowTop < windowHeight - 120) {
        row.style.opacity = "1";
        row.style.transform = "translateY(0)";
      }
    });
  };

  // Initial hidden state
  serviceRows.forEach((row) => {
    row.style.opacity = "0";
    row.style.transform = "translateY(40px)";
    row.style.transition = "all 0.7s ease";
  });

  window.addEventListener("scroll", revealServices);
  revealServices();

  /* ---------- WHY SERVICE CARDS INTERACTION ---------- */
  const whyCards = document.querySelectorAll(".why-service-card");

  whyCards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      card.style.transform = "translateY(-10px)";
    });

    card.addEventListener("mouseleave", () => {
      card.style.transform = "translateY(0)";
    });
  });
});

/* =========================================
   PRICING PAGE – SIMPLE ANIMATIONS
   ========================================= */

document.addEventListener("DOMContentLoaded", () => {
  /* --------- FADE UP CARDS ON SCROLL --------- */
  const pricingCards = document.querySelectorAll(".pricing-card-ai");
  const faqItems = document.querySelectorAll(".faq-item");

  const revealElements = (elements) => {
    const windowHeight = window.innerHeight;

    elements.forEach((el) => {
      const elementTop = el.getBoundingClientRect().top;

      if (elementTop < windowHeight - 100) {
        el.style.opacity = "1";
        el.style.transform = "translateY(0)";
      }
    });
  };

  /* Initial state */
  [...pricingCards, ...faqItems].forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(30px)";
    el.style.transition = "all 0.5s ease";
  });

  window.addEventListener("scroll", () => {
    revealElements(pricingCards);
    revealElements(faqItems);
  });

  /* Run once on load */
  revealElements(pricingCards);
  revealElements(faqItems);

  /* --------- FEATURED CARD HOVER FEEL --------- */
  const featured = document.querySelector(".pricing-card-ai.featured");

  if (featured) {
    featured.addEventListener("mouseenter", () => {
      featured.style.transform = "scale(1.06)";
    });

    featured.addEventListener("mouseleave", () => {
      featured.style.transform = "scale(1.05)";
    });
  }
});

/* =========================================
   CONTACT PAGE – SIMPLE JS
   ========================================= */

document.addEventListener("DOMContentLoaded", () => {
  /* ---- FADE-UP ANIMATION ON SCROLL ---- */
  const animatedItems = document.querySelectorAll(
    ".col-md-5, .col-md-7, .card",
  );

  const revealOnScroll = () => {
    const windowHeight = window.innerHeight;

    animatedItems.forEach((item) => {
      const itemTop = item.getBoundingClientRect().top;

      if (itemTop < windowHeight - 80) {
        item.style.opacity = "1";
        item.style.transform = "translateY(0)";
      }
    });
  };

  /* Initial state */
  animatedItems.forEach((item) => {
    item.style.opacity = "0";
    item.style.transform = "translateY(30px)";
    item.style.transition = "all 0.5s ease";
  });

  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll();

  /* ---- FORM BUTTON FEEDBACK ---- */
  const submitBtn = document.querySelector("form button");

  if (submitBtn) {
    submitBtn.addEventListener("click", () => {
      submitBtn.innerText = "Sending...";
    });
  }
});
