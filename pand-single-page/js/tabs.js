document.querySelectorAll('[data-tabs]').forEach(tabs => {
  const tabButtons = tabs.querySelectorAll('[role="tab"]');
  const panels = tabs.querySelectorAll('[role="tabpanel"]');

  function activateTab(btn) {
    tabButtons.forEach(b => {
      const active = b === btn;
      b.classList.toggle('is-active', active);
      b.setAttribute('aria-selected', active ? 'true' : 'false');
      b.tabIndex = active ? 0 : -1;
    });

    panels.forEach(p => {
      const active = p.id === btn.getAttribute('aria-controls');
      p.classList.toggle('is-active', active);
      p.hidden = !active;
    });
  }

  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => activateTab(btn));

    btn.addEventListener('keydown', e => {
      const idx = [...tabButtons].indexOf(btn);
      if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
        e.preventDefault();
        tabButtons[(idx + 1) % tabButtons.length].focus();
      }
      if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
        e.preventDefault();
        tabButtons[(idx - 1 + tabButtons.length) % tabButtons.length].focus();
      }
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        activateTab(btn);
      }
    });
  });

  // init
  activateTab(tabs.querySelector('.tab.is-active') || tabButtons[0]);
});