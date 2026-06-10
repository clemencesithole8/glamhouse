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

  const pageImages = Array.from(document.querySelectorAll('img'));

  pageImages.forEach((image, index) => {
    if (!image.hasAttribute('decoding')) {
      image.setAttribute('decoding', 'async');
    }

    if (!image.hasAttribute('loading')) {
      const isNearViewport = image.getBoundingClientRect().top < window.innerHeight * 0.9;
      image.setAttribute('loading', isNearViewport && index < 2 ? 'eager' : 'lazy');
    }
  });

  if (pageImages[0] && !pageImages[0].hasAttribute('fetchpriority')) {
    pageImages[0].setAttribute('fetchpriority', 'high');
  }

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const sliders = Array.from(document.querySelectorAll('[data-image-slider]'));

  sliders.forEach((slider) => {
    const slides = Array.from(slider.querySelectorAll('[data-slider-slide]'));
    const dots = Array.from(slider.querySelectorAll('[data-slider-dot]'));
    const previousButton = slider.querySelector('[data-slider-prev]');
    const nextButton = slider.querySelector('[data-slider-next]');
    const progress = slider.querySelector('[data-slider-progress]');
    const interval = Number.parseInt(slider.dataset.sliderInterval || '6200', 10);
    const safeInterval = Number.isFinite(interval) && interval >= 3000 ? interval : 6200;

    if (slides.length <= 1) {
      previousButton?.setAttribute('hidden', '');
      nextButton?.setAttribute('hidden', '');
      dots.forEach((dot) => dot.setAttribute('hidden', ''));
      return;
    }

    slider.style.setProperty('--hero-slider-duration', `${safeInterval}ms`);

    let currentIndex = Math.max(0, slides.findIndex((slide) => slide.classList.contains('is-active')));
    let autoTimer = 0;

    const restartProgress = () => {
      if (!progress || prefersReducedMotion) {
        return;
      }

      progress.classList.remove('is-running');
      void progress.offsetWidth;
      progress.classList.add('is-running');
    };

    const setSlide = (nextIndex) => {
      currentIndex = (nextIndex + slides.length) % slides.length;

      slides.forEach((slide, index) => {
        const isActive = index === currentIndex;
        slide.classList.toggle('is-active', isActive);
        slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
      });

      dots.forEach((dot, index) => {
        const isActive = index === currentIndex;
        dot.classList.toggle('is-active', isActive);
        dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });

      restartProgress();
    };

    const stopAuto = () => {
      window.clearInterval(autoTimer);
      autoTimer = 0;
    };

    const startAuto = () => {
      if (prefersReducedMotion || autoTimer) {
        return;
      }

      restartProgress();
      autoTimer = window.setInterval(() => {
        setSlide(currentIndex + 1);
      }, safeInterval);
    };

    previousButton?.addEventListener('click', () => {
      stopAuto();
      setSlide(currentIndex - 1);
      startAuto();
    });

    nextButton?.addEventListener('click', () => {
      stopAuto();
      setSlide(currentIndex + 1);
      startAuto();
    });

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        stopAuto();
        setSlide(Number.parseInt(dot.dataset.sliderDot || '0', 10));
        startAuto();
      });
    });

    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);
    slider.addEventListener('focusin', stopAuto);
    slider.addEventListener('focusout', startAuto);

    setSlide(currentIndex);
    startAuto();
  });

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

  const countNodes = Array.from(document.querySelectorAll('[data-countup]'));

  const runCountUp = (node) => {
    if (node.dataset.counted === '1') {
      return;
    }

    const target = Number.parseInt(node.dataset.countup || '0', 10);
    if (!Number.isFinite(target) || target < 0) {
      return;
    }

    const duration = Number.parseInt(node.dataset.countupDuration || '1200', 10);
    const safeDuration = Number.isFinite(duration) && duration > 0 ? duration : 1200;

    node.dataset.counted = '1';

    if (prefersReducedMotion) {
      node.textContent = String(target);
      return;
    }

    const startedAt = performance.now();

    const tick = (time) => {
      const progress = Math.min((time - startedAt) / safeDuration, 1);
      const value = Math.floor(progress * target);
      node.textContent = String(value);

      if (progress < 1) {
        window.requestAnimationFrame(tick);
      } else {
        node.textContent = String(target);
      }
    };

    window.requestAnimationFrame(tick);
  };

  if (countNodes.length > 0) {
    if (prefersReducedMotion) {
      countNodes.forEach(runCountUp);
    } else {
      const countObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) {
              return;
            }

            runCountUp(entry.target);
            observer.unobserve(entry.target);
          });
        },
        { threshold: 0.45 },
      );

      countNodes.forEach((node) => countObserver.observe(node));
    }
  }

  const tiltNodes = Array.from(document.querySelectorAll('.live-tilt'));

  if (!prefersReducedMotion && !window.matchMedia('(pointer: coarse)').matches) {
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
  }

  const servicesRoot = document.querySelector('[data-services-filter]');

  if (servicesRoot) {
    const chips = Array.from(servicesRoot.querySelectorAll('[data-service-filter]'));
    const cards = Array.from(servicesRoot.querySelectorAll('[data-service-card]'));
    const countNode = servicesRoot.querySelector('[data-service-count]');
    const emptyState = servicesRoot.querySelector('[data-service-empty]');

    const spotlightName = servicesRoot.querySelector('[data-service-name-out]');
    const spotlightPrice = servicesRoot.querySelector('[data-service-price-out]');
    const spotlightType = servicesRoot.querySelector('[data-service-type-out]');
    const spotlightDescription = servicesRoot.querySelector('[data-service-description-out]');

    const setSpotlight = (card) => {
      cards.forEach((item) => item.classList.toggle('is-focused', item === card));

      if (!card) {
        if (spotlightName) spotlightName.textContent = 'No package available';
        if (spotlightPrice) spotlightPrice.textContent = 'N/A';
        if (spotlightType) spotlightType.textContent = 'Please adjust filters.';
        if (spotlightDescription) spotlightDescription.textContent = 'No visible package matches your current filter selection.';
        return;
      }

      if (spotlightName) spotlightName.textContent = card.dataset.serviceName || 'Service';
      if (spotlightPrice) spotlightPrice.textContent = card.dataset.servicePrice || 'Consult';
      if (spotlightType) spotlightType.textContent = card.dataset.serviceTypeLabel || 'Package';
      if (spotlightDescription) spotlightDescription.textContent = card.dataset.serviceDescription || 'No description provided.';
    };

    const visibleCards = () => cards.filter((card) => !card.classList.contains('is-hidden'));

    const applyFilter = (filter) => {
      cards.forEach((card) => {
        const type = card.dataset.serviceType || 'standard';
        const priceType = card.dataset.servicePriceType || 'quote';

        const isVisible = filter === 'all'
          || (filter === 'consultation' && type === 'consultation')
          || (filter === 'priced' && priceType === 'priced');

        card.classList.toggle('is-hidden', !isVisible);
        card.setAttribute('aria-hidden', String(!isVisible));
        card.tabIndex = isVisible ? 0 : -1;
      });

      chips.forEach((chip) => {
        chip.classList.toggle('active', chip.dataset.serviceFilter === filter);
        chip.setAttribute('aria-pressed', chip.dataset.serviceFilter === filter ? 'true' : 'false');
      });

      const currentVisible = visibleCards();

      if (countNode) {
        countNode.textContent = `${currentVisible.length} package${currentVisible.length === 1 ? '' : 's'} shown`;
      }

      if (emptyState) {
        emptyState.hidden = currentVisible.length !== 0;
      }

      setSpotlight(currentVisible[0] ?? null);
    };

    cards.forEach((card) => {
      card.addEventListener('mouseenter', () => {
        if (!card.classList.contains('is-hidden')) {
          setSpotlight(card);
        }
      });

      card.addEventListener('click', () => {
        if (!card.classList.contains('is-hidden')) {
          setSpotlight(card);
        }
      });

      card.addEventListener('keydown', (event) => {
        if ((event.key === 'Enter' || event.key === ' ') && !card.classList.contains('is-hidden')) {
          event.preventDefault();
          setSpotlight(card);
        }
      });
    });

    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        applyFilter(chip.dataset.serviceFilter || 'all');
      });
    });

    applyFilter('all');
  }
});



