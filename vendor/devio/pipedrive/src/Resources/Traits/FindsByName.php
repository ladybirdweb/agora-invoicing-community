<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait FindsByName
{
    /**
     * Find an element by name.
     *
     * @param       $term
     * @param array $options
	 * @return Response
     */
    public function findByName($term, $options = [])
    {
        $options['term'] = $term;

        return $this->request->get('find', $options);
    }
}
