import re, urllib.request, urllib.error, sys
base='http://localhost:8080'
seen=set(); queue=['/']; bad=[]; pages=0
while queue:
    u=queue.pop()
    if u in seen: continue
    seen.add(u)
    try:
        r=urllib.request.urlopen(base+u, timeout=20); code=r.status; body=r.read().decode('utf-8','ignore')
    except urllib.error.HTTPError as e:
        code=e.code; body=''
    if code!=200: bad.append((u,code)); continue
    pages+=1
    for h in re.findall(r'href="(http://localhost:8080[^"#]*)"', body):
        p=h.replace(base,'') or '/'
        if any(x in p for x in ['/wp-admin','/wp-login','/feed','xmlrpc','/wp-json','?s=','/wp-content/','/wp-includes/','/comments/']): continue
        if p not in seen: queue.append(p)
    for s in re.findall(r'(?:src|href)="(http://localhost:8080/wp-content/[^"?]*)', body):
        if s not in seen:
            seen.add(s)
            try: urllib.request.urlopen(s, timeout=20)
            except urllib.error.HTTPError as e: bad.append((s,e.code))
print('crawled pages:', pages, 'urls checked:', len(seen)); print('broken:', bad or 'none')
