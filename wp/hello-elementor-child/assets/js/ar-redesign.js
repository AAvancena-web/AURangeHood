(function(){
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- sticky header state ---------- */
  var header = document.getElementById('ar-site-header');
  var progress = document.getElementById('ar-progress');
  var toTop = document.getElementById('ar-to-top');
  var callFloat = document.getElementById('ar-call-float');

  function onScroll(){
    var y = window.scrollY || document.documentElement.scrollTop;
    if (header) header.classList.toggle('ar-is-stuck', y > 10);
    var h = document.documentElement.scrollHeight - window.innerHeight;
    if (progress) progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
    if (toTop) toTop.classList.toggle('ar-is-visible', y > 700);
    if (callFloat) callFloat.classList.toggle('ar-is-visible', y > 380);
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();

  if (toTop) toTop.addEventListener('click', function(){
    window.scrollTo({top:0, behavior: reduce ? 'auto' : 'smooth'});
  });

  /* ---------- mobile drawer ---------- */
  var burger = document.getElementById('ar-burger');
  var drawer = document.getElementById('ar-drawer');
  var scrim  = document.getElementById('ar-scrim');
  var drawerClose = document.getElementById('ar-drawer-close');

  function setDrawer(open){
    if (!drawer || !scrim || !burger) return;
    drawer.classList.toggle('ar-is-open', open);
    scrim.classList.toggle('ar-is-open', open);
    scrim.hidden = !open;
    burger.classList.toggle('ar-is-open', open);
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.style.overflow = open ? 'hidden' : '';
  }
  if (burger) burger.addEventListener('click', function(){ setDrawer(!drawer.classList.contains('ar-is-open')); });
  if (drawerClose) drawerClose.addEventListener('click', function(){ setDrawer(false); });
  if (scrim) scrim.addEventListener('click', function(){ setDrawer(false); });
  if (drawer) Array.prototype.forEach.call(drawer.querySelectorAll('a'), function(a){
    a.addEventListener('click', function(){ setDrawer(false); });
  });

  /* ---------- scroll reveal ---------- */
  var revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var siblings = el.parentElement ? Array.prototype.indexOf.call(el.parentElement.children, el) : 0;
        el.style.transitionDelay = Math.min(siblings, 6) * 90 + 'ms';
        el.classList.add('ar-is-in');
        io.unobserve(el);
      });
    }, {threshold:0.12, rootMargin:'0px 0px -8% 0px'});
    Array.prototype.forEach.call(revealEls, function(el){ io.observe(el); });
  } else {
    Array.prototype.forEach.call(revealEls, function(el){ el.classList.add('ar-is-in'); });
  }

  /* ---------- animated counters ---------- */
  var counters = document.querySelectorAll('[data-count]');
  function runCounter(el){
    var target = parseFloat(el.getAttribute('data-count'));
    var decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
    var suffix = el.getAttribute('data-suffix') || '';
    if (reduce) { el.textContent = target.toFixed(decimals) + suffix; return; }
    var start = performance.now(), dur = 1500;
    function tick(now){
      var p = Math.min((now - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = (target * eased).toFixed(decimals) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }
  if ('IntersectionObserver' in window) {
    var cio = new IntersectionObserver(function(entries){
      entries.forEach(function(e){
        if (e.isIntersecting) { runCounter(e.target); cio.unobserve(e.target); }
      });
    }, {threshold:0.5});
    Array.prototype.forEach.call(counters, function(el){ cio.observe(el); });
  } else {
    Array.prototype.forEach.call(counters, runCounter);
  }

  /* ---------- FAQ accordion (one open at a time) ---------- */
  var accItems = document.querySelectorAll('#acc .acc__item');
  Array.prototype.forEach.call(accItems, function(item){
    var btn = item.querySelector('.ar-acc__btn');
    var panel = item.querySelector('.ar-acc__panel');
    btn.addEventListener('click', function(){
      var isOpen = item.classList.contains('ar-is-open');
      Array.prototype.forEach.call(accItems, function(other){
        other.classList.remove('ar-is-open');
        other.querySelector('.ar-acc__panel').style.maxHeight = '0px';
        other.querySelector('.ar-acc__btn').setAttribute('aria-expanded','false');
      });
      if (!isOpen) {
        item.classList.add('ar-is-open');
        panel.style.maxHeight = panel.scrollHeight + 'px';
        btn.setAttribute('aria-expanded','true');
      }
    });
  });
  window.addEventListener('resize', function(){
    var open = document.querySelector('#acc .acc__item.ar-open .acc__panel');
    if (open) open.style.maxHeight = open.scrollHeight + 'px';
  });

  /* ---------- gallery lightbox ---------- */
  var shots = Array.prototype.slice.call(document.querySelectorAll('.ar-shot'));
  var lb = document.getElementById('ar-lightbox');
  var lbImg = document.getElementById('ar-lb-img');
  var index = 0;

  function openLb(i){
    if (!lb || !shots.length) return;
    index = (i + shots.length) % shots.length;
    var btn = shots[index];
    lbImg.src = btn.getAttribute('data-full');
    lbImg.alt = btn.querySelector('img').alt;
    lb.classList.add('ar-is-open');
    document.body.style.overflow = 'hidden';
  }
  function closeLb(){
    if (!lb) return;
    lb.classList.remove('ar-is-open');
    document.body.style.overflow = '';
  }
  shots.forEach(function(btn, i){ btn.addEventListener('click', function(){ openLb(i); }); });
  var lbClose = document.getElementById('ar-lb-close');
  var lbPrev  = document.getElementById('ar-lb-prev');
  var lbNext  = document.getElementById('ar-lb-next');
  if (lbClose) lbClose.addEventListener('click', closeLb);
  if (lbPrev) lbPrev.addEventListener('click', function(){ openLb(index - 1); });
  if (lbNext) lbNext.addEventListener('click', function(){ openLb(index + 1); });
  if (lb) lb.addEventListener('click', function(e){ if (e.target === lb) closeLb(); });
  document.addEventListener('keydown', function(e){
    if (drawer && drawer.classList.contains('ar-is-open') && e.key === 'Escape') setDrawer(false);
    if (!lb || !lb.classList.contains('ar-is-open')) return;
    if (e.key === 'Escape') closeLb();
    if (e.key === 'ArrowLeft') openLb(index - 1);
    if (e.key === 'ArrowRight') openLb(index + 1);
  });

  /* ---------- forms ----------
     Front end validation only. Wire the markup to your WordPress form
     handler (Elementor Forms) to start receiving live enquiries. */
  Array.prototype.forEach.call(document.querySelectorAll('.ar-js-form'), function(form){
    var note = form.querySelector('.ar-form-note');
    form.addEventListener('submit', function(e){
      e.preventDefault();
      var invalid = null;
      Array.prototype.forEach.call(form.querySelectorAll('[required]'), function(f){
        var bad = !f.value.trim() || (f.type === 'email' && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(f.value));
        f.style.borderColor = bad ? '#D93025' : '';
        if (bad && !invalid) invalid = f;
      });
      if (invalid) {
        note.textContent = 'Please complete the highlighted fields so we can prepare your quote.';
        note.classList.add('ar-is-visible');
        invalid.focus();
        return;
      }
      note.textContent = 'Validation passed. Connect this form to your WordPress form handler to start receiving enquiries.';
      note.classList.add('ar-is-visible');
    });
  });

  /* ---------- smooth anchor offset for the sticky header ---------- */
  Array.prototype.forEach.call(document.querySelectorAll('a[href^="#"]'), function(a){
    a.addEventListener('click', function(e){
      var id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      var target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      var offset = header.getBoundingClientRect().height + 14;
      var top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({top: top, behavior: reduce ? 'auto' : 'smooth'});
    });
  });
})();
