<?php

/*
 * This file is part of php-cache\apc-adapter package.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\Hierarchy;

use Cache\Adapter\Apc\ApcCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Cache\IntegrationTests\CachePoolTest as BaseTest;

class HierarchicalCachePoolTest  extends \PHPUnit_Framework_TestCase
{
    private $cache;

    public function createCachePool()
    {
        return new HierarchicalCachePool($this->getCache());
    }

    public function getCache()
    {
        if ($this->cache === null) {
            $this->cache = new ArrayCachePool();
        }

        return $this->cache;
    }

    public function testBasicUsage()
    {
        $pool = $this->createCachePool();
        $user = 4711;
        for ($i = 0; $i < 10; $i++) {
            $item = $pool->getItem(sprintf('|users|%d|followers|%d|likes', $user, $i));
            $item->set('Justin Bieber');
            $pool->save($item);
        }

        $this->assertTrue($pool->hasItem('|users|4711|followers|4|likes'));
        $pool->deleteItem('|users|4711|followers');
        $this->assertFalse($pool->hasItem('|users|4711|followers|4|likes'));
    }
}
