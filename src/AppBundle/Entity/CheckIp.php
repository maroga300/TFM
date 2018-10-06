<?php

namespace AppBundle\Entity;

/**
 * CheckIp
 */
class CheckIp
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
     * @var string
     */
    private $ip;

    /**
     * @var \DateTime
     */
    private $timeCreated;


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
     * @return CheckIp
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
     * Set ip
     *
     * @param string $ip
     *
     * @return CheckIp
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set timeCreated
     *
     * @param \DateTime $timeCreated
     *
     * @return CheckIp
     */
    public function setTimeCreated($timeCreated)
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }
}

