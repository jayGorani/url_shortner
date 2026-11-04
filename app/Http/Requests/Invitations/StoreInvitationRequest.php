<?php

namespace App\Http\Requests\Invitations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ($user->isAdmin() || $user->isSuperAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isSuperAdmin = $this->user()?->isSuperAdmin() ?? false;

        return [
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email:rfc', 'max:255'],
            'role'         => ['required', $isSuperAdmin ? 'in:admin' : 'in:admin,member'],
            'company_name' => [
                $isSuperAdmin ? 'required': 'prohibited',
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }
}
