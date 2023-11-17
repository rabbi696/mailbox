<?php

namespace Mailbox\Config;

use CodeIgniter\Events\Events;
use Mailbox\Libraries\Imap;

Events::on('pre_system', function () {
    helper("mailbox_general");
});
