(function (W, D) {
  'use strict';

  var cfg = W.wpbbSuiteV130 || {};
  var slug = String(cfg.themeSlug || '').toLowerCase();
  if (slug.indexOf('tech') === -1) return;

  function qa(sel, root) {
    try { return Array.prototype.slice.call((root || D).querySelectorAll(sel)); }
    catch (e) { return []; }
  }
  function q(sel, root) {
    try { return (root || D).querySelector(sel); }
    catch (e) { return null; }
  }
  function norm(value) {
    return String(value || '').toLowerCase().replace(/\s+/g, ' ').trim();
  }

  function markTheme() {
    if (D.body) D.body.classList.add('wpbb-v130-theme-tech');
  }

  function repairCart() {
    qa('#wp-theme-main.wp-theme-woo-legacy--cart .woocommerce,.woocommerce-cart #wp-theme-main .woocommerce').forEach(function (wrap) {
      var form = q('.woocommerce-cart-form', wrap);
      var totals = q('.cart-collaterals', wrap);
      if (!form || !totals) return;
      wrap.classList.add('wpbb-v130-cart-layout');
      form.classList.add('wpbb-v130-cart-form');
      totals.classList.add('wpbb-v130-cart-totals');
    });
  }

  function removeCheckoutMarketingConsent() {
    qa('#wp-theme-main.wp-theme-woo-legacy--checkout form.checkout,.woocommerce-checkout #wp-theme-main form.checkout').forEach(function (form) {
      qa('.wpbb-v128-newsletter-consent,.wp-newslatter-campaigns-consent,.wp-newsletter-campaigns-consent', form).forEach(function (node) {
        var target = node.closest('.form-row,.woocommerce-form-row,label,p') || node;
        if (target && form.contains(target) && !target.querySelector('#payment,#order_review')) target.remove();
      });

      qa('label,p', form).forEach(function (node) {
        if (node.closest('.wp-theme-footer-newsletter,.wp-theme-footer-newsletter-band')) return;
        var value = norm(node.textContent);
        var isMarketing = value.indexOf('i agree to receive occasional email updates') !== -1 ||
          value.indexOf('i agree to receive email updates and can unsubscribe') !== -1 ||
          value.indexOf('send me wordpress newsletter updates') !== -1 ||
          value.indexOf('newsletter updates by email') !== -1;
        if (!isMarketing) return;
        var target = node.closest('.form-row,.woocommerce-form-row,label,p') || node;
        if (target && form.contains(target) && !target.querySelector('#payment,#order_review')) target.remove();
      });
    });
  }

  function repairAccountLinks() {
    var main = q('#wp-theme-main.wp-theme-woo-legacy--account');
    if (!main) return;
    var endpoints = ['orders','downloads','edit-address','edit-account','payment-methods','lost-password','view-order'];
    qa('a[href]', main).forEach(function (link) {
      try {
        var url = new URL(link.href, W.location.origin);
        if (url.origin !== W.location.origin) return;
        var parts = url.pathname.replace(/^\/+|\/+$/g, '').split('/').filter(Boolean);
        if (parts.length !== 1 || endpoints.indexOf(parts[0]) === -1) return;
        url.pathname = '/my-account/' + parts[0] + '/';
        link.href = url.toString();
      } catch (e) {}
    });
  }

  function run() {
    markTheme();
    repairCart();
    removeCheckoutMarketingConsent();
    repairAccountLinks();
  }

  if (D.readyState === 'loading') D.addEventListener('DOMContentLoaded', run, { once: true });
  else run();
  W.addEventListener('load', function () {
    run();
    W.setTimeout(run, 250);
    W.setTimeout(run, 900);
  });

  if (W.MutationObserver) {
    var queued = false;
    var observer = new MutationObserver(function (mutations) {
      if (!mutations.some(function (m) { return m.addedNodes && m.addedNodes.length; })) return;
      if (queued) return;
      queued = true;
      W.setTimeout(function () { queued = false; run(); }, 40);
    });
    observer.observe(D.documentElement, { childList: true, subtree: true });
  }
})(window, document);
