<?php
namespace App\Utils;
use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminOptionList extends CategoryTreeAbstract {

    public function getCategoryList(array $categories_array, int $repeat = 0)
    {
        /* this time, for option list,  it will be returned as assoc. array (name, id) not as html string */
        foreach ($categories_array as $value)
        {
            /* $repeat will add dashes to children to distingush the subcategories in option list*/
            $this->categorylist[] = ['name'=> str_repeat("-",$repeat).$value['name'], 'id'=>$value['id']];

            if(!empty($value['children']))
            {
                $repeat = $repeat + 2;
                $this->getCategoryList($value['children'],$repeat);
                $repeat = $repeat - 2;
            }

        }
        return $this->categorylist;
    }

}
