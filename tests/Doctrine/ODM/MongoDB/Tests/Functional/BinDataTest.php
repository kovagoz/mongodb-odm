<?php

namespace Doctrine\ODM\MongoDB\Tests\Functional;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Tests\BaseTest;

class BinDataTest extends BaseTest
{
    /**
     * @dataProvider provideData
     */
    public function testBinData($field, $data, $type)
    {
        $test = new BinDataTestUser();
        $test->$field = $data;
        $this->dm->persist($test);
        $this->dm->flush();

        $check = $this->dm->getDocumentCollection(get_class($test))->findOne(array());
        $this->assertInstanceOf('MongoBinData', $check[$field]);
        $this->assertEquals($type, $check[$field]->type);
        $this->assertEquals($data, $check[$field]->bin);
    }

    public function provideData()
    {
        return array(
            array('bin', 'test', 0), // MongoBinData::GENERIC is only defined in driver 1.5+
            array('binFunc', 'test', \MongoBinData::FUNC),
            array('binByteArray', 'test', \MongoBinData::BYTE_ARRAY),
            array('binUUID', 'test', \MongoBinData::UUID),
            array('binUUIDRFC4122', '1234567890ABCDEF', 4), // MongoBinData::UUID_RFC4122 is only defined in driver 1.5+
            array('binMD5', 'test', \MongoBinData::MD5),
            array('binCustom', 'test', \MongoBinData::CUSTOM),
        );
    }
}

/** @ODM\Document */
class BinDataTestUser
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Bin */
    public $bin;

    /** @ODM\Bin(type="bin_func") */
    public $binFunc;

    /** @ODM\Bin(type="bin_bytearray") */
    public $binByteArray;

    /** @ODM\Bin(type="bin_uuid") */
    public $binUUID;

    /** @ODM\Bin(type="bin_uuid_rfc4122") */
    public $binUUIDRFC4122;

    /** @ODM\Bin(type="bin_md5") */
    public $binMD5;

    /** @ODM\Bin(type="bin_custom") */
    public $binCustom;
}
