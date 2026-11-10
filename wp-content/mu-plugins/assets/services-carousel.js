(function () {
  "use strict";

  var carousels = document.querySelectorAll("[data-maa-services-carousel]");
  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  carousels.forEach(function (carousel) {
    var slides = Array.prototype.slice.call(carousel.querySelectorAll("[data-slide]"));
    var track = carousel.querySelector("[data-slides]");
    var viewport = carousel.querySelector(".maa-services-carousel__viewport");
    var counter = carousel.querySelector("[data-counter]");
    var previous = carousel.querySelector("[data-previous]");
    var next = carousel.querySelector("[data-next]");

    if (slides.length <= 3 || !track || !viewport || !previous || !next) {
      return;
    }

    slides.forEach(function (slide) {
      slide.removeAttribute("hidden");
    });

    var activeIndex = 0;
    var visibleCount = 3;
    var cloneCount = Math.min(visibleCount, slides.length);
    var trackPosition = cloneCount;
    var timer;
    var isAnimating = false;
    var gap = parseFloat(window.getComputedStyle(track).rowGap) || 0;
    var stride = slides[0].getBoundingClientRect().height + gap;

    var beforeClones = document.createDocumentFragment();
    slides.slice(-cloneCount).forEach(function (slide) {
      var clone = slide.cloneNode(true);
      clone.removeAttribute("data-slide");
      clone.setAttribute("aria-hidden", "true");
      clone.querySelectorAll("a").forEach(function (link) {
        link.setAttribute("tabindex", "-1");
      });
      beforeClones.appendChild(clone);
    });
    track.insertBefore(beforeClones, track.firstChild);

    slides.slice(0, cloneCount).forEach(function (slide) {
      var clone = slide.cloneNode(true);
      clone.removeAttribute("data-slide");
      clone.setAttribute("aria-hidden", "true");
      clone.querySelectorAll("a").forEach(function (link) {
        link.setAttribute("tabindex", "-1");
      });
      track.appendChild(clone);
    });

    function setTrackPosition(position, animate) {
      trackPosition = position;
      track.style.transition = animate ? "" : "none";
      track.style.transform = "translate3d(0, -" + (trackPosition * stride) + "px, 0)";
    }

    function updateVisibleSlides() {
      slides.forEach(function (slide, slideIndex) {
        var offset = (slideIndex - activeIndex + slides.length) % slides.length;
        var isVisible = offset < visibleCount;
        slide.setAttribute("aria-hidden", isVisible ? "false" : "true");
        slide.querySelector("a").setAttribute("tabindex", isVisible ? "0" : "-1");
      });
    }

    function updateCounter() {
      if (counter) {
        var firstIndex = (activeIndex + slides.length) % slides.length;
        var lastIndex = (firstIndex + visibleCount - 1) % slides.length;
        counter.textContent = String(firstIndex + 1).padStart(2, "0") + "-" +
          String(lastIndex + 1).padStart(2, "0") + " / " + String(slides.length).padStart(2, "0");
      }
    }

    function move(direction) {
      if (isAnimating) {
        return;
      }

      activeIndex += direction;
      isAnimating = !reducedMotion;
      setTrackPosition(cloneCount + activeIndex, !reducedMotion);
      updateVisibleSlides();
      updateCounter();

      if (reducedMotion) {
        if (activeIndex >= slides.length) {
          activeIndex = 0;
        } else if (activeIndex < 0) {
          activeIndex = slides.length - 1;
        }
        setTrackPosition(cloneCount + activeIndex, false);
        updateVisibleSlides();
        updateCounter();
      }
    }

    track.addEventListener("transitionend", function (event) {
      if (event.target !== track || "transform" !== event.propertyName) {
        return;
      }

      if (activeIndex >= slides.length) {
        activeIndex = 0;
        setTrackPosition(cloneCount, false);
      } else if (activeIndex < 0) {
        activeIndex = slides.length - 1;
        setTrackPosition(cloneCount + activeIndex, false);
      }

      updateVisibleSlides();
      updateCounter();
      isAnimating = false;
    });

    setTrackPosition(cloneCount, false);
    updateVisibleSlides();

    function stopAutoplay() {
      window.clearInterval(timer);
      timer = null;
    }

    function startAutoplay(force) {
      if (reducedMotion || timer || document.hidden || (!force && carousel.contains(document.activeElement))) {
        return;
      }

      timer = window.setInterval(function () {
        move(1);
      }, 5000);
    }

    previous.addEventListener("click", function () {
      move(-1);
      stopAutoplay();
      startAutoplay(true);
    });

    next.addEventListener("click", function () {
      move(1);
      stopAutoplay();
      startAutoplay(true);
    });

    carousel.addEventListener("focusin", stopAutoplay);
    carousel.addEventListener("focusout", function (event) {
      if (!carousel.contains(event.relatedTarget)) {
        startAutoplay();
      }
    });

    document.addEventListener("visibilitychange", function () {
      if (document.hidden) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    });

    startAutoplay();
  });
})();
