<?php
namespace s2member_domains
	{
		if(!defined('WPINC')) // MUST have WordPress.
			exit('Do NOT access this file directly: '.basename(__FILE__));

		class menu_page_domains
		{
			public $plugin; // Set by constructor.

			public function __construct()
				{
					$this->plugin = plugin();

					if(!current_user_can($this->plugin->cap))
						return; // Not possible.

					$_r = stripslashes_deep($_REQUEST);

					echo '<div id="'.esc_attr(__NAMESPACE__.'_wrap').'" class="wrap">'."\n";

					if(!empty($_r['add_domain'])) // Add a new domain to the list...
						{
							echo '   <h2>'.__('Map a New Domain to an s2Member™ Level', $this->plugin->text_domain).'</h2>'."\n";

							echo '   <form id="'.esc_attr(__NAMESPACE__.'_add_domain').'" method="post">'."\n";
							echo '      <table class="form-table">'."\n";
							echo '         <tbody>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_add_domain_domain').'">'."\n";
							echo '                     '.__('Email Domain Name', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <input type="text" id="'.esc_attr(__NAMESPACE__.'_add_domain_domain').'" name="'.esc_attr(__NAMESPACE__.'[add_domain][domain]').'" value="" class="regular-text" autocomplete="off" />'."\n";
							echo '                  <p class="description">'.__('e.g. user@<code>domain</code>, please enter a <code>domain</code> name only', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_add_domain_level').'">'."\n";
							echo '                     '.__('s2Member™ Level', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <input type="text" id="'.esc_attr(__NAMESPACE__.'_add_domain_level').'" name="'.esc_attr(__NAMESPACE__.'[add_domain][level]').'" value="" class="regular-text" autocomplete="off" />'."\n";
							echo '                  <p class="description">'.__('e.g. <code>1</code>, <code>2</code>, <code>3</code>, or <code>4</code>, etc.', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_add_domain_notes').'">'."\n";
							echo '                     '.__('Notes (Optional)', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <textarea type="text" id="'.esc_attr(__NAMESPACE__.'_add_domain_notes').'" name="'.esc_attr(__NAMESPACE__.'[add_domain][notes]').'" class="large-text" rows="3" spellcheck="false" autocomplete="off" /></textarea>'."\n";
							echo '                  <p class="description">'.__('For internal administrative use only.', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '         </tbody>'."\n";
							echo '      </table>'."\n";

							echo '      <p class="submit">'."\n";
							echo '         <input type="submit" id="'.esc_attr(__NAMESPACE__.'_btn').'" class="button button-primary" value="'.esc_attr(__('Submit (Add Domain)', $this->plugin->text_domain)).'">'."\n";
							echo '         '.wp_nonce_field(__NAMESPACE__.'_add_domain')."\n";
							echo '      </p>'."\n";

							echo '   </form>'."\n";
						}
					else if(!empty($_r['update_domain']) && ($_domain = $this->plugin->get_domain(strtolower($_r['update_domain']))))
						{
							echo '   <h2>'.__('Update this Domain', $this->plugin->text_domain).'</h2>'."\n";

							echo '   <form id="'.esc_attr(__NAMESPACE__.'_update_domain').'" method="post">'."\n";
							echo '      <table class="form-table">'."\n";
							echo '         <tbody>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_update_domain_domain').'">'."\n";
							echo '                     '.__('Email Domain Name', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <input type="text" id="'.esc_attr(__NAMESPACE__.'_update_domain_domain').'" name="'.esc_attr(__NAMESPACE__.'[update_domain][domain]').'" value="'.esc_attr($_domain->domain).'" class="regular-text" autocomplete="off" />'."\n";
							echo '                  <p class="description">'.__('e.g. user@<code>domain</code>, please enter a <code>domain</code> name only', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_update_domain_level').'">'."\n";
							echo '                     '.__('s2Member™ Level', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <input type="text" id="'.esc_attr(__NAMESPACE__.'_update_domain_level').'" name="'.esc_attr(__NAMESPACE__.'[update_domain][level]').'" value="'.esc_attr($_domain->level).'" class="regular-text" autocomplete="off" />'."\n";
							echo '                  <p class="description">'.__('e.g. <code>1</code>, <code>2</code>, <code>3</code>, or <code>4</code>, etc.', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '            <tr valign="top">'."\n";
							echo '               <th scope="row">'."\n";
							echo '                  <label for="'.esc_attr(__NAMESPACE__.'_update_domain_notes').'">'."\n";
							echo '                     '.__('Notes (Optional)', $this->plugin->text_domain)."\n";
							echo '                  </label>'."\n";
							echo '               </th>'."\n";
							echo '               <td>'."\n";
							echo '                  <textarea type="text" id="'.esc_attr(__NAMESPACE__.'_update_domain_notes').'" name="'.esc_attr(__NAMESPACE__.'[update_domain][notes]').'" class="large-text" rows="3" spellcheck="false" autocomplete="off" />'.esc_textarea($_domain->notes).'</textarea>'."\n";
							echo '                  <p class="description">'.__('For internal administrative use only.', $this->plugin->text_domain).'</p>'."\n";
							echo '               </td>'."\n";
							echo '            </tr>'."\n";

							echo '         </tbody>'."\n";
							echo '      </table>'."\n";

							echo '      <p class="submit">'."\n";
							echo '         <input type="submit" id="'.esc_attr(__NAMESPACE__.'_btn').'" class="button button-primary" value="'.esc_attr(__('Submit (Update Domain)', $this->plugin->text_domain)).'">'."\n";
							echo '         '.wp_nonce_field(__NAMESPACE__.'_update_domain')."\n";
							echo '      </p>'."\n";

							echo '   </form>'."\n";
						}
					else // Display a list of all existing domains.
						{
							echo '   <a href="'.esc_attr(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'_domains', 'add_domain' => 1)), self_admin_url('/admin.php'))).'" class="button button-primary" style="float:right; margin:10px 0 0 25px;">'.__('Add Domain', $this->plugin->text_domain).'</a>'."\n";
							echo '   <h2>'.__('Current Domains Mapped to an s2Member™ Level', $this->plugin->text_domain).'</h2>'."\n";
							echo '   <hr />'."\n"; // Divider...

							if(!empty($_r[__NAMESPACE__.'_error']))
								{
									echo '   <div class="error" style="padding:10px;">'."\n";
									echo '      '.esc_html($_r[__NAMESPACE__.'_error'])."\n";
									echo '   </div>'."\n";
								}
							if(!empty($_r[__NAMESPACE__.'_added_domain']))
								{
									$_domain     = strtolower((string)$_r[__NAMESPACE__.'_added_domain']);
									$_level      = $this->plugin->level_for($_domain);
									$_action_url = add_query_arg(urlencode_deep(array('page'        => __NAMESPACE__.'_domains',
									                                                  __NAMESPACE__ => array('update_users_with_domain' => array('domain' => $_domain)))), self_admin_url('/admin.php'));
									$_action_url = wp_nonce_url($_action_url, __NAMESPACE__.'_update_users_with_domain');

									$_notice = sprintf(__('Domain <code>%1$s</code> at level <code>%2$s</code> added successfully.', $this->plugin->text_domain), esc_html($_domain), esc_html($_level));
									$_notice .= ' '.sprintf(__('There are currently <code>%1$s</code> users with this domain. If you would like to update all of them to level <code>%2$s</code>, <a href="%3$s" onclick="%4$s">please click here</a>.', $this->plugin->text_domain),
									                        esc_html($this->plugin->total_users_with_domain($_domain)), esc_html($_level), esc_attr($_action_url), esc_attr(__("if(!confirm('Press OK to confirm please.')) return false;", $this->plugin->text_domain)));

									echo '   <div class="updated" style="padding:10px;">'."\n";
									echo '      '.$_notice."\n";
									echo '   </div>'."\n";

									unset($_domain, $_level, $_action_url, $_notice);
								}
							if(!empty($_r[__NAMESPACE__.'_updated_users_with_domain']) && strpos($_r[__NAMESPACE__.'_updated_users_with_domain'], ':') !== FALSE)
								{
									list($_domain, $_level) = explode(':', $_r[__NAMESPACE__.'_updated_users_with_domain'], 2);

									$_notice = sprintf(__('<code>%1$s</code> users with an email domain matching <code>@%2$s</code> have been updated to level <code>%3$s</code> successfully.', $this->plugin->text_domain),
									                   esc_html($this->plugin->total_users_with_domain($_domain)), esc_html($_domain), esc_html($_level));

									echo '   <div class="updated" style="padding:10px;">'."\n";
									echo '      '.$_notice."\n";
									echo '   </div>'."\n";

									unset($_domain, $_level, $_notice);
								}
							if(!empty($_r[__NAMESPACE__.'_updated_domain']))
								{
									$_domain     = strtolower((string)$_r[__NAMESPACE__.'_updated_domain']);
									$_level      = $this->plugin->level_for($_domain);
									$_action_url = add_query_arg(urlencode_deep(array('page'        => __NAMESPACE__.'_domains',
									                                                  __NAMESPACE__ => array('update_users_with_domain' => array('domain' => $_domain)))), self_admin_url('/admin.php'));
									$_action_url = wp_nonce_url($_action_url, __NAMESPACE__.'_update_users_with_domain');

									$_notice = sprintf(__('Domain <code>%1$s</code> has been updated to level <code>%2$s</code> successfully.', $this->plugin->text_domain), esc_html($_domain), esc_html($_level));
									$_notice .= ' '.sprintf(__('There are currently <code>%1$s</code> users with this domain. If you would like to update all of them to level <code>%2$s</code>, <a href="%3$s" onclick="%4$s">please click here</a>.', $this->plugin->text_domain),
									                        esc_html($this->plugin->total_users_with_domain($_domain)), esc_html($_level), esc_attr($_action_url), esc_attr(__("if(!confirm('Press OK to confirm please.')) return false;", $this->plugin->text_domain)));

									echo '   <div class="updated" style="padding:10px;">'."\n";
									echo '      '.$_notice."\n";
									echo '   </div>'."\n";

									unset($_domain, $_level, $_action_url, $_notice);
								}
							if(!empty($_r[__NAMESPACE__.'_level_changed_for_domain']))
								{
									$_domain     = strtolower((string)$_r[__NAMESPACE__.'_level_changed_for_domain']);
									$_level      = $this->plugin->level_for($_domain);
									$_action_url = add_query_arg(urlencode_deep(array('page'        => __NAMESPACE__.'_domains',
									                                                  __NAMESPACE__ => array('update_users_with_domain' => array('domain' => $_domain)))), self_admin_url('/admin.php'));
									$_action_url = wp_nonce_url($_action_url, __NAMESPACE__.'_update_users_with_domain');

									$_notice = sprintf(__('Domain <code>%1$s</code> has been changed to level <code>%2$s</code> successfully.', $this->plugin->text_domain), esc_html($_domain), esc_html($_level));
									$_notice .= ' '.sprintf(__('There are currently <code>%1$s</code> users with this domain. If you would like to update all of them to level <code>%2$s</code>, <a href="%3$s" onclick="%4$s">please click here</a>.', $this->plugin->text_domain),
									                        esc_html($this->plugin->total_users_with_domain($_domain)), esc_html($_level), esc_attr($_action_url), esc_attr(__("if(!confirm('Press OK to confirm please.')) return false;", $this->plugin->text_domain)));

									echo '   <div class="updated" style="padding:10px;">'."\n";
									echo '      '.$_notice."\n";
									echo '   </div>'."\n";

									unset($_domain, $_level, $_action_url, $_notice);
								}
							if(!empty($_r[__NAMESPACE__.'_deleted_domain']))
								{
									$_domain     = strtolower((string)$_r[__NAMESPACE__.'_deleted_domain']);
									$_action_url = add_query_arg(urlencode_deep(array('page'        => __NAMESPACE__.'_domains',
									                                                  __NAMESPACE__ => array('update_users_with_domain' => array('domain' => $_domain, 'level' => 0)))), self_admin_url('/admin.php'));
									$_action_url = wp_nonce_url($_action_url, __NAMESPACE__.'_update_users_with_domain');

									$_notice = sprintf(__('Domain <code>%1$s</code> has been deleted successfully.', $this->plugin->text_domain), esc_html($_domain));
									$_notice .= ' '.sprintf(__('There are currently <code>%1$s</code> users with this domain. If you would like to update all of them to a different level, <a href="%2$s" onclick="%3$s">please click here</a>.', $this->plugin->text_domain),
									                        esc_html($this->plugin->total_users_with_domain($_domain)), esc_attr($_action_url), esc_attr(__("var level = prompt('Level Number'); if(!level) return false; else this.href = this.href.replace(/\\[level\\]\\=0/, '[level]='+encodeURIComponent(level));", $this->plugin->text_domain)));

									echo '   <div class="updated" style="padding:10px;">'."\n";
									echo '      '.$_notice."\n";
									echo '   </div>'."\n";

									unset($_domain, $_action_url, $_notice);
								}
							echo '      <table class="widefat fixed">'."\n";
							echo '         <tbody>'."\n";

							echo '            <tr valign="middle">'."\n";
							echo '               <th scope="col" class="column-title" style="text-align:left;">'."\n";
							echo '                  '.__('Domain', $this->plugin->text_domain)."\n";
							echo '               </th>'."\n";
							echo '               <th scope="col" class="column-title" style="text-align:center;">'."\n";
							echo '                  '.__('Level', $this->plugin->text_domain)."\n";
							echo '               </th>'."\n";
							echo '               <th scope="col" class="column-title" style="text-align:center;">'."\n";
							echo '                  '.__('Notes', $this->plugin->text_domain)."\n";
							echo '               </th>'."\n";
							echo '               <th scope="col" class="column-title" style="text-align:right;">'."\n";
							echo '                  '.__('Actions', $this->plugin->text_domain)."\n";
							echo '               </th>'."\n";
							echo '            </tr>'."\n";

							foreach($this->plugin->get_all_domains() as $_domain)
								{
									$_update_url = add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'_domains', 'update_domain' => $_domain->domain)), self_admin_url('/admin.php'));
									$_delete_url = wp_nonce_url(add_query_arg(urlencode_deep(array('page' => __NAMESPACE__.'_domains', __NAMESPACE__ => array('delete_domain' => array('domain' => $_domain->domain)))), self_admin_url('/admin.php')), __NAMESPACE__.'_delete_domain');

									echo '      <tr valign="top" class="alternate">'."\n";
									echo '         <td style="width:25%; text-align:left;">'."\n";
									echo '            '.esc_html($_domain->domain)."\n";
									echo '         </td>'."\n";
									echo '         <td style="width:25%; text-align:center;">'."\n";
									echo '            '.esc_html($_domain->level)."\n";
									echo '         </td>'."\n";
									echo '         <td style="width:25%; text-align:center;">'."\n";
									echo '            '.esc_html($_domain->notes)."\n";
									echo '         </td>'."\n";
									echo '         <td style="width:25%; text-align:right;">'."\n";
									echo '            <a href="'.esc_attr($_update_url).'">'.__('edit', $this->plugin->text_domain).'</a>&nbsp;&nbsp;|'."\n";
									echo '            <a href="'.esc_attr($_delete_url).'" onclick="if(!confirm(\''.__('Press OK to confirm please.', $this->plugin->text_domain).'\')) return false;">'.__('delete', $this->plugin->text_domain).'</a>'."\n";
									echo '         </td>'."\n";
									echo '      </tr>'."\n";
								}
							echo '         </tbody>'."\n";
							echo '      </table>'."\n";
						}
					unset($_domain, $_update_url, $_delete_url); // Housekeeping.

					echo '</div>'."\n";
				}
		}

		$GLOBALS[__NAMESPACE__.'_menu_page_domains'] = new menu_page_domains();
	}
