#!/usr/bin/env python3
import json, pathlib, datetime
ROOT = pathlib.Path(__file__).parent.parent
full_path = ROOT / "tests/full_results.json"
cli_path = ROOT / "tests/cli_results.json"
core_path = ROOT / "tests/core_results.json"
web_path = ROOT / "tests/web_results.json"
fixed_path = ROOT / "tests/fixed_issues.json"

def load(p):
    return json.loads(p.read_text()) if p.exists() else {}

fullRes = load(full_path)
cliRes = load(cli_path)
coreRes = load(core_path)
webRes = load(web_path)
fixedRes = load(fixed_path)

if not fullRes:
    fullRes = {**cliRes, **coreRes, **webRes}

categories = {}
for name, res in fullRes.items():
    cat = res.get("category", "general")
    categories.setdefault(cat, {})[name] = res

total = len(fullRes)
passed = sum(1 for r in fullRes.values() if r.get("success"))
failed = total - passed
fixed = len(fixedRes) if isinstance(fixedRes, list) else len(fixedRes)
rate = round(passed/total*100,1) if total else 0

catStats = {}
for cat, tests in categories.items():
    cTotal = len(tests)
    cPassed = sum(1 for r in tests.values() if r.get("success"))
    catStats[cat] = {"total": cTotal, "passed": cPassed, "failed": cTotal-cPassed, "rate": round(cPassed/cTotal*100,1) if cTotal else 0}

# Build HTML with same template as PHP version but in python
# Use f-string with escaping
import html

def esc(s): return html.escape(str(s))

# Sidebar items
sidebar_items = ""
sidebar_items += f'<div class="nav-item active" onclick="showSection(event, \'dashboard-section\')">📊 Dashboard <span class="badge">{rate}%</span></div>\n'
for cat, tests in sorted(categories.items()):
    cPassed = catStats[cat]["passed"]
    cTotal = catStats[cat]["total"]
    sidebar_items += f'<div class="nav-item" onclick="showSection(event, \'{cat}-section\')">{cat.capitalize()} <span class="badge">{cPassed}/{cTotal}</span></div>\n'
sidebar_items += f'<div class="nav-item" onclick="showSection(event, \'fixed-section\')">🔧 Fixed Issues <span class="badge">{fixed}</span></div>\n'

health = "✅ All systems operational<br>Production ready" if rate==100 else ("⚠️ Minor issues<br>Review failures" if rate>=95 else "❌ Needs attention")

# Category grid
cat_grid = ""
for cat, stat in catStats.items():
    color = "var(--success)" if stat["failed"]==0 else "var(--danger)"
    bg = "#e6f9ed" if stat["failed"]==0 else "#fdeaea"
    tcolor = "var(--success)" if stat["failed"]==0 else "var(--danger)"
    bar_color = "var(--success)" if stat["failed"]==0 else "var(--warning)"
    cat_grid += f"""
                <div class="cat-card">
                    <h3>{cat.capitalize()} <span style="color:{color}">{stat['rate']}%</span></h3>
                    <div class="stats">
                        <span style="background:{bg}; color:{tcolor}">{stat['passed']}/{stat['total']}</span>
                        <span style="background:#f0f0f0; color:var(--light)">{stat['failed']} failed</span>
                    </div>
                    <div class="progress" style="margin-top:10px;"><div class="progress-bar" style="width:{stat['rate']}%; background:{bar_color}"></div></div>
                </div>
"""

# Failed table
failed_rows = ""
for name, res in fullRes.items():
    if not res.get("success"):
        failed_rows += f'<tr><td>{esc(name)}</td><td>{esc(res.get("category","general"))}</td><td><span class="status status-error">FAILED</span></td><td>{esc(res.get("message",""))}</td></tr>\n'
failed_section = ""
if failed>0:
    failed_section = f"""
            <div class="section" style="border:2px solid var(--danger);">
                <h2 style="color:var(--danger);">⚠️ Failed Tests ({failed})</h2>
                <table>
                    <thead><tr><th>Test</th><th>Category</th><th>Status</th><th>Message</th></tr></thead>
                    <tbody>
                    {failed_rows}
                    </tbody>
                </table>
            </div>
"""

# Category sections
cat_sections = ""
for cat, tests in sorted(categories.items()):
    stat = catStats[cat]
    rows = ""
    for name, res in tests.items():
        status_class = "status-success" if res.get("success") else "status-error"
        status_text = "SUCCESS" if res.get("success") else "FAILED"
        data_status = "success" if res.get("success") else "failed"
        rows += f"""                <tr class="test-row" data-status="{data_status}">
                    <td style="font-weight:600; font-size:13px;">{esc(name)}</td>
                    <td><span class="status {status_class}">{status_text}</span></td>
                    <td><pre>{esc(str(res.get("message",""))[:800])}</pre></td>
                </tr>
"""
    cat_sections += f"""
        <div id="{cat}-section" class="report-section section" style="display:none;">
            <h2>{cat.capitalize()} Tests <span class="count">{stat['passed']}/{stat['total']} passed</span></h2>
            <div class="filters">
                <button class="filter-btn active" onclick="filterTable('{cat}', 'all', this)">All ({stat['total']})</button>
                <button class="filter-btn" onclick="filterTable('{cat}', 'success', this)">Passed ({stat['passed']})</button>
                <button class="filter-btn" onclick="filterTable('{cat}', 'failed', this)">Failed ({stat['failed']})</button>
            </div>
            <table id="{cat}-table">
                <thead><tr><th>Test Name</th><th>Status</th><th>Message / Output</th></tr></thead>
                <tbody>
{rows}
                </tbody>
            </table>
        </div>
"""

# Fixed issues
fixed_items = ""
if isinstance(fixedRes, list):
    for issue in fixedRes:
        fixed_items += f"""
                <div class="fixed-item">
                    <h3>{esc(issue.get('title',''))} <span class="status status-fixed">FIXED</span></h3>
                    <p>{esc(issue.get('description',''))}</p>
                    <small style="color:var(--light);">ID: {esc(issue.get('id',''))} • Status: {esc(issue.get('status',''))}</small>
                </div>
"""
else:
    for k, v in fixedRes.items():
        fixed_items += f"<div class='fixed-item'><h3>{esc(k)}</h3><p>{esc(str(v))}</p></div>"

if not fixed_items:
    fixed_items = '<p style="color:var(--light);">No fixed issues tracked yet.</p>'

timestamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
html_content = f"""<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEX SPA - Full Test Report ({total} tests)</title>
    <style>
        :root {{ --primary:#3498db; --success:#2ecc71; --danger:#e74c3c; --warning:#f39c12; --bg:#f4f7f6; --card:#fff; --text:#333; --light:#7f8c8d; --dark:#2c3e50; }}
        * {{ box-sizing:border-box; }}
        body {{ font-family:'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background:var(--bg); margin:0; padding:0; color:var(--text); display:flex; min-height:100vh; }}
        .sidebar {{ width:280px; background:var(--dark); color:white; padding:30px 20px; position:fixed; height:100vh; overflow-y:auto; }}
        .sidebar h1 {{ font-size:20px; margin-bottom:10px; text-align:center; }}
        .sidebar .subtitle {{ text-align:center; font-size:12px; opacity:0.7; margin-bottom:30px; }}
        .nav-item {{ padding:12px 15px; margin-bottom:5px; border-radius:6px; cursor:pointer; transition:background 0.3s; display:flex; justify-content:space-between; align-items:center; }}
        .nav-item:hover {{ background:rgba(255,255,255,0.1); }}
        .nav-item.active {{ background:var(--primary); }}
        .nav-item .badge {{ background:rgba(255,255,255,0.2); padding:2px 8px; border-radius:12px; font-size:11px; }}
        .main {{ margin-left:280px; flex:1; padding:40px; max-width:1400px; }}
        .header {{ display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; flex-wrap:wrap; gap:20px; }}
        .header h2 {{ margin:0; font-size:24px; }}
        .timestamp {{ color:var(--light); font-size:14px; }}
        .dashboard {{ display:grid; grid-template-columns:repeat(4, 1fr); gap:20px; margin-bottom:30px; }}
        .card {{ background:var(--card); padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); text-align:center; position:relative; overflow:hidden; }}
        .card::before {{ content:''; position:absolute; top:0; left:0; right:0; height:4px; }}
        .card.total::before {{ background:var(--primary); }}
        .card.passed::before {{ background:var(--success); }}
        .card.failed::before {{ background:var(--danger); }}
        .card.fixed::before {{ background:var(--warning); }}
        .card .number {{ font-size:36px; font-weight:800; display:block; margin-bottom:8px; }}
        .card .label {{ color:var(--light); text-transform:uppercase; font-size:12px; letter-spacing:1px; font-weight:600; }}
        .card .sub {{ font-size:12px; color:var(--light); margin-top:5px; }}
        .progress {{ height:8px; background:#eee; border-radius:4px; overflow:hidden; margin-top:15px; }}
        .progress-bar {{ height:100%; background:var(--success); transition:width 0.5s; }}
        .category-grid {{ display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:15px; margin-bottom:30px; }}
        .cat-card {{ background:var(--card); padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); }}
        .cat-card h3 {{ margin:0 0 10px 0; font-size:16px; text-transform:capitalize; display:flex; justify-content:space-between; }}
        .cat-card .stats {{ display:flex; gap:10px; font-size:13px; }}
        .cat-card .stats span {{ padding:4px 10px; border-radius:20px; font-weight:600; font-size:11px; }}
        .section {{ background:var(--card); border-radius:12px; padding:25px; box-shadow:0 4px 12px rgba(0,0,0,0.05); margin-bottom:30px; }}
        .section h2 {{ margin-top:0; border-bottom:2px solid #f0f0f0; padding-bottom:15px; margin-bottom:20px; font-size:18px; color:var(--primary); display:flex; justify-content:space-between; align-items:center; }}
        .section h2 .count {{ font-size:13px; background:var(--primary); color:white; padding:4px 12px; border-radius:20px; }}
        table {{ width:100%; border-collapse:collapse; }}
        th, td {{ text-align:left; padding:12px; border-bottom:1px solid #eee; }}
        th {{ background:#f9f9f9; font-size:12px; color:var(--light); text-transform:uppercase; letter-spacing:0.5px; }}
        .status {{ font-weight:700; padding:5px 12px; border-radius:20px; font-size:11px; text-transform:uppercase; letter-spacing:0.5px; }}
        .status-success {{ background:#e6f9ed; color:var(--success); border:1px solid #c8f5d8; }}
        .status-error {{ background:#fdeaea; color:var(--danger); border:1px solid #f5c6cb; }}
        .status-fixed {{ background:#fff4e5; color:var(--warning); border:1px solid #ffe0b3; }}
        pre {{ background:#272822; color:#f8f8f2; padding:12px; border-radius:6px; font-size:11px; max-height:120px; overflow:auto; margin:0; }}
        .filters {{ margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; }}
        .filter-btn {{ padding:8px 16px; border:1px solid #ddd; background:white; border-radius:20px; font-size:12px; cursor:pointer; font-weight:600; transition:all 0.2s; }}
        .filter-btn.active {{ background:var(--primary); color:white; border-color:var(--primary); }}
        .fixed-list {{ list-style:none; padding:0; }}
        .fixed-item {{ padding:20px; border-left:4px solid var(--warning); background:#fffcf8; margin-bottom:15px; border-radius:0 8px 8px 0; }}
        .fixed-item h3 {{ margin:0 0 8px 0; font-size:16px; }}
        .fixed-item p {{ margin:0; font-size:14px; color:var(--light); line-height:1.5; }}
        .hidden {{ display:none; }}
        @media (max-width:768px) {{ .dashboard {{ grid-template-columns:repeat(2,1fr); }} .sidebar {{ display:none; }} .main {{ margin-left:0; }} }}
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>🚀 INEX SPA</h1>
        <div class="subtitle">Full Test Suite v2.0<br>{total} Tests • {rate}% Pass</div>
        {sidebar_items}
        <div style="margin-top:30px; padding:15px; background:rgba(255,255,255,0.05); border-radius:8px; font-size:12px; line-height:1.6;">
            <strong>Framework Health</strong><br>
            {"✅ All systems operational<br>Production ready" if rate==100 else ("⚠️ Minor issues<br>Review failures" if rate>=95 else "❌ Needs attention")}
        </div>
    </div>

    <div class="main">
        <div id="dashboard-section" class="report-section">
            <div class="header">
                <h2>Framework Health Dashboard</h2>
                <span class="timestamp">Report Generated: {timestamp} • Python Full Coverage</span>
            </div>

            <div class="dashboard">
                <div class="card total">
                    <span class="number" style="color:var(--primary);">{total}</span>
                    <span class="label">Total Tests</span>
                    <span class="sub">Across {len(categories)} categories</span>
                </div>
                <div class="card passed">
                    <span class="number" style="color:var(--success);">{passed}</span>
                    <span class="label">Passed</span>
                    <span class="sub">{rate}% success rate</span>
                    <div class="progress"><div class="progress-bar" style="width:{rate}%"></div></div>
                </div>
                <div class="card failed">
                    <span class="number" style="color:var(--danger);">{failed}</span>
                    <span class="label">Failed</span>
                    <span class="sub">{"No failures 🎉" if failed==0 else "Needs attention"}</span>
                </div>
                <div class="card fixed">
                    <span class="number" style="color:var(--warning);">{fixed}</span>
                    <span class="label">Fixed Issues</span>
                    <span class="sub">Recently resolved</span>
                </div>
            </div>

            <div class="category-grid">
                {cat_grid}
            </div>

            {failed_section}
        </div>

        {cat_sections}

        <div id="fixed-section" class="report-section section" style="display:none;">
            <h2>🔧 Fixed Issues <span class="count">{fixed} fixed</span></h2>
            <div class="fixed-list">
                {fixed_items}
            </div>
        </div>
    </div>

    <script>
        function showSection(event, id) {{
            document.querySelectorAll('.report-section').forEach(s => s.style.display='none');
            document.getElementById(id).style.display='block';
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            event.target.closest('.nav-item').classList.add('active');
            if(id==='dashboard-section') document.getElementById(id).style.display='block';
        }}
        document.getElementById('dashboard-section').style.display='block';
        function filterTable(tableId, status, btn) {{
            const table = document.getElementById(tableId + '-table');
            if(!table) return;
            const rows = table.querySelectorAll('.test-row');
            const section = document.getElementById(tableId+'-section');
            section.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            rows.forEach(row => {{
                if(status==='all' || row.dataset.status===status) row.style.display='';
                else row.style.display='none';
            }});
        }}
    </script>
</body>
</html>
"""
out = ROOT / "test_report.html"
out.write_text(html_content)
print(f"✅ Report generated: test_report.html ({len(html_content)} bytes)")
print(f"Total: {total} | Passed: {passed} | Failed: {failed} | Fixed: {fixed}")
