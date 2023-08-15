<?php

namespace Hakam\MultiTenancyBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Hakam\MultiTenancyBundle\Enum\DatabaseStatusEnum;

/**
 *  Trait to add tenant database configuration to an entity.
 * @author Ramy Hakam <pencilsoft1@gmail.com>
 */
trait TenantDbConfigTrait
{
    #[ORM\Column(type: 'string', length: 255)]
    protected string $dbName;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ["default" => null])]
    protected ?string $dbUserName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ["default" => null])]
    protected ?string $dbPassword = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ["default" => null])]
    private ?string $dbHost = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ["default" => null])]
    private ?string $dbPort = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ["default" => null])]
    private ?string $dbDriver = null;

    #[ORM\Column(type: 'string', length: 255, enumType: DatabaseStatusEnum::class, options: ["default" => DatabaseStatusEnum::DATABASE_NOT_CREATED])]
    private DatabaseStatusEnum $databaseStatus = DatabaseStatusEnum::DATABASE_NOT_CREATED;

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     * @return self
     */
    public function setDbName(string $dbName): self
    {
        $this->dbName = $dbName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDbUserName(): ?string
    {
        return $this->dbUserName;
    }

    /**
     * @param string|null $dbUser
     * @return self
     */
    public function setDbUserName(?string $dbUser = null): self
    {
        $this->dbUserName = $dbUser;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDbPassword(): ?string
    {
        return $this->dbPassword;
    }

    /**
     * @param string|null $dbPassword
     * @return self
     */
    public function setDbPassword(?string $dbPassword): self
    {
        $this->dbPassword = $dbPassword;
        return $this;
    }

    /**
     * @return DatabaseStatusEnum
     */
    public function getDatabaseStatus(): DatabaseStatusEnum
    {
        return $this->databaseStatus;
    }

    /**
     * @param DatabaseStatusEnum $databaseStatus
     * @return self
     */
    public function setDatabaseStatus(DatabaseStatusEnum $databaseStatus): self
    {
        $this->databaseStatus = $databaseStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDbHost(): ?string
    {
        return $this->dbHost;
    }

    /**
     * @param string $dbHost
     * @return \App\Entity\Main\TenantDbConfig|TenantDbConfigTrait
     */
    public function setDbHost(string $dbHost): self
    {
        $this->dbHost = $dbHost;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDbPort(): ?string
    {
        return $this->dbPort;
    }

    /**
     * @param string $dbPort
     * @return \App\Entity\Main\TenantDbConfig|TenantDbConfigTrait
     */
    public function setDbPort(string $dbPort): self
    {
        $this->dbPort = $dbPort;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDbDriver(): ?string
    {
        return $this->dbDriver;
    }

    /**
     * @param string $driver
     * @return \App\Entity\Main\TenantDbConfig|TenantDbConfigTrait
     */
    public function setDbDriver(string $dbDriver): self
    {
        $this->dbDriver = $dbDriver;

        return $this;
    }
}
