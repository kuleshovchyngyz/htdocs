<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryGroup extends Model
{
    protected $fillable = [
        'name', 'parent_group_id', 'project_id', 'region_id', 'target_path', 'is_active'
    ];

    public function project()
    {
        return $this->hasOne('App\Project', 'query_group_id');
    }
    public function region()
    {
        return $this->hasOne('App\Region', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\QueryGroup', 'id', 'parent_group_id');
    }

    public function children()
    {
        return $this->hasMany('App\QueryGroup', 'parent_group_id');
    }

    public function queries()
    {
        return $this->hasMany('App\Query');
    }

    public static function toTree($data)
    {
        $new = array();
        foreach ($data as $a){
            $new[] = $a->getAttributes();
        }
        return self::buildTree($new, 0);
    }

    public static function getAllChildren($data, $parentID)
    {
        $new = [];
        $result = [];
        foreach ($data as $a){
            $element = $a->getAttributes();
            $new[] = $element;
            if ($a->getAttributes()['id'] == $parentID ) {
                $element['level'] = 0;
                $result[] = $element;
                
            }
        }
        self::searchChild($new, $result, $parentID, 1);
        return $result;

    }

    private static function searchChild(array &$elements, array &$result, $parentID = 0, $level = 0) {

        foreach ($elements as &$element) {
            if ($element['parent_group_id'] == $parentID) {
                $element['level'] = $level;
                $result[] = $element;
                $children = self::searchChild($elements, $result, $element['id'], $level+1);
                unset($element);
                
            }
        }
    }

    private static function buildTree(array &$elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as &$element) {

            if ($element['parent_group_id'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($element);
            }
        }
        return $branch;
    }
}
