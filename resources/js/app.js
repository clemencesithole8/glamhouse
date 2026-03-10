import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  if (!body) {
    return;
  }

  body.classList.add('js-motion');

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const revealNodes = Array.from(document.querySelectorAll('.soft-reveal'));

  if (prefersReducedMotion) {
    revealNodes.forEach((node) => node.classList.add('is-visible'));
  } else {
    const revealObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }

          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        });
      },
      {
        threshold: 0.18,
        rootMargin: '0px 0px -8% 0px',
      },
    );

    revealNodes.forEach((node) => revealObserver.observe(node));
  }

  const tiltNodes = Array.from(document.querySelectorAll('.live-tilt'));

  if (prefersReducedMotion || window.matchMedia('(pointer: coarse)').matches) {
    return;
  }

  tiltNodes.forEach((node) => {
    let frame = 0;

    const resetTilt = () => {
      cancelAnimationFrame(frame);
      node.style.transform = 'perspective(900px) rotateX(0deg) rotateY(0deg)';
    };

    node.addEventListener('mousemove', (event) => {
      const rect = node.getBoundingClientRect();
      const x = (event.clientX - rect.left) / rect.width;
      const y = (event.clientY - rect.top) / rect.height;
      const rotateY = (x - 0.5) * 8;
      const rotateX = (0.5 - y) * 8;

      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => {
        node.style.transform = `perspective(900px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg)`;
      });
    });

    node.addEventListener('mouseleave', resetTilt);
    node.addEventListener('blur', resetTilt);
  });
});
