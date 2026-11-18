"""Capture pages of the admin template demo, with all "Dreams Rent"
branding hidden (logo images + any text containing "dream").

Usage:  python -I capture_template_dashboard.py <output_dir> [page ...]
        page = dashboard | cars | reservations | calendar | <any admin slug>
Writes: <output_dir>/<page>/sources/desktop.png (1440x900 @2x), desktop-full.png,
        tablet.png (1180x820 @2x), mobile.png (390x844 @2x), mobile-full.png
"""
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

ROOT = Path(sys.argv[1] if len(sys.argv) > 1 else "template-pages")
BASE = "https://dreamsrent.dreamstechnologies.com/laravel/template/public/admin/"
SLUG = {"dashboard": "index"}  # page name -> URL slug
PAGES = sys.argv[2:] or ["dashboard", "cars", "reservations", "calendar"]

HIDE_CSS = """
img[src*='logo' i], .logo, .logo-small, .logo-normal, .dark-logo, .logo-white,
.sidebar-logo img, .header-left .logo { visibility: hidden !important; }
"""
HIDE_TEXT_JS = """
() => {
  const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
  const hits = [];
  while (w.nextNode()) if (/dream/i.test(w.currentNode.textContent)) hits.push(w.currentNode);
  hits.forEach(t => {
    const el = t.parentElement && (t.parentElement.closest('footer, .footer, p, a, span, div') || t.parentElement);
    if (el) el.style.visibility = 'hidden';
  });
  // also hide any remaining <img> whose alt mentions the brand
  document.querySelectorAll('img[alt]').forEach(i => { if (/dream/i.test(i.alt)) i.style.visibility = 'hidden'; });
  return hits.length;
}
"""
CHECK_JS = """
() => {
  const vis = el => { const s = getComputedStyle(el); return s.visibility !== 'hidden' && s.display !== 'none' && el.offsetParent !== null; };
  const imgs = [...document.images].filter(i => /logo/i.test(i.src) && vis(i)).length;
  const w = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT); let txt = 0;
  while (w.nextNode()) { const p = w.currentNode.parentElement; if (/dream/i.test(w.currentNode.textContent) && p && vis(p)) txt++; }
  return { visibleLogoImgs: imgs, visibleBrandText: txt };
}
"""

SHOTS = [
    ("desktop", {"width": 1440, "height": 900}, False, False),
    ("desktop-full", {"width": 1440, "height": 900}, True, False),
    ("tablet", {"width": 1180, "height": 820}, False, True),
    ("mobile", {"width": 390, "height": 844}, False, True),
    ("mobile-full", {"width": 390, "height": 844}, True, True),
]

with sync_playwright() as p:
  browser = p.chromium.launch()
  for pg_name in PAGES:
    URL = BASE + SLUG.get(pg_name, pg_name)
    OUT = ROOT / pg_name / "sources"
    OUT.mkdir(parents=True, exist_ok=True)
    for name, vp, full, touch in SHOTS:
        ctx = browser.new_context(viewport=vp, device_scale_factor=2, is_mobile=touch and vp["width"] < 800, has_touch=touch)
        page = ctx.new_page()
        page.goto(URL, wait_until="networkidle", timeout=90000)
        if full:  # trigger lazy content / chart rendering further down
            h = page.evaluate("document.documentElement.scrollHeight")
            for y in range(0, h, 600):
                page.evaluate(f"window.scrollTo(0,{y})"); page.wait_for_timeout(120)
            page.evaluate("window.scrollTo(0,0)")
        page.add_style_tag(content=HIDE_CSS)
        hidden = page.evaluate(HIDE_TEXT_JS)
        page.wait_for_timeout(3000)  # charts animate in
        page.evaluate("document.fonts.ready.then(() => 1)")  # icon fonts must be loaded
        for _ in range(10):  # retry until the icon font reports loaded
            if page.evaluate("[...document.fonts].filter(f => /tabler/i.test(f.family) && f.status === 'loaded').length > 0 || ![...document.fonts].some(f => /tabler/i.test(f.family))"):
                break
            page.wait_for_timeout(1000)
        page.wait_for_timeout(500)
        page.screenshot(path=str(OUT / f"{name}.png"), full_page=full)
        print(pg_name, name, "| brand text hidden:", hidden, "| after:", page.evaluate(CHECK_JS))
        ctx.close()
  browser.close()
