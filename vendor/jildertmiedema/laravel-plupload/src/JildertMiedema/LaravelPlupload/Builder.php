<?php

namespace JildertMiedema\LaravelPlupload;

class Builder
{
    private $settings;
    private $prefix;

    public function createJsInit()
    {
        return sprintf('var %s_uploader = new plupload.Uploader(%s);', $this->prefix, json_encode($this->getSettings()));
    }

    public function createJsRun()
    {
        $script = sprintf('%s_uploader.init();', $this->prefix);
        $script .= sprintf('document.getElementById(\'%s-start-upload\').onclick = function() {%s_uploader.start();};', $this->prefix, $this->prefix);

        return $script;
    }

    public function addScripts()
    {
        $scriptUrl = '/vendor/jildertmiedema/laravel-plupload/js/plupload.full.min.js';

        return sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl);
    }

    public function getContainer()
    {
        $prefix = $this->prefix;
        $html = "<div id=\"{$prefix}-container\">";
        $html .= "<button type=\"button\" id=\"{$prefix}-browse-button\" class=\"btn btn-primary\">Browse...</button>";
        $html .= "<button type=\"button\" id=\"{$prefix}-start-upload\" class=\"btn btn-success\">Upload</button>";
        $html .= '</div>';

        return $html;
    }

    public function createHtml()
    {
        $html = '';
        $html .= $this->getContainer();
        $html .= $this->addScripts();
        $html .= '<script type="text/javascript">';
        $html .= $this->createJsInit();
        $html .= $this->createJsRun();
        $html .= '</script>';

        return $html;
    }

    public function getDefaultSettings()
    {
        $settings = [];
        $settings['runtimes'] = 'html5';
        $settings['browse_button'] = $this->prefix.'-browse-button';
        $settings['container'] = $this->prefix.'-container';
        $settings['url'] = '/upload';
        $settings['headers'] = [
            'Accept' => 'application/json',
            'X-CSRF-TOKEN' => csrf_token(),
        ];

        return $settings;
    }

    public function setDefaults()
    {
        $this->updateSettings($this->getDefaultSettings());
    }

    public function getSettings()
    {
        $settings = $this->getDefaultSettings();

        $this->settings = $this->settings ?: [];

        foreach ($this->settings as $name => $value) {
            $settings[$name] = $value;
        }

        return $settings;
    }

    public function updateSettings(array $settings)
    {
        foreach ($settings as $name => $value) {
            $this->settings[$name] = $value;
        }
    }

    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    public function withPrefix($value)
    {
        $this->setPrefix($value);

        return $this;
    }

    public static function make(array $settings = null)
    {
        $instance = static::init($settings);

        return $instance->createHtml();
    }

    public static function init(array $settings = null)
    {
        $instance = new static();

        if ($settings) {
            $instance->updateSettings($settings);
        }

        return $instance;
    }
}
