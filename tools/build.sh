#!/usr/bin/env bash
# Build installable ZIPs into dist/:
#   dist/play2grow-theme.zip  -> play2grow-theme/style.css at the first level
#   dist/play2grow-core.zip   -> play2grow-core/play2grow-core.php at the first level
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

python3 tools/md2blocks.py content-src play2grow-theme/starter-content

mkdir -p dist
rm -f dist/play2grow-theme.zip dist/play2grow-core.zip

EXCLUDES=( -x "*.DS_Store" -x "*/.git*" -x "*/node_modules/*" -x "*.log" -x "*~" -x "*.bak" -x "*.orig" )
zip -rq -X dist/play2grow-theme.zip play2grow-theme "${EXCLUDES[@]}"
zip -rq -X dist/play2grow-core.zip play2grow-core "${EXCLUDES[@]}"

for z in dist/*.zip; do
	echo "$z: $(unzip -Z1 "$z" | wc -l) files, $(du -h "$z" | cut -f1)"
done
( cd dist && sha256sum play2grow-theme.zip play2grow-core.zip > SHA256SUMS.txt )
