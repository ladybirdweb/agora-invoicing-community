<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
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
    public function report(Exception $e)
    {
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
    public function render($request, Exception $e)
    {
        switch ($e) {

            case $e instanceof ModelNotFoundException:

                return $this->renderException($e);
                break;
            
            case $e instanceof HttpException:
                dd($e);
                return $this->render404();
                break;
            
            default:
                return $this->render500($request, $e);
                break;
        }
    }

    protected function renderException($e)
    {
        switch ($e) {

            case $e instanceof ModelNotFoundException:
                return redirect('/')->with('fails', 'Please configure '.$e->getMessage());
                break;

            default:
                return (new SymfonyDisplayer(config('app.debug')))
                                ->createResponse($e);
        }
    }
    
    protected function render404(){
        return response()->view('errors.404');
    }
    
    protected function render500($request, $e){
        //\Config::get('app.debug')
        //dd($e);
        //if(\Config::get('app.debug')==true){
            return parent::render($request, $e);
        //}
        //return response()->view('errors.500');
    }
}
