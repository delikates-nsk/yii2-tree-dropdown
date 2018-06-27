<?php
namespace delikatesnsk\treedropdown;

class DropdownTreeWidget extends \yii\base\Widget
{
    public $form = null; //ActiveForm
    public $model = null; //model
    public $attribute = null; //model attribute
    public $multiSelect = 'auto'; //true, false or 'auto'
    public $searchPanel = [ 'visible' => false, 
                            'label' => '', //text before search input 
                            'placeholder' => '',  //serch input placeholder text
                            'searchCaseSensivity' => false 
                          ];
    public $rootNode = [
                          'visible' => true,  
                          'label' => 'Root' 
                       ];

    public $expand = false; //expand dropdown tree after show
 
   public $label = false; //label of dropdown

    public $items = null; //array of tree nodes with subnodes

    private $html = '';
    private $treeObject = null;

    private function isValidNode( $item ) {
        return ( is_object( $item ) && isset( $item->id ) && isset( $item->label ) );
    }

    private function hasChildrens( $item ) {
        return ( isset( $item->items ) && is_array( $item->items ) && count( $item->items ) > 0 );
    }

    private function buildTreeObject( $items, &$parentItem = null ) {
        if ( is_array( $items ) && count( $items ) > 0 ) {
            foreach ($items as $item) {
                if ( is_array( $item ) && isset( $item['id'] ) && isset( $item['label'] ) ) {
                    $node = new \stdClass();
                    $node->parent = $parentItem;
                    $node->id = $item['id'];
                    $node->label = $item['label'];
                    if ( isset( $item['items'] ) && is_array( $item['items'] ) && count( $item['items'] ) > 0 ) {
                        $node->items = [];
                        $this->buildTreeObject( $item['items'], $node );
                    }
                    $parentItem->items[] = $node;
                }
            }
        }
    }

    public function buildTreeView( $items ) {
        if ( is_array( $items ) && count( $items ) > 0 ) {
            foreach( $items as $index => $item ) {
                if ( $this->isValidNode( $item ) ) {
                    if ( $index == 0 ) {
                        //Если parent у item последний Node у своего parent добавляем класс last-node
                        $class =  ( isset( $item->parent ) && $item->parent !== null && isset( $item->parent->parent ) && $item->parent->parent !== null && $item->parent->parent->items[ count( $item->parent->parent->items ) - 1] == $item->parent ?  " class=\"last-node\"" : "" );
                        $this->html .= "<ul".$class.">\n";
                    }

                    $this->html .= "<li".( $this->hasChildrens( $item ) ? " class=\"parent\"" : "" ).">\n";
                    $this->html .= "    <div class=\"node\">\n";
                    $this->html .= "        ".( $this->hasChildrens( $item ) ? "<i class=\"fa fa-plus-square-o\"></i>\n" : "" );
                    $this->html .= "        ".( $this->multiSelect ? "<i class=\"fa fa-square-o\"></i>" : "" );
                    $this->html .= "        <span".( ( isset( $item->id ) ? " data-id='".$item->id."'" : "" ) ).">".( isset( $item->label ) ? $item->label : "&nbsp;" )."</span>\n";
                    $this->html .= "    </div>\n";
                    if ( $this->hasChildrens( $item ) ) {
                        $this->buildTreeView( $item->items );
                    }
                    $this->html .= "</li>\n";

                    if ( $index == count( $items ) - 1 ) {
                        $this->html .= "</ul>\n";
                    }
                }
            }
        }
    }

    public function init()
    {
        parent::init();
        if ( !is_bool( $this->multiSelect ) ) {

            $multiSelect = false;

            if ( mb_convert_case( $this->multiSelect, MB_CASE_LOWER ) == 'auto'  ) {

                if ( $this->form !== null && ( $this->form instanceof \yii\widgets\ActiveForm ) && $this->model !== null && $this->model[0] instanceof \yii\base\Model ) {
                    $multiSelect = true;
                }
            }

            $this->multiSelect = $multiSelect;
        }
        $this->treeObject = new \stdClass();
        $this->treeObject->id = -1;
        $this->treeObject->label = 'Root';
        $this->treeObject->items = [];
        $this->buildTreeObject($this->items, $this->treeObject );
        $this->buildTreeView( $this->treeObject->items );
    }

    public function run()
    {
        return $this->render('view', ['htmlData' => $this->html]);
    }
}