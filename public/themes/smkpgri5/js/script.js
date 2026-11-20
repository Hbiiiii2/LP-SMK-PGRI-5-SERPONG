/**
 * SMK PGRI 5 Serpong — Theme Interaction Script
 * Lightweight, accessible vanilla JS with zero external dependencies.
 */
(function () {
  'use strict';

  function qs(selector, root) {
    return (root || document).querySelector(selector);
  }

  function qsa(selector, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(selector));
  }

  function getCookie(name) {
    var escaped = name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    var match = document.cookie.match(new RegExp('(?:^|; )' + escaped + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : '';
  }

  function syncCsrfInputs(scope) {
    var token = getCookie('csrf_cookie_name');
    if (!token) return;
    qsa('input[name="csrf_test_name"]', scope).forEach(function (input) {
      input.value = token;
    });
  }

  var toastTimer = null;
  function showToast(message, isError) {
    var toast = qs('#toast');
    var msgEl = qs('#toastMsg');
    if (!toast || !msgEl) return;
    msgEl.textContent = message;
    toast.classList.toggle('is-error', Boolean(isError));
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
      toast.classList.remove('show');
    }, 3600);
  }

  /* 1. Sticky Masthead Scroll State */
  function initMasthead() {
    var masthead = qs('#masthead');
    if (!masthead) return;
    function onScroll() {
      masthead.classList.toggle('scrolled', window.scrollY > 12);
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* 2. Desktop "Lainnya" Dropdown & White Directory Overlay */
  function initNavigation() {
    var dropWrap = qs('#navMoreWrap');
    var dropBtn = qs('#navMoreBtn');
    if (dropWrap && dropBtn) {
      dropBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = dropWrap.classList.toggle('open');
        dropBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      document.addEventListener('click', function (e) {
        if (!dropWrap.contains(e.target)) {
          dropWrap.classList.remove('open');
          dropBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    var menuBtn = qs('#menuBtn');
    var overlay = qs('#menuOverlay');
    var closeBtn = qs('#closeOverlay');
    if (!menuBtn || !overlay) return;

    function openOverlay() {
      overlay.classList.add('open');
      overlay.setAttribute('aria-hidden', 'false');
      menuBtn.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
      if (closeBtn) closeBtn.focus();
    }

    function closeOverlay() {
      overlay.classList.remove('open');
      overlay.setAttribute('aria-hidden', 'true');
      menuBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      menuBtn.focus();
    }

    menuBtn.addEventListener('click', openOverlay);
    if (closeBtn) closeBtn.addEventListener('click', closeOverlay);

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeOverlay();
    });

    qsa('a', overlay).forEach(function (link) {
      link.addEventListener('click', function () {
        overlay.classList.remove('open');
        overlay.setAttribute('aria-hidden', 'true');
        menuBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        if (dropWrap && dropWrap.classList.contains('open')) {
          dropWrap.classList.remove('open');
          if (dropBtn) dropBtn.setAttribute('aria-expanded', 'false');
        }
        if (overlay.classList.contains('open')) {
          closeOverlay();
        }
      }
    });
  }

  /* 3. Seamless Ticker Marquee */
  function initTicker() {
    var track = qs('#tickerTrack');
    if (!track || !track.children.length) return;
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }
    var originals = Array.prototype.slice.call(track.children);
    originals.forEach(function (node) {
      var clone = node.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
    });
  }

  /* 4. Reusable Client-Side Filter Tabs (Achievements & Gallery) */
  function initFilters() {
    qsa('[data-filter-group]').forEach(function (groupEl) {
      var groupName = groupEl.getAttribute('data-filter-group');
      var listEl = qs('[data-filter-list="' + groupName + '"]');
      var emptyEl = qs('[data-filter-empty="' + groupName + '"]');
      if (!listEl) return;

      var buttons = qsa('.filter-btn', groupEl);
      var items = qsa('[data-filter-value]', listEl);

      buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var selected = (btn.getAttribute('data-filter') || 'semua').toLowerCase();
          buttons.forEach(function (b) {
            var active = b === btn;
            b.classList.toggle('active', active);
            b.setAttribute('aria-pressed', active ? 'true' : 'false');
          });

          var visibleCount = 0;
          items.forEach(function (item) {
            var val = (item.getAttribute('data-filter-value') || '').toLowerCase();
            var match = selected === 'semua' || val === selected;
            item.classList.toggle('hidden', !match);
            if (match) visibleCount += 1;
          });

          if (emptyEl) {
            emptyEl.classList.toggle('hidden', visibleCount > 0);
          }
        });
      });
    });
  }

  /* 5. FAQ Accordion */
  function initFaq() {
    var items = qsa('.faq-item');
    if (!items.length) return;

    items.forEach(function (item) {
      var btn = qs('.faq-q', item);
      if (!btn) return;
      btn.addEventListener('click', function () {
        var willOpen = !item.classList.contains('open');
        items.forEach(function (other) {
          other.classList.remove('open');
          var otherBtn = qs('.faq-q', other);
          if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
        });
        if (willOpen) {
          item.classList.add('open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  /* 6. Downloads Live Search & Category Ledger Filtering */
  function initDownloadSearch() {
    var input = qs('#dlSearch');
    var list = qs('#dlList');
    if (!input || !list) return;

    var cards = qsa('.dl-item', list);
    var blocks = qsa('.dl-category-block', list);
    var countEl = qs('#dlCountLabel');
    var emptyEl = qs('#dlEmpty');
    var totalCount = cards.length;

    input.addEventListener('input', function () {
      var term = input.value.trim().toLowerCase();
      var visible = 0;

      cards.forEach(function (card) {
        var haystack = (card.getAttribute('data-search') || card.textContent || '').toLowerCase();
        var match = !term || haystack.indexOf(term) !== -1;
        card.classList.toggle('hidden', !match);
        if (match) visible += 1;
      });

      blocks.forEach(function (block) {
        var visibleInBlock = qsa('.dl-item:not(.hidden)', block).length;
        block.classList.toggle('hidden', visibleInBlock === 0);
      });

      if (countEl) {
        countEl.textContent = term
          ? 'Menampilkan ' + visible + ' dari ' + totalCount + ' dokumen.'
          : 'Menampilkan ' + totalCount + ' dokumen tersedia.';
      }
      if (emptyEl) emptyEl.classList.toggle('hidden', visible > 0);
    });
  }

  /* 7. Lightbox */
  function initLightbox() {
    var box = qs('#lightbox');
    var img = qs('#lightboxImg');
    var cap = qs('#lightboxCap');
    var closeBtn = qs('#lightboxClose');
    if (!box || !img || !cap) return;

    var lastTrigger = null;

    function openBox(src, title, trigger) {
      lastTrigger = trigger || null;
      img.src = src;
      img.alt = title || 'Pratinjau galeri';
      cap.textContent = title || '';
      box.classList.add('open');
      box.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      if (closeBtn) closeBtn.focus();
    }

    function closeBox() {
      box.classList.remove('open');
      box.setAttribute('aria-hidden', 'true');
      img.src = '';
      document.body.style.overflow = '';
      if (lastTrigger && typeof lastTrigger.focus === 'function') {
        lastTrigger.focus();
      }
    }

    document.addEventListener('click', function (e) {
      var trigger = e.target.closest('[data-lightbox-src]');
      if (!trigger) return;
      e.preventDefault();
      var src = trigger.getAttribute('data-lightbox-src');
      var title = trigger.getAttribute('data-lightbox-title') || '';
      if (src) openBox(src, title, trigger);
    });

    if (closeBtn) closeBtn.addEventListener('click', closeBox);
    box.addEventListener('click', function (e) {
      if (e.target === box) closeBox();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && box.classList.contains('open')) {
        closeBox();
      }
    });
  }

  /* 8. Contact Form AJAX Enhancement */
  function initContactForm() {
    var form = qs('#contactForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      syncCsrfInputs(form);

      var btn = qs('button[type="submit"]', form);
      var feedback = qs('#contactFeedback');
      var origLabel = btn ? btn.innerHTML : '';

      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Mengirim...';
      }

      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: new FormData(form),
        credentials: 'same-origin'
      })
        .then(function (res) {
          return res.json().then(function (data) {
            return { ok: res.ok, data: data };
          });
        })
        .then(function (result) {
          syncCsrfInputs();
          var data = result.data || {};
          var isSuccess = result.ok && (data.success === true || data.status === 'ok' || data.status === 'success');
          var errText = '';
          if (!isSuccess && data.errors && typeof data.errors === 'object') {
            errText = Object.keys(data.errors).map(function (k) { return data.errors[k]; }).join(' ');
          }
          var msg = data.message || errText || (isSuccess ? 'Pesan Anda berhasil dikirim.' : 'Terjadi kesalahan saat mengirim pesan.');

          if (feedback) {
            feedback.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-error');
            feedback.textContent = msg;
            feedback.classList.remove('hidden');
          }
          showToast(msg, !isSuccess);

          if (isSuccess) {
            form.reset();
          }
        })
        .catch(function () {
          var fallbackMsg = 'Tidak dapat terhubung ke server. Silakan coba lagi.';
          if (feedback) {
            feedback.className = 'alert alert-error';
            feedback.textContent = fallbackMsg;
            feedback.classList.remove('hidden');
          }
          showToast(fallbackMsg, true);
        })
        .finally(function () {
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = origLabel;
          }
        });
    });
  }

  /* 9. Article Comment Counter, Reply State & Share Copy */
  function initArticleTools() {
    var commentForm = qs('#commentForm');
    var parentInput = qs('#commentParentId');
    var replyBanner = qs('#replyBanner');
    var replyNameEl = qs('#replyToName');
    var cancelReply = qs('#cancelReplyBtn');
    var textarea = qs('#commentMsg');
    var counter = qs('#commentCounter');

    if (textarea && counter) {
      var maxLen = parseInt(textarea.getAttribute('maxlength') || '2000', 10) || 2000;
      function updateCount() {
        counter.textContent = textarea.value.length + ' / ' + maxLen;
      }
      updateCount();
      textarea.addEventListener('input', updateCount);
    }

    if (commentForm && parentInput && replyBanner && replyNameEl) {
      qsa('[data-reply-id]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          parentInput.value = btn.getAttribute('data-reply-id') || '';
          replyNameEl.textContent = btn.getAttribute('data-reply-author') || 'Komentar';
          replyBanner.classList.remove('hidden');
          commentForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
          if (textarea) textarea.focus();
        });
      });

      if (cancelReply) {
        cancelReply.addEventListener('click', function () {
          parentInput.value = '';
          replyBanner.classList.add('hidden');
        });
      }

      commentForm.addEventListener('submit', function () {
        syncCsrfInputs(commentForm);
      });
    }

    qsa('[data-copy-url]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var url = btn.getAttribute('data-copy-url') || window.location.href;
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(function () {
            showToast('Tautan artikel disalin ke papan klip.', false);
          });
        } else {
          showToast('Tautan siap disalin: ' + url, false);
        }
      });
    });
  }

  /* 10. Back To Top & Dynamic Year */
  function initFooterUtilities() {
    var yearEl = qs('#year');
    if (yearEl) yearEl.textContent = String(new Date().getFullYear());

    var totop = qs('#totop');
    if (totop) {
      totop.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  }

  /* 11. Interactive Program Keahlian Focus Explorer */
  function initProgramExplorer() {
    var tabs = qsa('[data-prog-tab]');
    var panels = qsa('.prog-focus-panel');
    if (!tabs.length || !panels.length) return;

    function activateTab(tab, updateUrl) {
      var targetId = tab.getAttribute('data-prog-tab');
      var slug = tab.getAttribute('data-prog-slug');
      tabs.forEach(function (t) {
        var active = t === tab;
        t.classList.toggle('active', active);
        t.setAttribute('aria-selected', active ? 'true' : 'false');
      });

      panels.forEach(function (panel) {
        var match = panel.id === targetId;
        panel.classList.toggle('active', match);
        if (match) {
          panel.removeAttribute('hidden');
        } else {
          panel.setAttribute('hidden', 'hidden');
        }
      });

      if (updateUrl && slug && window.history && window.history.replaceState) {
        try {
          var nextUrl = new URL(window.location.href);
          nextUrl.searchParams.set('fokus', slug);
          window.history.replaceState({}, '', nextUrl.toString());
        } catch (err) {
          // Ignore URL update errors in older browsers
        }
      }
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        activateTab(tab, true);
        if (window.innerWidth < 960) {
          var stage = qs('#progExplorerStage');
          if (stage) {
            stage.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        }
      });
    });
  }

  /* 12. Global Lazy Loading + Shimmer Skeleton Placeholder */
  function initLazyMedia() {
    var imgs = qsa('main img, footer img');
    imgs.forEach(function (img, idx) {
      if (!img.getAttribute('decoding')) {
        img.setAttribute('decoding', 'async');
      }
      if (!img.getAttribute('loading') && idx > 0) {
        img.setAttribute('loading', 'lazy');
      }

      var host = img.parentElement;
      if (img.complete && img.naturalWidth > 0) {
        img.classList.add('is-img-loaded');
        return;
      }

      img.classList.add('is-img-loading');
      if (host && host.tagName !== 'BODY' && host.tagName !== 'A') {
        host.classList.add('img-skel-host');
      }

      function markDone() {
        img.classList.remove('is-img-loading');
        img.classList.add('is-img-loaded');
        if (host) host.classList.remove('img-skel-host');
      }

      img.addEventListener('load', markDone, { once: true });
      img.addEventListener('error', markDone, { once: true });
      setTimeout(markDone, 6000);
    });
  }

  /* 13. Top Navigation Progress Bar for Slow Networks */
  function initNavProgress() {
    var bar = qs('#navProgressBar');
    if (!bar) return;

    document.addEventListener('click', function (e) {
      var link = e.target.closest('a[href]');
      if (!link) return;
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
      if (link.target && link.target !== '_self') return;
      if (link.hasAttribute('download')) return;

      var href = link.getAttribute('href') || '';
      if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) {
        return;
      }

      try {
        var dest = new URL(link.href, window.location.href);
        if (dest.origin === window.location.origin && dest.href !== window.location.href) {
          bar.classList.add('is-active');
        }
      } catch (err) {
        // Ignore invalid URLs
      }
    });

    window.addEventListener('pageshow', function () {
      bar.classList.remove('is-active');
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    syncCsrfInputs();
    initMasthead();
    initNavigation();
    initTicker();
    initFilters();
    initFaq();
    initDownloadSearch();
    initLightbox();
    initContactForm();
    initArticleTools();
    initFooterUtilities();
    initProgramExplorer();
    initLazyMedia();
    initNavProgress();
  });
})();
