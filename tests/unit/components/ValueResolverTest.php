<?php
namespace unclead\multipleinput\tests\unit\components;

use unclead\multipleinput\components\ValueResolver;
use unclead\multipleinput\tests\unit\data\TestActiveRecord;
use unclead\multipleinput\tests\unit\data\TestActiveRecordRelated;
use unclead\multipleinput\tests\unit\data\TestModel;
use unclead\multipleinput\tests\unit\TestCase;
use yii\db\ActiveQuery;

class ValueResolverTest extends TestCase
{
    public function testResolveWithEmptyValue() {
        $defaultValue = 1;
        $columnName = 'columnName';

        $resolver = new ValueResolver();
        $this->assertEquals($defaultValue, $resolver->resolve($columnName, null, $defaultValue));
        $this->assertEquals($defaultValue, $resolver->resolve($columnName, [], $defaultValue));
        $this->assertEquals($defaultValue, $resolver->resolve($columnName, '', $defaultValue));
    }

    public function testResolveWhenDataIsStringOrNumber() {
        $columnName = 'columnName';

        $resolver = new ValueResolver();
        $this->assertEquals(100500, $resolver->resolve($columnName, 100500));
        $this->assertEquals('string', $resolver->resolve($columnName, 'string'));
    }

    public function testResolveWhenDataIsArray() {
        $data = [
            'test' => 'value'
        ];

        $resolver = new ValueResolver();
        $this->assertEquals('value', $resolver->resolve('test', $data));
    }

    public function testResolveModelProperty() {
        $expectedValue = 'test@test.com';

        $model = new TestModel();
        $model->email = $expectedValue;

        $resolver = new ValueResolver();
        $this->assertEquals($expectedValue, $resolver->resolve('email', $model));
    }

    public function testResolveActiveRecordDirectAttribute() {
        $expectedValue = 'test@test.com';

        $model = new TestActiveRecord();
        $model->email = $expectedValue;

        $resolver = new ValueResolver();
        $this->assertEquals($expectedValue, $resolver->resolve('email', $model));
    }

    public function testResolveActiveRecordRelation() {
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

        $resolver = new ValueResolver();

        $result = $resolver->resolve('testRelation', $model);

        $this->assertEquals($relatedModel, $result);
    }

    public function testResolveActiveRecordRelationWithSameNameAsAttributeName() {
        $relatedModel = new TestActiveRecordRelated();

        $model = new TestActiveRecord();
        $model->testRelation = $relatedModel;

        $resolver = new ValueResolver();
        $this->assertEquals($relatedModel, $resolver->resolve('testRelation', $model));
    }
}
