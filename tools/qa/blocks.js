const { chromium } = require('/opt/node-tools/node_modules/playwright');
(async () => {
  const b = await chromium.launch(); const ctx = await b.newContext({ viewport: { width: 1400, height: 900 } }); const p = await ctx.newPage();
  await p.goto('http://localhost:8080/wp-login.php');
  await p.fill('#user_login', 'admin'); await p.fill('#user_pass', 'admin'); await p.click('#wp-submit'); await p.waitForLoadState('networkidle');
  const errs = [];
  p.on('pageerror', e => errs.push('pageerror ' + e.message));
  for (const id of process.argv.slice(2)) {
    await p.goto(`http://localhost:8080/wp-admin/post.php?post=${id}&action=edit`);
    await p.waitForFunction(() => window.wp && wp.data && wp.data.select('core/block-editor') && wp.data.select('core/block-editor').getBlocks().length > 0, null, { timeout: 60000 }).catch(() => {});
    const r = await p.evaluate(() => {
      const bad = [];
      const walk = (bs) => bs.forEach(b => { if (!b.isValid) bad.push(b.name + ': ' + (b.originalContent || '').slice(0, 90)); walk(b.innerBlocks || []); });
      const blocks = wp.data.select('core/block-editor').getBlocks(); walk(blocks);
      return { title: wp.data.select('core/editor').getEditedPostAttribute('title'), count: blocks.length, bad };
    });
    console.log(id, r.title, 'blocks', r.count, 'invalid', r.bad.length, r.bad.slice(0, 5).join(' | '));
  }
  console.log(errs.join('\n') || 'no page errors');
  await b.close();
})();
