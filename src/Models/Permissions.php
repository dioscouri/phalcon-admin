<?php
namespace Dsc\Admin\Models;

/**
 * Permissions
 * Stores the permissions by profile
 */
class Permissions extends \Phalcon\Mvc\Collection
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $profilesId;

    /**
     *
     * @var string
     */
    public $resource;

    /**
     *
     * @var string
     */
    public $action;

    public function initialize()
    {
        $this->belongsTo('profilesId', 'Dsc\Admin\Models\Profiles', 'id', array(
            'alias' => 'profile'
        ));
    }
}
