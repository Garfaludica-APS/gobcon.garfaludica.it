<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

namespace App\Http\Middleware;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
	/**
	 * The root template that's loaded on the first page visit.
	 *
	 * @see https://inertiajs.com/server-side-setup#root-template
	 *
	 * @var string
	 */
	protected $rootView = 'app';

	/**
	 * Determines the current asset version.
	 *
	 * @see https://inertiajs.com/asset-versioning
	 */
	public function version(Request $request): ?string
	{
		return parent::version($request);
	}

	/**
	 * Define the props that are shared by default.
	 *
	 * @see https://inertiajs.com/shared-data
	 *
	 * @return array<string, mixed>
	 */
	public function share(Request $request): array
	{
		return array_merge(parent::share($request), [
			'flash' => [
				'message' => static fn() => $request->session()->get('flash.message'),
				'location' => static fn() => $request->session()->get('flash.location') ?? (empty($request->session()->get('flash.message')) ? 'none' : 'page'),
				'timeout' => static fn() => $request->session()->get('flash.timeout') ?? false,
				'style' => static fn() => $request->session()->get('flash.style') ?? 'default',
			],
			'auth.admin' => static fn() => $request->user()
				? $request->user()
				: null,
			'auth.admin.hotels' => static fn() => $request->user()
				? ($request->user()->is_super_admin ? Hotel::all() : $request->user()->hotels()->get())
				: null,
			'settings.portalOpen' => static function() {
				$open = config('gobcon.open', true);
				return ($open instanceof Carbon) ? $open->isPast() : $open;
			},
			'settings.portalTimer' => static function() {
				$timer = config('gobcon.open', false);
				return ($timer instanceof Carbon) ? ceil(abs($timer->diffInSeconds())) : null;
			},
		]);
	}
}
