<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;

class ImportPhones extends Command
{
    protected $signature = 'people:import-phones {file : CSV with full name, ..., email, phone, birth date}';
    protected $description = 'Fill phone/email on existing people, matched by full name + birth date';

    public function handle(): int
    {
        $fh = fopen($this->argument('file'), 'r');
        fgetcsv($fh); // header
        $updated = $missed = 0;

        while (($r = fgetcsv($fh)) !== false) {
            [$name, , , , $email, $phone, $birth] = array_pad($r, 7, '');
            if (! $phone && ! $email) {
                continue;
            }

            $iso = preg_match('#^(\d\d)/(\d\d)/(\d{4})$#', $birth, $m) ? "$m[3]-$m[2]-$m[1]" : null;
            $matches = Person::all()->filter(fn($p) => trim($p->full_name) === trim($name)
                && (! $iso || substr((string) $p->birth_date_gregorian, 0, 10) === $iso));

            if ($matches->count() !== 1) {
                $this->warn("לא נמצא התאמה חד-משמעית: $name $birth");
                $missed++;
                continue;
            }

            $p = $matches->first();
            $p->update(array_filter(['phone' => $phone, 'email' => $p->email ?: $email]));
            $updated++;
        }

        $this->info("עודכנו $updated, לא הותאמו $missed");
        return self::SUCCESS;
    }
}
