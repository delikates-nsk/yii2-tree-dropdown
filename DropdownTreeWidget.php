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
    public $ajax = null;
    //[
    //  'onNodeExpand' => [
    //                  'url' => '', URL for ajax request
    //                  'method' => 'post', //post or get
    //                  'params' => [
    //                                  'param1' => 'value1',
    //                                  'param2' => 'value1',
    //                                  'param3' => 'value1',
    //                                   ...
    //                                  'paramN' => 'valueN',
    //                                  'someparamName' => '%nodeId' // <-- %nodeId replaced to id of current node
    //                              ]
    //                  //returned Data will be array of array
    //                  //[
    //                  //  [
    //                  //      'id' =>
    //                  //      'label' =>
    //                  //      'items' => [
    //                  //                  [
    //                  //                      'id' =>
    //                  //                      'label' =>
    //                  //                  ],
    //                  //                  ... more
    //                  //                 ]
    //                  //  ],
    //                  //  ... more
    //                  //]
    //                ],
    //  'onNodeCollapse' => [
    //                      ... see OnExpand, but the returned data will not be processed, only send ajax request
    //                  ],
    //]
    public $label = false; //label of dropdown

    public $items = null; //array of tree nodes with subnodes

    private $html = '';
    private $treeObject = null;

    private function buildTreeObject( $items, &$parentItem = null ) {
        if ( is_array( $items ) ) {
            foreach ($items as $item) {
                if ( is_array( $item ) && isset( $item['id'] ) && isset( $item['label'] ) ) {
                    $node = new \stdClass();
                    $node->parent = $parentItem;
                    $node->id = $item['id'];
                    $node->label = $item['label'];
                    if ( isset( $item['items'] ) && is_array( $item['items'] ) && ( count( $item['items'] ) > 0 || $this->ajax !== null ) ) {
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
                if (is_object( $item ) && isset( $item->id ) && isset( $item->label ) ) {
                    if ( $index == 0 ) {
                        //Если parent у item последний Node у своего parent добавляем класс last-node
                        $class =  ( isset( $item->parent ) && $item->parent !== null && isset( $item->parent->parent ) && $item->parent->parent !== null && $item->parent->parent->items[ count( $item->parent->parent->items ) - 1] == $item->parent ?  " class=\"last-node\"" : "" );
                        $this->html .= "<ul".$class.">\n";
                    }

                    $this->html .= "<li".( isset( $item->items ) && is_array( $item->items ) ? " class=\"parent\"" : "" ).">\n";
                    $this->html .= "    <div class=\"node\">\n";
                    $this->html .= "        ".( isset( $item->items ) && is_array( $item->items ) && ( count( $item->items ) > 0 || $this->ajax !== null ) ? "<i class=\"fa fa-plus-square-o\"></i>\n" : "" );
                    $this->html .= "        ".( $this->multiSelect ? "<i class=\"fa fa-square-o\"></i>" : "" );
                    $this->html .= "        <span".( ( isset( $item->id ) ? " data-id='".$item->id."'" : "" ) ).">".( isset( $item->label ) ? $item->label : "&nbsp;" )."</span>\n";
                    $this->html .= "    </div>\n";
                    if ( isset( $item->items ) && is_array( $item->items ) && ( count( $item->items ) > 0 || $this->ajax !== null )  ) {
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