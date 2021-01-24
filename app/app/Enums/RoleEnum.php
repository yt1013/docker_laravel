<?php

namespace App\Enums;

use Eloquent\Enumeration\AbstractMultiton;
use Eloquent\Enumeration\Exception\ExtendsConcreteException;

/**
 * @method static RoleEnum ADMIN()
 * @method static RoleEnum USER()
 * @method static RoleEnum VIEWER()
 */
class RoleEnum extends AbstractMultiton
{
    private $roleId;
    private $roleName;

    /**
     * Returns the string key of this member.
     *
     * @api
     *
     * @return int The associated string key of this member.
     */
    final public function roleId(): int
    {
        return $this->roleId;
    }

    /**
     * Returns the string key of this member.
     *
     * @api
     *
     * @return string The associated string key of this member.
     */
    final public function roleName(): string
    {
        return $this->roleName;
    }

    /**
     * @param string $key
     * @param $roleId
     * @param $roleName
     * @throws ExtendsConcreteException
     */
    protected function __construct($key, $roleId, $roleName)
    {
        parent::__construct($key);

        $this->roleId = $roleId;
        $this->roleName = $roleName;
    }

    /**
     * @return array
     */
    public static function getKeys(): array
    {
        return array_keys(self::members());
    }

    /**
     * @return array
     */
    public static function getRoleIds(): array
    {
        $roleIds = [];
        foreach (self::members() as $member) {
            $roleIds[] = $member->roleId();
        }

        return $roleIds;
    }

    /**
     * @return array
     */
    public static function getRoleNames(): array
    {
        $roleNames = [];
        foreach (self::members() as $member) {
            $roleNames[] = $member->roleName();
        }

        return $roleNames;
    }

    /**
     * @return array
     */
    public static function getRoleSelector(): array
    {
        $selector = [0 => '-'];
        foreach (self::members() as $member) {
            $selector[$member->roleId()] = $member->roleName();
        }

        return $selector;
    }

    /**
     * @param $auth
     * @return bool
     */
    public static function isAdministrator($auth): bool
    {
        return $auth === self::ADMIN()->roleName;
    }

    /**
     * @throws ExtendsConcreteException
     */
    protected static function initializeMembers()
    {
        new static('ADMIN', 1, '管理者');
        new static('USER', 2, 'ユーザー');
        new static('VIEWER', 3, '閲覧者');
    }
}
