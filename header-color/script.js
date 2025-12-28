(function () {
const DESKTOP_MIN = 601; // >600px = desktop
  const mq = window.matchMedia(`(min-width: ${DESKTOP_MIN}px)`);

  const THEMES = [
    { cls: 'blue-header-color', theme: 'blue' },
    { cls: 'orange-header-color', theme: 'orange' },
    { cls: 'white-header-color', theme: 'white' },
  ];

  let observer = null;
  let header = null;
  let sections = [];

  const cleanup = () => {
    if (observer) {
      observer.disconnect();
      observer = null;
    }

    // Optional: reset classes when leaving desktop
    document.body.classList.remove(
      'header-theme--blue',
      'header-theme--orange',
      'header-theme--white'
    );
  };

  const setTheme = (theme) => {
    document.body.classList.remove(
      'header-theme--blue',
      'header-theme--orange',
      'header-theme--white'
    );
    document.body.classList.add('header-theme--' + theme);
  };

  const getHeaderHeight = () => {
    const h = Math.ceil(header?.getBoundingClientRect().height || 0);
    return h;
  };

  const initObserver = () => {
    // ✅ guard: only run on desktop
    if (!mq.matches) {
      cleanup();
      return;
    }

    header = document.querySelector('.header');
    if (!header) {
      cleanup();
      return;
    }

    sections = THEMES.flatMap(t =>
      Array.from(document.querySelectorAll('.' + t.cls))
        .map(el => ({ el, theme: t.theme }))
    );

    if (!sections.length) {
      cleanup();
      return;
    }

    console.log(
      '[header-theme] ✅ themed sections found:',
      sections.map(s => ({ theme: s.theme, el: s.el }))
    );

    // Default theme on desktop
    setTheme('blue');

    const headerHeight = getHeaderHeight();

    if (observer) {
      observer.disconnect();
    }

    observer = new IntersectionObserver((entries) => {
      const active = entries
        .filter(e => e.isIntersecting)
        .map(e => {
          const match = sections.find(s => s.el === e.target);
          if (!match) return null;

          const top = e.target.getBoundingClientRect().top;

          console.log(
            '[header-theme] ➜ intersecting:',
            match.theme,
            'top:',
            Math.round(top)
          );

          return { theme: match.theme, top };
        })
        .filter(Boolean);

      if (!active.length) {
        return;
      }

      active.sort(
        (a, b) =>
          Math.abs(a.top - headerHeight) -
          Math.abs(b.top - headerHeight)
      );

      setTheme(active[0].theme);

    }, {
      root: null,
      threshold: 0.01,
      rootMargin: `-${headerHeight}px 0px -60% 0px`
    });

    sections.forEach(s => {
      observer.observe(s.el);
    });
  };

  // Init once
  initObserver();

  // Re-init on desktop resize (still guarded)
  window.addEventListener('resize', () => {
    initObserver();
  }, { passive: true });

  // ✅ Also react instantly when crossing breakpoint (desktop <-> mobile)
  if (mq.addEventListener) {
    mq.addEventListener('change', () => {
      initObserver();
    });
  } else {
    // Safari older fallback
    mq.addListener(() => {
      initObserver();
    });
  }
})();