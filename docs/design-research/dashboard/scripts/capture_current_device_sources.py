"""Capture viewport-sized, 2x screenshots of the CURRENT backoffice to place
inside laptop / phone mockups. Logos and any "Dream…" branding text are hidden.

Usage:  python -I capture_current_device_sources.py <output_dir> [base_url]
Note: logging in through /login/demo records last_login_at + a demo-login
notification in the local database (that is what the app's demo login does).
"""
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

OUT = Path(sys.argv[1] if len(sys.argv) > 1 else "sources")
BASE = sys.argv[2] if len(sys.argv) > 2 else "http://127.0.0.1:8000"
OUT.mkdir(parents=True, exist_ok=True)

DESKTOP_PAGES = [
    ("dashboard", "/backoffice/dashboard"),
    ("vehicles", "/backoffice/vehicles"),
    ("contracts", "/backoffice/rental-contracts"),
    ("calendar", "/backoffice/bookings/calendar/view"),
    ("clients", "/backoffice/clients"),
    ("finance", "/backoffice/finance/transactions"),
]
MOBILE_PAGES = [
    ("dashboard", "/backoffice/dashboard"),
    ("vehicles", "/backoffice/vehicles"),
    ("contracts", "/backoffice/rental-contracts"),
]

HIDE_BRANDING_CSS = """
.logo, .logo-small, .dark-logo, .logo-normal, .sidebar-logo, .header-left img,
img[src*='logo'] { visibility: hidden !important; }
"""
HIDE_BRANDING_JS = """
() => {
  const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
  const hits = [];
  while (walker.nextNode()) if (/dream/i.test(walker.currentNode.textContent)) hits.push(walker.currentNode);
  hits.forEach(t => { const el = t.parentElement && (t.parentElement.closest('footer, .footer, p, div') || t.parentElement); if (el) el.style.visibility = 'hidden'; });
  return hits.length;
}
"""


def shoot(page, path, fn):
    page.goto(BASE + path, wait_until="networkidle", timeout=60000)
    page.add_style_tag(content=HIDE_BRANDING_CSS)
    hidden = page.evaluate(HIDE_BRANDING_JS)
    page.wait_for_timeout(1200)
    page.screenshot(path=str(fn), full_page=False)
    print(fn.name, "| branding text nodes hidden:", hidden, "| url:", page.url)


with sync_playwright() as p:
    browser = p.chromium.launch()
    desk = browser.new_context(viewport={"width": 1440, "height": 900}, device_scale_factor=2)
    page = desk.new_page()
    page.goto(BASE + "/login/demo", wait_until="networkidle", timeout=60000)
    for slug, path in DESKTOP_PAGES:
        shoot(page, path, OUT / f"desktop-{slug}.png")
    mob = browser.new_context(viewport={"width": 390, "height": 844}, device_scale_factor=2,
                              is_mobile=True, has_touch=True, storage_state=desk.storage_state())
    mpage = mob.new_page()
    for slug, path in MOBILE_PAGES:
        shoot(mpage, path, OUT / f"mobile-{slug}.png")
    browser.close()
