# Clonning

![Clone button example](https://raw.githubusercontent.com/unclead/yii2-multiple-input/master/resources/images/clone-button.gif)

```text
use unclead\multipleinput\MultipleInput;

...

$form->field($model, 'products')->widget(MultipleInput::className(), [
    'max' => 10,
    'cloneButton' => true,
    'columns' => [
        [
            'name'  => 'product_id',
            'type'  => 'dropDownList',
            'title' => 'Special Products',
            'defaultValue' => 1,
            'items' => [
                1 => 'id: 1, price: $19.99, title: product1',
                2 => 'id: 2, price: $29.99, title: product2',
                3 => 'id: 3, price: $39.99, title: product3',
                4 => 'id: 4, price: $49.99, title: product4',
                5 => 'id: 5, price: $59.99, title: product5',
            ],
        ],
        [
            'name'  => 'time',
            'type'  => DateTimePicker::className(),
            'title' => 'due date',
            'defaultValue' => date('d-m-Y h:i')
        ],
        [
            'name'  => 'count',
            'title' => 'Count',
            'defaultValue' => 1,
            'enableError' => true,
            'options' => [
                'type' => 'number',
                'class' => 'input-priority',
            ]
        ]
    ]
])->label(false);
```



