import sys
from PIL import Image
src, h = sys.argv[1], int(sys.argv[2]) if len(sys.argv) > 2 else 1500
im = Image.open(src)
w, H = im.size
n = 0
for y in range(0, H, h):
    im.crop((0, y, w, min(H, y + h))).save(src[:-4] + f'_s{n}.png'); n += 1
print(n)
