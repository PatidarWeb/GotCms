<?php
namespace Gc\Layout;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:10.
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        $model = Model::fromArray(array(
            'name' => 'name-collection-test',
            'identifier' => 'identifier-collection-test',
            'description' => 'description-collection-test',
            'content' => 'content-collection-test'
        ));

        $model->save();
        $this->_object = new Collection;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $model = Model::fromIdentifier('identifier-collection-test');
        $model->delete();
        unset($this->_object);
    }

    /**
     * @covers Gc\Layout\Collection::init
     */
    public function testInit()
    {
        $this->_object->init();
        $layouts = $this->_object->getLayouts();
        $this->assertTrue(count($layouts) >= 1);
    }

    /**
     * @covers Gc\Layout\Collection::getLayouts
     */
    public function testGetLayouts()
    {
        $layouts = $this->_object->getLayouts();
        $this->assertTrue(count($layouts) >= 1);
    }

    /**
     * @covers Gc\Layout\Collection::getSelect
     */
    public function testGetSelect()
    {
        $layouts = $this->_object->getSelect();
        $this->assertTrue(count($layouts) >= 1);
    }
}
