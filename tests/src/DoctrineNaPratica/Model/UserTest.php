<?php

namespace DoctrineNaPratica\Model;

use DoctrineNaPratica\Test\TestCase;
use DoctrineNaPratica\Model\User;

/**
 * @group Model
 */
class UserTest extends TestCase
{
    
    public function testUser()
    {
        $user = new User;
        $user->setName('Steve Jobs');
        $user->setLogin('steve');
        $user->setLogin('steve@apple.com');
        $user->setAvatar('steve.png');

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        //deve ter criado um id
        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());
        
        $savedUser = $this->getEntityManager()->find(get_class($user), $user->getId());
        
        $this->assertInstanceOf(get_class($user), $savedUser);
        $this->assertEquals($user->getName(), $savedUser->getName());

    }   
}