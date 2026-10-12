import re,html,sys,urllib.request
VI=re.compile(r'[ăâđêôơưạảấầẩẫậắằẳẵặẹẻẽếềểễệỉịọỏốồổỗộớờởỡợụủứừửữựỳỵỷỹ]',re.I)
seen={}
for u in open(sys.argv[1]).read().split():
    try: t=urllib.request.urlopen(u).read().decode()
    except Exception as e: print('ERR',u,e); continue
    t=re.sub(r'<script.*?</script>|<style.*?</style>','',t,flags=re.S)
    for m in re.findall(r'(?:alt|title|aria-label|placeholder|content)="([^"]*)"',t): 
        if VI.search(html.unescape(m)): seen.setdefault('ATTR: '+html.unescape(m)[:90],[]).append(u)
    for s in re.split(r'<[^>]+>',t):
        s=html.unescape(s).strip()
        if s and VI.search(s): seen.setdefault(s[:110],[]).append(u)
for k,v in sorted(seen.items(), key=lambda x:-len(x[1])): print(len(v), k, '' if len(v)>3 else v)
