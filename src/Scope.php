<?php

namespace URF\Scope;

/**
 * Scope is a simple handler of active and inactive scope
 *
 * @author echau <eriic.chau@gmail.com>
 */
class Scope
{
    /**
     * Scopes container
     * @var array
     */
    private $scopes;

    /**
     * Scope constructor, it initialize `scopes` property as an empty array
     */
    public function __construct()
    {
        $this->scopes = [];
    }

    /**
     * Adds scope to list
     *
     * @param string  $scope  the scope name/identifier
     * @param boolean $active define if provided scope has to be active now or not (default: false)
     * @return self
     * @throws InvalidArgumentException if scope already exists in list or if provided type of scope is not string
     */
    public function add($scope, $active = false)
    {
        if ($this->has($scope)) {
            throw new \InvalidArgumentException("`$scope` already exists in scope list");
        }

        $this->scopes[$scope] = (boolean) $active;

        return $this;
    }

    /**
     * Checks if provided scope name is already in list or not
     *
     * @param  string  $scope scope name/identifier
     * @return boolean true if found in list, else false
     * @throws InvalidArgumentException if scope's type is not string
     */
    public function has($scope)
    {
        if (!is_string($scope)) {
            throw new \InvalidArgumentException('Type of `scope` argument must be string');
        }

        return array_key_exists($scope, $this->scopes);
    }

    /**
     * Returns every scopes
     *
     * @return array
     */
    public function getAll()
    {
        return array_keys($this->scopes);
    }

    /**
     * Activate provided scope
     *
     * @param  string $scope the scope name/identifier
     * @return self
     * @throws InvalidArgumentException if provided scope name is not in scope list
     */
    public function active($scope)
    {
        $this->raiseExceptionIfScopeNotFound($scope);
        $this->scopes[$scope] = true;

        return $this;
    }

    /**
     * Checks if provided scope is active or not
     *
     * @param  string $scope the scope name/identifier
     * @return boolean true if scope is active, else false
     * @throws InvalidArgumentException if provided scope name is not in scope list
     */
    public function isActive($scope)
    {
        $this->raiseExceptionIfScopeNotFound($scope);

        return $this->scopes[$scope];
    }


    /**
     * Returns every scopes that are currently actives
     *
     * @return self
     */
    public function getActives()
    {
        $actives = [];
        foreach ($this->scopes as $scope => $state) {
            if ($state) {
                $actives[] = $scope;
            }
        }

        return $actives;
    }

    /**
     * Throws InvalidArgumentException if scope is not in list
     *
     * @param  string $scope the scope name/identifier
     * @throws InvalidArgumentException if scope is not in list
     */
    private function raiseExceptionIfScopeNotFound($scope)
    {
        if (!$this->has($scope)) {
            throw new \InvalidArgumentException("`$scope` is not in scope list");
        }
    }
}
