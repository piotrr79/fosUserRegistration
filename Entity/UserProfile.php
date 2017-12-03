<?php

namespace Websolutio\CijBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserProfile
 */
class UserProfile
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var \DateTime
     */
    private $paid_at;

    /**
     * @var \DateTime
     */
    private $expires_at;

    /**
     * @var \Websolutio\CijBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return UserProfile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return UserProfile
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set paid_at
     *
     * @param \DateTime $paidAt
     * @return UserProfile
     */
    public function setPaidAt($paidAt)
    {
        $this->paid_at = $paidAt;

        return $this;
    }

    /**
     * Get paid_at
     *
     * @return \DateTime 
     */
    public function getPaidAt()
    {
        return $this->paid_at;
    }

    /**
     * Set expires_at
     *
     * @param \DateTime $expiresAt
     * @return UserProfile
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expires_at = $expiresAt;

        return $this;
    }

    /**
     * Get expires_at
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    /**
     * Set user
     *
     * @param \Websolutio\CijBundle\Entity\User $user
     * @return UserProfile
     */
    public function setUser(\Websolutio\CijBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Websolutio\CijBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add payment
     *
     * @param \Websolutio\CijBundle\Entity\Payment $payment
     * @return UserProfile
     */
    public function addPayment(\Websolutio\CijBundle\Entity\Payment $payment)
    {
        $this->payment[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \Websolutio\CijBundle\Entity\Payment $payment
     */
    public function removePayment(\Websolutio\CijBundle\Entity\Payment $payment)
    {
        $this->payment->removeElement($payment);
    }

    /**
     * Get payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayment()
    {
        return $this->payment;
    }
    /**
     * @var string
     */
    private $usertype;


    /**
     * Set usertype
     *
     * @param string $usertype
     * @return UserProfile
     */
    public function setUsertype($usertype)
    {
        $this->usertype = $usertype;

        return $this;
    }

    /**
     * Get usertype
     *
     * @return string 
     */
    public function getUsertype()
    {
        return $this->usertype;
    }
    /**
     * @var string
     */
    private $avatar;


    /**
     * Set avatar
     *
     * @param string $avatar
     * @return UserProfile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    // for file upload
    public $file;
    

    protected function getUploadDir()
    {
    return 'uploads/user/avatar';
    }
 
    protected function getUploadRootDir()
    {
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
 
    public function getWebPath()
    {
    return null === $this->avatar ? null : $this->getUploadDir().'/'.$this->avatar;
    }
 
    public function getAbsolutePath()
    {
    return null === $this->avatar ? null : $this->getUploadRootDir().'/'.$this->avatar;
    }
    
    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {	
			$this->file = $file;
			// check if we have an old image path
			if (isset($this->avatar)) {
				// store the old name to delete after the update
				$this->temp = $this->avatar;
				$this->avatar = null;
			// !important! if new account, and img do not exists set avatar to null (as not exists)	
			} elseif (!isset($this->avatar)) {
				//$this->avatar = 'initial';
				// det avatar to null as avatar not exist yet
				$this->avatar = 'initial';
			}   
    }    

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Add your code here
        if (null !== $this->file) {
        // do whatever you want to generate a unique name
        $this->avatar = uniqid().'.'.$this->file->guessExtension();
        }
    }
      
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return ;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $dimage = $this->file;
        list($width, $height) = getimagesize($dimage);
        $newWidth = 200;
        $newHeight = ($height / $width) * $newWidth;
        
        $srcImg = imagecreatefromstring(file_get_contents($this->file));
        $destImg = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        $this->getFile()->move($this->getUploadRootDir(), $this->avatar);
        
        $savePath = $this->getUploadRootDir().'/'.$this->avatar;
        $imageQuality =99;
        
        imagejpeg($destImg, $savePath, $imageQuality);
        imagedestroy($srcImg);
        imagedestroy($destImg);
        unset($this->file);
        
		// check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;

    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        // Add your code here
        if ($avatar = $this->getAbsolutePath()) {
        unlink($avatar);
        }
    }
    /**
     * @var string
     */
    private $accesslevel;


    /**
     * Set accesslevel
     *
     * @param string $accesslevel
     * @return UserProfile
     */
    public function setAccesslevel($accesslevel)
    {
        $this->accesslevel = $accesslevel;

        return $this;
    }

    /**
     * Get accesslevel
     *
     * @return string 
     */
    public function getAccesslevel()
    {
        return $this->accesslevel;
    }
}
