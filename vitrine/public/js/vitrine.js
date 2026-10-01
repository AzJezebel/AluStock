/* =====================================================================
   public/js/vitrine.js — interactions de la vitrine
   ===================================================================== */
(() => {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => [...c.querySelectorAll(s)];
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const finePointer = matchMedia('(hover: hover) and (pointer: fine)').matches;

    /* ------------------------------------------------------------ reveal au scroll */
    const revealIO = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add('is-visible');
                revealIO.unobserve(e.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    $$('.reveal').forEach((el) => revealIO.observe(el));

    /* ------------------------------------------------------------ navigation / scroll */
    const navbar = $('#navbar');
    const bar = $('#scroll-progress');
    const topBtn = $('#back-to-top');
    const links = $$('.nav-link');
    const sections = ['accueil', 'a-propos', 'realisations', 'contact']
        .map((id) => document.getElementById(id)).filter(Boolean);
    let ticking = false;

    function onScroll() {
        const y = window.scrollY;
        navbar && navbar.classList.toggle('is-scrolled', y > 40);

        const max = document.documentElement.scrollHeight - window.innerHeight;
        if (bar) bar.style.transform = `scaleX(${max > 0 ? Math.min(1, y / max) : 0})`;
        topBtn && topBtn.classList.toggle('is-shown', y > 600);

        let current = sections.length ? sections[0].id : '';
        sections.forEach((s) => { if (s.getBoundingClientRect().top <= 140) current = s.id; });
        links.forEach((l) => l.classList.toggle('is-active', l.getAttribute('href') === '#' + current));

        ticking = false;
    }
    window.addEventListener('scroll', () => {
        if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
    }, { passive: true });
    onScroll();

    topBtn && topBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // menu mobile
    const menuBtn = $('#mobile-menu-button');
    const menu = $('#mobile-menu');
    if (menuBtn && menu) {
        const setMenu = (open) => {
            menu.classList.toggle('hidden', !open);
            menuBtn.setAttribute('aria-expanded', String(open));
            const icon = $('i', menuBtn);
            if (icon) icon.className = open ? 'fas fa-xmark text-xl' : 'fas fa-bars text-xl';
            // fond plein quand le menu est ouvert, même en haut de page
            navbar && navbar.classList.toggle('is-scrolled', open || window.scrollY > 40);
        };
        menuBtn.addEventListener('click', () => setMenu(menu.classList.contains('hidden')));
        $$('a', menu).forEach((a) => a.addEventListener('click', () => setMenu(false)));
    }

    /* ------------------------------------------------------------ hero : diaporama */
    const hero = $('#accueil');
    if (hero) {
        const slides = $$('.hero-slide', hero);
        const dots = $$('.hero-dot', hero);
        const counter = $('#hero-count');
        const interval = parseInt(hero.dataset.interval, 10) || 6500;
        let cur = 0;
        let timer = null;

        const pad = (n) => String(n).padStart(2, '0');

        function go(n) {
            if (slides.length < 2) return;
            const prev = slides[cur];
            prev.classList.remove('is-active');
            prev.classList.add('is-leaving');
            setTimeout(() => prev.classList.remove('is-leaving'), 2000);

            cur = (n + slides.length) % slides.length;
            slides[cur].classList.add('is-active');
            dots.forEach((d, i) => {
                d.classList.remove('is-active');
                if (i === cur) { void d.offsetWidth; d.classList.add('is-active'); }
            });
            if (counter) counter.textContent = `${pad(cur + 1)} / ${pad(slides.length)}`;
        }
        function stop() { clearInterval(timer); timer = null; }
        function play() {
            stop();
            if (reduce || slides.length < 2) return;
            timer = setInterval(() => go(cur + 1), interval);
        }

        dots.forEach((d, i) => d.addEventListener('click', () => { go(i); play(); }));
        document.addEventListener('visibilitychange', () => (document.hidden ? stop() : play()));
        play();

        /* -------------------------------------------------------- hero : souris (projecteur + parallaxe) */
        if (finePointer && !reduce) {
            let tx = 0.5, ty = 0.4, x = 0.5, y = 0.4, heroVisible = true;
            const layers = $$('[data-depth]', hero);

            new IntersectionObserver((es) => { heroVisible = es[0].isIntersecting; }).observe(hero);

            hero.addEventListener('mousemove', (e) => {
                const r = hero.getBoundingClientRect();
                tx = (e.clientX - r.left) / r.width;
                ty = (e.clientY - r.top) / r.height;
            });
            hero.addEventListener('mouseleave', () => { tx = 0.5; ty = 0.4; });

            (function loop() {
                if (heroVisible) {
                    x += (tx - x) * 0.08;
                    y += (ty - y) * 0.08;
                    hero.style.setProperty('--mx', (x * 100).toFixed(2) + '%');
                    hero.style.setProperty('--my', (y * 100).toFixed(2) + '%');
                    layers.forEach((l) => {
                        const d = parseFloat(l.dataset.depth) || 0;
                        l.style.transform = `translate3d(${((x - 0.5) * d).toFixed(2)}px, ${((y - 0.5) * d).toFixed(2)}px, 0)`;
                    });
                }
                requestAnimationFrame(loop);
            })();
        }
    }

    /* ------------------------------------------------------------ compteurs */
    const counterIO = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (!e.isIntersecting) return;
            counterIO.unobserve(e.target);
            const el = e.target;
            const target = parseInt(el.dataset.target, 10) || 0;
            if (reduce) { el.textContent = target.toLocaleString('fr-FR'); return; }
            const dur = 1600, t0 = performance.now();
            (function tick(t) {
                const p = Math.min(1, (t - t0) / dur);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('fr-FR');
                if (p < 1) requestAnimationFrame(tick);
            })(t0);
        });
    }, { threshold: 0.5 });
    $$('.counter').forEach((c) => counterIO.observe(c));

    /* ------------------------------------------------------------ filtres de la galerie */
    const chips = $$('.chip');
    const cards = $$('.ouvrage');

    chips.forEach((chip) => chip.addEventListener('click', () => {
        const f = chip.dataset.filter;
        chips.forEach((c) => {
            c.classList.toggle('is-active', c === chip);
            c.setAttribute('aria-pressed', String(c === chip));
        });

        let first = true;
        cards.forEach((card) => {
            const show = f === 'all' || card.dataset.cat === f;
            card.classList.toggle('is-hidden', !show);
            card.classList.remove('is-lead', 'is-entering');
            if (show) {
                card.classList.add('is-visible');
                if (first) { card.classList.add('is-lead'); first = false; }
                void card.offsetWidth;
                card.classList.add('is-entering');
            }
        });
    }));

    /* ------------------------------------------------------------ lightbox */
    const dataEl = $('#ouvrages-data');
    const lb = $('#lightbox');
    if (dataEl && lb) {
        const data = JSON.parse(dataEl.textContent);
        const stage = $('.lb-stage', lb);
        const thumbs = $('.lb-thumbs', lb);
        const els = {
            cat: $('#lb-cat'), title: $('#lb-title'), desc: $('#lb-desc'),
            meta: $('#lb-meta'), count: $('#lb-count'),
        };
        let order = [], pos = 0, imgIdx = 0, lastFocus = null;

        const placeholder = '<div class="ph" aria-hidden="true" style="--a:150deg;--x:30%;--y:25%">' +
            '<div class="ph-lines"></div><i class="fas fa-image ph-icon"></i><span class="ph-label">Photo à venir</span></div>';

        function showImage(item) {
            stage.querySelectorAll('.media-img').forEach((n) => n.remove());
            const src = item.images && item.images[imgIdx];
            if (!src) return;
            const img = new Image();
            img.className = 'media-img';
            img.alt = item.titre;
            img.onload = () => img.classList.add('is-loaded');
            img.onerror = () => img.remove();
            img.src = src;
            stage.appendChild(img);

            thumbs.querySelectorAll('.lb-thumb').forEach((t, i) => t.classList.toggle('is-active', i === imgIdx));
        }

        function render() {
            const item = data[order[pos]];
            els.cat.textContent = item.categorie || '';
            els.title.textContent = item.titre;
            els.desc.textContent = item.description || '';
            els.meta.textContent = [item.gamme, item.lieu, item.annee].filter(Boolean).join('  ·  ');
            els.count.textContent = `${pos + 1} / ${order.length}`;

            // on ne vide pas toute la scène : les boutons précédent/suivant y vivent aussi
            stage.querySelectorAll('.ph, .media-img').forEach((n) => n.remove());
            stage.insertAdjacentHTML('afterbegin', placeholder);
            thumbs.innerHTML = '';
            if (item.images && item.images.length > 1) {
                item.images.forEach((src, i) => {
                    const b = document.createElement('button');
                    b.className = 'lb-thumb';
                    b.type = 'button';
                    b.setAttribute('aria-label', `Photo ${i + 1}`);
                    b.innerHTML = `<img src="${src}" alt="" onerror="this.remove()">`;
                    b.addEventListener('click', () => { imgIdx = i; showImage(item); });
                    thumbs.appendChild(b);
                });
            }
            showImage(item);
        }

        function open(index) {
            order = $$('.ouvrage:not(.is-hidden)').map((c) => parseInt(c.dataset.index, 10));
            pos = Math.max(0, order.indexOf(index));
            imgIdx = 0;
            lastFocus = document.activeElement;
            render();
            lb.hidden = false;
            document.body.classList.add('lb-open');
            $('.lb-close', lb).focus();
        }
        function close() {
            lb.hidden = true;
            document.body.classList.remove('lb-open');
            lastFocus && lastFocus.focus && lastFocus.focus();
        }
        function step(d) {
            if (order.length < 2) return;
            pos = (pos + d + order.length) % order.length;
            imgIdx = 0;
            render();
        }

        cards.forEach((card) => {
            const idx = parseInt(card.dataset.index, 10);
            card.addEventListener('click', () => open(idx));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(idx); }
            });
        });

        $('.lb-close', lb).addEventListener('click', close);
        $('.lb-prev', lb).addEventListener('click', () => step(-1));
        $('.lb-next', lb).addEventListener('click', () => step(1));
        lb.addEventListener('click', (e) => { if (e.target === lb || e.target === stage) close(); });

        document.addEventListener('keydown', (e) => {
            if (lb.hidden) return;
            if (e.key === 'Escape') close();
            else if (e.key === 'ArrowLeft') step(-1);
            else if (e.key === 'ArrowRight') step(1);
        });

        // swipe tactile
        let sx = null;
        stage.addEventListener('touchstart', (e) => { sx = e.touches[0].clientX; }, { passive: true });
        stage.addEventListener('touchend', (e) => {
            if (sx === null) return;
            const dx = e.changedTouches[0].clientX - sx;
            if (Math.abs(dx) > 50) step(dx < 0 ? 1 : -1);
            sx = null;
        });
    }
})();
