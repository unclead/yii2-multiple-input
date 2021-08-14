# Using other icon libraries

Multiple input and Tabular input widgets now support FontAwesome and indeed any other icon library you chose to integrate into your project.

To take advantage of this, please proceed as follows:

1. Include the preferred icon library in your project. If you wish to use **font awesome**, you can use the included FontAwesomeAsset which will integrate the free fa from their CDN;
2. Add a mapping for your preferred icon library if it is not in the `iconMap` array of the widget, like the following;

```text
public $iconMap = [
    'glyphicons' => [
        'drag-handle' => 'glyphicon glyphicon-menu-hamburger',
        'remove' => 'glyphicon glyphicon-remove',
        'add' => 'glyphicon glyphicon-plus',
        'clone' => 'glyphicon glyphicon-duplicate',
    ],
    'fa' => [
        'drag-handle' => 'fa fa-bars',
        'remove' => 'fa fa-times',
        'add' => 'fa fa-plus',
        'clone' => 'fa fa-files-o',
    ],
    'my-amazing-icons' => [
        'drag-handle' => 'my my-bars',
        'remove' => 'my my-times',
        'add' => 'my my-plus',
        'clone' => 'my my-files',
    ]
];
```

3. Set the preferred icon source

```text
    public $iconSource = 'my-amazing-icons';
```

If you do none of the above, the default behavior which assumes you are using `glyphicons` is retained.

