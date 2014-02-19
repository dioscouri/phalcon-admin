<?php
namespace Dsc\Admin\Models;

/**
 * Dsc\Admin\Models\Profiles
 * All the profile levels in the application. Used in conjenction with ACL lists
 */
class Profiles extends \Phalcon\Mvc\Collection
{

    /**
     * ID
     * @var integer
     */
    public $id;

    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * Define relationships to Users and Permissions
     */
    public function initialize()
    {
        /*
        $this->hasMany('id', 'Dsc\Admin\Models\Users', 'profilesId', array(
            'alias' => 'users',
            'foreignKey' => array(
                'message' => 'Profile cannot be deleted because it\'s used on Users'
            )
        ));

        $this->hasMany('id', 'Dsc\Admin\Models\Permissions', 'profilesId', array(
            'alias' => 'permissions'
        ));
        */
    }
}
