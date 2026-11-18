"""Render the current-design screenshots inside laptop / phone frames.

Usage:  python -I render_device_mockups.py <mockups_dir>
        (mockups_dir = docs/design-research/dashboard/mockups/current)
Reads   <mockups_dir>/sources/*.png  and  <mockups_dir>/device-frame.html
Writes  <mockups_dir>/laptop-*.png, phone-*.png, scene-*.png (+ transparent/ variants)
"""
import sys
from pathlib import Path
from urllib.parse import urlencode

from playwright.sync_api import sync_playwright

ROOT = Path(sys.argv[1] if len(sys.argv) > 1 else ".").resolve()
FRAME = (ROOT / "device-frame.html").as_uri()
(ROOT / "transparent").mkdir(exist_ok=True)

LAPTOP = ["dashboard", "vehicles", "contracts", "clients", "finance", "calendar"]
PHONE = ["dashboard", "vehicles", "contracts"]
COMBO = [("dashboard", "dashboard"), ("vehicles", "vehicles"), ("contracts", "contracts")]

jobs = []
for p in LAPTOP:
    jobs.append((f"laptop-{p}", {"layout": "laptop", "d": f"sources/desktop-{p}.png"}))
for p in PHONE:
    jobs.append((f"phone-{p}", {"layout": "phone", "m": f"sources/mobile-{p}.png"}))
for dp, mp in COMBO:
    jobs.append((f"scene-{dp}-laptop-phone", {"layout": "combo", "d": f"sources/desktop-{dp}.png", "m": f"sources/mobile-{mp}.png"}))

with sync_playwright() as pw:
    browser = pw.chromium.launch()
    ctx = browser.new_context(viewport={"width": 1800, "height": 1200}, device_scale_factor=2)
    page = ctx.new_page()
    for name, params in jobs:
        for transparent in (False, True):
            q = dict(params, **({"bg": "0"} if transparent else {}))
            page.goto(FRAME + "?" + urlencode(q), wait_until="load")
            page.wait_for_function("document.documentElement.dataset.ready === '1'", timeout=15000)
            page.wait_for_timeout(200)
            out = ROOT / ("transparent" if transparent else "") / f"{name}.png"
            page.locator("#stage").screenshot(path=str(out), omit_background=transparent)
        print("rendered", name)
    browser.close()
