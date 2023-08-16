<?php

namespace App\Rules;

use App\Domain\WaitingListUser\Repo as RepoWaitingListUser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsAllowedToRegister implements ValidationRule
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
        $wlUser = $this->repoWaitingListUser->getByEmail($value);
        if (!$wlUser) {
            $fail('Sorry, we could not find you on our waiting list.');
        } elseif ($wlUser->allow_register == 0) {
            $fail('Oops... we aren\'t quite ready to sign you up yet. We\'ll be in touch soon!');
        } elseif ($wlUser->user_id != null) {
            $fail('It looks like you\'ve already signed up.');
        }
    }
}
