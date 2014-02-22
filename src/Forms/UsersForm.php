<?php
namespace Dsc\Admin\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Dsc\Admin\Models\Profiles;

class UsersForm extends Form
{

    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $username = new Text('username', array(
                        'placeholder' => 'Username'
        ));
        
        $username->addValidators(array(
                        new PresenceOf(array(
                                        'message' => 'Username is required'
                        ))
        ));
        
        $this->add($username);
        
        $first_name = new Text('first_name', array(
            'placeholder' => 'First name'
        ));

        $first_name->addValidators(array(
            new PresenceOf(array(
                'message' => 'First name is required'
            ))
        ));

        $this->add($first_name);
        
        $last_name = new Text('last_name', array(
                        'placeholder' => 'Last name'
        ));
        
        $last_name->addValidators(array(
                        new PresenceOf(array(
                                        'message' => 'Last name is required'
                        ))
        ));
        
        $this->add($last_name);

        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail is required'
            )),
            new Email(array(
                'message' => 'The e-mail is not valid'
            ))
        ));

        $this->add($email);

        $this->add(new Select('profilesId', Profiles::find(array(
        	array('active' => 'Y')
        )), array(
            'using' => array(
                'id',
                'name'
            ),
            'useEmpty' => true,
            'emptyText' => '...',
            'emptyValue' => ''
        )));

        $this->add(new Select('banned', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('suspended', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('active', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));
    }
}
