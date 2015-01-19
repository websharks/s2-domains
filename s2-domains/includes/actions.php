<?php
namespace s2member_domains
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	class actions
	{

		public $plugin; // Set by constructor.

		public function __construct()
		{
			$this->plugin = plugin();

			if(empty($_REQUEST[__NAMESPACE__])) return;
			if(empty($_REQUEST['_wpnonce'])) return;

			$namespace_request_vars = stripslashes_deep((array)$_REQUEST[__NAMESPACE__]);
			$_wpnonce               = stripslashes((string)$_REQUEST['_wpnonce']);

			foreach($namespace_request_vars as $action => $args)
				if(wp_verify_nonce($_wpnonce, __NAMESPACE__.'_'.$action))
					if(current_user_can($this->plugin->cap))
						if(method_exists($this, $action))
							$this->{$action}($args);
		}

		public function add_domain($args)
		{
			$redirect_to = self_admin_url('/admin.php');
			$query_args  = array('page' => __NAMESPACE__.'_domains');

			if(empty($args['domain']) || !is_string($args['domain']) || !($args['domain'] = strtolower($args['domain'])))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain name.', $this->plugin->text_domain);

			else if(empty($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain level.', $this->plugin->text_domain);

			else if(!is_numeric($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Non-numeric domain level.', $this->plugin->text_domain);

			else if(!$this->plugin->wpdb()->insert($this->plugin->db_table('domains'),
			                                       array('domain' => $args['domain'],
			                                             'level'  => $args['level'],
			                                             'notes'  => (string)@$args['notes'])) || !$this->plugin->wpdb()->insert_id
			) $query_args[__NAMESPACE__.'_error'] = __('DB insertion failure; possible domain duplicate.', $this->plugin->text_domain);

			else $query_args[__NAMESPACE__.'_added_domain'] = $args['domain'];

			wp_redirect(add_query_arg(urlencode_deep($query_args), $redirect_to)).exit();
		}

		public function update_domain($args)
		{
			$redirect_to = self_admin_url('/admin.php');
			$query_args  = array('page' => __NAMESPACE__.'_domains');

			if(empty($args['domain']) || !is_string($args['domain']) || !($args['domain'] = strtolower($args['domain'])))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain name.', $this->plugin->text_domain);

			else if(empty($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain level.', $this->plugin->text_domain);

			else if(!is_numeric($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Non-numeric domain level.', $this->plugin->text_domain);

			else // Update the domain & report success.
			{
				$this->plugin->wpdb()->update($this->plugin->db_table('domains'),
				                              array('level' => $args['level'],
				                                    'notes' => (string)@$args['notes']), array('domain' => $args['domain']));
				$query_args[__NAMESPACE__.'_updated_domain'] = $args['domain'];
			}
			wp_redirect(add_query_arg(urlencode_deep($query_args), $redirect_to)).exit();
		}

		public function change_domain_level($args)
		{
			$redirect_to = self_admin_url('/admin.php');
			$query_args  = array('page' => __NAMESPACE__.'_domains');

			if(empty($args['domain']) || !is_string($args['domain']) || !($args['domain'] = strtolower($args['domain'])))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain name.', $this->plugin->text_domain);

			else if(empty($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain level.', $this->plugin->text_domain);

			else if(!is_numeric($args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Non-numeric domain level.', $this->plugin->text_domain);

			else // Update the domain's membership level & report success.
			{
				$this->plugin->wpdb()->update($this->plugin->db_table('domains'),
				                              array('level' => $args['level']), array('domain' => $args['domain']));
				$query_args[__NAMESPACE__.'_level_changed_for_domain'] = $args['domain'];
			}
			wp_redirect(add_query_arg(urlencode_deep($query_args), $redirect_to)).exit();
		}

		public function delete_domain($args)
		{
			$redirect_to = self_admin_url('/admin.php');
			$query_args  = array('page' => __NAMESPACE__.'_domains');

			if(empty($args['domain']) || !is_string($args['domain']) || !($args['domain'] = strtolower($args['domain'])))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain name.', $this->plugin->text_domain);

			else // Delete the domain & report success.
			{
				$this->plugin->wpdb()->delete($this->plugin->db_table('domains'), array('domain' => $args['domain']));
				$query_args[__NAMESPACE__.'_deleted_domain'] = $args['domain'];
			}
			wp_redirect(add_query_arg(urlencode_deep($query_args), $redirect_to)).exit();
		}

		public function update_users_with_domain($args)
		{
			$redirect_to = self_admin_url('/admin.php');
			$query_args  = array('page' => __NAMESPACE__.'_domains');

			if(empty($args['domain']) || !is_string($args['domain']) || !($args['domain'] = strtolower($args['domain'])))
				$query_args[__NAMESPACE__.'_error'] = __('Missing domain name.', $this->plugin->text_domain);

			else if(isset($args['level']) && !($args['level'] = (integer)$args['level']))
				$query_args[__NAMESPACE__.'_error'] = __('Non-numeric domain level.', $this->plugin->text_domain);

			else // Update users with this domain & report success.
			{
				if(empty($args['level']) || !($args['level'] = (integer)$args['level']))
					$args['level'] = $this->plugin->level_for($args['domain']);

				$this->plugin->update_users_with_domain($args['domain'], $args['level']);
				$query_args[__NAMESPACE__.'_updated_users_with_domain'] = $args['domain'].':'.$args['level'];
			}
			wp_redirect(add_query_arg(urlencode_deep($query_args), $redirect_to)).exit();
		}
	}

	$GLOBALS[__NAMESPACE__.'_actions'] = new actions();
}