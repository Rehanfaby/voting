<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\CustomerGroup;
use App\Warehouse;
use App\Biller;
use App\Account;
use App\Currency;
use App\PosSetting;
use App\GeneralSetting;
use App\HrmSetting;
use App\RewardPointSetting;
use App\Helpers\AppCache;
use App\Helpers\SiteContent;
use App\Helpers\ImageOptimizer;
use DB;
use Auth;
use Illuminate\Support\Facades\Schema;
use ZipArchive;
use Twilio\Rest\Client;
use Clickatell\Rest;
use Clickatell\ClickatellException;

class SettingController extends Controller
{
    public function emptyDatabase()
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        $tables = DB::select('SHOW TABLES');
        $str = 'Tables_in_' . env('DB_DATABASE');
        foreach ($tables as $table) {
            if($table->$str != 'accounts' && $table->$str != 'general_settings' && $table->$str != 'hrm_settings' && $table->$str != 'languages' && $table->$str != 'migrations' && $table->$str != 'password_resets' && $table->$str != 'permissions' && $table->$str != 'pos_setting' && $table->$str != 'roles' && $table->$str != 'role_has_permissions' && $table->$str != 'users' && $table->$str != 'currencies' && $table->$str != 'reward_point_settings') {
                DB::table($table->$str)->truncate();
            }
        }
        return redirect()->back()->with('message', 'Database cleared successfully');
    }
    public function generalSetting()
    {
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $lims_account_list = Account::where('is_active', true)->get();
        $lims_currency_list = Currency::get();
        $zones_array = array();
        $timestamp = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return view('setting.general_setting', compact('lims_general_setting_data', 'lims_account_list', 'zones_array', 'lims_currency_list'));
    }

    public function gradingSetting()
    {
        $lims_general_setting_data = GeneralSetting::latest()->first();
        return view('setting.grading_setting', compact('lims_general_setting_data', ));
    }

    public function generalSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $this->validate($request, [
            'site_logo' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
            'email_header' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
            'email_footer' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
            'email_water_mark' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $data = $request->except('site_logo');
        //return $data;
        //writting timezone info in .env file
        $path = '.env';
        $searchArray = array('APP_TIMEZONE='.env('APP_TIMEZONE'));
        $replaceArray = array('APP_TIMEZONE='.$data['timezone']);

//        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

        $general_setting = GeneralSetting::latest()->first();
        $general_setting->id = 1;
        $general_setting->site_title = $data['site_title'];
        $general_setting->currency = $data['currency'];
        $general_setting->currency_position = $data['currency_position'];
        $general_setting->staff_access = $data['staff_access'];
        $general_setting->date_format = $data['date_format'];
//        $general_setting->developed_by = $data['developed_by'];
        $general_setting->invoice_format = $data['invoice_format'];
        $general_setting->state = $data['state'];
        $general_setting->vote_price = $data['vote_price'];
        $general_setting->vote_coin = $data['vote_coin'];
        if (Schema::hasColumn('general_settings', 'hide_votes')) {
            $general_setting->hide_votes = self::checkboxInt($request, 'hide_votes');
        }
        if (Schema::hasColumn('general_settings', 'is_voting_start')) {
            $general_setting->is_voting_start = self::checkboxInt($request, 'is_voting_start');
        }
        if (Schema::hasColumn('general_settings', 'require_contestant_approval')) {
            $general_setting->require_contestant_approval = self::checkboxInt($request, 'require_contestant_approval');
        }
        $logo = $request->site_logo;
        $email_header = $request->email_header;
        $email_footer = $request->email_footer;
        $email_water_mark = $request->email_water_mark;
        if ($logo) {
            $ext = pathinfo($logo->getClientOriginalName(), PATHINFO_EXTENSION);
            $logoName = date("Ymdhis") . '.' . $ext;
            $logo->move('public/logo', $logoName);
            ImageOptimizer::afterUpload(public_path('logo/' . $logoName), 'logo');
            $general_setting->site_logo = $logoName;
        }
        if ($email_header) {
            $ext = pathinfo($email_header->getClientOriginalName(), PATHINFO_EXTENSION);
            $headerName = date("Ymdhi") . '.' . $ext;
            $email_header->move('public/logo', $headerName);
            ImageOptimizer::afterUpload(public_path('logo/' . $headerName), 'logo');
            $general_setting->email_header = $headerName;
        }
        if ($email_footer) {
            $ext = pathinfo($email_footer->getClientOriginalName(), PATHINFO_EXTENSION);
            $footerName = date("Ymdis") . '.' . $ext;
            $email_footer->move('public/logo', $footerName);
            ImageOptimizer::afterUpload(public_path('logo/' . $footerName), 'logo');
            $general_setting->email_footer = $footerName;
        }
        if ($email_water_mark) {
            $ext = pathinfo($email_water_mark->getClientOriginalName(), PATHINFO_EXTENSION);
            $waterMarkName = date("Ymdhs") . '.' . $ext;
            $email_water_mark->move('public/logo', $waterMarkName);
            ImageOptimizer::afterUpload(public_path('logo/' . $waterMarkName), 'logo');
            $general_setting->email_water_mark = $waterMarkName;
        }
        $general_setting->save();
        AppCache::forgetSharedData();

        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function gradingSettingStore(Request $request)
    {
        $general_setting = GeneralSetting::latest()->first();
        $general_setting->id = 1;
        $general_setting->vote_percentage = $request->vote_percentage;
        $general_setting->judge_percentage = $request->judge_percentage;
        $general_setting->ambassador_percentage = $request->ambassador_percentage;
        $general_setting->number_of_elimination = $request->number_of_elimination;
        $general_setting->available_grading = $request->available_grading;

        $general_setting->save();
        AppCache::forgetSharedData();

        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function siteContent()
    {
        $content = SiteContent::all();
        $section_labels = SiteContent::homepageSectionKeys();
        $menu_labels = SiteContent::menuKeys();
        $menu_order = SiteContent::menuOrder();
        $partners = \App\Partner::orderBy('sort_order')->orderBy('id')->get();

        $all_permission = [];
        $role = \Spatie\Permission\Models\Role::find(Auth::user()->role_id);
        if ($role) {
            foreach ($role->permissions as $permission) {
                $all_permission[] = $permission->name;
            }
        }

        return view('setting.site_content', compact('content', 'section_labels', 'menu_labels', 'menu_order', 'partners', 'all_permission'));
    }

    public function siteContentStoreSection(Request $request)
    {
        $section = $request->input('section');
        $data = SiteContent::all();
        $message = 'Site content updated successfully';

        switch ($section) {
            case 'homepage_sections':
                $posted = (array) $request->input('sections', []);
                $sections = $data['sections'] ?? [];
                foreach (array_keys(SiteContent::homepageSectionKeys()) as $key) {
                    $sections[$key] = !empty($posted[$key]);
                }
                $data['sections'] = $sections;
                $message = 'Homepage sections saved.';
                break;

            case 'popup':
                if (!isset($data['sections']) || !is_array($data['sections'])) {
                    $data['sections'] = [];
                }
                $popupPosted = (array) $request->input('sections', []);
                $data['sections']['popup'] = !empty($popupPosted['popup']);

                $data['popup_link'] = trim((string) $request->input('popup_link', ''));
                $data['popup_countdown'] = $request->has('popup_countdown');
                $data['popup_countdown_label'] = trim((string) $request->input('popup_countdown_label', ''));
                $cdAt = trim((string) $request->input('popup_countdown_at', ''));
                if ($cdAt !== '') {
                    try {
                        $cdAt = \Carbon\Carbon::parse(str_replace('T', ' ', $cdAt))->format('Y-m-d H:i:s');
                    } catch (\Throwable $e) {
                        // keep raw value
                    }
                }
                $data['popup_countdown_at'] = $cdAt ?: null;

                if ($request->has('delete_popup_image')) {
                    $data['popup_image'] = null;
                    $message = 'Popup image removed.';
                } elseif ($request->hasFile('popup_image')) {
                    $request->validate([
                        'popup_image' => 'image|mimes:jpg,jpeg,png,gif|max:8192',
                    ]);
                    $dir = public_path('uploads/popup');
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0755, true);
                    }
                    $file = $request->file('popup_image');
                    $name = 'popup-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $name);
                    ImageOptimizer::afterUpload($dir . '/' . $name, 'banner');
                    $data['popup_image'] = 'uploads/popup/' . $name;
                    $message = 'Homepage popup saved.';
                } else {
                    $message = 'Homepage popup settings saved.';
                }
                break;

            case 'most_voted_hero':
                $data['most_voted_count'] = max(1, min(20, (int) $request->input('most_voted_count', $data['most_voted_count'] ?? 1)));
                $heroDir = public_path('uploads/hero');
                if (!is_dir($heroDir)) {
                    @mkdir($heroDir, 0755, true);
                }
                foreach (['hero_image_en' => 'hero-en', 'hero_image_fr' => 'hero-fr'] as $field => $prefix) {
                    if ($request->hasFile($field)) {
                        $request->validate([
                            $field => 'image|mimes:jpg,jpeg,png|max:8192',
                        ]);
                        $file = $request->file($field);
                        $name = $prefix . '-' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move($heroDir, $name);
                        ImageOptimizer::afterUpload($heroDir . '/' . $name, 'banner');
                        $data[$field] = 'uploads/hero/' . $name;
                    }
                }
                $message = 'Most voted & hero banner saved.';
                break;

            case 'casting':
                $data['casting_title'] = $request->input('casting_title', $data['casting_title'] ?? '');
                $data['casting_subtitle'] = $request->input('casting_subtitle', $data['casting_subtitle'] ?? '');
                $data['casting_countdown'] = $request->has('casting_countdown');
                $rows = [];
                $provinces = $request->input('province', []);
                $venues = $request->input('venue', []);
                $dates = $request->input('cast_date', []);
                foreach ($provinces as $i => $province) {
                    $province = trim($province);
                    if ($province === '' && trim($venues[$i] ?? '') === '' && trim($dates[$i] ?? '') === '') {
                        continue;
                    }
                    $rows[] = [
                        'province' => $province,
                        'venue' => trim($venues[$i] ?? ''),
                        'date' => trim($dates[$i] ?? ''),
                        'enabled' => $request->has("cast_enabled.$i"),
                    ];
                }
                $data['casting_rows'] = $rows;
                $message = 'Casting calendar saved.';
                break;

            case 'primes':
                $data['primes_title'] = $request->input('primes_title', $data['primes_title'] ?? 'Finals Schedule');
                $data['primes_countdown'] = $request->has('primes_countdown');
                $primes = [];
                $labels = $request->input('prime_label', []);
                $primeDates = $request->input('prime_date', []);
                $existingImages = $request->input('prime_image_existing', []);
                $primeDir = public_path('uploads/primes');
                if (!is_dir($primeDir)) {
                    @mkdir($primeDir, 0755, true);
                }
                foreach ($labels as $i => $label) {
                    $label = trim($label);
                    $date = trim($primeDates[$i] ?? '');
                    if ($date !== '') {
                        try {
                            $date = \Carbon\Carbon::parse(str_replace('T', ' ', $date))->format('Y-m-d H:i:s');
                        } catch (\Throwable $e) {
                            // keep raw value
                        }
                    }
                    $image = trim($existingImages[$i] ?? '');
                    $file = $request->file("prime_image.$i");
                    if ($file && $file->isValid()) {
                        $request->validate([
                            "prime_image.$i" => 'image|mimes:jpg,jpeg,png,gif,webp|max:8192',
                        ]);
                        $name = 'prime-' . $i . '-' . time() . '.' . $file->getClientOriginalExtension();
                        $file->move($primeDir, $name);
                        ImageOptimizer::afterUpload($primeDir . '/' . $name, 'banner');
                        $image = 'uploads/primes/' . $name;
                    }
                    if ($label === '' && $date === '' && $image === '') {
                        continue;
                    }
                    $primes[] = [
                        'label' => $label,
                        'date' => $date,
                        'image' => $image ?: null,
                        'enabled' => $request->has("prime_enabled.$i"),
                    ];
                }
                $data['primes'] = SiteContent::sortedPrimes($primes);
                $message = 'Finals schedule saved.';
                break;

            case 'gallery':
                $gallery = [];
                $existing = (array) $request->input('gallery_existing', []);
                $captions = (array) $request->input('gallery_caption', []);
                $removed = (array) $request->input('gallery_remove', []);
                foreach ($existing as $i => $path) {
                    $path = trim((string) $path);
                    if ($path === '' || in_array((string) $i, array_map('strval', $removed), true)) {
                        continue;
                    }
                    $gallery[] = ['image' => $path, 'caption' => trim((string) ($captions[$i] ?? ''))];
                }
                $galleryDir = public_path('uploads/gallery');
                if (!is_dir($galleryDir)) {
                    @mkdir($galleryDir, 0755, true);
                }
                $files = $request->file('gallery_images', []);
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if (!$file || !$file->isValid()) {
                            continue;
                        }
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                            continue;
                        }
                        $name = 'gallery-' . time() . '-' . mt_rand(1000, 9999) . '.' . $ext;
                        $file->move($galleryDir, $name);
                        ImageOptimizer::afterUpload($galleryDir . '/' . $name, 'banner');
                        $gallery[] = ['image' => 'uploads/gallery/' . $name, 'caption' => ''];
                    }
                }
                $data['gallery'] = array_values($gallery);
                $message = 'Gallery saved.';
                break;

            case 'menu_order':
                $postedOrder = (array) $request->input('menu_order', []);
                $validKeys = array_keys(SiteContent::menuKeys());
                $order = [];
                foreach ($postedOrder as $key) {
                    if (in_array($key, $validKeys, true) && !in_array($key, $order, true)) {
                        $order[] = $key;
                    }
                }
                foreach ($validKeys as $key) {
                    if (!in_array($key, $order, true)) {
                        $order[] = $key;
                    }
                }
                if (!empty($order)) {
                    $data['menu_order'] = $order;
                }
                $message = 'Side menu order saved.';
                break;

            default:
                return redirect()->back()->with('not_permitted', 'Unknown section.');
        }

        SiteContent::save($data);

        $anchors = [
            'homepage_sections' => 'sc-homepage_sections',
            'popup' => 'sc-popup',
            'most_voted_hero' => 'sc-most_voted_hero',
            'casting' => 'sc-casting',
            'primes' => 'sc-primes',
            'gallery' => 'sc-gallery',
            'menu_order' => 'sc-menu_order',
        ];
        $fragment = isset($anchors[$section]) ? '#' . $anchors[$section] : '';

        return redirect(route('setting.site_content') . $fragment)->with('message', $message);
    }

    /** @deprecated Use siteContentStoreSection — kept so old bookmarks still work. */
    public function siteContentStore(Request $request)
    {
        return $this->siteContentStoreSection($request->merge(['section' => 'homepage_sections']));
    }

    /**
     * @deprecated Section toggles are saved via siteContentStoreSection.
     */
    public function siteContentToggle(Request $request)
    {
        $key = (string) $request->input('key');
        $enabled = filter_var($request->input('enabled'), FILTER_VALIDATE_BOOLEAN);

        if (!array_key_exists($key, SiteContent::sectionKeys())) {
            return response()->json(['ok' => false, 'message' => 'Unknown section'], 422);
        }

        $data = SiteContent::all();
        if (!isset($data['sections']) || !is_array($data['sections'])) {
            $data['sections'] = [];
        }
        $data['sections'][$key] = $enabled;
        SiteContent::save($data);

        return response()->json(['ok' => true, 'key' => $key, 'enabled' => $enabled]);
    }

    public function rewardPointSetting()
    {
        $lims_reward_point_setting_data = RewardPointSetting::latest()->first();
        return view('setting.reward_point_setting', compact('lims_reward_point_setting_data'));
    }

    public function rewardPointSettingStore(Request $request)
    {
        $data = $request->all();
        if(isset($data['is_active']))
            $data['is_active'] = true;
        else
            $data['is_active'] = false;
        RewardPointSetting::latest()->first()->update($data);
        return redirect()->back()->with('message', 'Reward point setting updated successfully');
    }

    public function backup()
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        // Database configuration
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database_name = env('DB_DATABASE');

        // Get connection object and set the charset
        $conn = mysqli_connect($host, $username, $password, $database_name);
        $conn->set_charset("utf8");


        // Get All Table Names From the Database
        $tables = array();
        $sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $sqlScript = "";
        foreach ($tables as $table) {

            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);

            $sqlScript .= "\n\n" . $row[1] . ";\n\n";


            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);

            $columnCount = mysqli_num_fields($result);

            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i ++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j ++) {
                        $row[$j] = $row[$j];

                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"';
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }

            $sqlScript .= "\n";
        }

        if(!empty($sqlScript))
        {
            // Save the SQL script to a backup file
            $backup_file_name = public_path().'/'.$database_name . '_backup_' . time() . '.sql';
            //return $backup_file_name;
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

            $zip = new ZipArchive();
            $zipFileName = $database_name . '_backup_' . time() . '.zip';
            $zip->open(public_path() . '/' . $zipFileName, ZipArchive::CREATE);
            $zip->addFile($backup_file_name, $database_name . '_backup_' . time() . '.sql');
            $zip->close();

            // Download the SQL backup file to the browser
            /*header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_file_name));
            ob_clean();
            flush();
            readfile($backup_file_name);
            exec('rm ' . $backup_file_name); */
        }
        return redirect('public/' . $zipFileName);
    }

    public function changeTheme($theme)
    {
        $lims_general_setting_data = GeneralSetting::latest()->first();
        $lims_general_setting_data->theme = $theme;
        $lims_general_setting_data->save();
    }

    public function mailSetting()
    {
        return view('setting.mail_setting');
    }

    public function mailSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $data = $request->all();
        //writting mail info in .env file
        $path = '.env';
        $searchArray = array('MAIL_HOST="'.env('MAIL_HOST').'"', 'MAIL_PORT='.env('MAIL_PORT'), 'MAIL_FROM_ADDRESS="'.env('MAIL_FROM_ADDRESS').'"', 'MAIL_FROM_NAME="'.env('MAIL_FROM_NAME').'"', 'MAIL_USERNAME="'.env('MAIL_USERNAME').'"', 'MAIL_PASSWORD="'.env('MAIL_PASSWORD').'"', 'MAIL_ENCRYPTION="'.env('MAIL_ENCRYPTION').'"');
        //return $searchArray;

        $replaceArray = array('MAIL_HOST="'.$data['mail_host'].'"', 'MAIL_PORT='.$data['port'], 'MAIL_FROM_ADDRESS="'.$data['mail_address'].'"', 'MAIL_FROM_NAME="'.$data['mail_name'].'"', 'MAIL_USERNAME="'.$data['mail_address'].'"', 'MAIL_PASSWORD="'.$data['password'].'"', 'MAIL_ENCRYPTION="'.$data['encryption'].'"');

        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function smsSetting()
    {
        return view('setting.sms_setting');
    }

    public function smsSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $data = $request->all();
        //writting bulksms info in .env file
        $path = '.env';
        if($data['gateway'] == 'twilio'){
            $searchArray = array('SMS_GATEWAY='.env('SMS_GATEWAY'), 'ACCOUNT_SID='.env('ACCOUNT_SID'), 'AUTH_TOKEN='.env('AUTH_TOKEN'), 'Twilio_Number='.env('Twilio_Number') );

            $replaceArray = array('SMS_GATEWAY='.$data['gateway'], 'ACCOUNT_SID='.$data['account_sid'], 'AUTH_TOKEN='.$data['auth_token'], 'Twilio_Number='.$data['twilio_number'] );
        }
        else{
            $searchArray = array( 'SMS_GATEWAY='.env('SMS_GATEWAY'), 'CLICKATELL_API_KEY='.env('CLICKATELL_API_KEY') );
            $replaceArray = array( 'SMS_GATEWAY='.$data['gateway'], 'CLICKATELL_API_KEY='.$data['api_key'] );
        }

        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));
        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function createSms()
    {
        $lims_customer_list = Customer::where('is_active', true)->get();
        return view('setting.create_sms', compact('lims_customer_list'));
    }

    public function sendSms(Request $request)
    {
        $data = $request->all();
        $numbers = explode(",", $data['mobile']);

        if( env('SMS_GATEWAY') == 'twilio') {
            $account_sid = env('ACCOUNT_SID');
            $auth_token = env('AUTH_TOKEN');
            $twilio_phone_number = env('TWILIO_NUMBER');
            try{
                $client = new Client($account_sid, $auth_token);
            foreach ($numbers as $number) {
                    $client->messages->create(
                        $number,
                        array(
                            "from" => $twilio_phone_number,
                            "body" => $data['message']
                        )
                    );
                }
            }
            catch(\Exception $e){
                return redirect()->back()->with('not_permitted', 'Please setup your <a href="sms_setting">SMS Setting</a> to send SMS.');
            }
            $message = "SMS sent successfully";
        }
        elseif( env('SMS_GATEWAY') == 'clickatell') {
            try {
                $clickatell = new \Clickatell\Rest(env('CLICKATELL_API_KEY'));
                foreach ($numbers as $number) {
                    $result = $clickatell->sendMessage(['to' => [$number], 'content' => $data['message']]);
                }
            }
            catch (ClickatellException $e) {
                return redirect()->back()->with('not_permitted', 'Please setup your <a href="sms_setting">SMS Setting</a> to send SMS.');
            }
            $message = "SMS sent successfully";
        }
        else
            return redirect()->back()->with('not_permitted', 'Please setup your <a href="sms_setting">SMS Setting</a> to send SMS.');
        return redirect()->back()->with('message', $message);
    }

    public function hrmSetting()
    {
        $lims_hrm_setting_data = HrmSetting::latest()->first();
        return view('setting.hrm_setting', compact('lims_hrm_setting_data'));
    }

    public function hrmSettingStore(Request $request)
    {
        $data = $request->all();
        $lims_hrm_setting_data = HrmSetting::firstOrNew(['id' => 1]);
        $lims_hrm_setting_data->checkin = $data['checkin'];
        $lims_hrm_setting_data->checkout = $data['checkout'];
        $lims_hrm_setting_data->save();
        return redirect()->back()->with('message', 'Data updated successfully');

    }
    public function posSetting()
    {
    	$lims_customer_list = Customer::where('is_active', true)->get();
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        $lims_biller_list = Biller::where('is_active', true)->get();
        $lims_pos_setting_data = PosSetting::latest()->first();
        $lims_account_all = Account::where('is_active', true)->get();
        $lims_account_default = Account::where('is_default', true)->first();
        $lims_account_default_debit = Account::where('is_default_debit', true)->first();

    	return view('setting.pos_setting', compact('lims_customer_list', 'lims_warehouse_list', 'lims_biller_list', 'lims_pos_setting_data', 'lims_account_all', 'lims_account_default', 'lims_account_default_debit'));
    }

    public function posSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

    	$data = $request->all();
        //writting paypal info in .env file
        $path = '.env';
        $searchArray = array('PAYPAL_LIVE_API_USERNAME='.env('PAYPAL_LIVE_API_USERNAME'), 'PAYPAL_LIVE_API_PASSWORD='.env('PAYPAL_LIVE_API_PASSWORD'), 'PAYPAL_LIVE_API_SECRET='.env('PAYPAL_LIVE_API_SECRET') );

        $replaceArray = array('PAYPAL_LIVE_API_USERNAME='.$data['paypal_username'], 'PAYPAL_LIVE_API_PASSWORD='.$data['paypal_password'], 'PAYPAL_LIVE_API_SECRET='.$data['paypal_signature'] );

        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

    	$pos_setting = PosSetting::firstOrNew(['id' => 1]);
    	$pos_setting->id = 1;
    	$pos_setting->customer_id = $data['customer_id'];
    	$pos_setting->warehouse_id = $data['warehouse_id'];
    	$pos_setting->biller_id = $data['biller_id'];
    	$pos_setting->product_number = $data['product_number'];
    	$pos_setting->stripe_public_key = $data['stripe_public_key'];
    	$pos_setting->stripe_secret_key = $data['stripe_secret_key'];

        $lims_account_data = Account::where('is_default', true)->first();
        $lims_account_data->is_default = false;
        $lims_account_data->save();

        $lims_account_data = Account::find($data['account_id']);
        $lims_account_data->is_default = true;
        $lims_account_data->save();

        $lims_account_data = Account::where('is_default_debit', true)->first();
        $lims_account_data->is_default_debit = false;
        $lims_account_data->save();

        $lims_account_data = Account::find($data['debit_account_id']);
        $lims_account_data->is_default_debit = true;
        $lims_account_data->save();

        if(!isset($data['keybord_active']))
            $pos_setting->keybord_active = false;
        else
            $pos_setting->keybord_active = true;
    	$pos_setting->save();
    	return redirect()->back()->with('message', 'POS setting updated successfully');
    }

    /** Read a checkbox value (hidden 0 + optional checked 1). has() is wrong — hidden fields always present. */
    private static function checkboxInt(Request $request, $key)
    {
        $value = $request->input($key, 0);
        if (is_array($value)) {
            $value = end($value);
        }
        return (int) $value === 1 ? 1 : 0;
    }

    public function envSetting()
    {
        if (!config('app.allow_env_editor', false) || (int) Auth::user()->role_id !== 1) {
            return redirect()->back()->with('not_permitted', 'Environment editor is disabled.');
        }

        if (!env('USER_VERIFIED')) {
            return redirect()->back()->with('not_permitted', 'This feature is disabled for demo!');
        }

        $envPath = base_path('.env');
        $contents = file_exists($envPath) ? file_get_contents($envPath) : '';

        return view('setting.env_setting', compact('contents'));
    }

    public function envSettingStore(Request $request)
    {
        if (!config('app.allow_env_editor', false) || (int) Auth::user()->role_id !== 1) {
            return redirect()->back()->with('not_permitted', 'Environment editor is disabled.');
        }

        if (!env('USER_VERIFIED')) {
            return redirect()->back()->with('not_permitted', 'This feature is disabled for demo!');
        }

        $request->validate([
            'env_contents' => 'required|string|max:500000',
        ]);

        file_put_contents(base_path('.env'), $request->env_contents);
        \Artisan::call('config:clear');

        return redirect()->route('setting.env')->with('message', trans('file.Environment file updated successfully'));
    }
}
