<?php
return array(
    'guest' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '�����',
    ),
    'user' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '������������',
        'children' => array(
            'guest', 
        ),
    ),
    'poster' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '������',
        'children' => array(
            'user',         
        ),
    ),
    'admin' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '�������������',
        'children' => array(
            'poster',
        ),
    ),
    'owner' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '��������',
        'bizRule'=>'return Yii::app()->user->id===$params["userId"];',
        'children' => array(
            'user',
        ),
    ),
);