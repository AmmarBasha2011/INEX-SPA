<?php

class MakeSitemapCommand extends Command {
    public function __construct() {
        parent::__construct('make:sitemap', 'Generate sitemap.xml');
    }

    public function execute($args) {
        SitemapGenerator::generate();
        Terminal::success("Sitemap generated successfully!");
    }
}

$registry->register(new MakeSitemapCommand());
