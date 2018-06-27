<?php

use delikatesnsk\treedropdown\DropdownTreeListAsset;


/**
 * @var $this \yii\web\View
 * @var $widget \insperedia\treedropdown\DropdownTreeWidget
 * @var $htmlData string
 */
DropdownTreeListAsset::register($this);
$widget = $this->context;
$attribute = $widget->attribute;
?>

    <div class="dropdown-tree-container"<?= ( isset( $widget->id ) && $widget->id != "" ? "id=\"".$widget->id."\"" : ""); ?>>
        <?= ( is_string( $widget->label ) ? "<label>".$widget->label."</label>" : "") ?>
        <div class="form-control tree-input">
            <div class="icon">
                <span class="input-clear fa fa-times-circle hide"></span>
                <span class="caret"></span>
            </div>
            <?php
                if ( $widget->multiSelect ) {

                    ?><ul><li class="empty">&nbsp;</li></ul><?php
                } else {
                    ?><span>&nbsp;</span><?php
                }
            ?>
        </div>
        <div class="tree-dropdown dropdown-menu" role="menu">
            <?php
            if ( is_array( $widget->searchPanel ) && isset( $widget->searchPanel['visible'] ) && $widget->searchPanel['visible'] ) {
                ?>
                <div class="tree-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <span class="header-text"><?= ( isset( $widget->searchPanel['label'] ) ? $widget->searchPanel['label'] : '' ); ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="search-clear">&times;</span>
                            <input class="form-control search-input" name="search-input" type="text" placeholder="<?= ( isset( $widget->searchPanel['placeholder'] ) ? $widget->searchPanel['placeholder'] : '' ); ?>" />
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="tree">
                <?php
                    if ( isset( $widget->items ) && is_array( $widget->items ) && count( $widget->items ) > 0 ) {
                        if ( is_array( $widget->rootNode ) && isset( $widget->rootNode['visible'] ) && $widget->rootNode['visible'] ) {
                        ?>
                            <ul>
                                <li class="parent">
                                    <div class="root node">
                                        <i class="fa fa-minus-square-o"></i>
                                        <span><?= ( isset( $widget->rootNode['label'] ) ? $widget->rootNode['label'] : '' ); ?></span>
                                    </div>
                        <?php
                        }

                        echo $htmlData;

                        if ( is_array( $widget->rootNode ) && isset( $widget->rootNode['visible'] ) && $widget->rootNode['visible'] ) {
                        ?>
                                </li>
                            </ul>
                        <?php
                        }
                    }
                ?>
            </div>
        </div>
        <?php
        if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) &&
            $widget->model !== null && ( $widget->model instanceof \yii\base\Model ) && $attribute != '') {
            echo $widget->form->field($widget->model, $attribute.( is_array( $widget->model->$attribute ) ? "[]" : "" ))->hiddenInput()->label(false);
        }
        ?>
    </div>

<?php
$id = ( isset( $widget->id ) && $widget->id != "" ? "#".$widget->id : ".dropdown-tree-container");
$js = "
$(document).ready(function() {
    $('".$id." .tree ul li .node i.fa-plus-square-o').each(function(){
        $(this).parent().parent().children('ul').children('li').hide();
    });
    $('".$id." .tree-input').on('click', function(){
        if ( !$(this).children('.icon').children('.caret').hasClass('up') ) {
            $(this).children('.icon').children('.caret').addClass('up');
            $(this).parent().children('.tree-dropdown').addClass('open');
        } else {
            $(this).children('.icon').children('.caret').removeClass('up');
            $(this).parent().children('.tree-dropdown').removeClass('open');
        }
    });
    $('" . $id . " .tree-header .search-clear').on('click', function(){
        $('" . $id . " input[name=search-input]').val('');
        $('" . $id . " .tree ul li.hide').removeClass('hide');
    });
    $('" . $id . " .tree-header input[name=search-input]').on('keyup', function(e,v){
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode.toString() != 8) {
            var text = $(this).val()".( !is_array( $widget->searchPanel ) || !isset( $widget->searchPanel['searchCaseSensivity'] ) || !$widget->searchPanel['searchCaseSensivity']  ? ".toUpperCase()" : "" ).";
            $('" . $id . " .tree ul li .node:not(.root) span').each(function(){
                var spanText = $(this).text()".( !is_array( $widget->searchPanel ) || !isset( $widget->searchPanel['searchCaseSensivity'] ) || !$widget->searchPanel['searchCaseSensivity']  ? ".toUpperCase()" : "" ).";
                if ( spanText.indexOf( text ) == -1 ) {
                    $(this).parent().parent().addClass('hide');
                }
            });
         } else {
            $('" . $id . " .tree ul li.hide').removeClass('hide');
         }   
    });
    $('" . $id . " .tree-header input[name=search-input]').on('paste', function(){
        $('" . $id . " .tree ul li .node:not(.root) span:not(:contains( \$this.val() ))').parent().parent().addClass('hide');
    });
    $('" . $id . " .tree-input .icon .input-clear').on('click', function(e){
        e.stopPropagation();
        if ( $('" . $id . " .tree-input > ul').length != 0 ) {
            while ( $('" . $id . " > .form-group input[type=hidden]').length > 1 ) {
                 $('" . $id . " > .form-group input[type=hidden]:last-child').remove();
            }
            $('" . $id . " .tree-input > ul li').remove();
            $('" . $id . " .tree-input > ul').append('<li class=\"empty\">&nbsp;</li>');
            $('" . $id . " .tree .node .fa-check-square-o').removeClass('fa-check-square-o').addClass('fa-square-o');
        } else {
            $('" . $id . " .tree-input > span').html('&nbsp;');
        }
        $('" . $id . " > .form-group input[type=hidden]:eq(0)').val('');
        $('" . $id . " .tree-input .icon .input-clear').addClass('hide');
    });
    
    $('".$id." .tree ul li .node i').on('click', function(){
        if ( $(this).hasClass('fa-minus-square-o') ) {
            $(this).parent().parent().children('ul').children('li').hide();
            $(this).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
        } else if ( $(this).hasClass('fa-plus-square-o') ) {
            $(this).parent().parent().children('ul').children('li').show();
            $(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
        }
        
 ";
if ( $widget->multiSelect ) {
    $js .= "else if ( $(this).hasClass('fa-check-square-o') ) {
            $(this).removeClass('fa-check-square-o').addClass('fa-square-o');
            $('" . $id . " .tree-input ul li[data-id='+( $(this).next('span').attr('data-id') )+']').remove();
            if ( $('" . $id . " .tree-input ul li').length == 0 ) {
                $('" . $id . " .tree-input ul').append('<li class=\"empty\">&nbsp;</li>');
                $('" . $id . " .tree-input .icon .input-clear').addClass('hide');
            }
            if ( $('" . $id . " > .form-group input[type=hidden]').length > 1 ) {
                $('" . $id . " > .form-group input[type=hidden]:last-child').remove();
            } else {
                $('" . $id . " > .form-group input[type=hidden]:eq(0)').val('');
            } 
        } else if ( $(this).hasClass('fa-square-o') ) {
            $(this).removeClass('fa-square-o').addClass('fa-check-square-o');
            $('" . $id . " .tree-input ul li.empty').remove();
            $('" . $id . " .tree-input ul').append('<li data-id='+( \$(this).next('span').attr('data-id') )+'>'+$(this).next('span').html()+'</li>');
            if ( $('" . $id . " > .form-group input[type=hidden]').length != 0 ) {
                if ( $('" . $id . " > .form-group input[type=hidden]:eq(0)').val() != '' ) {
                
                    $('" . $id . " > .form-group input[type=hidden]:eq(0)').clone().appendTo('" . $id . " > .form-group');
                    $('" . $id . " > .form-group input[type=hidden]:last-child').val( $(this).next('span').attr('data-id') );
                } else {
                    $('" . $id . " > .form-group input[type=hidden]:eq(0)').val( $(this).next('span').attr('data-id') );
                }
            }
            $('" . $id . " .tree-input .icon .input-clear').removeClass('hide');
        }
    });    
";
} else {
    $js .= "});    
    $('" . $id . " .tree ul li .node:not(.root) > span').on('click', function(){
        $('" . $id . " > .form-group input[type=hidden]:eq(0)').val( $(this).attr('data-id') );
        $('" . $id . " .tree-input > span').html( $(this).html() );
        $('" . $id . " .tree-input .icon .input-clear').removeClass('hide');
        $('" . $id . " .tree-input').click();
        
    });
    ";
}
if ( $widget->expand ) {
    $js .= "$('" . $id . " .tree-input').click();";
}
if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) ) {
    $js .= "$('#" . $widget->form->id . "').on('beforeValidate', function(){
        if ( $('" . $id . " .tree-dropdown').hasClass('open') ) { 
            $('" . $id . " .tree-input').click();
        };
    });
    $('#" . $widget->form->id . "').on('afterValidate', function(event, messages){
        //var attributes = $(this).data().yiiActiveForm.attributes;
        //var settings = $(this).data().yiiActiveForm.settings;
        if ( $('" . $id . " > .form-group .help-block').html() != '' ) {
            $('" . $id . " > label').addClass('has-error');
            $('" . $id . " .tree-input').addClass('has-error');
        } else { 
            $('" . $id . " > label').removeClass('has-error').addClass('has-success');
            $('" . $id . " .tree-input').removeClass('has-error').addClass('has-success'); 
        }
    });
    ";
}
if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) &&
    $widget->model !== null && ( $widget->model instanceof \yii\base\Model ) && $attribute != '') {

    $attributes = ( is_array( $widget->model->$attribute ) ? $widget->model->$attribute : [ $widget->model->$attribute ] );

    if ( is_array( $attributes ) && count( $attributes ) > 0 ) {
        foreach ( $attributes as $attribute ) {
            if ( $attribute !== null ) {
                if ( $widget->multiSelect ) {
                    $js .= "$('".$id." .tree ul li .node span[data-id=\"".$attribute."\"]').prev('.fa-square-o').click();\n";
                } else {
                    $js .= "$('".$id." .tree ul li .node span[data-id=\"".$attribute."\"]').click();\n";
                }
            }
        }
    }
}

$js .= "});";

$this->registerJs( $js );
?>