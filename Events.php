<?php

namespace Mailbox\Config;

use CodeIgniter\Events\Events;
use Mailbox\Libraries\Imap;

Events::on('pre_system', function () {
    helper("mailbox_general");
});

Events::on('post_controller_constructor', function () {
    //fetch emails on cron job
    app_hooks()->add_action('app_hook_after_cron_run', function () {
        try {
            $imap = new Imap();
            $imap->run_imap();
        } catch (\Exception $ex) {
            log_message('error', '[ERROR] {exception}', ['exception' => $ex]);
        }
    });
});

