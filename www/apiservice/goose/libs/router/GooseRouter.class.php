<?php
namespace Goose\Libs\Router;

class GooseRouter extends \Libs\Router\BasicRouter {
	public function dispatch() {
		$path_args = $this->app->request->path_args;
		$version = array_shift($path_args);
		$module = array_shift($path_args);
		$action = array_shift($path_args);

		$module_namespace = $this->app->module_namespace;

		$class = $module_namespace . ucwords($module) . '\\' . ucwords($action);

		if (!class_exists($class)) {
			$class = '\\Goose\\Modules\\System\\BadCall';
		}
		return $class;
	}
}