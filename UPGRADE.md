Upgrading Instructions for yii2-multiple-widget
===============================================

!!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to following the instructions
for both A and B.

Upgrade from version less then 1.1.0
------------------------------------

After installing version 1.1.0 you have to rename js events following the next schema:

- Event `init` rename to `afterInit` 
- Event `addNewRow` rename to `afterAddRow`
- Event `removeRow` rename to `afterDeleteRow` 