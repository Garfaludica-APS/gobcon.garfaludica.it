<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

namespace App\Console\Commands;

use App\Models\Invitation;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;

use function Laravel\Prompts\text;

class InviteAdmin extends Command implements PromptsForMissingInput
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = <<<'EOD'
		admin:invite
						{email : Email address to send the invite to}
						{--s|super : Make the user a super admin}
		EOD;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Invite a new admin';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		Invitation::create([
			'email' => $this->argument('email'),
			'token' => Str::random(60),
			'is_super_admin' => $this->option('super'),
			'hotels' => [],
		]);
		// TODO: send

		$this->info('Invitation sent to \'' . $this->argument('email') . '\'.');
	}

	protected function promptForMissingArgumentsUsing(): array
	{
		return [
			'email' => static fn() => text(
				label: 'What is the email of the new admin?',
				placeholder: 'admin@garfaludica.it',
				default: 'admin@garfaludica.it',
				validate: ['email' => 'email:strict,dns,spoof|max:254|unique:invites|unique:admins'],
			),
		];
	}
}
