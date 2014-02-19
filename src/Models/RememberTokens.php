<?php
namespace Dsc\Admin\Models;

/**
 * RememberTokens
 * Stores the remember me tokens
 */
class RememberTokens extends \Phalcon\Mvc\Collection
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
    public $token;

    /**
     *
     * @var string
     */
    public $userAgent;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->createdAt = time();
    }

    public function initialize()
    {
        $this->belongsTo('usersId', 'Dsc\Admin\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
