<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
	 */
	public function rules(): array
	{
		return [
			'username' => 'required|string|min:3|max:254',
			'password' => 'required|string|min:8',
			'remember' => 'boolean',
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	public function credentials(): array
	{
		$username = $this->input('username');
		if ($this->isEmail($username))
			return [
				'email' => $username,
				'password' => $this->input('password'),
		];
		return $this->only('username', 'password');
	}

	private function isEmail(string $param): bool
	{
		$factory = $this->container->make(ValidationFactory::class);
		return $factory->make(
			['username' => $param],
			['username' => 'email']
		)->passes();
	}
}
