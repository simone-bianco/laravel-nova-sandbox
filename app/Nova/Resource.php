<?php

namespace App\Nova;

use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;
use Laravel\Nova\Panel;

abstract class Resource extends NovaResource
{
    protected array $rules = [];

    /**
     * @param array $aggregatedFields
     * @return array
     */
    protected function getFieldsAsStacks(array $aggregatedFields): array
    {
        $fieldsAsStacks = [];
        foreach ($aggregatedFields as $key => $fields) {
            if (!is_array($fields)) {
                $fieldsAsStacks[] = $fields;
                continue;
            }
            foreach ($fields as $field) {
                if ($field instanceof Text && $field->asHtml) {
                    continue;
                }
                $field->displayUsing(function () use ($field) {
                    if (!$field->name) {
                        return $field->value;
                    }
                    return $field->name . ': ' . $field->value;
                });
            }
            $fieldsAsStacks[] = Stack::make(__($key), $fields);
        }
        return $fieldsAsStacks;
    }

    private function addRule(&$field, $key): void
    {
        if (!$key) {
            return;
        }
        $rules = Arr::get($this->rules, $key);
        if (!$rules) {
            return;
        }
        $field->rules($rules);
    }

    private function addRules(&$fields): void
    {
        foreach ($fields as $fieldKey => $field) {
            $this->addRule($field, $fieldKey);
        }
    }

    protected function getFieldsAsPanels(array $aggregatedFields): array
    {
        $fieldsAsPanel = [];
        foreach ($aggregatedFields as $key => $fields) {
            if (!is_array($fields)) {
                $this->addRule($fields, $key);
                $fieldsAsPanel[] = $fields;
                continue;
            }
            $this->addRules($fields);
            $fieldsAsPanel[] = Panel::make(__($key), $fields);
        }
        return $fieldsAsPanel;
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
}
