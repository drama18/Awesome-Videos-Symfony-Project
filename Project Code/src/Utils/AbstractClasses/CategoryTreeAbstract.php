<?php
namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract {

    public $categoriesArrayFromDb;  // contains categories
    public $categorylist; //needed in controllers, will be string for html view
    protected static $dbconnection;  //saves data of database once

    public function __construct(EntityManagerInterface $entitymanager, UrlGeneratorInterface $urlgenerator)
    {
        $this->entitymanager = $entitymanager;
        $this->urlgenerator = $urlgenerator;
        $this->categoriesArrayFromDb = $this->getCategories();
    }

    abstract public function getCategoryList(array $categories_array); //to be implemented for each concrete category

    public function buildTree(int $parent_id = null): array //array of the calling category with its subcategories
    {
        $subcategory = [];
        foreach($this->categoriesArrayFromDb as $category)
        {
            if($category['parent_id'] == $parent_id) //find the calling category in the array
            {
                $children = $this->buildTree($category['id']); // get all its children in subcategories
                if($children)
                {
                    $category['children'] = $children; //add it to the  array
                }
                $subcategory[] = $category;
            }
        }
        return $subcategory;
    }

    private function getCategories(): array
    {
        if(self::$dbconnection)
        {
            return self::$dbconnection; // if data retrieved once, don't connect to database again
        }
        else
        {
            $conn = $this->entitymanager->getConnection();
            $sql = "SELECT * FROM categories";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return self::$dbconnection = $stmt->fetchAll();
        }
    }

}
