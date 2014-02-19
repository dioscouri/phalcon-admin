<?php
namespace Dsc\Admin\Models;

/**
 * SuccessLogins
 * This model registers successfull logins registered users have made
 */
class SuccessLogins extends \Phalcon\Mvc\Collection
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
     * @var string
     */
    public $userAgent;

    public function initialize()
    {
        $this->belongsTo('usersId', 'Dsc\Admin\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
