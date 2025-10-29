<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'primary_email',
        'name',
        'phone',
        'notes',
        'gdpr_consent',
        'gdpr_consent_date',
        'total_inquiries',
        'last_contact_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gdpr_consent' => 'boolean',
            'gdpr_consent_date' => 'datetime',
            'last_contact_at' => 'datetime',
            'total_inquiries' => 'integer',
        ];
    }

    /**
     * Get all inquiries for this customer.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(CustomerInquiry::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all email aliases for this customer.
     */
    public function emailAliases(): HasMany
    {
        return $this->hasMany(CustomerEmailAlias::class);
    }

    /**
     * Get all email addresses associated with this customer (primary + aliases).
     */
    public function getAllEmails(): array
    {
        $emails = [$this->primary_email];
        $aliases = $this->emailAliases->pluck('email')->toArray();
        return array_merge($emails, $aliases);
    }

    /**
     * Check if an email belongs to this customer.
     */
    public function hasEmail(string $email): bool
    {
        if (strtolower($this->primary_email) === strtolower($email)) {
            return true;
        }

        return $this->emailAliases()
            ->where('email', $email)
            ->exists();
    }

    /**
     * Increment total inquiries counter.
     */
    public function incrementInquiries(): void
    {
        $this->increment('total_inquiries');
        $this->touch('last_contact_at');
    }
}
