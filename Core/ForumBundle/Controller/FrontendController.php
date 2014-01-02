<?
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Core\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendController  extends Controller 
{
    
    protected $templateBundle = null;
    
    public function indexAction(){
        return $this->portalAction();
    }
    
    public function portalAction(){
        $forumList = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->findBy(array('parent' => null));
        $params = array('forumList' => $forumList);
        return $this->render($this->getTemplateBundleName('portal').':Portal:index.html.twig', $params);
    }
    
    public function forumAction(){
        
        $forumList = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->findBy(array('parent' => null), array('position' => 'asc'));
        $params = array('forumList' => $forumList);
        return $this->render($this->getTemplateBundleName('forum').':Forum:index.html.twig', $params);
    }
    
    public function forumShowAction($name, $id){
        $forum = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->find($id);
        $params = array('forum' => $forum);
        return $this->render($this->getTemplateBundleName('forum').':Forum:show.html.twig', $params);
    }
    
    public function newestAction(){
        
        if($this->getUser()->getSymbbType() !== 'user'){
            return $this->render($this->getTemplateBundleName('forum').':Exception:noGuest.html.twig');
        } 
        
        $em     = $this->get('doctrine')->getManager('symbb');
        $qb     = $em->createQueryBuilder();
        
        $dql    = "SELECT 
                        t 
                    FROM 
                        SymBB\Core\ForumBundle\Entity\Topic t 
                    WHERE
                        ( 
                            SELECT 
                                count(f)
                            FROM 
                                SymBB\Core\ForumBundle\Entity\Topic\Flag f
                            WHERE 
                                f.topic = t.id AND
                                f.flag = 'new' AND
                                f.user = ".(int)$this->getUser()->getId()."
                        ) > 0
                    ORDER BY
                        t.changed DESC"; 
        
        $query  = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            20
        );
        
        return $this->render($this->getTemplateBundleName('forum').':Forum:newest.html.twig', array('topicPagination' => $pagination));
    }
    
    public function ignoreAction($forum){
        
        $forum = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->find($forum);
        
        if(is_object($forum)){
            $this->ignoreForum($forum);
        }
        
        return $this->forward('SymBBCoreForumBundle:Frontend:forumShow', array(
                'name' => $forum->getSeoName(),
                'id'  => $forum->getId()
            ));
    }


    public function watchAction($forum){
        $forum = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->find($forum);
        if(is_object($forum)){
            $this->watchForum($forum);
        }
        return $this->forward('SymBBCoreForumBundle:Frontend:forumShow', array(
                'name' => $forum->getSeoName(),
                'id'  => $forum->getId()
            ));
    }


    public function readAction($forum){
        $forum = $this->get('doctrine')->getRepository('SymBBCoreForumBundle:Forum', 'symbb')
            ->find($forum);
        if(is_object($forum)){
            $this->readForum($forum);
        }
        return $this->forward('SymBBCoreForumBundle:Frontend:forumShow', array(
                'name' => $forum->getSeoName(),
                'id'  => $forum->getId()
            ));
    }
    
    protected function getTemplateBundleName($for = 'forum'){
        if($this->templateBundle === null){
            $config = $this->container->getParameter('symbb_config');
            $this->templateBundle = $config['template'][$for];
        }
        return $this->templateBundle;
    }
    
    protected function ignoreForum(\SymBB\Core\ForumBundle\Entity\Forum $forum){
        $flagHandler = $this->get('symbb.core.forum.flag');
        $flagHandler->insertFlag($forum, 'ignore');
        $subForms = $forum->getChildren();
        foreach($subForms as $subForm){
            $this->ignoreForum($subForm);
        }
    }
    
    protected function watchForum(\SymBB\Core\ForumBundle\Entity\Forum $forum){
        $flagHandler = $this->get('symbb.core.forum.flag');
        $flagHandler->removeFlag($forum, 'ignore');
        $subForms = $forum->getChildren();
        foreach($subForms as $subForm){
            $this->watchForum($subForm);
        }
    }
    
    protected function readForum(\SymBB\Core\ForumBundle\Entity\Forum $forum){
        
        $topics = $forum->getTopics();
        foreach($topics as $topic){
            $flagHandler = $this->get('symbb.core.forum.topic.flag');
            $flagHandler->removeFlag($topic, 'new');
        }
        
        $subForms = $forum->getChildren();
        foreach($subForms as $subForm){
            $this->readAction($subForm);
        }
        
    }
    
}