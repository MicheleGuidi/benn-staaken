<?php
if (defined('BORLABS_COOKIE_COMPATIBILITY_PATCH_' . strtoupper(basename(__FILE__))) !== true) {
    error_log('Direct call of ' . __FILE__ . ' is not allowed.');

    return null;
}
?>
<?php

use Borlabs\Cookie\Adapter\WpFunction;
use Borlabs\Cookie\System\Option\Option;

class BorlabsCookieCompatibilityPatchGoogleFonts
{
    private Borlabs\Cookie\Container\Container $container;

    private Option $option;

    private WpFunction $wpFunction;

    /**
     * @throws \Exception
     */
    public function __construct(Borlabs\Cookie\Container\ApplicationContainer $container)
    {
        $this->container = $container::get();
        $this->option = $this->container->get(Option::class);
        $this->wpFunction = $this->container->get(WpFunction::class);
    }

    public function init()
    {
        // DISABLE GOOGLE FONTS
        // Avada
        $this->wpFunction->addFilter(
            'avada_setting_get_gfonts_load_method',
            static function (): string {
                return 'local';
            },
            9999,
        );
        // Divi
        $this->wpFunction->addFilter(
            'et_get_option_et_divi_divi_google_fonts_inline',
            static function (): string {
                return 'off';
            },
            9999,
        );
        // DownloadManager
        $this->option->setThirdPartyOption('__wpdm_google_font', '');
        // Elementor
        $this->wpFunction->addFilter(
            'elementor/frontend/print_google_fonts',
            static function (): bool {
                return false;
            },
            9999,
        );
        // Enfold
        $this->wpFunction->addFilter(
            'avf_output_google_webfonts_script',
            static function (): bool {
                return false;
            },
            9999,
        );
        // JupiterX
        $this->wpFunction->addAction(
            'wp_head',
            static function (): void {
                \wp_deregister_script('jupiterx-webfont');
            },
            9999,
        );
        // Mailpoet
        $this->wpFunction->addFilter(
            'mailpoet_display_custom_fonts',
            static function (): bool {
                return false;
            },
            9999,
        );
        // Oxygen
        $this->option->setThirdPartyOption('oxygen_vsb_disable_google_fonts', 'true');
        $this->option->setThirdPartyOption('oxygen_vsb_use_css_for_google_fonts', 'true');
        // Themify
        $this->wpFunction->addAction(
            'after_setup_theme',
            static function (): void {
                if (defined('THEMIFY_GOOGLE_FONTS') === false) {
                    define('THEMIFY_GOOGLE_FONTS', false);
                }
            },
            9999,
        );
    }
}

?>
