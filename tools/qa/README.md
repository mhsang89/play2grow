# QA scripts (local use only)

Playwright (`node`) and Python scripts used during QA. They assume a **throwaway local WordPress test site** at `http://localhost:8080` with an `admin` / `admin` account (never use these on a real site) and Playwright installed (`PW=/path/to/node_modules/playwright`).

| Script | Purpose |
|---|---|
| `shots.js` | Full-page screenshots + horizontal-overflow check (`PAGES=/,/en/ OUT=dir VP=desktop,mobile`) |
| `blocks.js <post ids>` | Opens each post in the block editor and reports invalid blocks |
| `form.js`, `form_en.js` | Contact form tests (validation, AJAX, no-JS fallback); EN variant targets `/en/contact/` |
| `stale.js` | Form with an expired/stale token (cache scenario) |
| `nav.js` | Keyboard/mobile navigation checks |
| `links.py`, `vi_scan.py` | Link crawl; scan pages for leftover Vietnamese (EN pages) |
| `og.js`, `render_svg.js`, `weight.js`, `upload.js`, `slice.py` | Misc helpers (OG image render, logo render, page weight, upload test, screenshot slicing) |
| `phpcs-ruleset.xml` | PHPCS ruleset (WordPress-Extra + WordPress.WP.I18n). Run from the folder that holds `vendor/` with `installed_paths` set |

Several scripts have hard-coded URLs/paths from the original scratch environment; adjust before use.
