Yii2 multiple input change log
==============================

1.2.17 in development
---------------------

1.2.16
------

- Bug #70: replacing of the placeholder after preparing the content of row

1.2.15
------

- Added note about usage widget with ajax

1.2.14
------

- Bug #71: trigger the event after actual removal of row

1.2.13
------

- Added new js events (add/remove/clear inputs) and integrated the gulp for minification of assets (veksa)
- Added support of closure for parameter `options` (veksa)

1.2.12
------

- Hotfix: Fixed error when array_key_exits (kongoon)

1.2.11
------

- Bug #61: Fixed a rendering of remove button
- Bug #62: Incorrect behavior is case when `min` is equal to `limit`
- Bug #64: Radio/checkbox lists doesn't work correctly

1.2.10
------

- Enh #59 Added columnClass property (unclead)

1.2.9
-----

- Enh #56: add `rowOptions` property

1.2.8
-----

- Enh: Don't show action column when limit is `equal` to `min`

1.2.7
-----

- Bug #55: Attach click events to the widget wrapper instead of `$(document)`

1.2.6
-----

- Bug #49: urlencoded field token replacement in js template (rolmonk)
- Enh #48: Added option `min` for setting minimum number of rows
- Enh: added option `addButtonPosition`

1.2.5
-----

- Bug #46: Renamed placeholder to avoid conflict with other plugins
- Bug #47: Use Html helper for rendering buttons instead of Button widget
- Enh: Deleted yii2-bootstrap dependency 

1.2.4
-----

- Bug #39: TabularInput: now new row does't copy values from  the most recent row
- Enh #40: Pass the current row for removal when calling `beforeDeleteRow` event


1.2.3
-----

- Enh #34: Added option `allowEmptyList` (unclead)
- Enh #35: Added option `enableGuessTitle` for MultipleInput (unclead)
- Bug #36: Use PCRE_MULTILINE modifier in regex

1.2.2
-----

- Enh #31: Added support of anonymous function for `items` attribute (unclead, stepancher)
- Enh: added hidden field for radio and checkbox inputs (unclead, kotchuprik)
- Enh: improved css (fiamma06)

1.2.1
-----

- Bug #25 fixed rendering when data is empty
- Bug #27 fixed element's prefix generation

1.2.0
-----

- Bug #19 Refactoring rendering of inputs (unclead)
- Bug #20 Added hasAttribute checking for AR models (unclead)
- Enh #22 Added `TabularInput` widget (unclead), rendering logic has been moved to separate class (renderer)

1.1.0
-----

- Bug #17: display inline errors (unclead, mikbox74)
- Enh #11: Improve js events (unclead)
- Bug #16: correct use of defaultValue property (unclead)
- code improvements (unclead)

1.0.4
--------------------

- Bug #15: Fix setting current values of dropDownList (unclead)
- Bug #16: fix render of dropDown and similar inputs (unclead)
- Enh: Add attributeOptions property

1.0.3
-----
- Bug: Hidden fields no longer break markup (unclead, kotchuprik)

1.0.2
-----

- Enh: added minified version of js script (unclead)
- Enh #8: renamed placeholders for avoid conflicts with other widgets (unclead)
- Enh #7: customization of header cell

1.0.1
-----

- Enh #1: Implemented ability to use widget as column type (unclead)
- Enh: add js events (ZAYEC77)

1.0.0
-----

first stable release
