<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 15.02.20
 * Time: 23:27.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=240)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=240)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Stamp", inversedBy="comments")
     */
    private $stamp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * @param mixed $stamp
     */
    public function setStamp($stamp): void
    {
        $this->stamp = $stamp;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTimeOnPersist(): void
    {
        $this->time = new \DateTime();
    }
}
