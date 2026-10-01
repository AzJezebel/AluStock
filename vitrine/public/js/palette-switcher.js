/* =====================================================================
   public/js/palette-switcher.js — panneau de test des palettes
   Nécessite palette-data.js (généré). À retirer avant la mise en ligne
   (VITRINE_PALETTE_SWITCHER=false dans le .env).
   ===================================================================== */
(() => {
    'use strict';

    const P = window.VITRINE_PALETTES;
    if (!P) return;

    const KEY = 'vitrine.palette';
    const root = document.documentElement;
    const state = { ink: P.defaults.ink, accent: P.defaults.accent, fx: true };

    try { Object.assign(state, JSON.parse(localStorage.getItem(KEY) || '{}')); } catch (e) { /* ignore */ }
    if (!P.ink.some((i) => i.id === state.ink)) state.ink = P.defaults.ink;
    if (!P.accent.some((a) => a.id === state.accent)) state.accent = P.defaults.accent;

    const find = (list, id) => list.find((x) => x.id === id);
    const rgb = (hex) => [1, 3, 5].map((i) => parseInt(hex.slice(i, i + 2), 16)).join(' ');
    const el = (tag, cls, html) => {
        const n = document.createElement(tag);
        if (cls) n.className = cls;
        if (html !== undefined) n.innerHTML = html;
        return n;
    };

    // --- aperçu : 4 bandes de couleur
    const swatch = (scale, picks) => {
        const s = el('span', 'pal-swatch');
        picks.forEach((i) => { const b = el('i'); b.style.background = scale[i]; s.appendChild(b); });
        return s;
    };
    const INK_PICKS = [10, 8, 4, 0];     // 950, 800, 400, 50
    const ACC_PICKS = [3, 5, 7, 9];      // 300, 500, 700, 900

    // --- application
    function apply() {
        root.dataset.ink = state.ink;
        root.dataset.accent = state.accent;
        root.dataset.fx = state.fx ? 'on' : 'off';
        try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) { /* ignore */ }
        refresh();
    }

    // --- interface
    const toggle = el('button', 'pal-toggle', '<i class="fas fa-palette"></i><span>Palettes</span>');
    toggle.type = 'button';
    toggle.setAttribute('aria-expanded', 'false');

    const panel = el('div', 'pal-panel');
    panel.hidden = true;
    panel.setAttribute('role', 'dialog');
    panel.setAttribute('aria-label', 'Test des palettes');

    panel.appendChild(el('div', 'pal-head',
        '<strong>Test des palettes</strong><small>Clique un choix actif pour le désactiver</small>'));

    const body = el('div', 'pal-body');
    panel.appendChild(body);

    const buttons = { preset: [], ink: [], accent: [] };

    function section(title, hint) {
        const s = el('div', 'pal-section');
        s.appendChild(el('div', 'pal-title', title + (hint ? ` <small>${hint}</small>` : '')));
        const grid = el('div', 'pal-grid');
        s.appendChild(grid);
        body.appendChild(s);
        return grid;
    }

    // thèmes complets
    const gPresets = section('Thèmes complets', `${P.presets.length}`);
    P.presets.forEach((pr) => {
        const ink = find(P.ink, pr.ink), acc = find(P.accent, pr.accent);
        const b = el('button', 'pal-btn');
        b.type = 'button';
        b.title = `${ink.label} + ${acc.label}`;
        const sw = el('span', 'pal-swatch');
        [ink.scale[10], ink.scale[7], acc.scale[5], acc.scale[3]].forEach((c) => { const i = el('i'); i.style.background = c; sw.appendChild(i); });
        b.appendChild(sw);
        b.appendChild(el('span', 'pal-name', pr.label));
        b.addEventListener('click', () => {
            const active = state.ink === pr.ink && state.accent === pr.accent;
            state.ink = active ? P.defaults.ink : pr.ink;
            state.accent = active ? P.defaults.accent : pr.accent;
            apply();
        });
        b._preset = pr;
        buttons.preset.push(b);
        gPresets.appendChild(b);
    });

    // encres
    const gInk = section('Encre', 'fonds & textes sombres');
    P.ink.forEach((ink) => {
        const b = el('button', 'pal-btn');
        b.type = 'button';
        b.appendChild(swatch(ink.scale, INK_PICKS));
        b.appendChild(el('span', 'pal-name', ink.label));
        b.addEventListener('click', () => { state.ink = state.ink === ink.id ? P.defaults.ink : ink.id; apply(); });
        b._id = ink.id;
        buttons.ink.push(b);
        gInk.appendChild(b);
    });

    // accents
    const gAcc = section('Accent', 'boutons, liens, détails');
    P.accent.forEach((acc) => {
        const b = el('button', 'pal-btn');
        b.type = 'button';
        b.appendChild(swatch(acc.scale, ACC_PICKS));
        b.appendChild(el('span', 'pal-name', acc.label));
        b.addEventListener('click', () => { state.accent = state.accent === acc.id ? P.defaults.accent : acc.id; apply(); });
        b._id = acc.id;
        buttons.accent.push(b);
        gAcc.appendChild(b);
    });

    // options
    const opts = el('div', 'pal-section pal-opts');
    const fxLabel = el('label', 'pal-check', '<input type="checkbox"> <span>Effets du hero (aurore, grille, grain…)</span>');
    const fxInput = fxLabel.querySelector('input');
    fxInput.addEventListener('change', () => { state.fx = fxInput.checked; apply(); });
    opts.appendChild(fxLabel);

    const row = el('div', 'pal-row');
    const reset = el('button', 'pal-action', '<i class="fas fa-rotate-left"></i> Réinitialiser');
    const copy = el('button', 'pal-action pal-action-main', '<i class="fas fa-copy"></i> Copier le CSS');
    reset.type = copy.type = 'button';
    row.append(reset, copy);
    opts.appendChild(row);
    const status = el('div', 'pal-status');
    status.setAttribute('aria-live', 'polite');
    opts.appendChild(status);
    body.appendChild(opts);

    reset.addEventListener('click', () => {
        state.ink = P.defaults.ink; state.accent = P.defaults.accent; state.fx = true; apply();
    });

    copy.addEventListener('click', async () => {
        const ink = find(P.ink, state.ink), acc = find(P.accent, state.accent);
        const lines = [`/* encre : ${ink.label} — accent : ${acc.label} */`, ':root {'];
        P.shades.forEach((s, i) => lines.push(`  --ink-${s}: ${rgb(ink.scale[i])};`));
        P.shades.forEach((s, i) => lines.push(`  --accent-${s}: ${rgb(acc.scale[i])};`));
        lines.push(`  --on-accent: ${acc.darkText ? 'var(--ink-950)' : '255 255 255'};`, '}');
        const text = lines.join('\n');

        try {
            await navigator.clipboard.writeText(text);
        } catch (e) {
            const ta = el('textarea');
            ta.value = text; document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); } catch (err) { /* ignore */ }
            ta.remove();
        }
        status.textContent = 'CSS copié — colle-le dans :root de palettes.css';
        setTimeout(() => { status.textContent = ''; }, 3500);
    });

    function refresh() {
        buttons.preset.forEach((b) => {
            const on = state.ink === b._preset.ink && state.accent === b._preset.accent;
            b.classList.toggle('is-on', on); b.setAttribute('aria-pressed', String(on));
        });
        buttons.ink.forEach((b) => {
            const on = state.ink === b._id;
            b.classList.toggle('is-on', on); b.setAttribute('aria-pressed', String(on));
        });
        buttons.accent.forEach((b) => {
            const on = state.accent === b._id;
            b.classList.toggle('is-on', on); b.setAttribute('aria-pressed', String(on));
        });
        fxInput.checked = state.fx;
    }

    toggle.addEventListener('click', () => {
        panel.hidden = !panel.hidden;
        toggle.setAttribute('aria-expanded', String(!panel.hidden));
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !panel.hidden) { panel.hidden = true; toggle.setAttribute('aria-expanded', 'false'); }
    });

    document.body.append(panel, toggle);
    apply();
})();
