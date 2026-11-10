(function () {
  const scrollToTop = document.querySelector('.scroll-to-top');
  const formStatus = document.querySelector('.form-status');

  if (formStatus) {
    window.setTimeout(function () {
      formStatus.hidden = true;
    }, 5000);
  }

  function getHashTarget(hash) {
    const id = decodeURIComponent(hash.replace(/^#/, ''));
    return document.getElementById(id) || (id === 'quote' ? document.querySelector('.quote-section') : null);
  }

  function scrollToTarget(target) {
    if ('home' === target.id) {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    const stickyHeader = document.querySelector('.wp-site-blocks > .wp-block-template-part:first-child');
    const headerHeight = stickyHeader ? stickyHeader.getBoundingClientRect().height : 0;
    const top = window.scrollY + target.getBoundingClientRect().top - headerHeight;

    window.scrollTo({ top: top, behavior: 'smooth' });
  }

  function updateActiveNavigation(hash) {
    const navigation = document.querySelector('.primary-navigation');
    if (!navigation) {
      return;
    }

    const activeId = hash ? decodeURIComponent(hash.replace(/^#/, '')) : '';
    navigation.querySelectorAll('a[href*="#"]').forEach(function (link) {
      const linkHash = new URL(link.href, window.location.href).hash;
      const linkId = linkHash ? decodeURIComponent(linkHash.replace(/^#/, '')) : '';
      const active = activeId && activeId === linkId;

      link.classList.toggle('is-active', Boolean(active));
      if (active) {
        link.setAttribute('aria-current', 'location');
      } else if ('location' === link.getAttribute('aria-current')) {
        link.removeAttribute('aria-current');
      }
    });
  }

  function updateScrollToTop() {
    if (scrollToTop) {
      scrollToTop.classList.toggle('is-visible', window.scrollY > 400);
    }
  }

  window.addEventListener('scroll', updateScrollToTop, { passive: true });
  updateScrollToTop();

  function scrollToCurrentHash() {
    if (!window.location.hash) {
      updateActiveNavigation('');
      return;
    }

    updateActiveNavigation(window.location.hash);
    const target = getHashTarget(window.location.hash);
    if (target) {
      scrollToTarget(target);
    }
  }

  if (document.readyState === 'complete') {
    window.setTimeout(scrollToCurrentHash, 50);
  } else {
    window.addEventListener('load', function () {
      window.setTimeout(scrollToCurrentHash, 50);
    }, { once: true });
  }

  window.addEventListener('popstate', function () {
    updateActiveNavigation(window.location.hash);
  });
  window.addEventListener('hashchange', function () {
    updateActiveNavigation(window.location.hash);
  });

  document.addEventListener('click', function (event) {
    const navigationLink = event.target.closest('.brand, .primary-navigation a');
    if (
      navigationLink &&
      event.button === 0 &&
      !event.metaKey &&
      !event.ctrlKey &&
      !event.shiftKey &&
      !event.altKey
    ) {
      const searchParams = new URLSearchParams(window.location.search);
      if (searchParams.has('enquiry')) {
        searchParams.delete('enquiry');
        const query = searchParams.toString();
        window.history.replaceState(
          window.history.state,
          '',
          window.location.pathname + (query ? '?' + query : '') + window.location.hash
        );
      }
    }

    const link = event.target.closest('a[href*="#"]');
    if (
      !link ||
      event.button !== 0 ||
      event.metaKey ||
      event.ctrlKey ||
      event.shiftKey ||
      event.altKey
    ) {
      return;
    }

    const destination = new URL(link.href, window.location.href);
    if (
      destination.origin !== window.location.origin ||
      destination.pathname !== window.location.pathname ||
      !destination.hash
    ) {
      return;
    }

    const target = getHashTarget(destination.hash);
    if (!target) {
      return;
    }

    event.preventDefault();
    updateActiveNavigation(destination.hash);
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.delete('enquiry');
    const query = searchParams.toString();
    window.history.pushState(null, '', destination.pathname + (query ? '?' + query : '') + destination.hash);
    scrollToTarget(target);
  }, true);

  const toggle = document.querySelector('.menu-toggle');
  const navigation = document.querySelector('.primary-navigation');

  if (!toggle || !navigation) {
    return;
  }

  toggle.addEventListener('click', function () {
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    toggle.setAttribute('aria-label', expanded ? 'Open navigation menu' : 'Close navigation menu');
    navigation.classList.toggle('is-open', !expanded);
  });

  navigation.addEventListener('click', function (event) {
    if (event.target.closest('a') && window.matchMedia('(max-width: 1000px)').matches) {
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open navigation menu');
      navigation.classList.remove('is-open');
    }
  });
})();
