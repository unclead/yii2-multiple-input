<?php
namespace unclead\multipleinput\tests\unit\components;

use unclead\multipleinput\components\ValuePreparer;
use unclead\multipleinput\tests\unit\data\TestActiveRecord;
use unclead\multipleinput\tests\unit\data\TestActiveRecordRelated;
use unclead\multipleinput\tests\unit\data\TestModel;
use unclead\multipleinput\tests\unit\TestCase;
use yii\db\ActiveQuery;

class ValuePreparerTest extends TestCase
{
    public function testPrepareWithEmptyValue() {
        $model = new TestModel();
        $defaultValue = 1;
        $preparer = new ValuePreparer(null, $defaultValue);
        $this->assertEquals($defaultValue, $preparer->prepare(null));
        $this->assertEquals($defaultValue, $preparer->prepare([]));
        $this->assertEquals($defaultValue, $preparer->prepare(''));
    }

    public function testPrepareStringOrNumber() {
        $model = new TestModel();
        $preparer = new ValuePreparer();
        $this->assertEquals(1, $preparer->prepare(1));
        $this->assertEquals('1', $preparer->prepare('1'));
    }

    public function testPrepareArrayKey() {
        $model = new TestModel();
        $preparer = new ValuePreparer('test');
        $this->assertEquals(1, $preparer->prepare([
            'test' => 1
        ]));
    }

    public function testPrepareModelAttribute() {
        $model = new TestModel();
        $exprectedValue = [
            'test'
        ];
        $model->email = $exprectedValue;
        $preparer = new ValuePreparer('email');
        $this->assertEquals($exprectedValue, $preparer->prepare($model));
    }

    public function testPrepareActiveRecordDirectAttribute() {
        $model = new TestActiveRecord();
        $exprectedValue = 'test';
        $model->email = $exprectedValue;
        $preparer = new ValuePreparer('email');
        $this->assertEquals($exprectedValue, $preparer->prepare($model));
    }

    public function testPrepareActiveRecordRelation() {
        $relatedModel = new TestActiveRecordRelated();
        $model = $this->createMock(TestActiveRecord::class);
        $query = $this->createMock(ActiveQuery::class);
        $query->expects($this->once())
            ->method('findFor')
            ->with('testRelation', $model)
            ->willReturn($relatedModel);

        $model->expects($this->once())
            ->method('getRelation')
            ->with('testRelation', false)
            ->willReturn($query);

        $preparer = new ValuePreparer('testRelation');

        $result = $preparer->prepare($model);

        $this->assertEquals($relatedModel, $result);
    }

    public function testPrepareActiveRecordRelationWithSameAsAttributeName() {
        $model = new TestActiveRecord();
        $relatedModel = new TestActiveRecordRelated();
        $model->testRelation = $relatedModel;

        $preparer = new ValuePreparer( 'testRelation');
        $this->assertEquals($relatedModel, $preparer->prepare($model));
    }
}