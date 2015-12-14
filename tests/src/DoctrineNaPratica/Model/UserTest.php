<?php

namespace DoctrineNaPratica\Model;

use DoctrineNaPratica\Test\TestCase;
use DoctrineNaPratica\Model\User;
use DoctrineNaPratica\Model\Subscription;
use DoctrineNaPratica\Model\Enrollment;
use DoctrineNaPratica\Model\Course;
use DoctrineNaPratica\Model\Lesson;
use DoctrineNaPratica\Model\GithubProfile;
use DoctrineNaPratica\Model\FacebookProfile;
use DoctrineNaPratica\Model\TwitterProfile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Model
 */
class UserTest extends TestCase
{
    
    //testa a criação do User
    public function testUser()
    {
        
        $user = $this->buildUser();

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        //deve ter criado um id
        $this->assertNotNull($user->getId());
        $this->assertEquals(1, $user->getId());
        
        $savedUser = $this->getEntityManager()->find(get_class($user), $user->getId());
        
        $this->assertInstanceOf(get_class($user), $savedUser);
        $this->assertEquals($user->getName(), $savedUser->getName());
    }   

    //testa a Subscription do User
    public function testUserSubscription()
    {
        $user = $this->buildUser();

        $subscription = new Subscription;
        $subscription->setStatus(1);
        $subscription->setStarted(new \DateTime('NOW'));

        $user->setSubscription($subscription);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        //deve ter criado um id
        $this->assertNotNull($subscription->getId());
        $this->assertEquals(1, $subscription->getId());

        $savedUser = $this->getEntityManager()->find(get_class($user), $user->getId());
        
        $this->assertInstanceOf(get_class($subscription), $savedUser->getSubscription());
        $this->assertEquals($subscription->getId(), $savedUser->getSubscription()->getId());
    }

    //testa os Enrollments do User
    public function testUserEnrollment()
    {
        $user = $this->buildUser();
        $courseA = $this->buildCourse('PHP');
        $courseB = $this->buildCourse('Doctrine');

        $enrollmentA = new Enrollment;
        $enrollmentA->setUser($user);
        $enrollmentA->setCourse($courseA);

        $enrollmentB = new Enrollment;
        $enrollmentB->setUser($user);
        $enrollmentB->setCourse($courseB);

        $enrollmentCollection = new ArrayCollection();
        $enrollmentCollection->add($enrollmentA);
        $enrollmentCollection->add($enrollmentB);
        
        $user->setEnrollmentCollection($enrollmentCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), 1);
        $this->assertEquals(2, count($savedUser->getEnrollmentCollection()));
    }

    //testa os Courses do User (teacher)
    public function testUserCourse()
    {
        $user = $this->buildUser();

        $courseA = new Course;
        $courseA->setName('PHP');
        $courseA->setDescription('Curso de PHP');
        $courseA->setValue(100);
        $courseA->setTeacher($user);

        $courseB = new Course;
        $courseB->setName('Doctrine');
        $courseB->setDescription('Curso de Doctrine');
        $courseB->setValue(400);
        $courseB->setTeacher($user);

        $courseCollection = new ArrayCollection;
        $courseCollection->add($courseA);
        $courseCollection->add($courseB);
        $user->setCourseCollection($courseCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), 1);
        $this->assertEquals(2, count($savedUser->getCourseCollection()));
        $savedCourses = $savedUser->getCourseCollection();
        $this->assertEquals('Doctrine', $savedCourses[1]->getName());
    }

    //testa as Lessons do User
    public function testUserLesson()
    {
        $user = $this->buildUser();

        $course = new Course;
        $course->setName('PHP');
        $course->setDescription('Curso de PHP');
        $course->setValue(100);
        $course->setTeacher($user);

        $lessonA = new Lesson;
        $lessonA->setName('Arrays');
        $lessonA->setDescription('Aula sobre Arrays');
        $lessonA->setCourse($course);

        $lessonB = new Lesson;
        $lessonB->setName('Datas');
        $lessonB->setDescription('Aula sobre Datas');
        $lessonB->setCourse($course);

        $lessonCollection = new ArrayCollection;
        $lessonCollection->add($lessonA);
        $lessonCollection->add($lessonB);
        $user->setLessonCollection($lessonCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), 1);
        $this->assertEquals(2, count($savedUser->getLessonCollection()));
        $savedLessons = $savedUser->getLessonCollection();
        $this->assertEquals(1, $savedLessons[0]->getId());
    }

    //testa as Profiles do User
    public function testUserProfile()
    {
        $user = $this->buildUser();

        $github = new GithubProfile;
        $github->setName('Elton Minetto');
        $github->setLogin('eminetto');
        $github->setEmail('eminetto@coderockr.com');
        $github->setAvatar('eminetto.png');

        $facebook = new FacebookProfile;
        $facebook->setName('Elton Luís Minetto');
        $facebook->setLogin('eminetto');
        $facebook->setEmail('eminetto@gmail.com');
        $facebook->setAvatar('eminetto.png');

        $twitter = new TwitterProfile;
        $twitter->setName('Elton Minetto');
        $twitter->setLogin('eminetto');
        $twitter->setEmail('eminetto@coderockr.com');
        $twitter->setAvatar('eminetto.jpg');

        $profileCollection = new ArrayCollection;
        $profileCollection->add($github);
        $profileCollection->add($facebook);
        $profileCollection->add($twitter);
        $user->setProfileCollection($profileCollection);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $savedUser = $this->getEntityManager()->find(get_class($user), 1);
        $this->assertEquals(3, count($savedUser->getProfileCollection()));
        $savedProfiles = $savedUser->getProfileCollection();
           
        $this->assertInstanceOf(get_class($github), $savedProfiles[0]);  
        $this->assertInstanceOf(get_class($facebook), $savedProfiles[1]);  
        $this->assertInstanceOf(get_class($twitter), $savedProfiles[2]);  
    }



    //cria um User
    private function buildUser()
    {
        $user = new User;
        $user->setName('Steve Jobs');
        $user->setLogin('steve');
        $user->setLogin('steve@apple.com');
        $user->setAvatar('steve.png');

        return $user;
    }

    //cria um Course
    private function buildCourse($courseName)
    {
        $teacher = $this->buildUser();
        //login é unique
        $teacher->setLogin('jobs'.$courseName);

        $course = new Course;
        $course->setName($courseName);
        $course->setDescription('Curso de PHP');
        $course->setValue(100);
        $course->setTeacher($teacher);

        return $course;
    }
}