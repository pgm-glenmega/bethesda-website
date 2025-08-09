document.addEventListener("DOMContentLoaded", function () {
  if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;

  gsap.registerPlugin(ScrollTrigger);

  const $fadeTitles = document.querySelectorAll("[data-animation='moveUpTitle']");

  $fadeTitles.forEach(e => {
    gsap.from(e, {
      y: 100,
      opacity: 0,
      rotate: 2,
      duration: 1.5,
      ease: "power3.out",
      scrollTrigger: {
        trigger: e,
        start: "top 80%",
        toggleActions: "play none none reset"
      }
    });
  });
});
