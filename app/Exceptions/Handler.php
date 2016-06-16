<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Swift_TransportException;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function report(Exception $e) {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        switch ($e) {

            case $e instanceof ModelNotFoundException:
                return $this->renderException($e);
            default:
                return $this->common($request, $e);
        }
    }

    protected function renderException($e) {
        switch ($e) {

            case $e instanceof ModelNotFoundException:
                return redirect('/')->with('fails', 'Please configure ' . $e->getMessage());
                break;

            default:
                return (new SymfonyDisplayer(config('app.debug')))
                                ->createResponse($e);
        }
    }

    /**
     * Function to render 500 error page
     * @param type $request
     * @param type $e
     * @return type mixed
     */
    public function render500($request, $e) {
        
        //$this->mail($request, $e);
        
        if (config('app.debug') == true) {
            return parent::render($request, $e);
        }
        return view('errors.500');
    }

    /**
     * Function to render 404 error page
     * @param type $request
     * @param type $e
     * @return type mixed
     */
    public function render404($request, $e) {

        if (config('app.debug') == false) {
            return parent::render($request, $e);
        }
    }

    /**
     * Common finction to render both types of codes
     * @param type $request
     * @param type $e
     * @return type mixed
     */
    public function common($request, $e) {
        switch ($e) {
            case $e instanceof HttpException :
                return $this->render404($request, $e);
            case $e instanceof NotFoundHttpException :
                return $this->render404($request, $e);
            default :
                return $this->render500($request, $e);
        }
        return parent::render($request, $e);
    }

    public function mail($request, $e) {
        
        $setting_controller = new \App\Http\Controllers\Common\TemplateController();
        $mail = $setting_controller->smtp();
        return $this->mailReport($request, $e);
    }

    public function mailReport($request, $e) {
        
        $set = new \App\Model\Common\Setting();
        $setting = $set->find(1);
        //$subject = 'Agora Invoicing Error';
        
        if ($setting->error_log == 1 && $setting->error_email!='') {
            
            \Mail::send('errors.report', ['e' => $e], function($m) use ($setting) {
                $m->from($setting->email, $setting->company);

                $m->to($setting->error_email, 'Agora Error')->subject('Agora Invoicing Error');
            });
        }
        return 'success';
    }

}
