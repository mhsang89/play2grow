const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  const out = [];
  // 1. JS: empty submit -> client errors
  let ctx = await b.newContext({ viewport: { width: 390, height: 844 } }); let p = await ctx.newPage();
  await p.goto('http://localhost:8080/en/contact/');
  await p.click('.lead-form__submit');
  out.push('empty submit errors: ' + JSON.stringify(await p.$$eval('.field__error:not([hidden])', els => els.map(e => e.id))));
  out.push('focused: ' + await p.evaluate(() => document.activeElement.id));
  // 2. invalid phone
  await p.fill('#p2g-name', 'Nguyễn Thị Lan'); await p.fill('#p2g-phone', '12345'); await p.check('#p2g-consent');
  await p.click('.lead-form__submit');
  out.push('bad phone: ' + JSON.stringify(await p.$$eval('.field__error:not([hidden])', els => els.map(e => e.textContent))));
  // 3. too fast -> server rejects (stamp < 3s) ; then wait and submit valid
  await p.fill('#p2g-phone', '0912 345 678');
  await p.waitForTimeout(3200);
  await p.selectOption('#p2g-interest', { index: 1 }); await p.selectOption('#p2g-age', { index: 2 });
  await p.fill('#p2g-message', 'Tôi muốn nhận thông tin sản phẩm.');
  await p.click('.lead-form__submit');
  await p.waitForSelector('.form-status:not([hidden])');
  out.push('ajax result: ' + (await p.textContent('.form-status')).trim() + ' | class=' + await p.getAttribute('.form-status', 'class') + ' | form hidden=' + await p.$eval('.lead-form', f => getComputedStyle(f).display));
  await p.screenshot({ path: process.env.OUT + '/form_success_mobile.png' });
  await ctx.close();
  // 4. No-JS submission
  ctx = await b.newContext({ javaScriptEnabled: false }); p = await ctx.newPage();
  await p.goto('http://localhost:8080/en/contact/');
  await p.fill('#p2g-name', 'A'); await p.fill('#p2g-email', 'not-an-email');
  await p.waitForTimeout(3200);
  await p.click('.lead-form__submit'); await p.waitForLoadState('load');
  out.push('nojs url: ' + p.url().replace(/p2g_t=\w+/, 'p2g_t=TOKEN'));
  out.push('nojs status: ' + (await p.textContent('.form-status')).trim());
  out.push('nojs errors: ' + JSON.stringify(await p.$$eval('.field__error:not([hidden])', els => els.map(e => e.textContent))));
  out.push('nojs kept name value: ' + await p.inputValue('#p2g-name'));
  await p.fill('#p2g-name', 'Trần Văn Bình'); await p.fill('#p2g-email', 'binh@example.com'); await p.check('#p2g-consent');
  await p.waitForTimeout(3200);
  await p.click('.lead-form__submit'); await p.waitForLoadState('load');
  out.push('nojs success: ' + (await p.textContent('.form-status')).trim());
  await ctx.close();
  await b.close();
  console.log(out.join('\n'));
})();
