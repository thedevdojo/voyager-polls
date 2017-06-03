<?php

use Illuminate\Events\Dispatcher;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PollsServiceProvider extends \Illuminate\Support\ServiceProvider
{
	public function boot(\Illuminate\Routing\Router $router, Dispatcher $events)
	{
		$events->listen('voyager.admin.routing', [$this, 'addPollsRoutes']);
		$events->listen('voyager.menu.display', [$this, 'addPollsMenuItem']);
		$this->loadViewsFrom(base_path('hooks/voyager-polls/resources/views'), 'polls');
		
		// if(Request::is('*/voyager-polls/enable')){
		// 	dd('blah');
		// }
	}

	public function addPollsRoutes($router)
    {
        $namespacePrefix = '\\Hooks\\VoyagerPolls\\Http\\Controllers\\';
        $router->get('polls', ['uses' => $namespacePrefix.'PollsController@browse', 'as' => 'polls']);
        $router->get('polls/add', ['uses' => $namespacePrefix.'PollsController@add', 'as' => 'polls.add']);
    }

	public function addPollsMenuItem(Menu $menu)
	{
	    if ($menu->name == 'admin') {
	        $url = route('voyager.polls', [], false);
	        $menuItem = $menu->items->where('url', $url)->first();
	        if (is_null($menuItem)) {
	            $menu->items->add(MenuItem::create([
	                'menu_id'    => $menu->id,
	                'url'        => $url,
	                'title'      => 'Polls',
	                'target'     => '_self',
	                'icon_class' => 'voyager-bar-chart',
	                'color'      => null,
	                'parent_id'  => null,
	                'order'      => 99,
	            ]));
	            $this->ensurePermissionExist();
	            $this->addPollsTable();
	        }
	    }
	}

	protected function ensurePermissionExist()
    {
        $permission = Permission::firstOrNew([
            'key'        => 'browse_polls',
            'table_name' => 'admin',
        ]);
        if (!$permission->exists) {
            $permission->save();
            $role = Role::where('name', 'admin')->first();
            if (!is_null($role)) {
                $role->permissions()->attach($permission);
            }
        }
    }

    private function addPollsTable(){
    	if(!Schema::hasTable('voyager_poll')){

    		Schema::create('voyager_poll', function (Blueprint $table) {
	            $table->increments('id');
				$table->string('name');
				$table->string('slug')->unique();
				$table->string('image')->nullable();
				$table->timestamps();
	        });

	    	Schema::create('voyager_poll_questions', function (Blueprint $table) {
	            $table->increments('id');
	            $table->integer('poll_id')->unsigned()->index();
	            $table->foreign('poll_id')->references('id')->on('voyager_poll')->onDelete('cascade');
				$table->string('question');
				$table->string('image')->nullable();
				$table->timestamps();
	        });

	    	Schema::create('voyager_poll_answers', function (Blueprint $table) {
	            $table->increments('id');
	            $table->integer('question_id')->unsigned()->index();
	            $table->foreign('question_id')->references('id')->on('voyager_poll_questions')->onDelete('cascade');
	            $table->string('answer');
	            $table->string('image')->nullable();
	            $table->integer('votes')->default(0);
	            $table->timestamps();
	        });

	        Schema::create('voyager_poll_answers_users', function (Blueprint $table) {
	        	$table->integer('answer_id')->unsigned()->index();
	            $table->foreign('answer_id')->references('id')->on('voyager_poll_answers')->onDelete('cascade');
	            $table->integer('user_id')->unsigned()->index();
	            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	            $table->primary(['answer_id', 'user_id']);
	        });

	    }
    }
}
