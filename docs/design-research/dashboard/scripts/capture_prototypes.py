"""Screenshot the 3 dashboard maquettes with Playwright.

Usage:  python -I capture_prototypes.py <dashboard_dir> [concept ...]
        concept = a | b | c   (default: all)

Prototype contract (each prototypes/concept-*/index.html must implement):
  window.App.go(route)   -> render a route: overview, analytics, contracts,
                            contract, new-contract, users, settings
  window.App.demo(name)  -> open a UI state: 'modal', 'notifications', 'menu'
  document.documentElement.dataset.ready = '1' once the first render is done.
Console errors and page errors are collected into generated/_capture-log.json.
"""
import json
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

ROOT = Path(sys.argv[1] if len(sys.argv) > 1 else ".").resolve()
WANT = set(sys.argv[2:]) or {"a", "b", "c"}
OUT = ROOT / "generated"
OUT.mkdir(parents=True, exist_ok=True)

CONCEPTS = {
    "a": ("concept-a-minimal-premium", "prototypes/concept-a-minimal/index.html"),
    "b": ("concept-b-modern-saas", "prototypes/concept-b-saas/index.html"),
    "c": ("concept-c-enterprise", "prototypes/concept-c-enterprise/index.html"),
}
DESKTOP = {"width": 1440, "height": 900}
MOBILE = {"width": 390, "height": 844}

# (shot name, viewport, route, demo state or None, full_page)
SHOTS = [
    ("01-overview-desktop", "d", "overview", None, True),
    ("02-overview-mobile", "m", "overview", None, True),
    ("03-analytics-desktop", "d", "analytics", None, True),
    ("04-listing-contracts-desktop", "d", "contracts", None, True),
    ("05-detail-contract-desktop", "d", "contract", None, True),
    ("06-form-new-contract-desktop", "d", "new-contract", None, True),
    ("07-users-roles-desktop", "d", "users", None, True),
    ("08-settings-notifications-desktop", "d", "settings", None, True),
    ("09-modal-desktop", "d", "contracts", "modal", False),
    ("10-notifications-dropdown-desktop", "d", "overview", "notifications", False),
    ("11-listing-contracts-mobile", "m", "contracts", None, True),
    ("12-menu-drawer-mobile", "m", "overview", "menu", False),
    ("13-detail-contract-mobile", "m", "contract", None, True),
]

log = []
with sync_playwright() as p:
    browser = p.chromium.launch()
    for key in sorted(WANT):
        slug, rel = CONCEPTS[key]
        url = (ROOT / rel).as_uri()
        ctxs = {
            "d": browser.new_context(viewport=DESKTOP, device_scale_factor=1),
            "m": browser.new_context(viewport=MOBILE, device_scale_factor=2, is_mobile=True, has_touch=True),
        }
        for name, vp, route, demo, full in SHOTS:
            page = ctxs[vp].new_page()
            errors = []
            page.on("console", lambda m, e=errors: e.append(m.text) if m.type == "error" else None)
            page.on("pageerror", lambda exc, e=errors: e.append(str(exc)))
            entry = {"concept": slug, "shot": name}
            try:
                page.goto(url, wait_until="load", timeout=60000)
                page.wait_for_function("document.documentElement.dataset.ready === '1'", timeout=15000)
                page.evaluate("r => window.App.go(r)", route)
                page.wait_for_timeout(500)
                if demo:
                    page.evaluate("d => window.App.demo(d)", demo)
                    page.wait_for_timeout(450)
                fn = OUT / f"{slug}--{name}.png"
                page.screenshot(path=str(fn), full_page=full)
                entry.update(ok=True, file=fn.name)
            except Exception as exc:
                entry.update(ok=False, error=str(exc)[:300])
            entry["errors"] = errors
            print(json.dumps(entry, ensure_ascii=False))
            log.append(entry)
            page.close()
        for c in ctxs.values():
            c.close()
    browser.close()

prev = OUT / "_capture-log.json"
old = json.loads(prev.read_text(encoding="utf-8")) if prev.exists() else []
keep = [e for e in old if e.get("concept") not in {CONCEPTS[k][0] for k in WANT}]
prev.write_text(json.dumps(keep + log, indent=2, ensure_ascii=False), encoding="utf-8")
