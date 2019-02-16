<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Scoper
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, array $scopes)
    {
        foreach ($this->filterScopes($scopes) as $key => $scope) {
            $this->applyScope($builder, $scope, $key);
        }

        return $builder;
    }

    private function filterScopes($scopes)
    {
        return array_only($scopes, array_keys($this->request->all()));
    }

    private function applyScope(Builder $builder, Scope $scope, $key)
    {
        $scope->apply($builder, $this->request->get($key));
    }
}
