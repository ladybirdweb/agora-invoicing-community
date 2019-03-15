<?php

namespace Devio\Pipedrive\Resources\Traits;

use Devio\Pipedrive\Http\Response;

trait ListsUpdates
{
    /**
     * Get the resource updates.
     *
     * @param       $id
     * @param array $options
     * @return Response
     */
    public function updates($id, $options = [])
    {
        array_set($options, 'id', $id);

        return $this->request->get(':id/flow', $options);
    }
}
