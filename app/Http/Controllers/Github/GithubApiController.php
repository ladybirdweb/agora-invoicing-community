<?php

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Model\Github\Github;

class GithubApiController extends Controller
{
    private $username;
    private $password;
    private $github;

    public function __construct()
    {
        $model = new Github();
        $this->github = $model->firstOrFail();

        $this->username = $this->github->username;
        $this->password = $this->github->password;
    }

    public function postCurl($url, $data = '', $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent:$this->username"]);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        $content = curl_exec($ch);
        curl_close($ch);

        return json_decode($content, true);
    }

    public function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent:$this->username"]);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        $content = curl_exec($ch);
        curl_close($ch);

        return json_decode($content, true);
    }
}
