<?php
namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminList extends CategoryTreeAbstract
{
    public $html_1 = '<ul class="fa-ul text-left">';
    public $html_2 = '<li><i class="fa-li fa fa-arrow-right"></i>  ';
    public $html_3 = '<a href="';
    public $html_4 = '">';
    public $html_5 = '</a> <a onclick="return confirm(\'Are you sure?\');" href="';
    public $html_6 = '">';
    public $html_7 = '</a>';
    public $html_8 = '</li>';
    public $html_9 = '</ul>';

    public function getCategoryList(array $categories_array)
        /* overrided abstract method to return html version of subcategories array*/
    {
        $this->categorylist .= $this->html_1;  //public attribute that will save the html
        foreach ($categories_array as $value)
        {
            /* generate url for editing and deleting category*/
            $url_edit = $this->urlgenerator->generate('edit_category', ['id' => $value['id']]);
            $url_delete = $this->urlgenerator->generate('delete_category', ['id' => $value['id']]);
            $this->categorylist .= $this->html_2 . $value['name'] . $this->html_3 . $url_edit . $this->html_4 . ' Edit'
                . $this->html_5 . $url_delete . $this->html_6 . 'Delete' . $this->html_7;
            if (!empty($value['children']))  // if it has children, continue nested category
            {
                $this->getCategoryList($value['children']); //recall same method until end of tree
            }
            $this->categorylist .= $this->html_8;
        }
        $this->categorylist .= $this->html_9;
        return $this->categorylist;
    }

}
