<?php
/*
 * This file is part of Wolf Microsystems' LDAP Connector
 *
 * (c) Andrew Moore <me@andrewmoore.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WMS\Ldap\Helper;

use Zend\Ldap\Node;

final class NodeUtil
{
    public static function isObjectClass(Node $node, $objectClass)
    {
        foreach ($node->getObjectClass() as $nodeObjectClass) {
            if (strcasecmp($nodeObjectClass, $objectClass) === 0) {
                return true;
            }
        }

        return false;
    }

    private function __construct()
    {
    }
} 