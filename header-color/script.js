(function () {
  console.log('[header-theme] script loaded');

  const header = document.querySelector('.header');
  if (!header) {
    console.warn('[header-theme] ❌ .header NOT found');
    return;
  }
  console.log('[header-theme] ✅ header found', header);

  const THEMES = [
    { cls: 'blue-header-color', theme: 'blue' },
    { cls: 'orange-header-color', theme: 'orange' },
    { cls: 'white-header-color', theme: 'white' },
  ];

  const sections = THEMES.flatMap(t =>
    Array.from(document.querySelectorAll('.' + t.cls))
      .map(el => ({ el, theme: t.theme }))
  );

  if (!sections.length) {
    console.warn('[header-theme] ❌ No sections found with theme classes');
    return;
  }

  console.log(
    '[header-theme] ✅ themed sections found:',
    sections.map(s => ({ theme: s.theme, el: s.el }))
  );

  const setTheme = (theme) => {
    console.log('[header-theme] 🎨 setting theme →', theme);

    document.body.classList.remove(
      'header-theme--blue',
      'header-theme--orange',
      'header-theme--white'
    );
    document.body.classList.add('header-theme--' + theme);
  };

  // Default theme
  setTheme('blue');

  const getHeaderHeight = () => {
    const h = Math.ceil(header.getBoundingClientRect().height || 0);
    console.log('[header-theme] header height =', h);
    return h;
  };

  let observer = null;

  const initObserver = () => {
    if (observer) {
      console.log('[header-theme] 🔄 reinitializing observer');
      observer.disconnect();
    }

    const headerHeight = getHeaderHeight();

    observer = new IntersectionObserver((entries) => {
      console.log('[header-theme] 👀 observer entries:', entries.length);

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
        console.log('[header-theme] ⚠️ no active themed sections');
        return;
      }

      active.sort(
        (a, b) =>
          Math.abs(a.top - headerHeight) -
          Math.abs(b.top - headerHeight)
      );

      console.log('[header-theme] 🏆 chosen theme:', active[0].theme);
      setTheme(active[0].theme);

    }, {
      root: null,
      threshold: 0.01,
      rootMargin: `-${headerHeight}px 0px -60% 0px`
    });

    sections.forEach(s => {
      observer.observe(s.el);
      console.log('[header-theme] 👁 observing section:', s.theme, s.el);
    });
  };

  initObserver();

  window.addEventListener('resize', () => {
    console.log('[header-theme] 📐 resize detected');
    initObserver();
  }, { passive: true });

})();