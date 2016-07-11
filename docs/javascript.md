#JavaScript events

This widget has following events:
 - `afterInit`: triggered after initialization
 - `afterAddRow`: triggered after new row insertion
 - `beforeDeleteRow`: triggered before the row removal
 - `afterDeleteRow`: triggered after the row removal

**Example**

```js
jQuery('#multiple-input').on('afterInit', function(){
    console.log('calls on after initialization event');
}).on('beforeAddRow', function(e) {
    console.log('calls on before add row event');
}).on('afterAddRow', function(e) {
    console.log('calls on after add row event');
}).on('beforeDeleteRow', function(e, row){
    // row - HTML container of the current row for removal.
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('Are you sure you want to delete row?')
}).on('afterDeleteRow', function(){
    console.log('calls on after remove row event');
});
```

#JavaScript operations

Dynamically operations in widget:
 - `add`: adding new row, **param** *object*: object with values for inputs, can be filled with <option> tags for dynamically added options for select (for ajax select).
 - `remove`: remove row, **param** *integer*: row number for removing, if not specified then removes last row.
 - `clear`: remove all rows

**Examples**

```js
$('#multiple-input').multipleInput('add', {first: 10, second: '<option value="2" selected="selected">second</option>'});
$('#multiple-input').multipleInput('remove', 2);
$('#multiple-input').multipleInput('clear');
```