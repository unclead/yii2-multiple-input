# yii2-multiple-field


 * For example, model as an attribute contacts and you want to add to user ability input more than one
 * value.
 *
 * In this case you have to use this wi
 *
 * Пример использования виджета в случае, когда используется 1 атрибут, а на выходе надо
 * получить массив со значениями сгруппированными по определенному ключу.
 * Например, у нас есть атрибут contacts и он может быть разных типов, например email
 *
 * Пример конфигурации может быть следующим:
 *
 * $form->field($model, 'contacts')->widget(MultipleInput::className(), [
 *      'limit' => 4,
 *      'name'  => $model->formName() . '[contacts]',
 *      'data'  => $model->contacts[UserContact::getTypeEnum(UserContact::TYPE_PHONE)],
 *      'columns' => [
 *          [
 *              'name' => UserContact::getTypeEnum(UserContact::TYPE_PHONE),
 *              'value' =>  function ($data) {
 *                  return $data;
 *              }
 *          ]
 *      ]
 * ]);