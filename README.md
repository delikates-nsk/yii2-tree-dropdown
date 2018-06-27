# Tree Dropdown

Dropdown with tree.
This is extension for Yii2 framework. 

## Usage

```php
<?php
    echo \delikates-nsk\treedropdown\DropdownTreeWidget::widget([
        'id' => 'organizationsList', //<-- id of dropdown container
        'form' => $form, // <-- ActiveForm (for generate hidden input)
        'model' => $model, // <-- Model  (for generate hidden input)
        'attribute' => 'org_id', //<-- Model attribute  (for generate hidden input)
        'label' => \Yii::t('app', 'Organization'), //Label of dropdown
        'multiSelect' => true, //multiple select dropdown items (true, false, 'auto')
        'searchPanel' => [
                             'visible' => true, //show or hide search panel
                             'label' => \Yii::t('app', 'Choose Organization'), //title for search panel
                             'placeholder' => '',  //serch input placeholder text
                             'searchCaseSensivity' => false //search text inside dropdown width caseSensivity or No
                         ], 
        'rootNode' => [
                          'visible' => true, //show tree root node or not 
                          'label' => \Yii::t('app', 'Root Node') //label for root node
                      ],
        'expand' => false, //expand dropdown tree after show
        'items' => [
                        [
                            'id' => 1,
                            'label' => 'Apple Inc.',
                            'items' => [
                                           [
                                               'id' => 2,
                                               'label' => 'Sale Department',
                                               'items' => [
                                                              [
                                                                  'id' => 3,
                                                                  'label' => 'Supervisors'
                                                              ],
                                                              [
                                                                  'id' => 4,
                                                                  'label' => 'Managers'
                                                              ],
                                                              [
                                                                  'id' => 5,
                                                                  'label' => 'Assistants'
                                                              ],
                                                          ]
                                           ],
                                           [
                                               'id' => 6,
                                               'label' => 'Contracts Department',
                                               'items' => [
                                                              [
                                                                  'id' => 7,
                                                                  'label' => 'Coordinators'
                                                              ],
                                                              [
                                                                  'id' => 8,
                                                                  'label' => 'Managers'
                                                              ],
                                                              [
                                                                  'id' => 9,
                                                                  'label' => 'Clerks'
                                                              ],
                                                          ]
                                           ],
                                           [
                                               'id' => 10,
                                               'label' => 'IT Department',
                                               'items' => [
                                                              [
                                                                  'id' => 11,
                                                                  'label' => 'Engineers'
                                                              ],
                                                              [
                                                                  'id' => 12,
                                                                  'label' => 'Programmers'
                                                              ],
                                                          ]
                                           ]

                                       ]
                        ],
                   ]
    ]);
?>
```
