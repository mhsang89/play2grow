const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch(); const p = await (await b.newContext()).newPage();
  await p.goto('http://localhost:8080/lien-he/');
  // Simulate a page served from cache days later: invalid nonce + expired stamp.
  await p.evaluate(() => { document.querySelector('[name=p2g_nonce]').value = 'stale'; document.querySelector('[name=p2g_stamp]').value = '1000000000.deadbeefdeadbeef'; });
  const t0 = Date.now();
  await p.fill('#p2g-name', 'Đỗ Hoa'); await p.fill('#p2g-email', 'hoa@example.com'); await p.check('#p2g-consent');
  await p.click('.lead-form__submit');
  await p.waitForSelector('.form-status:not([hidden])', { timeout: 15000 });
  console.log('stale-page result:', (await p.textContent('.form-status')).trim().slice(0, 60), '| took', Date.now() - t0, 'ms');
  await b.close();
})();
