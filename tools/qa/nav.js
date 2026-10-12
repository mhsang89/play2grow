const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch(); const out = [];
  // Mobile menu
  let p = await (await b.newContext({ viewport: { width: 390, height: 844 } })).newPage();
  await p.goto('http://localhost:8080/');
  out.push('nav hidden initially: ' + !(await p.isVisible('#site-nav')));
  await p.click('.nav-toggle');
  out.push('after toggle visible: ' + await p.isVisible('#site-nav') + ' aria-expanded=' + await p.getAttribute('.nav-toggle', 'aria-expanded'));
  const sub = p.locator('.primary-nav .submenu-toggle').first();
  await sub.click();
  out.push('submenu open: ' + await sub.getAttribute('aria-expanded') + ' visible=' + await p.locator('.primary-nav .sub-menu').first().isVisible());
  await p.keyboard.press('Escape'); await p.waitForTimeout(100);
  out.push('esc closes submenu: ' + await sub.getAttribute('aria-expanded'));
  await p.keyboard.press('Escape');
  out.push('esc closes menu: ' + await p.getAttribute('.nav-toggle', 'aria-expanded') + ' focus=' + await p.evaluate(() => document.activeElement.className));
  await p.screenshot({ path: process.env.OUT + '/mobile_menu.png' });
  // Desktop keyboard: Tab to skip link, dropdown via focus-within
  p = await (await b.newContext({ viewport: { width: 1366, height: 900 } })).newPage();
  await p.goto('http://localhost:8080/');
  await p.keyboard.press('Tab');
  out.push('first tab: ' + await p.evaluate(() => document.activeElement.textContent.trim()));
  await p.keyboard.press('Enter'); await p.waitForTimeout(100);
  out.push('skip link target focused: ' + await p.evaluate(() => document.activeElement.id));
  await p.focus('.primary-nav > ul > li.menu-item-has-children > a');
  out.push('dropdown on focus: ' + await p.locator('.primary-nav .sub-menu').first().isVisible());
  // Search toggle
  await p.click('[data-search-toggle]');
  out.push('search panel open, focus=' + await p.evaluate(() => document.activeElement.type));
  // Editor notes: guest vs editor
  const g = await (await p.request.get('http://localhost:8080/gioi-thieu/')).text();
  out.push('guest notes: ' + (g.match(/p2g-editor-note/g) || []).length);
  const c = await b.newContext(); const e = await c.newPage();
  await e.goto('http://localhost:8080/wp-login.php'); await e.fill('#user_login', 'editor1'); await e.fill('#user_pass', 'editor1'); await e.click('#wp-submit'); await e.waitForLoadState('networkidle');
  await e.goto('http://localhost:8080/gioi-thieu/');
  out.push('editor notes: ' + await e.locator('.p2g-editor-note').count());
  const r = await e.goto('http://localhost:8080/wp-admin/edit.php?post_type=p2g_lead');
  out.push('editor can open leads list: ' + (r.status() === 200 && !(await e.content()).includes('not allowed') && !(await e.content()).includes('không được phép') ? 'YES' : 'NO (status ' + r.status() + ')'));
  await b.close(); console.log(out.join('\n'));
})();
