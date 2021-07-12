<?php
namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;
use App\Twig\AppExtension;

class CategoryTreeFrontPage extends CategoryTreeAbstract {

    public $html_1 = '<ul>';
    public $html_2 = '<li>';
    public $html_3 = '<a href="';
    public $html_4 = '">';
    public $html_5 = '</a>';
    public $html_6 = '</li>';
    public $html_7 = '</ul>';

    public function getCategoryListAndParent(int $id): string
    {
        $this->slugger = new AppExtension(); // Twig extension to simplify url's for categories
        $parentData = $this->getMainParent($id); // root parent of subcategory
        $this->mainParentName = $parentData['name']; // for access in view file
        $this->mainParentId = $parentData['id']; // for access in view file
        $key = array_search($id, array_column($this->categoriesArrayFromDb,'id')); // finds position of calling category
        $this->currentCategoryName = $this->categoriesArrayFromDb[$key]['name']; // for access in view file
        $categories_array = $this->buildTree($parentData['id']); // builds nested array for generating the html list
        return $this->getCategoryList($categories_array);
    }

    public function getCategoryList(array $categories_array)
        /* converts the array with nested subcategories to html list string*/
    {
        $this->categorylist .= $this->html_1;
        foreach ($categories_array as $value)
        {
            $catName = $this->slugger->slugify($value['name']); // simplify it in lower chars and with dashes instead of white spaces

            $url = $this->urlgenerator->generate('video_list', ['categoryname'=>$catName, 'id'=>$value['id']]);
            $this->categorylist .= $this->html_2 . $this->html_3 . $url . $this->html_4 . $catName . $this->html_5;
            if(!empty($value['children'])) //if the category has children
            {
                $this->getCategoryList($value['children']); //recall function until it reaches end of tree
            }
            $this->categorylist .= $this->html_6;

        }
        $this->categorylist .= $this->html_7;
        return $this->categorylist;  // html version of list
    }

    public function getMainParent(int $id): array
    {
        $key = array_search($id, array_column($this->categoriesArrayFromDb, 'id'));  //find position of calling category
        if($this->categoriesArrayFromDb[$key]['parent_id'] != null)  // if it has a parent
        {
            return $this->getMainParent($this->categoriesArrayFromDb[$key]['parent_id']);  //recall function to climb until root parent is null
        }
        else
        {
            return [
                'id'=>$this->categoriesArrayFromDb[$key]['id'], //return data of the root category
                'name'=>$this->categoriesArrayFromDb[$key]['name']
            ];
        }
    }

    public function getChildIds(int $parent): array
    {
        static $ids = []; // must be static because it's a recursive function and the previous values will lose otherwise
        foreach($this->categoriesArrayFromDb as $val)  // loo pthrough all categories
        {
            if($val['parent_id'] == $parent) // if the calling category is its parent
            {
                $ids[] = $val['id'].','; // add id to the array
                $this->getChildIds($val['id']);
            }
        }

        return $ids;
    }

}
