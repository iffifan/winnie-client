<?php

namespace Iffifan\WinnieClient\Models;

use Illuminate\Support\Collection;

class User
{

    protected int $id;
    protected string $name;
    protected string $email;
    protected string $username;
    protected string $first_name;
    protected string $last_name;
    protected Collection $roles;
    protected Collection $groups;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return User
     */
    public function setFirstName(string $first_name): User
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return User
     */
    public function setLastName(string $last_name): User
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Collection $roles
     * @return User
     */
    public function setRoles(Collection $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Collection $groups
     * @return User
     */
    public function setGroups(Collection $groups): User
    {
        $this->groups = $groups;
        return $this;
    }

    public function hasRole(string $role): bool
    {
        return match ($role) {
            'user' => (bool)$this->getRoles()->whereIn('name', ['user', 'admin', 'sa'])->first(),
            'admin' => (bool)$this->getRoles()->whereIn('name', ['admin', 'sa'])->first(),
            'sa' => (bool)$this->getRoles()->whereIn('name', ['sa'])->first(),
            default => false,
        };
    }

    public static function makeFromArray(array $winnieUserData): self
    {
        $winnieUser = new self();
        $winnieUser->setId($winnieUserData['id'])
            ->setName($winnieUserData['name'])
            ->setEmail($winnieUserData['email'])
            ->setUsername($winnieUserData['username'] ?? '')
            ->setFirstName($winnieUserData['first_name'] ?? '')
            ->setLastName($winnieUserData['last_name'] ?? '')
            ->setRoles(
                is_array($winnieUserData['roles']) ? collect($winnieUserData['roles']) : $winnieUserData['roles']
            )
            ->setGroups(
                is_array($winnieUserData['groups']) ? collect($winnieUserData['groups']) : $winnieUserData['groups']
            );
        return $winnieUser;
    }

}
