const { chromium } = require('/opt/node-tools/node_modules/playwright');
const B = 'http://localhost:8081';
(async () => {
  const b = await chromium.launch(); const p = await (await b.newContext({ viewport: { width: 1400, height: 1000 } })).newPage();
  const errs = []; p.on('pageerror', e => errs.push(e.message));
  await p.goto(B + '/wp-login.php'); await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin'); await p.click('#wp-submit'); await p.waitForLoadState('networkidle');
  // Theme upload (Giao diện → Thêm mới → Tải giao diện lên)
  await p.goto(B + '/wp-admin/theme-install.php?browse=popular');
  await p.click('.upload-view-toggle');
  await p.setInputFiles('#themezip', process.argv[2]);
  await p.click('#install-theme-submit'); await p.waitForLoadState('networkidle');
  console.log('THEME:', (await p.textContent('.wrap')).replace(/\s+/g, ' ').slice(0, 300));
  await p.click('a.activatelink'); await p.waitForLoadState('networkidle');
  console.log('ACTIVATED notice:', (await p.textContent('#wpbody-content')).replace(/\s+/g, ' ').match(/Đã kích hoạt giao diện Play2Grow[^.]*\.|New theme activated\.?/g));
  // Plugin upload
  await p.goto(B + '/wp-admin/plugin-install.php?tab=upload');
  await p.setInputFiles('#pluginzip', process.argv[3]);
  await p.click('#install-plugin-submit'); await p.waitForLoadState('networkidle');
  console.log('PLUGIN:', (await p.textContent('.wrap')).replace(/\s+/g, ' ').slice(0, 250));
  await p.click('a.button-primary[href*="action=activate"]'); await p.waitForLoadState('networkidle');
  // Starter content step 1 (default = draft)
  await p.goto(B + '/wp-admin/themes.php?page=p2g-settings');
  await p.click('form:has(input[name=p2g_do][value=import]) [type=submit]'); await p.waitForLoadState('networkidle');
  console.log('IMPORT:', (await p.textContent('.notice-success')).replace(/\s+/g, ' ').slice(0, 300));
  await p.screenshot({ path: process.env.OUT + '/admin_settings.png', fullPage: true });
  console.log(errs.join('\n') || 'no js errors');
  await b.close();
})();
