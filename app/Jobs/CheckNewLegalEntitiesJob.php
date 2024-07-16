<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OrganizationType;
use App\Mail\OrganizationsReport;
use App\Models\Organization;
use App\Models\PhysicalPerson;
use App\Modules\Checko\Parsers\PhysicalPersonOrganizationsParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckNewLegalEntitiesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var PhysicalPersonOrganizationsParser $parser */
        $parser = app(PhysicalPersonOrganizationsParser::class);

        $result = [];

        PhysicalPerson::query()
            ->chunk(100, function ($persons) use (&$result, $parser): void {
                foreach ($persons as $person) {
                    try {
                        /** @var PhysicalPerson $person */
                        $parseResult = $parser->__invoke($person);
                        if ($parseResult->count() > 0) {
                            $result[] = [
                                'name' => implode(' ', [$person->second_name, $person->first_name, $person->last_name ?? '']),
                                'inn' => $person->inn,
                                'supervisor' => $parseResult->filter(fn (Organization $organization) => OrganizationType::SUPERVISOR === $organization->type)
                                    ->values()
                                    ->map(fn (Organization $organization) => $organization->name)
                                    ->all(),
                                'founder' => $parseResult->filter(fn (Organization $organization) => OrganizationType::FOUNDER === $organization->type)
                                    ->values()
                                    ->map(fn (Organization $organization) => $organization->name)
                                    ->all(),
                                'individual' => $parseResult->filter(fn (Organization $organization) => OrganizationType::INDIVIDUAL === $organization->type)
                                    ->values()
                                    ->map(fn (Organization $organization) => $organization->name)
                                    ->all(),
                            ];
                        }
                    } catch (Throwable $exception) {
                    }
                }
            });

        /** @var string|null $reportRecipient */
        $reportRecipient = config('mail.organizations_report_recipient');
        if ($reportRecipient && count($result) > 0) {
            Mail::to($reportRecipient)->send(new OrganizationsReport($result));
        }
    }
}
