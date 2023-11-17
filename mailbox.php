namespace Mailbox\Config;

use CodeIgniter\Config\BaseConfig;
use Mailbox\Models\Mailbox_settings_model;
class Mailbox extends BaseConfig {

    public $app_settings_array = array(
        "mailbox_email_file_path" => PLUGIN_URL_PATH. "Mailbox/files/email_files/"
    );

    public function __construct() {
        $mailbox_settings_model = new Mailbox_settings_model();

        $settings = $mailbox_settings_model->get_all_settings()->getResult();
        foreach ($settings as $setting) {
            $this->app_settings_array[$setting->setting_name] = $setting->setting_value;
        }
    }

}
