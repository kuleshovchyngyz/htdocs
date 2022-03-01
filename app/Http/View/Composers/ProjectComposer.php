<?php

namespace App\Http\View\Composers;

use App\Project;
use Illuminate\View\View;

class ProjectComposer
{

   protected $selected_project;
   protected $all_projects;

   public function __construct()
   {
      // Dependencies automatically resolved by service container...
      $this->all_projects =  Project::all();
      if (session('selected_project_id') !== null) {
         $this->selected_project = Project::find(session('selected_project_id'));
      }
   }

   /**
    * Bind data to the view.
    *
    * @param  View  $view
    * @return void
    */
   public function compose(View $view)
   {
      $view->with('selected_project', $this->selected_project)
         ->with('all_projects', $this->all_projects);
   }
}
