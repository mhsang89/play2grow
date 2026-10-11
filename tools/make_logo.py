#!/usr/bin/env python3
"""Generate the Play2Grow logo SVGs (text converted to outlines from Be Vietnam Pro Bold, OFL).

Usage: python3 tools/make_logo.py <BeVietnamPro-Bold.ttf> <out_dir>
"""
import sys
from fontTools.ttLib import TTFont
from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen

INK, TEAL, TEAL_DARK, SUN, CORAL, MINT, WHITE = '#17282B', '#0E6B5C', '#0A4F44', '#FFB938', '#E2553A', '#E3F4EC', '#FFFFFF'


def text_paths(font, parts, size, x0, baseline, tracking=-0.01):
    """parts: list of (text, fill). Returns (svg_elements, end_x)."""
    gs = font.getGlyphSet()
    cmap = font.getBestCmap()
    upm = font['head'].unitsPerEm
    scale = size / upm
    x = x0
    out = []
    for text, fill in parts:
        d_all = []
        for ch in text:
            gname = cmap[ord(ch)]
            pen = SVGPathPen(gs)
            tpen = TransformPen(pen, (scale, 0, 0, -scale, x, baseline))
            gs[gname].draw(tpen)
            d_all.append(pen.getCommands())
            x += gs[gname].width * scale + tracking * size
        out.append('<path fill="%s" d="%s"/>' % (fill, ' '.join(d_all)))
    return out, x


def mark(tile, stem, leaf_l, leaf_r, ground):
    """Speech-bubble tile with a sprout: talk (English) + grow + play (sun-yellow leaf)."""
    return (
        '<path fill="%s" d="M32 4h56c15.5 0 28 12.5 28 28v40c0 15.5-12.5 28-28 28H50L24 118l4-18C14 97 4 86 4 72V32C4 16.5 16.5 4 32 4z"/>' % tile
        + '<path fill="none" stroke="%s" stroke-width="7" stroke-linecap="round" d="M60 76c0-9-1-17 0-26"/>' % stem
        + '<path fill="%s" d="M60 60c-14 0-25-9-27-23 14-1 25 8 27 23z"/>' % leaf_l
        + '<path fill="%s" d="M60 52c3-17 17-27 34-25-2 17-17 27-34 25z"/>' % leaf_r
        + '<path fill="none" stroke="%s" stroke-width="7" stroke-linecap="round" d="M38 76c5 9 13 13 22 13s17-4 22-13"/>' % ground
    )


def build(font_path, out):
    font = TTFont(font_path)
    size, baseline, gap = 74, 82, 22
    variants = {
        'logo.svg': (mark(TEAL, WHITE, MINT, SUN, WHITE), [('Play', INK), ('2', CORAL), ('Grow', TEAL)]),
        'logo-white.svg': (mark(WHITE, TEAL, '#7CC4B0', SUN, TEAL), [('Play', WHITE), ('2', SUN), ('Grow', WHITE)]),
    }
    for name, (m, parts) in variants.items():
        paths, end_x = text_paths(font, parts, size, 120 + gap, baseline)
        w = int(end_x + 6)
        svg = ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d 122" width="%d" height="122" role="img" aria-labelledby="t">'
               '<title id="t">Play2Grow</title>%s%s</svg>\n') % (w, w, m, ''.join(paths))
        open('%s/%s' % (out, name), 'w').write(svg)
    open('%s/logo-mark.svg' % out, 'w').write(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 122" width="120" height="122" role="img" aria-labelledby="t"><title id="t">Play2Grow</title>%s</svg>\n' % mark(TEAL, WHITE, MINT, SUN, WHITE))
    # Square app/site icon (padding, cream background).
    open('%s/site-icon.svg' % out, 'w').write(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" width="512" height="512"><rect width="160" height="160" rx="36" fill="#FFF8EC"/><g transform="translate(20 19)">%s</g></svg>\n' % mark(TEAL, WHITE, MINT, SUN, WHITE))


if __name__ == '__main__':
    build(sys.argv[1], sys.argv[2])
