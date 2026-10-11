#!/usr/bin/env python3
"""Convert the lightweight Markdown in content-src/ into WordPress block markup.

Usage: python3 tools/md2blocks.py content-src play2grow-theme/starter-content

Supported syntax (one construct per blank-line separated chunk):
  ## / ### / ####         headings (H1 is reserved for the page title)
  paragraph               plain text, inline **bold**, *italic*, [text](url)
  - item / 1. item        lists
  > text                  quote
  | a | b |               table (second line must be |---|---|)
  [[shortcode ...]]       shortcode block
  <!-- p2g:pattern x -->  passed through (rendered by the importer)
  :::note … :::           editor-only note (shortcode [p2g_note])
  :::callout [sun|mint] … :::   highlighted box (nested markdown)
  :::details Question … :::     collapsible FAQ item (nested markdown)
  :::activity Label | Title … ::: example activity card (nested markdown)
  :::checklist / :::phrases / :::tagline … :::   styled lists
  :::section [cream|mint|sky] … :::  full-width band (nested markdown)
  :::columns … ::: with "---col---" separators
"""
import html
import json
import os
import re
import sys


def inline(text):
    text = html.escape(text, quote=False)
    text = re.sub(r"\[([^\]]+)\]\(([^)\s]+)\)", lambda m: '<a href="%s">%s</a>' % (m.group(2), m.group(1)), text)
    text = re.sub(r"\*\*(.+?)\*\*", r"<strong>\1</strong>", text)
    text = re.sub(r"(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])", r"<em>\1</em>", text)
    return text


def attrs(d):
    return (" " + json.dumps(d, ensure_ascii=False, separators=(",", ":"))) if d else ""


def paragraph(text, cls=None):
    a = {"className": cls} if cls else {}
    c = ' class="%s"' % cls if cls else ""
    return "<!-- wp:paragraph%s -->\n<p%s>%s</p>\n<!-- /wp:paragraph -->" % (attrs(a), c, inline(text))


def heading(level, text):
    a = {} if level == 2 else {"level": level}
    return '<!-- wp:heading%s -->\n<h%d class="wp-block-heading">%s</h%d>\n<!-- /wp:heading -->' % (attrs(a), level, inline(text), level)


def listblock(items, ordered=False, cls=None):
    a = {}
    if ordered:
        a["ordered"] = True
    if cls:
        a["className"] = cls
    tag = "ol" if ordered else "ul"
    klass = "wp-block-list" + (" " + cls if cls else "")
    lis = "\n\n".join("<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->" % inline(i) for i in items)
    return '<!-- wp:list%s -->\n<%s class="%s">%s</%s>\n<!-- /wp:list -->' % (attrs(a), tag, klass, lis, tag)


def table(rows):
    def cells(row):
        return [c.strip() for c in row.strip().strip("|").split("|")]
    head = cells(rows[0])
    body = [cells(r) for r in rows[2:]]
    thead = "<thead><tr>" + "".join("<th>%s</th>" % inline(c) for c in head) + "</tr></thead>"
    tbody = "<tbody>" + "".join("<tr>" + "".join("<td>%s</td>" % inline(c) for c in r) + "</tr>" for r in body) + "</tbody>"
    return '<!-- wp:table -->\n<figure class="wp-block-table"><table class="has-fixed-layout">%s%s</table></figure>\n<!-- /wp:table -->' % (thead, tbody)


def group(inner, cls):
    a = {"className": cls, "layout": {"type": "default"}}
    return '<!-- wp:group%s -->\n<div class="wp-block-group %s">%s</div>\n<!-- /wp:group -->' % (attrs(a), cls, inner)


def section(inner, variant):
    cls = "p2g-section p2g-section--narrow" + (" p2g-section--" + variant if variant else "")
    a = {"align": "full", "className": cls, "layout": {"type": "default"}}
    return '<!-- wp:group%s -->\n<div class="wp-block-group alignfull %s">%s</div>\n<!-- /wp:group -->' % (attrs(a), cls, inner)


def columns(parts):
    cols = "\n\n".join('<!-- wp:column -->\n<div class="wp-block-column">%s</div>\n<!-- /wp:column -->' % convert(p) for p in parts)
    return '<!-- wp:columns -->\n<div class="wp-block-columns">%s</div>\n<!-- /wp:columns -->' % cols


def details(summary, inner):
    return '<!-- wp:details -->\n<details class="wp-block-details"><summary>%s</summary>%s</details>\n<!-- /wp:details -->' % (inline(summary), convert(inner))


def split_chunks(lines):
    """Yield ('block', name, arg, inner_lines) or ('chunk', lines)."""
    i = 0
    buf = []
    while i < len(lines):
        line = lines[i]
        m = re.match(r"^:::(\w+)\s*(.*)$", line.strip())
        if m:
            if buf:
                yield ("chunk", buf)
                buf = []
            name, arg = m.group(1), m.group(2).strip()
            depth = 1
            inner = []
            i += 1
            while i < len(lines):
                s = lines[i].strip()
                if re.match(r"^:::\w+", s):
                    depth += 1
                elif s == ":::":
                    depth -= 1
                    if depth == 0:
                        break
                inner.append(lines[i])
                i += 1
            yield ("block", name, arg, inner)
            i += 1
            continue
        if line.strip() == "":
            if buf:
                yield ("chunk", buf)
                buf = []
        else:
            buf.append(line)
        i += 1
    if buf:
        yield ("chunk", buf)


def chunk_to_block(lines):
    first = lines[0].strip()
    if first.startswith("<!--"):
        return "\n".join(lines)
    m = re.match(r"^(#{2,4})\s+(.*)$", first)
    if m and len(lines) == 1:
        return heading(len(m.group(1)), m.group(2))
    if first.startswith("[[") and first.endswith("]]"):
        return "<!-- wp:shortcode -->\n[%s]\n<!-- /wp:shortcode -->" % first[2:-2]
    if all(re.match(r"^\s*[-*]\s+", l) for l in lines):
        return listblock([re.sub(r"^\s*[-*]\s+", "", l) for l in lines])
    if all(re.match(r"^\s*\d+\.\s+", l) for l in lines):
        return listblock([re.sub(r"^\s*\d+\.\s+", "", l) for l in lines], ordered=True)
    if first.startswith("|"):
        return table(lines)
    if first.startswith(">"):
        text = " ".join(l.lstrip("> ").strip() for l in lines)
        return '<!-- wp:quote -->\n<blockquote class="wp-block-quote">%s</blockquote>\n<!-- /wp:quote -->' % paragraph(text)
    return paragraph(" ".join(l.strip() for l in lines))


def convert(lines):
    if isinstance(lines, str):
        lines = lines.split("\n")
    out = []
    for item in split_chunks(lines):
        if item[0] == "chunk":
            out.append(chunk_to_block(item[1]))
            continue
        _, name, arg, inner = item
        text = "\n".join(inner).strip()
        if name == "note":
            out.append("<!-- wp:shortcode -->\n[p2g_note]%s[/p2g_note]\n<!-- /wp:shortcode -->" % inline(" ".join(text.split())))
        elif name == "callout":
            out.append(group(convert(inner), "p2g-callout" + (" p2g-callout--" + arg if arg else "")))
        elif name == "details":
            out.append(details(arg, inner))
        elif name == "activity":
            label, _, title = arg.partition("|")
            body = paragraph(label.strip(), "p2g-label") + "\n\n" + heading(3, title.strip()) + "\n\n" + convert(inner)
            out.append(group(body, "p2g-activity"))
        elif name in ("checklist", "phrases", "tagline"):
            cls = {"checklist": "p2g-checklist", "phrases": "p2g-phrases", "tagline": "p2g-tagline-strip"}[name]
            items = [re.sub(r"^\s*[-*]\s+", "", l) for l in inner if l.strip()]
            out.append(listblock(items, cls=cls))
        elif name == "section":
            out.append(section(convert(inner), arg))
        elif name == "columns":
            parts, cur = [], []
            for l in inner:
                if l.strip() == "---col---":
                    parts.append(cur)
                    cur = []
                else:
                    cur.append(l)
            parts.append(cur)
            out.append(columns(parts))
        else:
            raise SystemExit("Unknown block :::%s" % name)
    return "\n\n".join(out)


def main(src, dst):
    count = 0
    for root, _, files in os.walk(src):
        for f in sorted(files):
            if not f.endswith(".md"):
                continue
            rel = os.path.relpath(os.path.join(root, f), src)
            with open(os.path.join(root, f), encoding="utf-8") as fh:
                text = fh.read()
            # Strip the front-matter comment block (metadata for humans, mirrored in manifest.php).
            text = re.sub(r"\A<!--meta.*?-->\s*", "", text, flags=re.S)
            out = convert(text.split("\n")) + "\n"
            target = os.path.join(dst, rel[:-3] + ".html")
            os.makedirs(os.path.dirname(target), exist_ok=True)
            with open(target, "w", encoding="utf-8") as fh:
                fh.write(out)
            count += 1
    print("converted %d files" % count)


if __name__ == "__main__":
    main(sys.argv[1], sys.argv[2])
