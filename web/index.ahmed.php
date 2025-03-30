@section("content")
<div id="contentBox" class="container">
    <div class="glowing-text">
        <h1>INEX SPA</h1>
    </div>
    <p class="subtitle">
        @getLang("subtitle")
    </p>
    <p class="version">@getLang("version") @getEnv("VERSION")</p>
    <p class="edit-prompt">@getLang("edit-prompt")</p>
    <div class="cta-button">
        <form>
        <a type="button" onclick="submitData('setLanguage', [], 'POST', '', [{'lang':'en'}])">English</a>
        <a type="button" onclick="submitData('setLanguage', [], 'POST', '', [{'lang':'ar'}])">العربية</a>
        </form>
        <hr>
        <a href="https://github.com/AmmarBasha2011/INEX-SPA">@getLang("get-started")</a>
    </div>
</div>

<div id="toggleIcon">
    <i class="fa-solid fa-chevron-down"></i>
</div>

<div id="appDetailsTable" style="display: none;">
    <table border="1" style="margin: 0 auto; color: #fff; background-color: rgba(0,0,0,0.5);">
        <tr>
            <th>@getLang("field")</th>
            <th>@getLang("value")</th>
        </tr>
        <tr>
            <td>@getLang("app-name")</td>
            <td>@getEnv("APP_NAME")</td>
        </tr>
        <tr>
            <td>@getLang("use-app-name")</td>
            <td>@getEnv("USE_APP_NAME_IN_TITLE")</td>
        </tr>
        <tr>
            <td>@getLang("web-url")</td>
            <td>@getEnv("WEBSITE_URL")</td>
        </tr>
        <tr>
            <td>@getLang("version")</td>
            <td>@getEnv("VERSION")</td>
        </tr>
        <tr>
            <td>@getLang("dev-mode")</td>
            <td>@getEnv("DEV_MODE")</td>
        </tr>
        <tr>
            <td>@getLang("db-host")</td>
            <td>@getEnv("DB_HOST")</td>
        </tr>
        <tr>
            <td>@getLang("db-user")</td>
            <td>@getEnv("DB_USER")</td>
        </tr>
        <tr>
            <td>@getLang("db-pass")</td>
            <td>@getEnv("DB_PASS")</td>
        </tr>
        <tr>
            <td>@getLang("db-name")</td>
            <td>@getEnv("DB_NAME")</td>
        </tr>
        <tr>
            <td>@getLang("db-use")</td>
            <td>@getEnv("DB_USE")</td>
        </tr>
        <tr>
            <td>@getLang("db-check")</td>
            <td>@getEnv("DB_CHECK")</td>
        </tr>
        <!-- <tr>
            <td>@getLang("req-https")</td>
            <td>@getEnv("REQUIRED_HTTPS")</td>
        </tr> -->
        <tr>
            <td>@getLang("use-boot")</td>
            <td>@getEnv("USE_BOOTSTRAP")</td>
        </tr>
        <tr>
            <td>@getLang("use-cache")</td>
            <td>@getEnv("USE_CACHE")</td>
        </tr>
        <tr>
            <td>@getLang("gemini-api-key")</td>
            <td>@getEnv("GEMINI_API_KEY")</td>
        </tr>
        <tr>
            <td>@getLang("gemini-model-id")</td>
            <td>@getEnv("GEMINI_MODEL_ID")</td>
        </tr>
        <tr>
            <td>@getLang("gemini-endpoint")</td>
            <td>@getEnv("GEMINI_ENDPOINT")</td>
        </tr>
        <tr>
            <td>@getLang("use-rate")</td>
            <td>@getEnv("USE_RATELIMITER")</td>
        </tr>
        <tr>
            <td>@getLang("rph")</td>
            <td>@getEnv("REQUESTS_PER_HOUR")</td>
        </tr>
        <tr>
            <td>@getLang("use-lang")</td>
            <td>@getEnv("DETECT_LANGUAGE")</td>
        </tr>
        <tr>
            <td>@getLang("use-cookie")</td>
            <td>@getEnv("USE_COOKIE")</td>
        </tr>
    </table>
</div>

<div class="performance-info" id="performanceInfo">
    Loading...
</div>
<script src="@getEnv("WEBSITE_URL")script.js"></script>
@endSection
@render("main", "index", "GET", [])