<?php

class PollsServiceProvider extends \Illuminate\Support\ServiceProvider
{
  public function boot(\Illuminate\Routing\Router $router)
  {
    $router->get('test', function () {
      return 'PollsServiceProvider';
    });
  }
}
