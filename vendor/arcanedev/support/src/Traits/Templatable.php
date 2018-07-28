<?php namespace Arcanedev\Support\Traits;

/**
 * Trait     Templatable
 *
 * @package  Arcanedev\Support\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  array  $data
 */
trait Templatable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The view template - master, layout (... whatever).
     *
     * @var string
     */
    protected $template = '_templates.default.master';

    /**
     * The layout view.
     *
     * @var \Illuminate\View\View
     */
    private $layout;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get template.
     *
     * @return string
     */
    protected function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set template.
     *
     * @param  string  $template
     *
     * @return self
     */
    protected function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Display the view.
     *
     * @param  string  $name
     * @param  array   $data
     *
     * @return \Illuminate\View\View
     */
    protected function view($name, array $data = [])
    {
        $this->viewExistsOrFail($name);

        $this->data = array_merge($this->data, $data);

        $this->beforeViewRender();
        $view = $this->renderView($name);
        $this->afterViewRender();

        return $view;
    }

    /**
     * Do random stuff before rendering view.
     */
    protected function beforeViewRender()
    {
        //
    }

    /**
     * Render the view.
     *
     * @param  string  $name
     *
     * @return \Illuminate\View\View
     */
    private function renderView($name)
    {
        return $this->layout
            ->with($this->data)
            ->nest('content', $name, $this->data);
    }

    /**
     * Do random stuff before rendering view.
     */
    protected function afterViewRender()
    {
        //
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if view exists.
     *
     * @param  string  $view
     *
     * @return bool
     */
    protected function isViewExists($view)
    {
        /** @var \Illuminate\View\Factory $viewFactory */
        $viewFactory = view();

        return $viewFactory->exists($view);
    }

    /**
     * Check if view exists or fail.
     *
     * @param  string  $view
     * @param  string  $message
     */
    protected function viewExistsOrFail($view, $message = 'The view [:view] not found')
    {
        if ( ! $this->isViewExists($view)) {
            abort(500, str_replace(':view', $view, $message));
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->setupLayout();

        return parent::callAction($method, $parameters);
    }

    /**
     * Setup the template/layout.
     */
    protected function setupLayout()
    {
        if (is_null($this->template)) {
            abort(500, 'The layout is not set');
        }

        $this->viewExistsOrFail(
            $this->template,
            "The layout [$this->template] not found"
        );

        $this->layout = view($this->template);
    }
}
