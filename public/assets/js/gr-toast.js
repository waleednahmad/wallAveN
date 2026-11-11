/*
 * Golden Rugs toasts: one small notification style for the whole storefront.
 *   grToast('success' | 'error' | 'info' | 'warning', 'Message', { action: { label, onClick }, duration })
 * - Desktop: bottom-left (the cart and quick add panels open on the right). Phone: bottom, full width.
 * - The same message shown again replaces the old one instead of stacking.
 * - Pauses while hovered or focused; Esc or the close button dismisses.
 */
(function () {
  var region;
  var MAX = 3;
  var ICONS = {
    success: '<path d="M5 12.5l4.2 4.2L19 7" />',
    error: '<path d="M12 7v6M12 16.5v.5" /><circle cx="12" cy="12" r="9" />',
    warning: '<path d="M12 8v5M12 16.5v.5" /><path d="M10.3 3.9L2.6 17.4A2 2 0 004.3 20.4h15.4a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />',
    info: '<circle cx="12" cy="12" r="9" /><path d="M12 11v5M12 7.5v.5" />'
  };

  function ensureRegion() {
    if (region && document.body.contains(region)) return region;
    region = document.createElement('div');
    region.className = 'gr-toasts';
    region.setAttribute('aria-live', 'polite');
    region.setAttribute('aria-relevant', 'additions');
    document.body.appendChild(region);
    return region;
  }

  function text(v) {
    if (Array.isArray(v)) v = v[0];
    if (v && typeof v === 'object') v = v.message || v.title || v[0] || '';
    return String(v == null ? '' : v);
  }

  function dismiss(el) {
    if (!el || el.dataset.leaving) return;
    el.dataset.leaving = '1';
    clearTimeout(el._t);
    el.classList.add('is-leaving');
    setTimeout(function () { el.remove(); }, 220);
  }

  function arm(el, ms) {
    clearTimeout(el._t);
    el._t = setTimeout(function () { dismiss(el); }, ms);
  }

  window.grToast = function (type, message, opts) {
    opts = opts || {};
    type = ICONS[type] ? type : 'info';
    message = text(message).trim();
    if (!message) return;
    var r = ensureRegion();
    var duration = opts.duration || (type === 'error' || type === 'warning' ? 6000 : 3500);

    // Same message already visible: refresh it rather than stacking duplicates.
    var existing = Array.prototype.find.call(r.children, function (c) { return c.dataset.msg === type + '|' + message && !c.dataset.leaving; });
    if (existing) { existing.classList.remove('gr-toast-bump'); void existing.offsetWidth; existing.classList.add('gr-toast-bump'); arm(existing, duration); return existing; }

    var el = document.createElement('div');
    el.className = 'gr-toast gr-toast-' + type;
    el.dataset.msg = type + '|' + message;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML =
      '<span class="gr-toast-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + ICONS[type] + '</svg></span>' +
      '<p class="gr-toast-msg"></p>' +
      (opts.action ? '<button type="button" class="gr-toast-action"></button>' : '') +
      '<button type="button" class="gr-toast-close" aria-label="Dismiss">' +
      '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>';
    el.querySelector('.gr-toast-msg').textContent = message;
    if (opts.action) {
      var b = el.querySelector('.gr-toast-action');
      b.textContent = opts.action.label;
      b.addEventListener('click', function () { dismiss(el); opts.action.onClick && opts.action.onClick(); });
    }
    el.querySelector('.gr-toast-close').addEventListener('click', function () { dismiss(el); });
    el.addEventListener('mouseenter', function () { clearTimeout(el._t); });
    el.addEventListener('mouseleave', function () { arm(el, 2000); });
    el.addEventListener('focusin', function () { clearTimeout(el._t); });
    el.addEventListener('focusout', function () { arm(el, 2000); });
    el.addEventListener('keydown', function (e) { if (e.key === 'Escape') dismiss(el); });

    r.appendChild(el);
    var live = Array.prototype.filter.call(r.children, function (c) { return !c.dataset.leaving; });
    while (live.length > MAX) dismiss(live.shift());
    arm(el, duration);
    return el;
  };

  window.grOpenCart = function () {
    var oc = document.getElementById('cartOffcanva');
    if (!oc || !window.bootstrap) return;
    var qa = document.getElementById('quickAddOffcanva');
    var qaInst = qa && window.bootstrap.Offcanvas.getInstance(qa);
    if (qaInst) qaInst.hide();
    window.bootstrap.Offcanvas.getOrCreateInstance(oc).show();
    if (window.Livewire) window.Livewire.dispatch('refresh');
  };
})();
