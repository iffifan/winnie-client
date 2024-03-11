<?php

namespace Iffifan\WinnieClient\Tests\Unit\Models;

use Iffifan\WinnieClient\Models\KPI;
use Iffifan\WinnieClient\Tests\TestCase;

class KPITest extends TestCase
{

    public function testUserId()
    {
        $kpi = new KPI();
        $kpi->setUserId(1);
        $this->assertEquals(1, $kpi->getUserId());
    }

    public function testEmail()
    {
        $kpi = new KPI();
        $kpi->setEmail('foo@mail.com');
        $this->assertEquals('foo@mail.com', $kpi->getEmail());
    }

    public function testExternalID()
    {
        $kpi = new KPI();
        $kpi->setExternalID('foo');
        $this->assertEquals('foo', $kpi->getExternalID());
    }

    public function testValue()
    {
        $kpi = new KPI();
        $kpi->setValue(1.0);
        $this->assertEquals(1.0, $kpi->getValue());
    }

    public function testWeight()
    {
        $kpi = new KPI();
        $kpi->setWeight(1.0);
        $this->assertEquals(1.0, $kpi->getWeight());
    }

    public function testTimestamp()
    {
        $kpi = new KPI();
        $time = new \DateTime('2023-12-04 06:27:13');
        $kpi->setTimestamp($time);
        $this->assertEquals($time, $kpi->getTimestamp());
    }

    public function testMeta()
    {
        $kpi = new KPI();
        $kpi->setMeta([]);
        $this->assertEquals([], $kpi->getMeta());
    }

    public function testToArray()
    {
        $kpi = new KPI();
        $kpi->setUserId(1);
        $kpi->setEmail('foo@mail.com');
        $kpi->setExternalID('foo');
        $kpi->setValue(1.0);
        $kpi->setWeight(1.0);
        $time = new \DateTime('2023-12-04 06:27:13');
        $kpi->setTimestamp($time);
        $kpi->setMeta([]);
        $this->assertEquals([
            'user_id'     => 1,
            'value'       => 1.0,
            'weight'      => 1.0,
            'timestamp'   => $time->format('Y-m-d H:i'),
            'meta'        => [],
        ], $kpi->toArray());
    }

    public function testToJson()
    {
        $kpi = new KPI();
        $kpi->setUserId(1);
        $kpi->setEmail('foo@mail.com');
        $kpi->setExternalID('foo');
        $kpi->setValue(1.0);
        $kpi->setWeight(1.0);
        $time = new \DateTime('2023-12-04 06:27:13');
        $kpi->setTimestamp($time);
        $kpi->setMeta([]);
        $this->assertEquals(json_encode([
            'user_id'   => 1,
            'value'     => 1.0,
            'weight'    => 1.0,
            'timestamp' => $time->format('Y-m-d H:i'),
            'meta'      => [],
        ]), $kpi->toJson());
    }

    public function testFromArray()
    {
        $kpi = KPI::fromArray([
            'userId'     => 1,
            'email'      => 'foo@mail.com',
            'external_id' => 'foo',
            'value'      => 1.0,
            'weight'     => 1.0,
            'timestamp'  => '2023-12-04 06:27:13',
            'meta'       => []
        ]);
        $this->assertEquals(1, $kpi->getUserId());
        $this->assertEquals('foo@mail.com', $kpi->getEmail());
        $this->assertEquals('foo', $kpi->getExternalID());
        $this->assertEquals(1.0, $kpi->getValue());
        $this->assertEquals(1.0, $kpi->getWeight());
        $this->assertEquals('2023-12-04 06:27:13', $kpi->getTimestamp()->format('Y-m-d H:i:s'));
        $this->assertEquals([], $kpi->getMeta());
    }

    public function testFromArrayWithoutUserIdEmailOrExternalID()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Either user_id, email or external_id must be set');
        KPI::fromArray([
            'value'      => 1.0,
            'weight'     => 1.0,
            'timestamp'  => '2023-12-04 06:27:13',
            'meta'       => []
        ]);
    }


    public function testUsingKPIAsString()
    {
        $kpi = KPI::fromArray([
            'userId'      => 1,
            'email'       => 'foo@mail.com',
            'external_id' => 'foo',
            'value'       => 1.0,
            'weight'      => 1.0,
            'timestamp'   => '2023-12-04 06:27:13',
            'meta'        => []
        ]);
        $this->assertEquals(json_encode([
            'user_id'   => 1,
            'value'     => 1.0,
            'weight'    => 1.0,
            'timestamp' => '2023-12-04 06:27',
            'meta'      => [],
        ]), (string)$kpi);
    }

}
