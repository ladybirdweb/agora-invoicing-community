<?php

namespace Devio\Pipedrive\Resources;

use Devio\Pipedrive\Http\Response;
use Devio\Pipedrive\Resources\Basics\Resource;
use Devio\Pipedrive\Resources\Traits\HandlesAssignments;

class Roles extends Resource
{
    use HandlesAssignments;

    /**
     * Disabled abstract methods.
     *
     * @var array
     */
    protected $disabled = ['deleteBulk'];

    /**
     * List the role subroles.
     *
     * @param       $id
     * @param array $options
     * @return Response
     */
    public function subRoles($id, $options = [])
    {
        array_set($options, 'id', $id);

        return $this->request->get(':id/roles', $options);
    }

    /**
     * List the role settings.
     *
     * @param $id
     * @return Response
     */
    public function settings($id)
    {
        return $this->request->get(':id/settings', compact('id'));
    }

    /**
     * Add or update a setting value.
     *
     * @param $id
     * @param $setting_key
     * @param $value
     * @return Response
     */
    public function setSetting($id, $setting_key, $value)
    {
        return $this->request->post(
            ':id/settings',
            compact('id', 'setting_key', 'value')
        );
    }
}
