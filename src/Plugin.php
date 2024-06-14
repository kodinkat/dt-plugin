<?php

namespace DT\Plugin;

use DT\Plugin\League\Container\Container;
use DT\Plugin\League\Config\Configuration;

/**
 * This is the entry-object for the plugin.
 * Handle any setup and bootstrapping here.
 */
class Plugin {
	/**
	 * The instance of the plugin
	 * @var Plugin
	 */
	public static Plugin $instance;

	/**
	 * The container
	 * @see https://laravel.com/docs/10.x/container
	 * @var Container
	 */
	public Container $container;
    public Configuration $config;

	/**
	 * Plugin constructor.
	 *
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
        static::$instance = $this;
        $this->container = $container;
	}

	/**
	 * Get the instance of the plugin
	 * @return void
	 */
	public function init() {
        $this->config = $this->container->get( Configuration::class );
        add_action( 'wp_loaded', [ $this, 'wp_loaded' ], 20 );
		add_filter( 'dt_plugins', [ $this, 'dt_plugins' ] );
	}

	/**
	 * Runs after wp_loaded
	 * @return void
	 */
	public function wp_loaded(): void {
		if ( ! $this->is_dt_version() ) {
			add_action( 'admin_notices', [ $this, 'admin_notices' ] );
			add_action( 'wp_ajax_dismissed_notice_handler', [ $this, 'ajax_notice_handler' ] );

			return;
		}

		if ( ! $this->is_dt_theme() ) {
			return;
		}

		if ( ! defined( 'DT_FUNCTIONS_READY' ) ) {
			require_once get_template_directory() . '/dt-core/global-functions.php';
		}
	}

	/**
	 * is DT up-to-date?
	 * @return bool
	 */
	public function is_dt_version(): bool {
		if ( ! $this->is_dt_theme() ) {
			return false;
		}
		$wp_theme = wp_get_theme();

		return version_compare(
            $wp_theme->version,
            $this->config->get('plugin.dt_version')
            , '>='
        );
	}

	/**
	 * Is the DT Theme installed?
	 * @return bool
	 */
	protected function is_dt_theme(): bool {
		return class_exists( 'Disciple_Tools' );
	}
	/**
	 * Register the plugin with disciple.tools
	 * @return array
	 */
	public function dt_plugins(): array {
		$plugin_data = get_file_data( __FILE__, [
			'Version'     => '0.0',
			'Plugin Name' => 'DT Plugin',
		], false );

		$plugins['dt-plugin'] = [
			'plugin_url' => trailingslashit( plugin_dir_url( __FILE__ ) ),
			'version'    => $plugin_data['Version'] ?? null,
			'name'       => $plugin_data['Plugin Name'] ?? null,
		];

		return $plugins;
	}
}
