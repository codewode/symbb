<?
namespace SymBB\Core\InstallBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\HelloBundle\Entity\Group;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        $guestName  = $this->container->get('translator')->trans('Guest');
        $adminName  = $this->container->get('translator')->trans('Admin');
        $userName   = $this->container->get('translator')->trans('User');
        
        $groupGuest = new \SymBB\Core\UserBundle\Entity\Group();
        $groupGuest->setType('guest');
        $groupGuest->addRole('ROLE_GUEST');
        $groupGuest->setName($guestName);
        
        $groupUser = new \SymBB\Core\UserBundle\Entity\Group();
        $groupUser->setType('user');
        $groupUser->addRole('ROLE_GUEST');
        $groupUser->addRole('ROLE_USER');
        $groupUser->setName($userName);
        
        $groupAdmin = new \SymBB\Core\UserBundle\Entity\Group();
        $groupAdmin->setType('admin');
        $groupAdmin->addRole('ROLE_GUEST');
        $groupAdmin->addRole('ROLE_USER');
        $groupAdmin->addRole('ROLE_ADMIN');
        $groupAdmin->setName($adminName);

        $manager->persist($groupGuest);
        $manager->persist($groupUser);
        $manager->persist($groupAdmin);
        $manager->flush();

        $this->addReference('guest-group', $groupGuest);
        $this->addReference('user-group', $groupUser);
        $this->addReference('admin-group', $groupAdmin);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}