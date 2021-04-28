<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{Badge, BelongsTo, Boolean, ID, Number, Textarea};


class Bonuse extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bonuse::class;


	public static $group = 'ADMIN';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
	public static $search = [
		'id',
		'amount',
		'comment',
		'status',
		'paid',
	];


	public static $searchRelations = [
		'booster' => [
			'username',
			'first_name',
			'last_name',
			'social',
			'email',
			'country',
		],
	];



	/**
	 * Build an "index" query for the given resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		if ($request->user()->hasRole('Admin') || $request->user()->hasRole('Superadmin')) {
			return $query;
		}
		return $query->where('booster_id', auth()->id());
	}

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request): array
	{
		return [
			ID::make(__('ID'), 'id')->sortable(),
			BelongsTo::make(__('Booster'), 'booster', User::class),
			Number::make(__('Amount'), 'amount')->required()->min(1)->max(1000)->step(0.01),
			Textarea::make(__('Comment'))->required(),
			Badge::make(__('Status'), 'status')->map([
				'Balance Transfer' => 'danger',
			]),
			Boolean::make(__('Paid'), 'paid')->hideWhenCreating()
		];
	}
    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function cards(Request $request)
    // {
    //     return [];
    // }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function filters(Request $request)
    // {
    //     return [];
    // }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function lenses(Request $request)
    // {
    //     return [];
    // }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function actions(Request $request)
    // {
    //     return [];
    // }
}