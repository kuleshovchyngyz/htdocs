<?php

namespace App\support\Query;

class Query
{
    public $name;
    public $target;
    public $group_name;
    public $group_folder_path ;
    public $region ;

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }
    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }
    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target): void
    {
        $domain = parse_url($target);
        $this->target = $domain['path'];
    }

    /**
     * @param mixed|string $group_name
     */
    public function setGroupName($group_name): void
    {
        $this->group_name = $this->remove_s($group_name);
    }

    /**
     * @param mixed|string $group_folder_path
     */
    public function setGroupFolderPath($group_folder_path): void
    {
        $this->group_folder_path = $this->remove_slashes($group_folder_path);
    }
    public function remove_slashes($target)
    {
        $target = $this->remove_s($target);
        if ($target == "")
        {
            return "";
        }

        $pattern_start = "/^\/+/";
        $pattern_end = "/\/+$/";
        if (preg_match($pattern_start, $target))
        {
            $target = preg_split($pattern_start, $target);
            $target = $target[1];
        }
        if (preg_match($pattern_end, $target))
        {
            $target = preg_split($pattern_end, $target);
            $target = $target[0];
        }

        return ($target);
    }

    public function remove_s($target)
    {
        if ($target == "")
        {
            return "";
        }

        $pattern_start = "/^\"+/";
        $pattern_end = "/\"+$/";
        if (preg_match($pattern_start, $target))
        {
            $target = preg_split($pattern_start, $target);
            $target = $target[1];
        }
        if (preg_match($pattern_end, $target))
        {
            $target = preg_split($pattern_end, $target);
            $target = $target[0];
        }

        return $target;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @return mixed
     */
    public function  getGroupFolderPath()
    {
        return $this->group_folder_path;
    }

}
