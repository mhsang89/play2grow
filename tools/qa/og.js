const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
(async () => {
  const logo = fs.readFileSync(process.argv[2]).toString('base64');
  const font = fs.readFileSync(process.argv[3]).toString('base64');
  const b = await chromium.launch(); const p = await b.newPage({ viewport: { width: 1200, height: 630 } });
  await p.setContent(`<html><head><style>@font-face{font-family:B;src:url(data:font/woff2;base64,${font})}
  body{margin:0;width:1200px;height:630px;background:#FFF8EC;font-family:B;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative;overflow:hidden}
  .c1{position:absolute;right:-90px;top:-90px;width:320px;height:320px;border-radius:50%;background:#FFB938;opacity:.55}
  .c2{position:absolute;left:-120px;bottom:-160px;width:420px;height:420px;border-radius:50%;background:#E3F4EC}
  img{width:760px;position:relative} p{position:relative;margin:36px 0 0;font-size:40px;color:#0A4F44;letter-spacing:.01em}</style></head>
  <body><div class="c1"></div><div class="c2"></div><img src="data:image/svg+xml;base64,${logo}"><p>Explore the world. Play with English. Grow every day.</p></body></html>`);
  await p.waitForTimeout(300);
  await p.screenshot({ path: process.argv[4] });
  await b.close();
})();
