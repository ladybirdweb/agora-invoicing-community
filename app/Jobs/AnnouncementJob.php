<?php

namespace App\Jobs;

use App\Http\Controllers\Common\CronController;
use App\Model\MailJob\QueueService;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $message;
    protected $message_type;
    protected $version;
    protected $product;
    protected $from;
    protected $till;
    protected $reappear;
    protected $condition;

    public function __construct($message, $message_type, $version, $product, $from = '', $till = '', $reappear = '', $condition = '')
    {
        $this->setDriver();
        $this->message = $message;
        $this->message_type = $message_type;
        $this->version = $version;
        $this->product = $product;
        $this->from = $from;
        $this->till = $till;
        $this->reappear = $reappear;
        $this->condition = $condition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //ini_set so that we can ping and curl many urls
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '-1');
            set_time_limit(0);
            //Gets the product based on the filter that has been passed from the front end.
            $products = (in_array('Every Product available',$this->product))?
                Product::all()->pluck('id')->toArray():
                Product::whereIn('name',$this->product)->pluck('id')->toArray();
            //Gets the order_id of the product based on versions expiry
            $subscriptions=Subscription::whereIn('product_id',$products)
                ->when($this->version,function($query){
                      (in_array('Every version available',$this->version))?:
                          $query->whereIn('version',$this->version);
                })->when($this->from || $this->till, function ($query){
                    $from = empty($this->from)?Carbon::nowWithSameTz():Carbon::parse($this->from);
                    $to = empty($this->till)?Carbon::parse('01/01/5050'):Carbon::parse($this->till);
                    $query->whereBetween('update_ends_at', [$from, $to])
                          ->orWhereBetween('support_ends_at',[$from,$to])
                          ->orWhereBetween('ends_at',[$from,$to]);
                })->pluck('order_id')->toArray();
            //Gets the Installation path based on the filtered order_id
            $urls=InstallationDetail::whereIn('order_id',$subscriptions)
                ->pluck('installation_path')->toArray();
           //Pushes the messages to the product closeable and non-closeable
            foreach ($urls as $url) {
                $this->aplCustomPost('https://'.$url.'/api/message', "message=$this->message&condition=$this->condition&reappear=$this->reappear");
            }
        }
        Catch(\Exception $e){
            //ignore if there are exceptions, because of one client the others should not be stopped.
        }
    }

    //make post requests with cookies and referrers, return array with server headers, errors, and body content
    private function aplCustomPost($url, $post_info = '', $refer = '')
    {
        $user_agent = 'phpmillion cURL';
        $connect_timeout = 10;
        $server_response_array = [];
        $formatted_headers_array = [];

        if (filter_var($url, FILTER_VALIDATE_URL) && ! empty($post_info)) {
            if (empty($refer) || ! filter_var($refer, FILTER_VALIDATE_URL)) { //use original URL as refer when no valid refer URL provided
                $refer = $url;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $connect_timeout);
            curl_setopt($ch, CURLOPT_REFERER, $refer);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$formatted_headers_array) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) { //ignore invalid headers
                        return $len;
                    }

                    $name = strtolower(trim($header[0]));
                    $formatted_headers_array[$name] = trim($header[1]);

                    return $len;
                }
            );

            $result = curl_exec($ch);
            $curl_error = curl_error($ch); //returns a human readable error (if any)
            curl_close($ch);

            $server_response_array['headers'] = $formatted_headers_array;
            $server_response_array['error'] = $curl_error;
            $server_response_array['body'] = $result;
        }

        return $server_response_array;
    }

    private function setDriver()
    {
        $queue_driver = 'sync';
        if ($driver = QueueService::where('status', 1)->first()) {
            $queue_driver = $driver->short_name;
        }
        app('queue')->setDefaultDriver($queue_driver);
    }
}
