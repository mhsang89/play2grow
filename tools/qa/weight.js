const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch();
  for (const u of ['/', '/hoc-tieng-anh-qua-trai-nghiem-la-gi/', '/lien-he/']) {
    const p = await (await b.newContext({ viewport: { width: 390, height: 844 } })).newPage();
    let n = 0, bytes = 0; const types = {};
    p.on('response', async r => { try { const buf = await r.body(); n++; bytes += buf.length; const t = r.request().resourceType(); types[t] = (types[t] || 0) + buf.length; } catch (e) {} });
    await p.goto('http://localhost:8080' + u, { waitUntil: 'networkidle' });
    const cls = await p.evaluate(() => new Promise(res => { let v = 0; new PerformanceObserver(l => { for (const e of l.getEntries()) if (!e.hadRecentInput) v += e.value; }).observe({ type: 'layout-shift', buffered: true }); setTimeout(() => res(v), 1500); }));
    console.log(u, 'requests', n, 'KB', (bytes / 1024).toFixed(0), JSON.stringify(Object.fromEntries(Object.entries(types).map(([k, v]) => [k, Math.round(v / 1024)]))), 'CLS', cls.toFixed(3));
  }
  await b.close();
})();
