<?php
namespace s2member_domains
	{
		if(!defined('WPINC')) // MUST have WordPress.
			exit('Do NOT access this file directly: '.basename(__FILE__));

		class plugin
		{
			public $version = '000000-dev';

			public $file = ''; // Set by constructor.

			public $text_domain = ''; // Set by constructor.

			public $cap = 'edit_others_posts';

			public function __construct()
				{
					$this->file        = str_replace('.inc.php', '.php', __FILE__);
					$this->text_domain = str_replace('_', '-', __NAMESPACE__);

					add_action('init', array($this, 'init'));
					register_activation_hook($this->file, array($this, 'activate'));
					add_action('ws_plugin__s2member_during_configure_user_registration', array($this, 'registration'));
				}

			public function init()
				{
					load_plugin_textdomain($this->text_domain);

					add_action('wp_loaded', array($this, 'actions'));
					add_action('admin_menu', array($this, 'add_menu_pages'));
					add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
				}

			public function add_menu_pages()
				{
					add_menu_page(__('s2 Domains', $this->text_domain), __('s2 Domains', $this->text_domain),
					              $this->cap, __NAMESPACE__.'_domains', array($this, 'menu_page_domains'));
				}

			public function enqueue_admin_scripts()
				{
					if(empty($_GET['page']) || strpos($_GET['page'], __NAMESPACE__) !== 0)
						return; // Nothing to do; NOT a plugin page.

					wp_enqueue_script(__NAMESPACE__.'_domains', $this->url('/menu-pages/script.min.js'), array('jquery'), $this->version, TRUE);
				}

			public function menu_page_domains()
				{
					require_once dirname(__FILE__).'/includes/menu-pages/domains.php';
				}

			public function actions()
				{
					if(empty($_REQUEST[__NAMESPACE__]))
						return; // Nothing to do here.

					if(!current_user_can($this->cap))
						return; // Not possible.

					require_once dirname(__FILE__).'/includes/actions.php';
				}

			public function url($file = '', $scheme = '')
				{
					static $plugin_directory;

					if(!isset($plugin_directory))
						$plugin_directory = rtrim(plugin_dir_url($this->file), '/');

					$url = $plugin_directory.$file;

					if($scheme) // A specific URL scheme?
						$url = set_url_scheme($url, $scheme);

					return $url;
				}

			public function registration($vars)
				{
					$user_id = (integer)$vars['user_id'];
					$email   = (string)$vars['email'];

					if(($level = $this->level_for($email)))
						if(($user = new \WP_User($user_id)) && $user->exists() && !$user->has_cap('access_s2member_level'.$level) && !$user->has_cap($this->cap))
							$user->set_role('s2member_level'.$level);
				}

			public function level_for($email_or_domain)
				{
					$email_or_domain = (string)$email_or_domain;

					if(strpos($email_or_domain, '@') !== FALSE)
						$domain = ltrim(strstr($email_or_domain, '@'), '@');
					else $domain = $email_or_domain;

					if(!$domain) return 0; // Not possible.

					$query = "SELECT `level` FROM `".esc_sql($this->db_table('domains'))."`".
					         " WHERE `domain` = '".esc_sql($domain)."' ORDER BY `level` DESC LIMIT 1";

					return (integer)$this->wpdb()->get_var($query);
				}

			public function user_ids_with_domain($domain)
				{
					if(!($domain = (string)$domain))
						return array(); // Not possible.

					$query = "SELECT `ID` FROM `".esc_sql($this->wpdb()->users)."`".
					         " WHERE `user_email` LIKE '".esc_sql('%@'.like_escape($domain))."'";

					return $this->wpdb()->get_col($query);
				}

			public function total_users_with_domain($domain)
				{
					if(!($domain = (string)$domain))
						return 0; // Not possible.

					$query = "SELECT `ID` FROM `".esc_sql($this->wpdb()->users)."`".
					         " WHERE `user_email` LIKE '".esc_sql('%@'.like_escape($domain))."' LIMIT 1";

					return $this->calc_found_rows($query);
				}

			public function update_users_with_domain($domain, $level = 0)
				{
					$total_role_changes = 0;

					if(!($domain = (string)$domain))
						return 0; // Not possible.

					$level = (integer)$level;
					if($level) $level_for_domain = $level;
					else if(!($level_for_domain = $this->level_for($domain)))
						return 0; // Not possible.

					foreach($this->user_ids_with_domain($domain) as $_user_id)
						{
							$_user = new \WP_User($_user_id);
							if(!$_user->has_cap($this->cap) && !$_user->has_cap('access_s2member_level'.$level))
							$_user->set_role('s2member_level'.$level_for_domain);
							$total_role_changes++; // Increment counter.
						}
					unset($_user_id, $_user); // Housekeeping.

					return $total_role_changes;
				}

			public function get_all_domains()
				{
					$query = "SELECT * FROM `".esc_sql($this->db_table('domains'))."` ORDER BY `domain` ASC";

					return $this->wpdb()->get_results($query);
				}

			public function get_domain($domain)
				{
					if(!($domain = (string)$domain))
						return NULL; // Not possible.

					$query = "SELECT * FROM `".esc_sql($this->db_table('domains'))."`".
					         " WHERE `domain` = '".esc_sql($domain)."' LIMIT 1";

					return $this->wpdb()->get_row($query);
				}

			public function calc_found_rows($query)
				{
					if(!($query = trim((string)$query)))
						return 0; // Not possible.

					if(!preg_match('/^SELECT\s+/i', $query))
						return 0; // Not possible.

					$query = preg_replace('/^SELECT\s+/i', 'SELECT SQL_CALC_FOUND_ROWS ', $query);
					$query = preg_replace('/\s+LIMIT\s+[0-9\s,]*$/i', '', $query).' LIMIT 1';

					$this->wpdb()->query($query);

					return (integer)$this->wpdb()->get_var('SELECT FOUND_ROWS()');
				}

			public function activate()
				{
					$charset = $this->wpdb()->charset;
					$collate = $this->wpdb()->collate;
					if(!$collate) $collate = 'utf8_unicode_ci';

					$this->wpdb()->query('CREATE TABLE IF NOT EXISTS `'.esc_sql($this->db_table('domains')).'`'.
					                     '('.
					                     '   `ID` bigint(20) NOT NULL AUTO_INCREMENT,'.
					                     '   `domain` varchar(255) COLLATE '.esc_sql($collate).' NOT NULL,'.
					                     '   `level` int(11) NOT NULL,'.
					                     '   `notes` text COLLATE '.esc_sql($collate).' NOT NULL,'.
					                     '   PRIMARY KEY (`ID`), UNIQUE KEY `domain` (`domain`)'.
					                     ')'.
					                     ' DEFAULT CHARSET='.esc_sql($charset).
					                     ' COLLATE='.esc_sql($collate).
					                     ' AUTO_INCREMENT=1;');
				}

			public function db_table($name)
				{
					return $this->wpdb()->prefix.__NAMESPACE__.'_'.(string)$name;
				}

			/** @return \wpdb */
			public function wpdb()
				{
					return $GLOBALS['wpdb'];
				}
		}

		/** @var plugin Class instance. */
		$GLOBALS[__NAMESPACE__] = new plugin();

		/** @return plugin Class instance. */
		function plugin()
			{
				return $GLOBALS[__NAMESPACE__];
			}
	}