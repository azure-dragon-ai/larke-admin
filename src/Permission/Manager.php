<?php

declare (strict_types = 1);

namespace Larke\Admin\Permission;

use Larke\Auth\Facades\Enforcer;
use Casbin\Enforcer as CasbinEnforcer;

/**
 * 权限
 *
 * @create 2020-12-24
 * @author deatil
 */
class Manager
{
    /**
     * @var string 默认决策器
     */
    protected string $defaultGuard = 'larke';
    
    /**
     * @var \Casbin\Enforcer 决策器
     */
    protected CasbinEnforcer $enforcer;
    
    /**
     * 构造函数
     *
     * @param string $guard 决策器名称
     */
    public function __construct(string $guard = '') 
    {
        if (empty($guard)) {
            $guard = $this->defaultGuard;
        }
        
        // 决策器
        $this->enforcer = Enforcer::guard($guard);
    }
    
    /**
     * 读取自定义配置
     * 
     * @param  string $guard 决策器名称
     * 
     * @return self
     */
    public static function guard(string $name = ''): self
    {
        return new self($name);
    }
    
    /**
     * 设置决策器
     *
     * @param CasbinEnforcer $enforcer 决策器
     *
     * @return self
     */
    public function withEnforcer(CasbinEnforcer $enforcer): self
    {
        // 决策器
        $this->enforcer = $enforcer;
        
        return $this;
    }
    
    /**
     * 获取决策器
     *
     * @return CasbinEnforcer 决策器
     */
    public function getEnforcer(): CasbinEnforcer
    {
        return $this->enforcer;
    }
    
    // =========================
    
    /**
     * 用户添加角色
     */
    public function addRoleForUser(string $user, string $role, string ...$domain): mixed
    {
        return $this->enforcer->addRoleForUser($user, $role, ...$domain);
    }
    
    /**
     * 用户批量添加角色
     */
    public function addRolesForUser(string $user, array $roles, string ...$domain): mixed
    {
        return $this->enforcer->addRolesForUser($user, $roles, ...$domain);
    }
    
    /**
     * 用户是否拥有某角色
     */
    public function hasRoleForUser(string $user, string $role, string ...$domain): bool
    {
        return $this->enforcer->hasRoleForUser($user, $role, ...$domain);
    }
    
    /**
     * 用户拥有的所有角色
     */
    public function getRolesForUser(string $user, string ...$domain): mixed
    {
        return $this->enforcer->getRolesForUser($user, ...$domain);
    }
    
    /**
     * 角色拥有的所有用户
     */
    public function getUsersForRole(string $role, string ...$domain): mixed
    {
        return $this->enforcer->getUsersForRole($role, ...$domain);
    }
    
    /**
     * 删除用户的一个角色
     */
    public function deleteRoleForUser(string $user, string $role, string ...$domain): mixed
    {
        return $this->enforcer->deleteRoleForUser($user, $role, ...$domain);
    }
    
    /**
     * 删除用户所有角色
     */
    public function deleteRolesForUser(string $user, string ...$domain): mixed
    {
        return $this->enforcer->deleteRolesForUser($user, ...$domain);
    }
    
    /**
     * 删除用户信息
     */
    public function deleteUser(string $user): mixed
    {
        return $this->enforcer->deleteUser($user);
    }
    
    /**
     * 删除角色信息
     */
    public function deleteRole(string $role): mixed
    {
        return $this->enforcer->deleteRole($role);
    }
    
    /**
     * 删除权限信息
     */
    public function deletePermission(string ...$permission): mixed
    {
        return $this->enforcer->deletePermission(...$permission);
    }
    
    // =========================
    
    /**
     * 添加权限
     */
    public function addPolicy(string $name, string $type, string $rule): mixed
    {
        return $this->enforcer->addPolicy($name, $type, $rule);
    }
    
    /**
     * 批量添加权限
     * 
     * $rules array<name, type, rule>
     */
    public function addPolicies(array $rules): mixed
    {
        return $this->enforcer->addPolicies($rules);
    }
    
    /**
     * 删除权限
     */
    public function deletePolicy(string $name, string $type, string $rule): mixed
    {
        return $this->enforcer->deletePermissionForUser($name, $type, $rule);
    }
    
    /**
     * 删除标识所有权限
     */
    public function deletePolicies(string $name): mixed
    {
        return $this->enforcer->deletePermissionsForUser($name);
    }
    
    /**
     * 判断是否有权限
     */
    public function hasPolicyForUser(string $name, string $type, string $rule): bool
    {
        return $this->enforcer->hasPermissionForUser($name, $type, $rule);
    }
    
    // =========================
    
    /**
     * 给用户添加权限
     */
    public function addPermissionForUser(string $user, string ...$permission): bool
    {
        $params = array_merge([$user], $permission);

        return $this->addPolicy(...$params);
    }
    
    /**
     * 批量给用户添加权限
     */
    public function addPermissionsForUser(string $user, array ...$permissions): bool
    {
        return $this->enforcer->addPermissionsForUser($user, ...$permissions);
    }
    
    /**
     * 判断是否有权限
     */
    public function hasPermissionForUser(string $name, string $type, string $rule): mixed
    {
        return $this->enforcer->hasPermissionForUser($name, $type, $rule);
    }
    
    // =========================
    
    /**
     * 全部权限，只包含对用户直接授权的权限
     */
    public function getPermissionsForUser(string $user, string ...$domain): mixed
    {
        return $this->enforcer->getPermissionsForUser($user, ...$domain);
    }
    
    /**
     * 全部权限，包含用户授权的用户组包含权限及自定义权限
     */
    public function getImplicitPermissionsForUser(string $user): mixed
    {
        return $this->enforcer->getImplicitPermissionsForUser($user);
    }
    
    /**
     * 权限所有的用户
     */
    public function getImplicitUsersForPermission(string ...$permission): mixed
    {
        return $this->enforcer->getImplicitUsersForPermission(...$permission);
    }
    
    /**
     * 用户所有的角色
     */
    public function getImplicitRolesForUser(string $name, string ...$domain): mixed
    {
        return $this->enforcer->getImplicitRolesForUser($name, ...$domain);
    }
    
    /**
     * 角色所有的用户
     */
    public function getImplicitUsersForRole(string $name, string ...$domain): mixed
    {
        return $this->enforcer->getImplicitUsersForRole($name, ...$domain);
    }
    
    /**
     * 用户所有的策略
     */
    public function getImplicitResourcesForUser(string $user, string ...$domain): mixed
    {
        return $this->enforcer->getImplicitResourcesForUser($user, ...$domain);
    }
    
    /**
     * 域名所有的用户
     */
    public function getAllUsersByDomain(string $domain): mixed
    {
        return $this->enforcer->getAllUsersByDomain($domain);
    }
    
    // =========================
    
    /**
     * 验证用户权限
     */
    public function enforce(string $user, string $type, string $rule): bool
    {
        return $this->enforcer->enforce($user, $type, $rule);
    }
    
    // =========================

    /**
     * 访问没有添加的方法
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->enforcer->{$method}(...$parameters);
    }
}
