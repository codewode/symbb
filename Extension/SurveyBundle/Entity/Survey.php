<?php
/**
*
* @package symBB
* @copyright (c) 2013 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\Extension\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_topic_post_surveys")
 * @ORM\Entity()
 */
class Survey
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SymBB\Core\ForumBundle\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", unique=true, onDelete="cascade")
     */
    private $post;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $answers;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $choices;

    /**
     * @ORM\Column(type="boolean")
     */
    private $choicesChangeable = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;
    
    ############################################################################
    # Default Get and Set
    ############################################################################
    public function getId(){return $this->id;}
    public function setId($value){$this->id = $value;}
    public function setPost($object){$this->post = $object;}
    public function getPost(){return $this->post;}
    public function setEnd(\DateTime $value = null){$this->end = $value;}
    public function getEnd(){return $this->end;}
    public function setChoicesChangeable($value){$this->choicesChangeable = $value;}
    public function getChoicesChangeable(){return $this->choicesChangeable;}
    public function setChoices($value){$this->choices = $value;}
    public function getChoices(){return $this->choices;}
    public function setAnswers($value){$this->answers = $value;}
    public function getAnswers(){return $this->answers;}
    public function setQuestion($value){$this->question = $value;}
    public function getQuestion(){return $this->question;}
    ############################################################################
    
}