<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerEmailAlias;
use App\Models\CustomerInquiry;

class CustomerService
{
    /**
     * Find or create a customer by email.
     * Auto-merges if email matches exactly (primary or alias).
     *
     * @param string $email
     * @param string $name
     * @param string|null $phone
     * @param bool $gdprConsent
     * @return Customer
     */
    public function findOrCreateCustomer(string $email, string $name, ?string $phone = null, bool $gdprConsent = false): Customer
    {
        $email = strtolower(trim($email));

        // Check if customer exists by primary email
        $customer = Customer::where('primary_email', $email)->first();

        // Check if email exists as an alias
        if (!$customer) {
            $alias = CustomerEmailAlias::where('email', $email)->first();
            if ($alias) {
                $customer = $alias->customer;
            }
        }

        // If customer found, update if needed
        if ($customer) {
            // Update name if different (keep most recent)
            if ($name && $name !== $customer->name) {
                $customer->name = $name;
            }

            // Update phone if provided and customer doesn't have one
            if ($phone && !$customer->phone) {
                $customer->phone = $phone;
            }

            // Update GDPR consent if provided and customer doesn't have it
            if ($gdprConsent && !$customer->gdpr_consent) {
                $customer->gdpr_consent = true;
                $customer->gdpr_consent_date = now();
            }

            $customer->save();
            return $customer;
        }

        // Create new customer
        return Customer::create([
            'primary_email' => $email,
            'name' => $name,
            'phone' => $phone,
            'gdpr_consent' => $gdprConsent,
            'gdpr_consent_date' => $gdprConsent ? now() : null,
            'total_inquiries' => 0,
        ]);
    }

    /**
     * Create a new inquiry for a customer.
     *
     * @param Customer $customer
     * @param array $inquiryData
     * @return CustomerInquiry
     */
    public function createInquiry(Customer $customer, array $inquiryData): CustomerInquiry
    {
        $inquiry = CustomerInquiry::create([
            'customer_id' => $customer->id,
            'email_used' => $inquiryData['email'],
            'name_used' => $inquiryData['name'],
            'phone' => $inquiryData['phone'] ?? null,
            'product_id' => $inquiryData['product_id'] ?? null,
            'product_title' => $inquiryData['product_title'] ?? '',
            'message' => $inquiryData['message'],
        ]);

        // Increment customer inquiry counter
        $customer->incrementInquiries();

        return $inquiry;
    }

    /**
     * Merge two customers into one.
     *
     * @param Customer $primaryCustomer
     * @param Customer $secondaryCustomer
     * @return Customer
     */
    public function mergeCustomers(Customer $primaryCustomer, Customer $secondaryCustomer): Customer
    {
        // Move all inquiries from secondary to primary
        CustomerInquiry::where('customer_id', $secondaryCustomer->id)
            ->update(['customer_id' => $primaryCustomer->id]);

        // Add secondary email as alias if different
        if (strtolower($secondaryCustomer->primary_email) !== strtolower($primaryCustomer->primary_email)) {
            // Check if alias doesn't already exist
            if (!$primaryCustomer->hasEmail($secondaryCustomer->primary_email)) {
                CustomerEmailAlias::create([
                    'customer_id' => $primaryCustomer->id,
                    'email' => $secondaryCustomer->primary_email,
                ]);
            }
        }

        // Move all aliases from secondary to primary
        $secondaryAliases = CustomerEmailAlias::where('customer_id', $secondaryCustomer->id)->get();
        foreach ($secondaryAliases as $alias) {
            // Check if alias doesn't already exist
            if (!$primaryCustomer->hasEmail($alias->email)) {
                $alias->customer_id = $primaryCustomer->id;
                $alias->save();
            } else {
                // Delete duplicate alias
                $alias->delete();
            }
        }

        // Update primary customer with best data
        if (!$primaryCustomer->phone && $secondaryCustomer->phone) {
            $primaryCustomer->phone = $secondaryCustomer->phone;
        }
        if (!$primaryCustomer->notes && $secondaryCustomer->notes) {
            $primaryCustomer->notes = $secondaryCustomer->notes;
        }
        if (!$primaryCustomer->gdpr_consent && $secondaryCustomer->gdpr_consent) {
            $primaryCustomer->gdpr_consent = true;
            $primaryCustomer->gdpr_consent_date = $secondaryCustomer->gdpr_consent_date ?? now();
        }

        // Recalculate total inquiries
        $primaryCustomer->total_inquiries = CustomerInquiry::where('customer_id', $primaryCustomer->id)->count();
        
        // Update last contact
        $lastInquiry = CustomerInquiry::where('customer_id', $primaryCustomer->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastInquiry) {
            $primaryCustomer->last_contact_at = $lastInquiry->created_at;
        }

        $primaryCustomer->save();

        // Delete secondary customer
        $secondaryCustomer->delete();

        return $primaryCustomer;
    }

    /**
     * Find potential duplicate customers by email, name, or phone.
     *
     * @param string $email
     * @param string|null $name
     * @param string|null $phone
     * @return array<Customer>
     */
    public function findPotentialDuplicates(string $email, ?string $name = null, ?string $phone = null): array
    {
        $email = strtolower(trim($email));
        $duplicates = [];

        // Exact email match (primary or alias)
        $customer = Customer::where('primary_email', $email)->first();
        if (!$customer) {
            $alias = CustomerEmailAlias::where('email', $email)->first();
            if ($alias) {
                $customer = $alias->customer;
            }
        }
        if ($customer) {
            $duplicates[] = $customer;
        }

        // We only auto-merge on exact email match, but we can suggest other matches
        // This could be extended later for manual review

        return array_unique($duplicates, SORT_REGULAR);
    }
}



