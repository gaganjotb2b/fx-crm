<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ThrottleSubmission implements Rule
{
    protected $user;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        return $this->user->latestMessage != null ? $this->user->latestMessage->created_at->lt(
            now()->subMinutes(5)
        ): null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Try submitting after 5 minutes.';
    }
}
