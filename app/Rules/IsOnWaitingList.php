<?php

namespace App\Rules;

use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsOnWaitingList implements ValidationRule
{
    public function __construct(
        protected RepoWaitingListUser $repoWaitingListUser
    ){

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->repoWaitingListUser->emailExists($value)) {
            $fail('Sorry, we could not find you on our waiting list.');
        }
    }
}
