<?php
return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Ãîñòü',
    ),
    'user' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Ïîëüçîâàòåëü',
        'children' => array(
            'guest', 
        ),
    ),
    'poster' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Ïîñòåğ',
        'children' => array(
            'user',         
        ),
    ),
    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Àäìèíèñòğàòîğ',
        'children' => array(
            'poster',
        ),
    ),
    'owner' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Âëàäåëåö',
        'bizRule'=>'return Yii::app()->user->id===$params["userId"];',
        'children' => array(
            'user',
        ),
    ),
);