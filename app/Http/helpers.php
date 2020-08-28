<?php

use App\Model\Product\ProductUpload;
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

        return "<label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='$dateTime'>$date</label>";
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

function currencyFormat($amount = null, $currency = null, $include_symbol = true)
{
    if ($currency == 'INR') {
        return '₹'.getIndianCurrencyFormat($amount);
    }

    return app('currency')->format($amount, $currency, $include_symbol);
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
