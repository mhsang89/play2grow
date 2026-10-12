# HANDOFF – Play2Grow (read this first in Claude Code local)

Status as of 2026-10-12. Version **1.2.0**. Branch `claude/awesome-feynman-uy0rki` of `mhsang89/play2grow` (no PR opened; the owner never asked for one).

```bash
git clone https://github.com/mhsang89/play2grow && cd play2grow
git checkout claude/awesome-feynman-uy0rki
```

## 1. What this project is

Deliverable for **play2grow.edu.vn**, a children's centre teaching English through experience (plus art, music, dance classes). The owner is Vietnamese; all owner-facing docs and the default content are in Vietnamese, with a full English version.

- **Theme** `play2grow-theme/` – classic WordPress theme (text domain `play2grow-theme`, function prefix `p2g_`).
- **Plugin** `play2grow-core/` – contact form, lead storage (CPT `p2g_lead`), company info, shortcodes `[p2g_*]` (text domain `play2grow-core`, prefix `p2gc_`).
- **Installable ZIPs**: `dist/play2grow-theme.zip`, `dist/play2grow-core.zip`, `dist/SHA256SUMS.txt` (built by `tools/build.sh`; rebuild after any change).
- **Source content**: `content-src/` (Markdown; `en/` = English). `tools/md2blocks.py` turns it into block markup in `play2grow-theme/starter-content/`.
- **Brand**: `brand/` (logo SVG/PNG, favicon, OG image; regenerate with `tools/make_logo.py`, needs fontTools + Be Vietnam Pro).
- **Docs** (Vietnamese): README, INITIAL_AUDIT, PRODUCT_AUDIT, DESIGN_SPEC, IMPLEMENTATION_PLAN, CONTENT_AND_SITEMAP, COMPANY_INFO_REQUIRED, QA_REPORT, INSTALLATION_CHECKLIST, CHANGELOG, NETWORK_ACCESS_GUIDE.

## 2. Non-negotiable rules (from the owner's master prompts)

- Never fabricate company/legal info, testimonials, statistics, features, licences or AI features. Only publish what the owner supplied or what is verified.
- Do **not** touch production: no deploys, no overwriting the live theme, no deleting data, no DNS/hosting changes. Everything is a package for the owner to install on staging first.
- No secrets and no tracking/third-party assets in the theme. Do not copy ILA (ila.edu.vn) assets or text. ILA is behind Cloudflare; we never bypassed it. "Similar style" is based on generic education-site patterns only.
- All content must be editable in WP Admin (no hardcoded page content).
- Report honestly what was tested, statically checked, or not tested.
- Git: commit often; trailers `Co-Authored-By: …` and `Claude-Session: …` as per the session reminder; never put model identifiers in code or commits; do not create a PR unless asked.

## 3. Owner-confirmed facts (the only company facts allowed on the site)

| Item | Value | Notes |
|---|---|---|
| Business model | Centre with real classes, 7 programs actually running | confirmed 11/10/2026 |
| Founder + contact person | **Bùi Thủy** | name with diacritics confirmed 12/10 |
| Email | buithuy@play2grow.edu.vn | old play2grow9@gmail.com dropped |
| Phone | +84982534460 | confirmed 12/10; Zalo not confirmed (same number? ask) |
| Address | Khối đế P1, Khu A5 Diamond, số 3 đường Tân Thạnh, phường Tân Sơn Nhì, TP. Hồ Chí Minh | district removed on request. Owner typed "Nhỉ" once, "Nhì" twice; we kept **Nhì**. Unknown if class location or registered HQ |
| Operating entity name | **Công ty TNHH Giáo dục Play2Grow** | owner asked to display it on 12/10. Earlier that day owner had said the legal entity is "BÙI THỦY". **NOT verified against a registration certificate**; flagged in COMPANY_INFO_REQUIRED.md. Shown in About page, privacy policy and JSON-LD `legalName` |
| Logo | the one in `brand/` | approved 12/10 |

Not provided and therefore NOT shown: tax/enterprise code, registration details, legal representative, licences (education licence need unassessed), opening hours, social links, class details (age, schedule, fee, teacher show "Đang cập nhật – liên hệ để được tư vấn").

## 4. Architecture notes you need

- **Classic theme quirk**: group blocks get `.wp-block-group__inner-container`; CSS handles both wrapped and unwrapped.
- **Starter content importer** (`play2grow-theme/inc/starter-content.php`, UI at *Giao diện → Play2Grow*): Step 1 creates VI pages/posts/categories (drafts by default, never overwrites; existing URLs get "[Bản mới]" drafts), **Step 1b** creates the English version linked as translations (WPML or Polylang), Step 2 (`p2g_run_starter_finalize`) creates menus (VI + EN), sets reading options, and fills **empty** company fields from `starter-content/manifest.php` → `'company'`. Tokens in content: `{{page:}} {{post:}} {{cat:}} {{posts_page}} {{asset:}} {{contact}}`, `<!-- p2g:pattern slug -->`.
- **Vietnamese import always renders source strings** via `p2g_force_source_strings()` (gettext filters), because `switch_to_locale('vi')` fails when the vi language pack is not installed. English import uses `switch_to_locale('en_US')`.
- **Programs** = pages using template `page-templates/template-program.php` with meta `_p2g_prog_group` (english/art/music/dance/other), `_p2g_prog_status` (live/upcoming/beta/paused) and fact fields. The overview `template-programs.php` groups them.
- **Multilingual** (`inc/multilingual.php`): `p2g_ml_plugin()` (wpml|polylang|''), `p2g_current_lang()`, `p2g_translate_id()`, `p2g_language_switcher()`, `p2g_home_url()` (use this instead of `home_url('/')` in templates). Source language strings are Vietnamese; English comes from `languages/en_US.mo` (+ `en_GB.mo`) for the theme and `play2grow-core-en_US.mo` for the plugin. Both need `Domain Path: /languages` (already set).
- **Translations workflow**: `wp i18n make-pot …` → edit `tools/i18n/theme.en.txt` / `core.en.txt` (one JSON string per line, **same order as the POT**, plurals as `singular\u0000plural`) → `python3 tools/i18n/build_po.py` → `wp i18n make-mo` → copy to `en_GB`. Adding or changing a translatable string requires redoing this.
- **Form security** (plugin): nonce + signed timestamp (≥3 s), honeypot, rate limit (filter `p2g_rate_limit`), token refresh endpoint for LiteSpeed cache, no personal data in URLs, privacy exporter/eraser, retention cron `p2g_purge_leads` (default 180 days).
- **SEO**: meta description, OG, canonical for archives, JSON-LD (Organization/WebSite/BreadcrumbList/BlogPosting); turns itself off when an SEO plugin is active.
- Site icon/logo fall back to bundled `assets/images/*` when none is set (filter `p2g_use_default_logo`).

## 5. What was tested (and what was not)

Tested on WordPress 7.1.3 / PHP 8.3 (local `php -S` + SQLite, scratch environment that does **not** persist):
- ZIP install via WP Admin on a fresh site; Theme Check **PASS** (only RECOMMENDED/INFO items); `php -l` clean; PHPCS (WordPress-Extra + I18n) 0 errors.
- Import VI + EN: 46 pages/posts, 0 invalid blocks; 63 unique links, none broken (except WordPress's own `xmlrpc.php`); no console errors; no horizontal overflow at 390 px and 1366 px.
- Form: JS and no-JS paths, honeypot, nonce, rate limit, XSS, stale cache, privacy export/erase, retention purge, English form strings.
- Lighthouse (home, local server, no gzip): 93/98/100/100.
- English flow with **Polylang 3.8** (vi default, en).

**Not tested**: WPML (the live site uses it; the `wpml_*` calls are written but never run), WPML Menu Sync, a live site that already has an `/en/` built by WPML, Elementor Pro, LiteSpeed, Revolution Slider, real email delivery (needs SMTP), real iOS/Android devices, PHP < 8.3, WordPress < 7.1. The English copy and the English privacy policy/terms are our translation, not reviewed by a human or a lawyer. The Vietnamese privacy policy and terms are drafts needing legal review.

## 6. Known limitations

- Test env lacked the Vietnamese language pack, so core strings such as "Search" and the comment-cookie text appeared in English there; on the live (vi) site they are Vietnamese.
- Without the plugin, the privacy page loses the retention number and the form/company blocks hide (documented).
- Rate limiting uses `REMOTE_ADDR` – behind a CDN/proxy configure the real IP or filter `p2g_rate_limit`.
- The logo has not been checked for trademark conflicts.
- The old live site shows demo content from LittleDino (fake testimonials, New York address, `littledino@wgl.com`, demo blog post) that should be removed – see COMPANY_INFO_REQUIRED.md §4. We did not touch it.

## 7. Open items for the owner (waiting on answers)

1. Registration certificate for "Công ty TNHH Giáo dục Play2Grow" (name, enterprise code, issuing authority, legal representative). If the company is **not** registered, remove the legal name in Customizer → Play2Grow – Company details (the row hides itself).
2. Is the Tân Sơn Nhì address the class location or the registered HQ? Confirm ward spelling.
3. Is +84982534460 also the Zalo number? Otherwise give the Zalo URL. Social links are empty.
4. Education/operating licence: owner should ask a legal professional whether one is required.
5. Per-class details (age, schedule, fee, teacher, class size) and real photos with parental consent.
6. Notification email for the form + SMTP set-up.

## 8. Suggested next steps (local)

1. Re-create a test WordPress locally (e.g. wp-env / LocalWP / Docker or `php -S` + SQLite), install both ZIPs, optionally Polylang, run Step 1 → 1b → 2, and repeat the checks in section 5. The QA scripts are in `tools/qa/` (see its README; they assume a throwaway test site on `localhost:8080` with an `admin/admin` account, adjust paths). Playwright and Lighthouse were used for screenshots, block validity and link crawling.
2. Install on **staging** of the real site with WPML and test Step 1b there; fix anything WPML-specific (menus, translation linking, language switcher).
3. After the owner answers section 7, update `starter-content/manifest.php` → `'company'`, the About-page note in `content-src/pages/gioi-thieu.md`, the docs, run `tools/build.sh`, rebuild `.po/.mo` if strings changed.
4. Optional improvements: social links, class photos, real teachers/facilities pages (or hide/noindex them), 301 redirects (`/contacts/` → `/lien-he/`, `/blog-grid/` → `/goc-hoc-tap/`), Vietnamese `.po` is not needed (source strings are Vietnamese).
5. Production rollout only via INSTALLATION_CHECKLIST.md with a backup; rollback = reactivate LittleDino Child.

## 9. Handy commands

```bash
tools/build.sh                         # md2blocks + ZIPs + SHA256SUMS
python3 tools/md2blocks.py content-src play2grow-theme/starter-content
python3 tools/make_logo.py             # regenerate brand/*
python3 tools/i18n/build_po.py         # .pot + txt -> .po (then wp i18n make-mo)
```
