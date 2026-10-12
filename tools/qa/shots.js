const { chromium } = require(process.env.PW || 'playwright');
(async () => {
  const browser = await chromium.launch();
  const pages = (process.env.PAGES || '/').split(',');
  const vps = { desktop: { width: 1366, height: 900 }, mobile: { width: 390, height: 844 }, tablet: { width: 820, height: 1180 } };
  const which = (process.env.VP || 'desktop,mobile').split(',');
  const errors = [];
  for (const v of which) {
    const ctx = await browser.newContext({ viewport: vps[v], deviceScaleFactor: 1 });
    const page = await ctx.newPage();
    page.on('console', m => { if (m.type() === 'error') errors.push(`${v} console: ${m.text()}`); });
    page.on('pageerror', e => errors.push(`${v} pageerror: ${e.message}`));
    page.on('requestfailed', r => errors.push(`${v} reqfail: ${r.url()}`));
    for (const p of pages) {
      const resp = await page.goto('http://localhost:8080' + p, { waitUntil: 'networkidle' });
      const name = (p.replace(/[^a-z0-9]+/gi, '_') || 'home') + '_' + v;
      const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
      if (overflow > 0) errors.push(`${v} ${p} horizontal overflow ${overflow}px`);
      await page.screenshot({ path: `${process.env.OUT}/${name}.png`, fullPage: process.env.FULL !== '0' });
      console.log(resp.status(), p, v, 'overflow', overflow);
    }
    await ctx.close();
  }
  await browser.close();
  console.log(errors.join('\n') || 'no errors');
})();
