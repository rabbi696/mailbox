<?php

defined('PLUGINPATH') or exit('No direct script access allowed');

use App\Controllers\Security_Controller;

//add menu item to left menu
app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {
    $instance = new Security_Controller();
    $allowed_mailboxes_ids = get_allowed_mailboxes_ids();

    if ($allowed_mailboxes_ids) {
        $active_mailbox = get_mailbox_setting("user_" . $instance->login_user->id . "_active_mailbox");
        $active_mailbox = in_array($active_mailbox, $allowed_mailboxes_ids) ? $active_mailbox : ""; //don't add previously permitted mailbox if it doesn't have access now

        $sidebar_menu["mailbox"] = array(
            "name" => "mailbox",
            "url" => "mailbox" . ($active_mailbox ? "/$active_mailbox" : ""),
            "class" => "inbox",
            "position" => 6,
            "badge" => mailbox_count_unread_emails(),
            "badge_class" => "bg-primary"
        );
    }

    return $sidebar_menu;
});

app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {
    $settings_menu["plugins"][] = array("name" => "mailbox", "url" => "mailbox_settings");
    return $settings_menu;
});

app_hooks()->add_filter('app_filter_client_details_ajax_tab', 'mailbox_details_view_ajax_tab');
app_hooks()->add_filter('app_filter_lead_details_ajax_tab', 'mailbox_details_view_ajax_tab');

if (!function_exists('mailbox_details_view_ajax_tab')) {

    function mailbox_details_view_ajax_tab($hook_tabs, $client_id = 0) {
        if (!$client_id) {
            return $hook_tabs;
        }

        if (get_allowed_mailboxes_ids()) {
            $hook_tabs[] = array(
                "title" => app_lang('mailbox'),
                "url" => get_uri("mailbox/clientEmails/$client_id"),
                "target" => "tab-mailbox_client_emails"
            );
        }

        return $hook_tabs;
    }

}

//install dependencies
register_installation_hook("Mailbox", function ($item_purchase_code) {
    include PLUGINPATH . "Mailbox/install/do_install.php";
});

use Mailbox\Controllers\Mailbox_Updates;

register_update_hook("Mailbox", function () {
    $update = new Mailbox_Updates();
    return $update->index();
});
