const { chromium } = require('/opt/node-tools/node_modules/playwright');
const fs = require('fs');
// usage: node render_svg.js in.svg out.png width [bg]
(async () => {
  const [inp, out, width, bg] = process.argv.slice(2);
  const svg = fs.readFileSync(inp, 'utf8');
  const b = await chromium.launch(); const p = await b.newPage({ deviceScaleFactor: 1 });
  await p.setContent(`<html><body style="margin:0;background:${bg || 'transparent'}"><img id="i" style="display:block;width:${width}px" src="data:image/svg+xml;base64,${Buffer.from(svg).toString('base64')}"></body></html>`);
  const el = await p.$('#i'); await el.screenshot({ path: out, omitBackground: !bg });
  await b.close();
})();
