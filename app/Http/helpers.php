<?php

use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Order\InstallationDetail;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\TaxByState;
use App\Model\Product\ProductUpload;
use App\Traits\TaxCalculation;
use App\User;
use Carbon\Carbon;

function getLocation()
{
    try {
        $location = \GeoIP::getLocation();

        return $location;
    } catch (Exception $ex) {
        app('log')->error($ex->getMessage());
        $location = \Config::get('geoip.default_location');

        return $location;
    }
}

function checkArray($key, $array)
{
    $value = '';
    if (is_array($array) && array_key_exists($key, $array)) {
        $value = $array[$key];
    }

    return $value;
}

function mime($type)
{
    if ($type == 'jpg' ||
            $type == 'png' ||
            $type == 'jpeg' ||
            $type == 'gif' ||
            starts_with($type, 'image')) {
        return 'image';
    }
}

function isInstall()
{
    $check = false;
    $env = base_path('.env');
    if (\File::exists($env) && env('DB_INSTALL') == 1) {
        $check = true;
    }

    return $check;
}

// For API response
/**
 * Format the error message into json error response.
 *
 * @param string|array $message    Error message
 * @param int          $statusCode
 *
 * @return HTTP json response
 */
function errorResponse($message, $statusCode = 400)
{
    return response()->json(['success' => false, 'message' => $message], $statusCode);
}

/**
 * Format success message/data into json success response.
 *
 * @param string       $message    Success message
 * @param array|string $data       Data of the response
 * @param int          $statusCode
 *
 * @return HTTP json response
 */
function successResponse($message = '', $data = '', $statusCode = 200)
{
    $response = ['success' => true];

    // if message given
    if (! empty($message)) {
        $response['message'] = $message;
    }

    // If data given
    if (! empty($data)) {
        $response['data'] = $data;
    }

    return response()->json($response, $statusCode);
}

/**
 * Gets time in logged in user's timezone.
 * @param string $dateTimeString
 * @param string $format
 * @return string
 */
function getTimeInLoggedInUserTimeZone(string $dateTimeString, $format = 'M j, Y, g:i a')
{
    // caching for 4 seconds so for consecutive queries, it will be readily available. And even if someone updates their
    // timezone, it will start showing the new timezone after 4 seconds
    $timezone = Cache::remember('timezone_'.Auth::user()->id, 5, function () {
        return Auth::user()->timezone->name;
    });

    return ((new DateTime($dateTimeString))->setTimezone(new DateTimeZone($timezone)))->format($format);
}

/**
 * Gets date in a formatted HTML.
 * @param string|null $dateTimeString
 * @return string
 */
function getDateHtml(string $dateTimeString = null)
{
    try {
        if (! $dateTimeString) {
            return '--';
        }

        $date = getTimeInLoggedInUserTimeZone($dateTimeString, 'M j, Y');
        $dateTime = getTimeInLoggedInUserTimeZone($dateTimeString);

        return "<label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='".$dateTime."'>".$date.'</label>';
    } catch (Exception $e) {
        return '--';
    }
}

function getExpiryLabel($expiryDate, $badge = 'badge')
{
    if ($expiryDate < (new Carbon())->toDateTimeString()) {
        return getDateHtml($expiryDate).'&nbsp;<span class="'.$badge.' '.$badge.'-danger"  <label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Order has Expired">

                         </label>
            Expired</span>';
    } else {
        return getDateHtml($expiryDate);
    }
}

function getVersionAndLabel($productVersion, $productId, $badge = 'label')
{
    $latestVersion = \Cache::remember('latest_'.$productId, 10, function () use ($productId) {
        return ProductUpload::where('product_id', $productId)->latest()->value('version');
    });
    if ($productVersion) {
        if ($productVersion < $latestVersion) {
            return '<span class='.'"'.$badge.' '.$badge.'-warning" <label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Outdated Version">
                 </label>'.$productVersion.'</span>';
        } else {
            return '<span class='.'"'.$badge.' '.$badge.'-success" <label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title="Latest Version">
                 </label>'.$productVersion.'</span>';
        }
    }
}

function getInstallationDetail($ip)
{
    $ipAndDomain = explode(',', $ip);

    return InstallationDetail::where('installation_path', 'like', '%'.$ipAndDomain[0].'%')
    ->where('installation_ip', $ipAndDomain[1])->first();
}

function tooltip($tootipText = '')
{
    return '<label data-toggle="tooltip" style="font-weight:500;" data-placement="top" title='.$tootipText.'>
             </label>';
}

function getStatusLabel($status, $badge = 'badge')
{
    switch ($status) {
        case 'Success':
            return '<span class='.'"'.$badge.' '.$badge.'-success">Paid</span>';

            case 'Pending':
            return '<span class='.'"'.$badge.' '.$badge.'-danger">Unpaid</span>';

            case 'renewed':
            return '<span class='.'"'.$badge.' '.$badge.'-primary">Renewed</span>';

            default:
            return '<span class='.'"'.$badge.' '.$badge.'-warning">Partially paid</span>';
    }
}

function getCountryByCode($code)
{
    try {
        $country = \App\Model\Common\Country::where('country_code_char2', $code)->first();
        if ($country) {
            return $country->nicename;
        }
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function findCountryByGeoip($iso)
{
    try {
        $country = \App\Model\Common\Country::where('country_code_char2', $iso)->first();
        if ($country) {
            return $country->country_code_char2;
        } else {
            return '';
        }
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function findStateByRegionId($iso)
{
    try {
        $states = \App\Model\Common\State::where('country_code_char2', $iso)
        ->pluck('state_subdivision_name', 'state_subdivision_code')->toArray();

        return $states;
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function getTimezoneByName($name)
{
    try {
        $timezone = \App\Model\Common\Timezone::where('name', $name)->first();
        if ($timezone) {
            $timezone = $timezone->id;
        } else {
            $timezone = '114';
        }

        return $timezone;
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function checkPlanSession()
{
    try {
        if (Session::has('plan')) {
            return true;
        }

        return false;
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function getStateByCode($code)
{
    try {
        $result = ['id' => '', 'name' => ''];

        $subregion = \App\Model\Common\State::where('state_subdivision_code', $code)->first();
        if ($subregion) {
            $result = ['id' => $subregion->state_subdivision_code,
                'name'         => $subregion->state_subdivision_name, ];
        }

        return $result;
    } catch (\Exception $ex) {
        throw new \Exception($ex->getMessage());
    }
}

function userCurrencyAndPrice($userid = '', $plan, $productid = '')
{
    try {
        if (! \Auth::user()) {//When user is not logged in
            $location = getLocation();
            $country = findCountryByGeoip($location['iso_code']);
            $currencyAndSymbol = getCurrencySymbolAndPriceForPlans($country, $plan);
        }
        if (\Auth::user() && ! $userid) {
            $country = \Auth::user()->country;
            $currencyAndSymbol = getCurrencySymbolAndPriceForPlans($country, $plan);
        }

        if ($userid) {//For Admin Panel Clients
            $userCountry = User::where('id', $userid)->first()->country;
            $currencyAndSymbol = getCurrencySymbolAndPriceForPlans($userCountry, $plan);
        }
        $currency = $currencyAndSymbol['currency'];
        $symbol = $currencyAndSymbol['currency_symbol'];
        $plan = $currencyAndSymbol['userPlan'];

        return ['currency'=>$currency, 'symbol'=>$symbol, 'plan'=>$plan];
    } catch (\Exception $ex) {
        return redirect()->back()->with('fails', $ex->getMessage());
    }
}

/**
 * Fetches currency and price for a plan. If the country code sent has a price defined for them in a plan then
 * that price will be displayed in the respective currency of that country else the default price for that plan will be displayed along with the default currency.
 *
 * @param  string $countryCode Code of the country
 * @param  obj    $plan   Plan for which price is to be fetched
 * @return array          Currency, symbol and plan details
 */
function getCurrencySymbolAndPriceForPlans($countryCode, $plan)
{
    $country = Country::where('country_code_char2', $countryCode)->first();
    $userPlan = $plan->planPrice->where('country_id', $country->country_id)->first() ?: $plan->planPrice->where('country_id', 0)->first();
    $currency = $userPlan->currency;
    $currency_symbol = Currency::where('code', $currency)->value('symbol');

    return compact('currency', 'currency_symbol', 'userPlan');
}

/**
 * Get client currency on the basis of country. This is applicable when client logs in to detect his currency.
 * @param  string $countryCode The country code('IN','US')
 * @return string              The currency code('INR','USD')
 */
function getCurrencyForClient($countryCode)
{
    $defaultCurrency = Setting::find(1)->default_currency;
    $country = Country::where('country_code_char2', $countryCode)->first();
    $currencyStatus = $country->currency->status;
    if ($currencyStatus) {
        $currency = Currency::where('id', $country->currency_id)->first();

        return $currency->code;
    }

    return $defaultCurrency;
}

function currencyFormat($amount = null, $currency = null, $include_symbol = true)
{
    $amount = rounding($amount);
    if ($currency == 'INR') {
        $symbol = getIndianCurrencySymbol($currency);

        return $symbol.getIndianCurrencyFormat($amount);
    }

    return app('currency')->format($amount, $currency, $include_symbol);
}

function rounding($price)
{
    try {
        $tax_rule = new \App\Model\Payment\TaxOption();
        $rule = $tax_rule->findOrFail(1);
        $rounding = $rule->rounding;
        if ($rounding) {
            return round($price);
        } else {
            return round($price, 2);
        }
    } catch (\Exception $ex) {
    }
}

function userCountryId()
{
    if (\Auth::check()) {
        $country = Country::where('country_code_char2', \Auth::user()->country)->first()->country_id;
    } else {
        $location = getLocation();
        $country = Country::where('country_code_char2', $location['iso_code'])->first()->country_id;
    }

    return $country;
}

function getIndianCurrencySymbol($currency)
{
    return \DB::table('format_currencies')->where('code', $currency)->value('symbol');
}

function getIndianCurrencyFormat($number)
{
    $explrestunits = '';
    $number = explode('.', $number);
    $num = $number[0];
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
      $restunits = (strlen($restunits) % 2 == 1) ? '0'.$restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
      $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i].','; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].',';
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    if (! empty($number[1])) {
        if (strlen($number[1]) == 1) {
            return $thecash.'.'.$number[1].'0';
        } elseif (strlen($number[1]) == 2) {
            return $thecash.'.'.$number[1];
        } else {
            return 'cannot handle decimal values more than two digits...';
        }
    } else {
        return $thecash;
    }
}

function bifurcateTax($taxName, $taxValue, $currency, $state, $price = '')
{
    if (\Auth::user()->country == 'IN') {
        $gst = TaxByState::where('state_code', $state)->select('c_gst', 's_gst', 'ut_gst')->first();
        if ($taxName == 'CGST+SGST') {
            $html = 'CGST@'.$gst->c_gst.'%<br>SGST@'.$gst->s_gst.'%';

            $cgst_value = currencyFormat(TaxCalculation::taxValue($gst->c_gst, $price), $currency);

            $sgst_value = currencyFormat(TaxCalculation::taxValue($gst->s_gst, $price), $currency);

            return ['html'=>$html, 'tax'=>$cgst_value.'<br>'.$sgst_value];
        } elseif ($taxName == 'CGST+UTGST') {
            $html = 'CGST@'.$gst->c_gst.'%<br>UTGST@'.$gst->ut_gst.'%';

            $cgst_value = currencyFormat(TaxCalculation::taxValue($gst->c_gst, $price), $currency);
            $utgst_value = currencyFormat(TaxCalculation::taxValue($gst->ut_gst, $price), $currency);

            return ['html'=>$html, 'tax'=>$cgst_value.'<br>'.$utgst_value];
        } else {
            $html = $taxName.'@'.$taxValue;
            $tax_value = currencyFormat(TaxCalculation::taxValue($taxValue, $price), $currency);

            return ['html'=>$html, 'tax'=>$tax_value];
        }
    } else {
        $html = $taxName.'@'.$taxValue;
        $tax_value = currencyFormat(TaxCalculation::taxValue($taxValue, $price), $currency);

        return ['html'=>$html, 'tax'=>$tax_value];
    }
}

/**
 * sets mail config and reloads the config into the container
 * NOTE: this is getting used outside the class to set service config.
 * @return void
 */
function setServiceConfig($emailConfig)
{
    $sendingProtocol = $emailConfig->driver;
    if ($sendingProtocol && $sendingProtocol != 'smtp' && $sendingProtocol != 'mail') {
        $services = \Config::get("services.$sendingProtocol");
        $dynamicServiceConfig = [];

        //loop over it and assign according to the keys given by user
        foreach ($services as $key => $value) {
            $dynamicServiceConfig[$key] = isset($emailConfig[$key]) ? $emailConfig[$key] : $value;
        }

        //setting that service configuration
        \Config::set("services.$sendingProtocol", $dynamicServiceConfig);
    } else {
        \Config::set('mail.host', $emailConfig['host']);
        \Config::set('mail.port', $emailConfig['port']);
        \Config::set('mail.password', $emailConfig['password']);
        \Config::set('mail.security', $emailConfig['encryption']);
    }

    //setting mail driver as $sending protocol
    \Config::set('mail.driver', $sendingProtocol);
    \Config::set('mail.from.address', $emailConfig['email']);
    \Config::set('mail.from.name', $emailConfig['company']);
    \Config::set('mail.username', $emailConfig['email']);

    //setting the config again in the service container
    (new \Illuminate\Mail\MailServiceProvider(app()))->register();
}

function persistentCache($key, Closure $closure, $noOfSeconds = 30, array $variables = [])
{
    $keySalt = json_encode($variables);

    return Cache::remember($key.$keySalt, $noOfSeconds, $closure);
}

function emailSendingStatus()
{
    $status = false;
    if (Setting::value('sending_status')) {
        $status = true;
    }

    return $status;
}

function installationStatusLabel(string $lastConnectionDate, string $createdAt)
{
    return $lastConnectionDate > (new Carbon('-30 days'))->toDateTimeString() && $lastConnectionDate != $createdAt ? "&nbsp;<span class='badge badge-primary' style='background-color:darkcyan !important;' <label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='Installation is Active'>
                     </label>Active</span>" : "&nbsp;<span class='badge badge-info' <label data-toggle='tooltip' style='font-weight:500;background-color:crimson;' data-placement='top' title='Installation inactive for more than 30 days'>
                    </label>Inactive</span>";
}

//return root url from long url (http://www.domain.com/path/file.php?aa=xx becomes http://www.domain.com/path/), remove scheme, www. and last slash if needed
function getRootUrl($url, $remove_scheme, $remove_www, $remove_path, $remove_last_slash)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $url_array = parse_url($url); //parse URL into arrays like $url_array['scheme'], $url_array['host'], etc

        $url = str_ireplace($url_array['scheme'].'://', '', $url); //make URL without scheme, so no :// is included when searching for first or last /

        if ($remove_path == 1) { //remove everything after FIRST / in URL, so it becomes "real" root URL
        $first_slash_position = stripos($url, '/'); //find FIRST slash - the end of root URL
        if ($first_slash_position > 0) { //cut URL up to FIRST slash
            $url = substr($url, 0, $first_slash_position + 1);
        }
        } else { //remove everything after LAST / in URL, so it becomes "normal" root URL
        $last_slash_position = strripos($url, '/'); //find LAST slash - the end of root URL
        if ($last_slash_position > 0) { //cut URL up to LAST slash
            $url = substr($url, 0, $last_slash_position + 1);
        }
        }

        if ($remove_scheme != 1) { //scheme was already removed, add it again
            $url = $url_array['scheme'].'://'.$url;
        }

        if ($remove_www == 1) { //remove www.
            $url = str_ireplace('www.', '', $url);
        }

        if ($remove_last_slash == 1) { //remove / from the end of URL if it exists
        while (substr($url, -1) == '/') { //use cycle in case URL already contained multiple // at the end
            $url = substr($url, 0, -1);
        }
        }
    }

    return trim($url);
}
