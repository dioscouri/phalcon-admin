<?php
namespace Dsc\Admin\Models;

/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends \Phalcon\Mvc\Collection
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
    public $usersId;

    /**
     *
     * @var string
     */
    public $ipAddress;

    /**
     *
     * @var integer
     */
    public $attempted;

    public function initialize()
    {
        /*
        $this->belongsTo('usersId', 'Dsc\Admin\Models\Users', 'id', array(
            'alias' => 'user'
        ));
        */
    }
}
