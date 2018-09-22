<?php

namespace AppBundle\Entity;

/**
 * Instance
 */
class Instance
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $surveyid;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     */
    private $modificationDate;

    /**
     * @var int
     */
    private $active;

    /**
     * @var string
     */
    private $code;
    
    /**
     * @var \DateTime
     */
    private $startDate;
    
    /**
     * @var \DateTime
     */
    private $endDate;
    


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Instance
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
     * Set description
     *
     * @param string $description
     *
     * @return Instance
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set questionid
     *
     * @param integer $questionid
     *
     * @return Instance
     */
    public function setSurveyid($surveyid)
    {
        $this->surveyid = $surveyid;

        return $this;
    }

    /**
     * Get questionid
     *
     * @return int
     */
    public function getSurveyid()
    {
        return $this->surveyid;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Instance
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     *
     * @return Instance
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Instance
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Instance
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    
    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Instance
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }
    
    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
    
    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Instance
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
    
    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}

