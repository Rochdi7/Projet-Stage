"""Screenshot the CURRENT backoffice (read-only GET pages) via the demo login.

Usage:  python -I capture_current.py <output_dir> [base_url]
Logos are hidden with injected CSS so no brand mark appears in the captures.
"""
import json
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

OUT = Path(sys.argv[1] if len(sys.argv) > 1 else "current")
BASE = sys.argv[2] if len(sys.argv) > 2 else "http://127.0.0.1:8000"
OUT.mkdir(parents=True, exist_ok=True)

PAGES = [
    ("dashboard", "/backoffice/dashboard"),
    ("rental-contracts", "/backoffice/rental-contracts"),
    ("vehicles", "/backoffice/vehicles"),
    ("bookings", "/backoffice/bookings"),
    ("bookings-calendar", "/backoffice/bookings/calendar/view"),
    ("clients", "/backoffice/clients"),
    ("payments", "/backoffice/payments"),
    ("invoices", "/backoffice/invoices"),
    ("finance-transactions", "/backoffice/finance/transactions"),
    ("vehicle-credits", "/backoffice/vehicle-credits"),
    ("oil-changes", "/backoffice/vehicle-documents/oil-changes"),
    ("users", "/backoffice/users"),
    ("notifications", "/backoffice/notifications"),
    ("profile", "/backoffice/profile"),
]
MOBILE = {"dashboard", "rental-contracts", "vehicles"}

HIDE_LOGO = """
.logo, .logo-small, .dark-logo, .logo-normal, .header-left img, .sidebar-logo,
.sidebar-logo img, img[src*='logo'] { visibility: hidden !important; }
"""

log = []
with sync_playwright() as p:
    browser = p.chromium.launch()
    ctx = browser.new_context(viewport={"width": 1440, "height": 900})
    page = ctx.new_page()
    page.goto(BASE + "/login/demo", wait_until="networkidle", timeout=60000)
    print("after login:", page.url)

    def shoot(pg, slug, path, suffix, full):
        pg.goto(BASE + path, wait_until="networkidle", timeout=60000)
        pg.add_style_tag(content=HIDE_LOGO)
        pg.wait_for_timeout(1500)
        fn = OUT / f"current-{slug}-{suffix}.png"
        pg.screenshot(path=str(fn), full_page=full)
        return {"slug": slug, "view": suffix, "url": pg.url, "file": fn.name}

    for slug, path in PAGES:
        try:
            log.append(shoot(page, slug, path, "desktop", True))
        except Exception as exc:
            log.append({"slug": slug, "error": str(exc)[:200]})
        print(log[-1])

    mctx = browser.new_context(viewport={"width": 390, "height": 844}, device_scale_factor=2,
                               is_mobile=True, has_touch=True,
                               storage_state=ctx.storage_state())
    mpage = mctx.new_page()
    for slug, path in PAGES:
        if slug in MOBILE:
            try:
                log.append(shoot(mpage, slug, path, "mobile", False))
            except Exception as exc:
                log.append({"slug": slug, "error": str(exc)[:200]})
            print(log[-1])
    browser.close()

(OUT / "_capture-log.json").write_text(json.dumps(log, indent=2), encoding="utf-8")
