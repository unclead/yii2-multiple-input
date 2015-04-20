#Single column example

For example your application contains the model `User` that has the related model `UserEmail` 
You can add virtual attribute `emails` for collect emails from form and then you can save them to database. 

In this case you can use `yii2-multiple-input` widget for supporting multiple inputs how to describe below.

First of all we have to declare virtual attribute in model

```
class ExampleModel extends Model
{
    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;
```