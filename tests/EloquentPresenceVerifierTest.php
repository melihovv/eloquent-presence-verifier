<?php

namespace Melihovv\EloquentPresenceVerifier\Tests;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Model;
use Melihovv\EloquentPresenceVerifier\EloquentPresenceVerifier;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class EloquentPresenceVerifierTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testBasicCount()
    {
        $verifier = new EloquentPresenceVerifier(
            $model = m::mock(Model::class),
            $db = m::mock(ConnectionResolverInterface::class)
        );
        $verifier->setConnection('connection');

        $model->shouldReceive('setConnection')->once()->with('connection')->andReturnSelf();
        $model->shouldReceive('setTable')->once()->with('table')->andReturnSelf();
        $model->shouldReceive('useWritePdo')->once()->andReturnSelf();
        $model->shouldReceive('where')->with('column', '=', 'value')->andReturn($builder = m::mock('stdClass'));
        $builder->shouldReceive('whereNull')->with('foo');
        $builder->shouldReceive('whereNotNull')->with('bar');
        $builder->shouldReceive('where')->with('baz', 'taylor');
        $builder->shouldReceive('where')->with('faz', true);
        $builder->shouldReceive('where')->with('not', '!=', 'admin');
        $builder->shouldReceive('count')->once()->andReturn(100);

        $this->assertEquals(100, $verifier->getCount('table', 'column', 'value', null, null, [
            'foo' => 'NULL',
            'bar' => 'NOT_NULL',
            'baz' => 'taylor',
            'faz' => true,
            'not' => '!admin',
        ]));
    }

    public function testBasicCountWithClosures()
    {
        $verifier = new EloquentPresenceVerifier(
            $model = m::mock(Model::class),
            $db = m::mock(ConnectionResolverInterface::class)
        );
        $verifier->setConnection('connection');

        $model->shouldReceive('setConnection')->once()->with('connection')->andReturnSelf();
        $model->shouldReceive('setTable')->once()->with('table')->andReturnSelf();
        $model->shouldReceive('useWritePdo')->once()->andReturnSelf();
        $model->shouldReceive('where')->with('column', '=', 'value')->andReturn($builder = m::mock('stdClass'));
        $closure = function ($query) {
            $query->where('closure', 1);
        };
        $builder->shouldReceive('whereNull')->with('foo');
        $builder->shouldReceive('whereNotNull')->with('bar');
        $builder->shouldReceive('where')->with('baz', 'taylor');
        $builder->shouldReceive('where')->with('faz', true);
        $builder->shouldReceive('where')->with('not', '!=', 'admin');
        $builder->shouldReceive('where')->with(m::type('Closure'))->andReturnUsing(function () use ($builder, $closure) {
            $closure($builder);
        });
        $builder->shouldReceive('where')->with('closure', 1);
        $builder->shouldReceive('count')->once()->andReturn(100);

        $this->assertEquals(100, $verifier->getCount('table', 'column', 'value', null, null, [
            'foo' => 'NULL',
            'bar' => 'NOT_NULL',
            'baz' => 'taylor',
            'faz' => true,
            'not' => '!admin',
            0 => $closure,
        ]));
    }

    public function testMultiCount()
    {
        $verifier = new EloquentPresenceVerifier(
            $model = m::mock(Model::class),
            $db = m::mock(ConnectionResolverInterface::class)
        );
        $verifier->setConnection('connection');

        $model->shouldReceive('setConnection')->once()->with('connection')->andReturnSelf();
        $model->shouldReceive('setTable')->once()->with('table')->andReturnSelf();
        $model->shouldReceive('useWritePdo')->once()->andReturnSelf();
        $model->shouldReceive('whereIn')->with('column', ['value1', 'value2'])->andReturnSelf();
        $model->shouldReceive('count')->andReturn(100);

        $this->assertEquals(100, $verifier->getMultiCount('table', 'column', ['value1', 'value2'], []));
    }
}
