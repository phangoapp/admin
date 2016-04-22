<?php

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Forms\SelectForm;
use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaView\View;

function AdminListView($admin)
{
    echo View::show_flash();
    
    if($admin->list->yes_search==1)
    {
    
        $admin->list->search_by_url();
        
        $select=new SelectForm('field_search', $_GET['field_search']);
        
        //$select->arr_select=$admin->list->load_fields_showed($admin->list->arr_fields_no_search);
        
        foreach($admin->list->arr_fields_search as $field)
        {
        
            $select->arr_select[$field]=Webmodel::$model[$admin->model_name]->forms[$field]->label;
        
        }
        
        if($_GET['field_search']=='')
        {
        
            $select->default_value=$admin->list->default_field_search;
        
        }
        
        $select_order=new SelectForm('order', $_GET['order']);
        
        $select_order->arr_select=array(0 => I18n::lang('common', 'asc', 'Ascendent'), 1 => I18n::lang('common', 'desc', 'Descendent') );
        
        $search=new BaseForm('search', $_GET['search']);
        
        
        ?>
        <div class="cont search">
            <form method="get" action="<?php echo $admin->url; ?>">
                <?php echo I18n::lang('common', 'search', 'Search'); ?>
                <?php echo $search->form(); ?>
                <?php echo $select->form(); ?><?php echo $select_order->form(); ?>
                <input type="submit" value="<?php echo I18n::lang('common', 'search', 'Search'); ?>" />
                <input type="reset" value="<?php echo I18n::lang('common', 'reset', 'Reset'); ?>" onclick="javascript:location.href='<?php echo $admin->url; ?>';"/>
            </form>
        </div>
        <?php
    }
    
    if(!$admin->no_insert)
    {
    
    ?>
        <p><a href="<?php echo Routes::add_get_parameters($admin->url, array('op_admin' => 1)); ?>"><?php echo $admin->text_add_item; ?></a></p>
    <?php
    
    }
    
    $admin->list->show();

}

?>
