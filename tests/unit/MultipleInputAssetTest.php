<?php

namespace unclead\multipleinput\tests\unit;
use unclead\multipleinput\assets\MultipleInputAsset;

/**
 * Class MultipleInputAssetTest
 * @package unclead\multipleinput\tests\unit
 */
class MultipleInputAssetTest extends TestCase
{
    public function testConstuctor() {
        $asset = new MultipleInputAsset();
        $this->assertIsString($asset->sourcePath);
        $this->assertCount(1, $asset->js);
        $this->assertCount(1, $asset->css);
    }

    public function testSetEmptyJsViaConfig()
    {
        $asset = new MultipleInputAsset([
            'js' => [],
            'css' => [],
            'sourcePath' => 'test',
        ]);
        $this->assertEquals('test', $asset->sourcePath);
        $this->assertCount(0, $asset->js);
        $this->assertCount(0, $asset->css);
    }
}
