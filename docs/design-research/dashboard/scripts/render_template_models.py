"""Render template page captures in every mockup model.

Usage:  python -I render_template_models.py <dir> [page ...]
        dir  = mockups/template-pages   (holds device-models.html and <page>/sources/)
        page = dashboard | cars | reservations | calendar   (default: every <page>/sources found)
Writes: <dir>/<page>/model-<name>.png and <dir>/<page>/transparent/model-<name>.png
"""
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

ROOT = Path(sys.argv[1] if len(sys.argv) > 1 else ".").resolve()
FRAME = (ROOT / "device-models.html").as_uri()
PAGES = sys.argv[2:] or sorted(p.parent.name for p in ROOT.glob("*/sources"))
MODELS = ["laptop", "browser", "browser-full", "tablet", "phone", "phones-trio", "combo", "family", "perspective"]

with sync_playwright() as pw:
    browser = pw.chromium.launch()
    page = browser.new_context(viewport={"width": 2200, "height": 1400}, device_scale_factor=2).new_page()
    for pg in PAGES:
        (ROOT / pg / "transparent").mkdir(parents=True, exist_ok=True)
        for m in MODELS:
            for transparent in (False, True):
                page.goto(f"{FRAME}?p={pg}&model={m}" + ("&bg=0" if transparent else ""), wait_until="load")
                page.wait_for_function("document.documentElement.dataset.ready === '1'", timeout=20000)
                page.wait_for_timeout(250)
                out = ROOT / pg / ("transparent" if transparent else "") / f"model-{m}.png"
                page.locator("#stage").screenshot(path=str(out), omit_background=transparent)
        print("rendered", pg, "->", len(MODELS), "models")
    browser.close()
