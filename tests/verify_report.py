from playwright.sync_api import sync_playwright
import os

def run_cuj(page):
    # Navigate to the test report
    page.goto("http://localhost:8080/test_report.html")
    page.wait_for_timeout(1000)

    # Screenshot Dashboard
    page.screenshot(path="/home/jules/verification/screenshots/dashboard.png")
    page.wait_for_timeout(500)

    # Navigate to CLI Commands via Sidebar
    page.locator(".sidebar").get_by_text("CLI Commands").click()
    page.wait_for_timeout(1000)
    page.screenshot(path="/home/jules/verification/screenshots/cli_commands.png")

    # Filter for success
    page.click("button:has-text('Success')")
    page.wait_for_timeout(500)

    # Navigate to Core Classes via Sidebar
    page.locator(".sidebar").get_by_text("Core Classes").click()
    page.wait_for_timeout(1000)
    page.screenshot(path="/home/jules/verification/screenshots/core_classes.png")

    # Navigate to Web Routes via Sidebar
    page.locator(".sidebar").get_by_text("Web Routes").click()
    page.wait_for_timeout(1000)
    page.screenshot(path="/home/jules/verification/screenshots/web_routes.png")

    # Navigate to Fixed Issues via Sidebar
    page.locator(".sidebar").get_by_text("Fixed Issues").click()
    page.wait_for_timeout(1000)
    page.screenshot(path="/home/jules/verification/screenshots/fixed_issues.png")

    # Final Screenshot
    page.screenshot(path="/home/jules/verification/screenshots/verification.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
