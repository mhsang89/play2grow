#!/usr/bin/env python3
"""Build en_US .po files from the POT + an ordered list of translations (one JSON string per line).

Usage: tools/i18n/build_po.py   (run from repo root after regenerating the POT files)
Plural entries are written as "singular\u0000plural".
"""
import json, sys, polib

JOBS = [
    ('play2grow-theme/languages/play2grow-theme.pot', 'tools/i18n/theme.en.txt', 'play2grow-theme/languages/en_US.po'),
    ('play2grow-core/languages/play2grow-core.pot', 'tools/i18n/core.en.txt', 'play2grow-core/languages/play2grow-core-en_US.po'),
]
ok = True
for pot, src, out in JOBS:
    p = polib.pofile(pot)
    tr = [json.loads(l) for l in open(src, encoding='utf-8') if l.strip()]
    if len(tr) != len(p):
        print(f'{src}: {len(tr)} lines but {len(p)} entries in {pot}'); ok = False; continue
    for e, t in zip(p, tr):
        if e.msgid_plural:
            s, pl = t.split('\x00')
            e.msgstr_plural = {0: s, 1: pl}
        else:
            assert '\x00' not in t, (e.msgid, t)
            e.msgstr = t
        # Placeholders must survive translation.
        import re
        ph = lambda x: sorted(re.findall(r'%(?:\d\$)?[sd]', x))
        if ph(e.msgid) != ph(t.split('\x00')[0]):
            print('placeholder mismatch:', e.msgid, '=>', t); ok = False
    p.metadata.update({'Language': 'en_US', 'Plural-Forms': 'nplurals=2; plural=(n != 1);', 'Content-Type': 'text/plain; charset=UTF-8'})
    p.save(out)
    print('wrote', out, len(p))
sys.exit(0 if ok else 1)
