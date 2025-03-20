<?php
Layout::start('content');
?>
<div id="contentBox" class="container">
    <div class="glowing-text">
        <h1>INEX SPA</h1>
    </div>
    <p class="subtitle">
        <?= Language::get('subtitle') ?>
    </p>
    <p class="version"><?= Language::get('version') ?> <?php echo getEnvValue('VERSION'); ?></p>
    <p class="edit-prompt"><?= Language::get('edit-prompt') ?></p>
    <div class="cta-button">
        <form>
        <a type="button" onclick="submitData('setLanguage', [], 'POST', '', [{'lang':'en'}])">English</a>
        <a type="button" onclick="submitData('setLanguage', [], 'POST', '', [{'lang':'ar'}])">العربية</a>
        </form>
        <hr>
        <a href="https://github.com/AmmarBasha2011/INEX-SPA"><?= Language::get('get-started') ?></a>
    </div>
</div>

<div id="toggleIcon">
    <i class="fa-solid fa-chevron-down"></i>
</div>

<div id="appDetailsTable" style="display: none;">
    <table border="1" style="margin: 0 auto; color: #fff; background-color: rgba(0,0,0,0.5);">
        <tr>
            <th><?= Language::get('field') ?></th>
            <th><?= Language::get('value') ?></th>
        </tr>
        <tr>
            <td><?= Language::get('app-name') ?></td>
            <td><?php echo getEnvValue('APP_NAME'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('use-app-name') ?></td>
            <td><?= getEnvValue('USE_APP_NAME_IN_TITLE'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('web-url') ?></td>
            <td><?php echo getEnvValue('WEBSITE_URL'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('version') ?></td>
            <td><?php echo getEnvValue('VERSION'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('dev-mode') ?></td>
            <td><?php echo getEnvValue('DEV_MODE'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('db-host') ?></td>
            <td><?php echo getEnvValue('DB_HOST'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('db-user') ?></td>
            <td><?php echo getEnvValue('DB_USER'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('db-pass') ?></td>
            <td><?php echo getEnvValue('DB_PASS'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('db-name') ?></td>
            <td><?php echo getEnvValue('DB_NAME'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('db-use') ?></td>
            <td><?php echo getEnvValue('DB_USE'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('req-https') ?></td>
            <td><?php echo getEnvValue('REQUIRED_HTTPS'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('use-boot') ?></td>
            <td><?php echo getEnvValue('USE_BOOTSTRAP'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('use-cache') ?></td>
            <td><?= getEnvValue('USE_CACHE'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('gemini-api-key') ?></td>
            <td><?= getEnvValue('GEMINI_API_KEY') ?></td>
        </tr>
        <tr>
            <td><?= Language::get('gemini-model-id') ?></td>
            <td><?= getEnvValue('GEMINI_MODEL_ID'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('gemini-endpoint') ?></td>
            <td><?= getEnvValue('GEMINI_ENDPOINT'); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('use-rate') ?></td>
            <td><?= getEnvValue("USE_RATELIMITER"); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('rph') ?></td>
            <td><?= getEnvValue("REQUESTS_PER_HOUR"); ?></td>
        </tr>
        <tr>
            <td><?= Language::get('use-lang') ?></td>
            <td><?= getEnvValue('DETECT_LANGUAGE'); ?></td>
        </tr>
    </table>
</div>

<div class="performance-info" id="performanceInfo">
    Loading...
</div>
<?php
echo '<script src="' . getEnvValue('WEBSITE_URL') . 'script.js"></script>';
Layout::end();

Layout::render('main', 'index'); // تشغيل التخطيط والملف المطلوب
?>
