# Getting started

I found this small guide here [https://stackoverflow.com/a/51849747](https://stackoverflow.com/a/51849747) and I think it is a good example of basic usage of the widget

## Question

I want to generate a different number of rows with values from my database. How can I do this?

I can design my columns in view and edit data manually after a page was generated. But miss how to program the number of rows and their values in the view.

My code is as follows:

```text
 <?= $form->field($User, 'User')->widget(MultipleInput::className(), [
        'min' => 0,
        'max' => 4,
        'columns' => [
            [
                'name'  => 'name',
                'title' => 'Name',
                'type' => 'textInput',
                'options' => [
                    'onchange' => $onchange,
                ],
            ],
            [
                'name'  => 'birth',
                'type'  => \kartik\date\DatePicker::className(),
                'title' => 'Birth',
                'value' => function($data) {
                    return $data['day'];
                },

                'options' => [
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true
                    ]
                ]
            ],

        ]
        ])->label(false);
```

How can I make \(for example\) 8 rows with different values, and also have the ability to edit/remove/update some of them?

## Answer

You need to look into the documentation as it says that you need to assign a separate field into the model which will store all the schedule in form of JSON and then provide it back to the field when editing/updating the model.

You have not added the appropriate model to verify how are you creating the field User in your given case above. so, I will try to create a simple example that will help you implement it in your scenario.

For Example.

You have to store a user in the database along with his favorite books.

```text
User
id, name, email

Books
id, name
```

Create a field/column in your User table with the name schedule of type text, you can write a migration or add manually. Add it to the rules in the User model as safe.

like below

```text
public function rules() {
    return [
        ....//other rules
        [ [ 'schedule'] , 'safe' ]
    ];
}
```

Add the widget to the newly created column in ActiveForm

```text
echo $form->field($model,'schedule')->widget(MultipleInput::class,[
    'max' => 4,
    'columns' => [
        [
            'name'  => 'book_id',
            'type'  => 'dropDownList',
            'title' => 'Book',
            'items' => ArrayHelper::map( Books::find()->asArray()->all (),'id','name'),
        ],
    ]

]);
```

When saving the User model convert the array to JSON string

```text
if( Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) ){
    $model->schedule = \yii\helpers\Json::encode($model->schedule);
    $model->save();
}
```

Override the afterFind\(\) of the User model to covert the JSON back to the array before loading the form

```text
public function afterFind() {
    parent::afterFind();
    $this->schedule = \yii\helpers\Json::decode($this->schedule);
}
```

Now when saved the schedule field against the current user will have the JSON for the selected rows for the books, as many selected, for example, if I saved three books having ids\(1,2,3\) then it will have JSON

```text
{
  "0": {
    "book_id": "1"
  },
  "2": {
    "book_id": "2"
  },
  "3": {
    "book_id": "3"
  }
}
```

The above JSON will be converted to an array in the afterFind\(\) so that the widget loads the saved schedule when you EDIT the record.

Now go to your update page or edit the newly saved model you will see the books loaded automatically.

