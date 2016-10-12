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
}).on('afterDeleteRow', function(e, row){
    console.log('calls on after remove row event');
    console.log(row);
});
```

#JavaScript operations

####add

Adding new row with specified settings.

Input arguments:
- *object* - values for inputs, can be filled with <option> tags for dynamically added options for select (for ajax select).
 
Example:

```js
$('#multiple-input').multipleInput('add', {first: 10, second: '<option value="2" selected="selected">second</option>'});
```

####remove

Remove row with specified index.

Inout arguments:
- *integer* - row number for removing, if not specified then removes last row.

Example:

```js
$('#multiple-input').multipleInput('remove', 2);
```

####clear

Remove all rows

```js
$('#multiple-input').multipleInput('clear');
```

####option

Get or set a particular option

Input arguments:
- *string* - a name of an option
- *mixed* - a value of an option (optional). If specified will be used as a new value of an option;

Example:

```js
$('#multiple-input').multipleInput('option', 'max');
$('#multiple-input').multipleInput('option', 'max', 10);

```


