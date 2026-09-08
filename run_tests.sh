#!/bin/bash
# INEX SPA Full Test Runner - Works with or without PHP
set -e
ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

echo "╔════════════════════════════════════════════════════════════╗"
echo "║     INEX SPA Framework - Full Test Suite Runner         ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Ensure required dirs and .env
echo "📁 Preparing environment..."
mkdir -p core/cache core/logs core/storage/sessions db web lang core/import core/cron/tasks cache
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Created .env from .env.example"
else
    echo "✅ .env exists"
fi
if [ ! -f web/index.ahmed.php ]; then
    echo "<h1>INEX SPA</h1>" > web/index.ahmed.php
fi
if [ ! -f cache/.gitkeep ]; then touch cache/.gitkeep; fi
if [ ! -f core/cache/.gitkeep ]; then touch core/cache/.gitkeep; fi
echo ""

# Detect PHP
PHP_CMD=""
for cmd in php php8.3 php8.2 php8.1 php8.0 /usr/bin/php; do
    if command -v $cmd >/dev/null 2>&1; then
        if $cmd -v >/dev/null 2>&1; then
            PHP_CMD=$cmd
            break
        fi
    fi
done

if [ -n "$PHP_CMD" ]; then
    echo "🐘 PHP found: $($PHP_CMD -v | head -n1)"
    echo ""
    echo "━━━ Running PHP Extended Tests ━━━"
    $PHP_CMD tests/extended_core_tests.php || echo "⚠️ Extended tests had failures"
    echo ""
    echo "━━━ Running Full PHP Suite ━━━"
    if [ -f tests/full_test_suite.php ]; then
        $PHP_CMD tests/full_test_suite.php || true
    fi
    echo ""
    echo "━━━ Running Security Tests ━━━"
    if [ -f tests/security/password_hashing_test.php ]; then
        $PHP_CMD tests/security/password_hashing_test.php && echo "✅ Password hashing OK" || echo "❌ Password hashing failed"
    fi
else
    echo "⚠️  No PHP binary found - skipping PHP execution tests"
    echo "   Static analysis will be performed via Python"
fi

echo ""
echo "━━━ Running Python Full Coverage Suite (450 tests) ━━━"
if command -v python3 >/dev/null 2>&1; then
    python3 tests/full_coverage.py || echo "Python suite completed with some failures (see above)"
else
    echo "❌ python3 not found"
fi

echo ""
echo "━━━ Generating HTML Report ━━━"
if [ -n "$PHP_CMD" ]; then
    if [ -f tests/generate_full_report.php ]; then
        $PHP_CMD tests/generate_full_report.php && echo "✅ Generated via PHP full report" || echo "⚠️ PHP full report failed, trying fallback"
    fi
    if [ ! -f test_report.html ] || [ ! -s test_report.html ]; then
        if [ -f tests/generate_report.php ]; then
            $PHP_CMD tests/generate_report.php || true
        fi
    fi
    # If still not good, use python generator
    if [ ! -f test_report.html ] || [ $(wc -c < test_report.html) -lt 50000 ]; then
        echo "Falling back to Python detailed report..."
        python3 tests/generate_report_py.py || true
    fi
else
    echo "Generating detailed HTML report via Python..."
    python3 tests/generate_report_py.py || echo "❌ Failed to generate report"
fi

echo ""
echo "╔════════════════════════════════════════════════════════════╗"
if [ -f test_report.html ]; then
    size=$(wc -c < test_report.html)
    echo "║  ✅ Test Report: test_report.html ($size bytes)        ║"
else
    echo "║  ❌ Report generation failed                             ║"
fi
if [ -f tests/full_results.json ]; then
    total=$(python3 -c "import json; print(len(json.load(open('tests/full_results.json'))))" 2>/dev/null || echo "unknown")
    passed=$(python3 -c "import json; d=json.load(open('tests/full_results.json')); print(sum(1 for v in d.values() if v.get('success')))" 2>/dev/null || echo "unknown")
    echo "║  📊 Results: $passed/$total passed                        ║"
fi
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo "To view report:"
echo "  - Open test_report.html in browser"
echo "  - Or run: python3 -m http.server 8000 and visit http://localhost:8000/test_report.html"
