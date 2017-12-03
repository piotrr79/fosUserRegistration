<?php
// src/Websolutio/CijBundle/Entity/User.php

namespace Websolutio\CijBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
		
		public function __construct()
		{
			parent::__construct();
			// your own logic
			$this->roles = array('ROLE_SUBCRIBERUSER');
		}

}
