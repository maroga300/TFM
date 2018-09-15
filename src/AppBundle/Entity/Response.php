<?php

namespace AppBundle\Entity;

/**
 * Response
 */
class Response
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $instanceId;

    /**
     * @var int
     */
    private $questionId;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $creationDate;


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
     * Set instanceId
     *
     * @param integer $instanceId
     *
     * @return Response
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    /**
     * Get instanceId
     *
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * Set questionId
     *
     * @param integer $questionId
     *
     * @return Response
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Response
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Response
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
}

