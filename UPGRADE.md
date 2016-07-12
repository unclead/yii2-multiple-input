Upgrading Instructions for yii2-multiple-widget
===============================================

!!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to following the instructions
for both A and B.

Upgrade from 1.2 to 1.3
-----------------------

- The mechanism of customization configuration by using index placeholder was changed in scope of implementing support of nested `MultipleInput`
If you customize configuration by using index placeholder you have to add ID of widget to the placeholder.
For example, `multiple_index` became `multiple_index_question_list`


Upgrade from version less then 1.1.0
------------------------------------

After installing version 1.1.0 you have to rename js events following the next schema:

- Event `init` rename to `afterInit` 
- Event `addNewRow` rename to `afterAddRow`
- Event `removeRow` rename to `afterDeleteRow` 