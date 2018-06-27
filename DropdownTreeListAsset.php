<?php
namespace delikatesnsk\treedropdown;

use yii\web\AssetBundle;

class DropdownTreeListAsset extends AssetBundle
{
    public $sourcePath = '@vendor/delikates-nsk/yii2-tree-dropdown/assets';

    public $js = [
    ];

    public $css = [
        'css/treedropdown.css?v=1',
    ];
}