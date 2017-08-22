<?php

namespace VoyagerPolls;

use Illuminate\Events\Dispatcher;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PollsServiceProvider extends \Illuminate\Support\ServiceProvider
{
	private $models = [
		'Poll',
		'PollAnswer',
		'PollQuestion'
	];

	public function register()
	{
		app(Dispatcher::class)->listen('voyager.admin.routing', [$this, 'addPollsRoutes']);
		app(Dispatcher::class)->listen('voyager.menu.display', [$this, 'addPollsMenuItem']);
	}

	public function boot(\Illuminate\Routing\Router $router, Dispatcher $events)
	{
		$this->pollRoutesAPI($router);
		$this->loadViewsFrom(base_path('hooks/voyager-polls/resources/views'), 'polls');
		$this->loadModels();
	}

	public function addPollsRoutes($router)
    {
        $namespacePrefix = '\\VoyagerPolls\\Http\\Controllers\\';
        $router->get('polls', ['uses' => $namespacePrefix.'PollsController@browse', 'as' => 'polls']);
        $router->get('polls/add', ['uses' => $namespacePrefix.'PollsController@add', 'as' => 'polls.add']);
    	$router->post('polls/add', ['uses' => $namespacePrefix.'PollsController@add_post', 'as' => 'polls.add.post']);
    	$router->get('polls/{id}/edit', ['uses' => $namespacePrefix.'PollsController@edit', 'as' => 'polls.edit']);
    	$router->post('polls/edit', ['uses' => $namespacePrefix.'PollsController@edit_post', 'as' => 'polls.edit.post']);
    	$router->delete('polls/delete', ['uses' => $namespacePrefix.'PollsController@delete', 'as' => 'polls.delete']);
		$router->get('polls/{id}', ['uses' => $namespacePrefix.'PollsController@read', 'as' => 'polls.read']);
    }

    public function pollRoutesAPI($router){
    	$namespacePrefix = '\\VoyagerPolls\\Http\\Controllers\\';
    	$router->post(env('ROUTE_PREFIX') . '/polls/api/vote/{id}', ['uses' => $namespacePrefix.'PollsController@api_vote', 'as' => 'polls.vote']);
    	$router->get(env('ROUTE_PREFIX') . '/polls/api/{slug}.json', ['uses' => $namespacePrefix.'PollsController@json', 'as' => 'polls.json']);
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

	private function loadModels(){
		foreach($this->models as $model){
			$namespacePrefix = 'VoyagerPolls\\Models\\';
			if(!class_exists($namespacePrefix . $model)){
				@include(__DIR__.'/Models/' . $model . '.php');
			}
		}
	}

	protected function ensurePermissionExist()
    {
        $permissions = [
        	Permission::firstOrNew(['key' => 'browse_polls', 'table_name' => 'polls']),
        	Permission::firstOrNew(['key' => 'read_polls', 'table_name' => 'polls']),
        	Permission::firstOrNew(['key' => 'edit_polls', 'table_name' => 'polls']),
        	Permission::firstOrNew(['key' => 'add_polls', 'table_name' => 'polls']),
        	Permission::firstOrNew(['key' => 'delete_polls', 'table_name' => 'polls'])
        ];

        foreach($permissions as $permission){
	        if (!$permission->exists) {
	            $permission->save();
	            $role = Role::where('name', 'admin')->first();
	            if (!is_null($role)) {
	                $role->permissions()->attach($permission);
	            }
	        }
	    }
    }

    private function addPollsTable(){
    	if(!Schema::hasTable('voyager_polls')){

    		Schema::create('voyager_polls', function (Blueprint $table) {
	            $table->increments('id');
				$table->string('name');
				$table->string('slug')->unique();
				$table->string('image')->nullable();
				$table->timestamps();
	        });

	    	Schema::create('voyager_poll_questions', function (Blueprint $table) {
	            $table->increments('id');
	            $table->integer('poll_id')->unsigned()->index();
	            $table->foreign('poll_id')->references('id')->on('voyager_polls')->onDelete('cascade');
				$table->string('question');
				$table->string('image')->nullable();
				$table->integer('order')->default(1);
				$table->timestamps();
	        });

	    	Schema::create('voyager_poll_answers', function (Blueprint $table) {
	            $table->increments('id');
	            $table->integer('question_id')->unsigned()->index();
	            $table->foreign('question_id')->references('id')->on('voyager_poll_questions')->onDelete('cascade');
	            $table->string('answer');
	            $table->string('image')->nullable();
	            $table->integer('votes')->default(0);
	            $table->integer('order')->default(1);
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
