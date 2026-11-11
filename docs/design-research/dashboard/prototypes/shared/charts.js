/* Tiny dependency-free SVG chart helper shared by the 3 maquettes.
 * Charts.line / bars / donut / hbars / spark — all re-render on resize and ship
 * a hover layer (crosshair + tooltip for lines, per-mark tooltip for bars/arcs).
 * Theme tokens (set them in each concept's CSS):
 *   --chart-ink (axis text) --chart-grid (gridlines) --chart-surface (gap colour)
 *   --tip-bg --tip-fg --tip-border
 * Colours passed in may be CSS values such as "var(--series-1)".
 */
(function () {
  const NS = 'http://www.w3.org/2000/svg';
  const css = `
  .cx{position:relative;width:100%}
  .cx svg{display:block;width:100%;overflow:visible}
  .cx text{fill:var(--chart-ink,#6b6a66);font:inherit;font-size:11px;font-variant-numeric:tabular-nums}
  .cx .grid{stroke:var(--chart-grid,#ecebe7);stroke-width:1;shape-rendering:crispEdges}
  .cx .base{stroke:var(--chart-axis,var(--chart-grid,#d9d8d3));stroke-width:1;shape-rendering:crispEdges}
  .cx .hit{fill:transparent;cursor:crosshair}
  .cx .mark{transition:opacity .12s}
  .cx.dim .mark:not(.on){opacity:.35}
  .cx-tip{position:absolute;pointer-events:none;z-index:5;min-width:120px;padding:8px 10px;border-radius:8px;
    background:var(--tip-bg,#111);color:var(--tip-fg,#fff);border:1px solid var(--tip-border,transparent);
    font-size:12px;line-height:1.45;box-shadow:0 8px 24px rgba(0,0,0,.18);opacity:0;transform:translateY(4px);transition:opacity .1s,transform .1s;white-space:nowrap}
  .cx-tip.show{opacity:1;transform:none}
  .cx-tip b{display:block;font-weight:600;margin-bottom:2px}
  .cx-tip i{display:inline-block;width:8px;height:8px;border-radius:2px;margin-right:6px;vertical-align:0}
  .cx-tip span{float:right;margin-left:16px;font-variant-numeric:tabular-nums;font-weight:600}
  .cx-legend{display:flex;flex-wrap:wrap;gap:6px 16px;margin-top:10px;font-size:12px;color:var(--chart-legend,var(--chart-ink,#6b6a66))}
  .cx-legend i{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px;vertical-align:-1px}
  .cx-hbars{display:grid;gap:10px}
  .cx-hrow{display:grid;grid-template-columns:minmax(90px,38%) 1fr auto;align-items:center;gap:10px;font-size:13px}
  .cx-hrow .lab{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .cx-hrow .trk,.cx-hrow .bar{display:block}
  .cx-hrow .trk{height:8px;border-radius:4px;background:var(--chart-track,var(--chart-grid,#ecebe7));overflow:hidden}
  .cx-hrow .bar{height:100%;border-radius:4px}
  .cx-hrow .val{font-variant-numeric:tabular-nums;font-weight:600;min-width:42px;text-align:right}
  `;
  const st = document.createElement('style'); st.textContent = css; document.head.appendChild(st);

  const el = (tag, attrs = {}, parent) => {
    const n = document.createElementNS(NS, tag);
    for (const k in attrs) {
      if (k === 'style') n.setAttribute('style', attrs[k]);
      else n.setAttribute(k, attrs[k]);
    }
    if (parent) parent.appendChild(n);
    return n;
  };
  const niceMax = (v) => {
    if (v <= 0) return 1;
    const p = Math.pow(10, Math.floor(Math.log10(v)));
    const f = v / p;
    const m = f <= 1 ? 1 : f <= 2 ? 2 : f <= 2.5 ? 2.5 : f <= 3 ? 3 : f <= 4 ? 4 : f <= 5 ? 5 : f <= 6 ? 6 : f <= 8 ? 8 : 10;
    return m * p;
  };
  const defaultFmt = (n) => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 1 }).format(n).replace(/ /g, ' ');
  const shortFmt = (n) => (Math.abs(n) >= 1000 ? (n / 1000).toLocaleString('fr-FR', { maximumFractionDigits: 0 }) + ' k' : defaultFmt(n));

  function setup(host, cfg, draw) {
    host.classList.add('cx');
    host.__cfg = cfg;
    const render = () => {
      const w = host.clientWidth;
      if (!w || w === host.__w) return;
      host.__w = w;
      host.innerHTML = '';
      const tip = document.createElement('div'); tip.className = 'cx-tip';
      draw(host, host.__cfg, w, tip);
      host.appendChild(tip);
      if (cfg.legend !== false && cfg.series && cfg.series.length > 1) legend(host, cfg.series.map((s) => ({ label: s.name, color: s.color })));
      if (cfg.legend !== false && cfg.items && cfg.legendItems) legend(host, cfg.items.map((s) => ({ label: s.label, color: s.color })));
    };
    if (!host.__ro) { host.__ro = new ResizeObserver(() => render()); host.__ro.observe(host); }
    host.__w = 0; render();
  }
  function legend(host, items) {
    const lg = document.createElement('div'); lg.className = 'cx-legend';
    lg.innerHTML = items.map((i) => `<span><i style="background:${i.color}"></i>${i.label}</span>`).join('');
    host.appendChild(lg);
  }
  function placeTip(host, tip, x, y, html) {
    tip.innerHTML = html; tip.classList.add('show');
    const tw = tip.offsetWidth, th = tip.offsetHeight, W = host.clientWidth;
    let left = x + 14; if (left + tw > W) left = x - tw - 14; if (left < 0) left = 0;
    let top = y - th - 10; if (top < -10) top = y + 14;
    tip.style.left = left + 'px'; tip.style.top = top + 'px';
  }
  const hideTip = (host, tip) => { tip.classList.remove('show'); host.classList.remove('dim'); };
  const roundTop = (x, y, w, h, r) => {
    r = Math.min(r, w / 2, h);
    if (h <= 0) return '';
    return `M${x},${y + h}V${y + r}Q${x},${y} ${x + r},${y}H${x + w - r}Q${x + w},${y} ${x + w},${y + r}V${y + h}Z`;
  };
  const axisY = (svg, padL, padT, innerW, innerH, max, ticks, fmt) => {
    for (let i = 0; i <= ticks; i++) {
      const v = (max / ticks) * i; const y = padT + innerH - (v / max) * innerH;
      el('line', { class: i === 0 ? 'base' : 'grid', x1: padL, x2: padL + innerW, y1: y, y2: y }, svg);
      const t = el('text', { x: padL - 8, y: y + 4, 'text-anchor': 'end' }, svg); t.textContent = fmt(v);
    }
  };
  const axisX = (svg, labels, xAt, y, innerW) => {
    const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(innerW / 56))));
    labels.forEach((l, i) => {
      if (i % every !== 0 && i !== labels.length - 1) return;
      if (i !== labels.length - 1 && labels.length - 1 - i < every && i % every === 0 && i !== 0) return;
      const t = el('text', { x: xAt(i), y, 'text-anchor': 'middle' }, svg); t.textContent = l;
    });
  };

  /* ---------- line / area ---------- */
  function line(host, cfg) {
    setup(host, cfg, (h, c, W, tip) => {
      const H = c.height || 240, padL = c.yAxis === false ? 8 : 48, padR = 12, padT = 10, padB = c.xAxis === false ? 6 : 26;
      const iw = W - padL - padR, ih = H - padT - padB;
      const all = c.series.flatMap((s) => s.values);
      const max = niceMax(Math.max(...all) * 1.05), ticks = c.ticks || 4;
      const fmt = c.axisFormat || shortFmt, tf = c.format || defaultFmt;
      const n = c.labels.length;
      const xAt = (i) => padL + (n === 1 ? iw / 2 : (i / (n - 1)) * iw);
      const yAt = (v) => padT + ih - (v / max) * ih;
      const svg = el('svg', { viewBox: `0 0 ${W} ${H}`, height: H, role: 'img', 'aria-label': c.aria || 'Graphique' }, h);
      if (c.yAxis !== false) axisY(svg, padL, padT, iw, ih, max, ticks, fmt);
      else el('line', { class: 'base', x1: padL, x2: padL + iw, y1: padT + ih, y2: padT + ih }, svg);
      if (c.xAxis !== false) axisX(svg, c.labels, xAt, H - 6, iw);
      c.series.forEach((s, si) => {
        const pts = s.values.map((v, i) => [xAt(i), yAt(v)]);
        const d = pts.map((p, i) => (i ? 'L' : 'M') + p[0].toFixed(1) + ',' + p[1].toFixed(1)).join('');
        if (c.area && (si === 0 || c.areaAll)) {
          const gid = 'g' + Math.random().toString(36).slice(2);
          const defs = el('defs', {}, svg); const g = el('linearGradient', { id: gid, x1: 0, x2: 0, y1: 0, y2: 1 }, defs);
          el('stop', { offset: '0%', style: `stop-color:${s.color};stop-opacity:${c.areaOpacity || 0.18}` }, g);
          el('stop', { offset: '100%', style: `stop-color:${s.color};stop-opacity:0` }, g);
          el('path', { d: d + `L${xAt(n - 1)},${padT + ih}L${xAt(0)},${padT + ih}Z`, fill: `url(#${gid})` }, svg);
        }
        el('path', { d, fill: 'none', style: `stroke:${s.color};stroke-width:${s.width || 2};stroke-linejoin:round;stroke-linecap:round${s.dash ? ';stroke-dasharray:4 4' : ''}` }, svg);
      });
      // hover layer
      const cross = el('line', { y1: padT, y2: padT + ih, style: 'stroke:var(--chart-ink,#888);stroke-width:1;stroke-dasharray:3 3;opacity:0' }, svg);
      const dots = c.series.map((s) => el('circle', { r: 4.5, style: `fill:${s.color};stroke:var(--chart-surface,#fff);stroke-width:2;opacity:0` }, svg));
      const hit = el('rect', { class: 'hit', x: padL, y: 0, width: iw, height: H }, svg);
      const move = (ev) => {
        const r = svg.getBoundingClientRect(); const x = ((ev.clientX - r.left) / r.width) * W;
        const i = Math.max(0, Math.min(n - 1, Math.round(((x - padL) / iw) * (n - 1))));
        cross.setAttribute('x1', xAt(i)); cross.setAttribute('x2', xAt(i)); cross.style.opacity = 1;
        c.series.forEach((s, si) => { dots[si].setAttribute('cx', xAt(i)); dots[si].setAttribute('cy', yAt(s.values[i])); dots[si].style.opacity = 1; });
        const rows = c.series.map((s) => `<div><i style="background:${s.color}"></i>${s.name}<span>${tf(s.values[i])}</span></div>`).join('');
        placeTip(h, tip, (xAt(i) / W) * r.width, (yAt(Math.max(...c.series.map((s) => s.values[i]))) / H) * r.height, `<b>${c.tipLabels ? c.tipLabels[i] : c.labels[i]}</b>${rows}`);
      };
      hit.addEventListener('mousemove', move);
      hit.addEventListener('mouseleave', () => { cross.style.opacity = 0; dots.forEach((d) => (d.style.opacity = 0)); hideTip(h, tip); });
    });
  }

  /* ---------- vertical bars (grouped or stacked) ---------- */
  function bars(host, cfg) {
    setup(host, cfg, (h, c, W, tip) => {
      const H = c.height || 240, padL = c.yAxis === false ? 8 : 48, padR = 8, padT = 10, padB = 26;
      const iw = W - padL - padR, ih = H - padT - padB, n = c.labels.length;
      const totals = c.labels.map((_, i) => (c.stacked ? c.series.reduce((a, s) => a + s.values[i], 0) : Math.max(...c.series.map((s) => s.values[i]))));
      const max = niceMax(Math.max(...totals) * 1.05);
      const fmt = c.axisFormat || shortFmt, tf = c.format || defaultFmt;
      const band = iw / n, groupW = Math.min(band * 0.68, c.maxGroup || 56);
      const svg = el('svg', { viewBox: `0 0 ${W} ${H}`, height: H, role: 'img', 'aria-label': c.aria || 'Graphique' }, h);
      if (c.yAxis !== false) axisY(svg, padL, padT, iw, ih, max, c.ticks || 4, fmt);
      const xc = (i) => padL + band * i + band / 2;
      axisX(svg, c.labels, xc, H - 6, iw);
      const yAt = (v) => padT + ih - (v / max) * ih;
      const marks = [];
      c.labels.forEach((lab, i) => {
        const g = el('g', { class: 'mark' }, svg);
        if (c.stacked) {
          let acc = 0; const bw = groupW, x = xc(i) - bw / 2;
          c.series.forEach((s, si) => {
            const v = s.values[i]; const y1 = yAt(acc + v), y0 = yAt(acc);
            const top = si === c.series.length - 1;
            const hgt = Math.max(0, y0 - y1 - (si > 0 ? 2 : 0));
            if (top) el('path', { d: roundTop(x, y1, bw, hgt, 4), style: `fill:${s.color}` }, g);
            else el('rect', { x, y: y1, width: bw, height: hgt, style: `fill:${s.color}` }, g);
            acc += v;
          });
        } else {
          const k = c.series.length, gap = 2, bw = (groupW - gap * (k - 1)) / k;
          c.series.forEach((s, si) => {
            const x = xc(i) - groupW / 2 + si * (bw + gap); const y = yAt(s.values[i]);
            el('path', { d: roundTop(x, y, bw, padT + ih - y, 4), style: `fill:${s.highlight && s.highlight(i) ? s.highlightColor : s.color}` }, g);
          });
        }
        const hit = el('rect', { class: 'hit', x: padL + band * i, y: padT, width: band, height: ih, style: 'cursor:pointer' }, svg);
        marks.push(g);
        hit.addEventListener('mousemove', (ev) => {
          const r = svg.getBoundingClientRect();
          h.classList.add('dim'); marks.forEach((m) => m.classList.toggle('on', m === g));
          const rows = c.series.map((s) => `<div><i style="background:${s.color}"></i>${s.name}<span>${tf(s.values[i])}</span></div>`).join('');
          const tot = c.stacked && c.series.length > 1 ? `<div style="border-top:1px solid rgba(127,127,127,.35);margin-top:4px;padding-top:4px">Total<span>${tf(totals[i])}</span></div>` : '';
          placeTip(h, tip, ev.clientX - r.left, (yAt(totals[i]) / H) * r.height, `<b>${c.tipLabels ? c.tipLabels[i] : lab}</b>${rows}${tot}`);
        });
        hit.addEventListener('mouseleave', () => hideTip(h, tip));
      });
    });
  }

  /* ---------- donut ---------- */
  function donut(host, cfg) {
    cfg.legendItems = cfg.legendItems !== false;
    setup(host, cfg, (h, c, W, tip) => {
      const S = Math.min(W, c.size || 200), R = S / 2, th = c.thickness || Math.max(14, S * 0.13);
      const total = c.items.reduce((a, i) => a + i.value, 0), tf = c.format || ((v) => defaultFmt(v));
      const wrap = document.createElement('div'); wrap.style.cssText = `position:relative;width:${S}px;height:${S}px;margin:${c.align === 'left' ? '0' : '0 auto'}`;
      h.appendChild(wrap);
      const svg = el('svg', { viewBox: `0 0 ${S} ${S}`, width: S, height: S, role: 'img', 'aria-label': c.aria || 'Graphique' }, wrap);
      let a0 = -Math.PI / 2; const marks = [];
      c.items.forEach((it) => {
        const a1 = a0 + (it.value / total) * Math.PI * 2;
        const r0 = R - th, r1 = R - 1, large = a1 - a0 > Math.PI ? 1 : 0;
        const p = (r, a) => [R + r * Math.cos(a), R + r * Math.sin(a)];
        const [x1, y1] = p(r1, a0), [x2, y2] = p(r1, a1), [x3, y3] = p(r0, a1), [x4, y4] = p(r0, a0);
        const path = el('path', { class: 'mark', d: `M${x1},${y1}A${r1},${r1} 0 ${large} 1 ${x2},${y2}L${x3},${y3}A${r0},${r0} 0 ${large} 0 ${x4},${y4}Z`, style: `fill:${it.color};stroke:var(--chart-surface,#fff);stroke-width:2;cursor:pointer` }, svg);
        marks.push(path);
        path.addEventListener('mousemove', (ev) => {
          const r = h.getBoundingClientRect(); h.classList.add('dim'); marks.forEach((m) => m.classList.toggle('on', m === path));
          placeTip(h, tip, ev.clientX - r.left, ev.clientY - r.top, `<b>${it.label}</b><div>${tf(it.value)}<span>${Math.round((it.value / total) * 100)} %</span></div>`);
        });
        path.addEventListener('mouseleave', () => hideTip(h, tip));
        a0 = a1;
      });
      if (c.centerValue) {
        const ctr = document.createElement('div');
        ctr.style.cssText = 'position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;text-align:center';
        ctr.innerHTML = `<div class="cx-center-v" style="font-size:${Math.round(S * 0.13)}px;font-weight:650;line-height:1.1;color:var(--chart-strong,inherit);font-variant-numeric:tabular-nums">${c.centerValue}</div><div class="cx-center-l" style="font-size:12px;color:var(--chart-ink,#888);margin-top:2px">${c.centerLabel || ''}</div>`;
        wrap.appendChild(ctr);
      }
    });
  }

  /* ---------- horizontal ranked bars (HTML) ---------- */
  function hbars(host, cfg) {
    host.classList.add('cx');
    const max = Math.max(...cfg.items.map((i) => i.value)), tf = cfg.format || defaultFmt;
    host.innerHTML = `<div class="cx-hbars">${cfg.items.map((i) => `
      <div class="cx-hrow" title="${i.label} : ${tf(i.value)}"><span class="lab">${i.label}</span>
      <span class="trk"><span class="bar" style="width:${(i.value / max) * 100}%;background:${i.color || cfg.color}"></span></span>
      <span class="val">${tf(i.value)}</span></div>`).join('')}</div>`;
  }

  /* ---------- sparkline ---------- */
  function spark(host, cfg) {
    setup(host, Object.assign({ legend: false }, cfg), (h, c, W) => {
      const H = c.height || 36, v = c.values, n = v.length, mn = Math.min(...v), mx = Math.max(...v);
      const x = (i) => 1 + (i / (n - 1)) * (W - 2), y = (val) => 3 + (H - 6) * (1 - (val - mn) / (mx - mn || 1));
      const svg = el('svg', { viewBox: `0 0 ${W} ${H}`, height: H, 'aria-hidden': 'true' }, h);
      const d = v.map((val, i) => (i ? 'L' : 'M') + x(i).toFixed(1) + ',' + y(val).toFixed(1)).join('');
      if (c.area !== false) {
        const gid = 's' + Math.random().toString(36).slice(2);
        const g = el('linearGradient', { id: gid, x1: 0, x2: 0, y1: 0, y2: 1 }, el('defs', {}, svg));
        el('stop', { offset: '0%', style: `stop-color:${c.color};stop-opacity:.2` }, g);
        el('stop', { offset: '100%', style: `stop-color:${c.color};stop-opacity:0` }, g);
        el('path', { d: d + `L${x(n - 1)},${H}L${x(0)},${H}Z`, fill: `url(#${gid})` }, svg);
      }
      el('path', { d, fill: 'none', style: `stroke:${c.color};stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round` }, svg);
    });
  }

  window.Charts = { line, bars, donut, hbars, spark };
})();
