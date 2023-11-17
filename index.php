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
