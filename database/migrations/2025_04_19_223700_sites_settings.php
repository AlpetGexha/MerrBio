<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SitesSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.site_name', 'MerrBio');
        $this->migrator->add('sites.site_description', 'Creative Solutions');
        $this->migrator->add('sites.site_keywords', 'Graphics, Marketing, Programming');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Alpet Gexha');
        $this->migrator->add('sites.site_address', 'Kosova');
        $this->migrator->add('sites.site_email', 'agexha@gmail.com');
        $this->migrator->add('sites.site_phone', '+383 44 567 631');
        $this->migrator->add('sites.site_phone_code', '+2');
        $this->migrator->add('sites.site_location', 'Kosova');
        $this->migrator->add('sites.site_currency', 'KS');
        $this->migrator->add('sites.site_language', 'English');
        $this->migrator->add('sites.site_social', []);
    }
}
