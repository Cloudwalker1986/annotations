<?php
declare(strict_types=1);

namespace JsonTest;

use Autowired\DependencyContainer;
use Autowired\Exception\InterfaceArgumentException;
use DateTimeImmutable;
use Json\Exception\NotJsonSerializableException;
use Json\Resolver;
use JsonException;
use JsonTest\Example\ObjectA;
use JsonTest\Example\ObjectB;
use JsonTest\Example\ObjectC;
use JsonTest\Example\ObjectD;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ResolverTest extends TestCase
{
    /**
     * @test
     *
     * @throws ReflectionException
     * @throws JsonException
     * @throws InterfaceArgumentException
     */
    public function resolveSimpleJson(): void
    {
        $json = '{"fieldOne": "Hello World","fieldTwo": 1}';
        /** @var Resolver $resolver */
        $resolver = DependencyContainer::getInstance()->get(Resolver::class);

        /** @var ObjectA $object */
        $object = $resolver->fromJson(json_decode($json, true, 512, JSON_THROW_ON_ERROR), ObjectA::class);

        $this->assertInstanceOf(ObjectA::class,  $object);
        $this->assertEquals('Hello World', $object->getFieldOne());
        $this->assertEquals(1, $object->getFieldTwoThree());
    }
    /**
     * @test
     *
     * @throws ReflectionException
     * @throws JsonException
     * @throws InterfaceArgumentException
     */
    public function resolveMultidimensionalJson(): void
    {
        $json = '{"fieldOneThree":[{"fieldOne": {"fieldOne": "Hello World","fieldTwo": 1}},{"fieldOne": {"fieldOne": "Berlin","fieldTwo": 500}}], "time":"2022-01-01 00:00:00"}';
        /** @var Resolver $resolver */
        $resolver = DependencyContainer::getInstance()->get(Resolver::class);

        /** @var ObjectC $object */
        $object = $resolver->fromJson(
            json_decode($json, true, 512, JSON_THROW_ON_ERROR),
            ObjectC::class
        );

        $this->assertInstanceOf(ObjectC::class,  $object);
        $this->assertInstanceOf(DateTimeImmutable::class,  $object->getTime());
        $this->assertEquals('2022-01-01 00:00:00', $object->getTime()->format('Y-m-d H:i:s'));

        /** @var ObjectB $firstObjectB */
        $firstObjectB = $object->getFieldOne()->getByIndex(0);
        $this->assertInstanceOf(ObjectB::class, $firstObjectB);
        $this->assertEquals('Hello World', $firstObjectB->getFieldOne()->getFieldOne());
        $this->assertEquals(1, $firstObjectB->getFieldOne()->getFieldTwoThree());

        /** @var ObjectB $secondObjectB */
        $secondObjectB = $object->getFieldOne()->getByIndex(1);
        $this->assertInstanceOf(ObjectB::class, $secondObjectB);
        $this->assertEquals('Berlin', $secondObjectB->getFieldOne()->getFieldOne());
        $this->assertEquals(500, $secondObjectB->getFieldOne()->getFieldTwoThree());
    }

    /**
     * @test
     *
     * @throws ReflectionException
     * @throws InterfaceArgumentException
     * @throws JsonException
     */
    public function missingJsonSerializableAttribute(): void
    {
        $this->expectException(NotJsonSerializableException::class);
        $json = '{"fieldOne": "Hello World","fieldTwo": 1}';

        /** @var Resolver $resolver */
        $resolver = DependencyContainer::getInstance()->get(Resolver::class);
        $resolver->fromJson(json_decode($json, true, 512, JSON_THROW_ON_ERROR), ObjectD::class);
    }
}
