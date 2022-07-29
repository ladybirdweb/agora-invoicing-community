<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;

class Leads extends Resource
{
    /**
     * Disabled abstract methods.
     *
     * @var array
     */
    protected $disabled = ['deleteBulk'];

    protected $addPostedAsJson = true;

    /**
     * Get all labels.
     *
     * @return Response
     */
    public function labels()
    {
        $this->request->setResource('leadLabels');

        return $this->request->get('');
    }

    /**
     * Add a label.
     *
     * @return Response
     */
    public function addLabel(array $values = [])
    {
        $this->request->setResource('leadLabels');

        $values['json'] = true;

        return $this->request->post('', $values);
    }


    /**
     * Delete a label.
     *
     * @return Response
     */
    public function deleteLabel($id)
    {
        $this->request->setResource('leadLabels');

        return $this->request->delete('' . $id);
    }

    /**
     * @param $id
     * @param array $values
     * @return Response
     */
    public function update($id, array $values = [])
    {
        $this->request->setResource('leadLabels');

        return $this->request->put('/' . $id, $values);
    }

    /**
     * Get all sources.
     *
     * @return Response
     */
    public function sources()
    {
        $this->request->setResource('leadSources');

        return $this->request->get('');
    }
}
